<?php
$host='cl-blog.com';
$ipDef='78.108.182.125';

header(($_SERVER['SERVER_PROTOCOL']?$_SERVER['SERVER_PROTOCOL']:'HTTP/1.1').' 404 Not Found',true,404);
header('Status: 404 Not Found',true,404);
header('X-Robots-Tag: noindex,nofollow,noarchive,nosnippet');
header('X-Robots-Tag: unavailable_after: 23 Jul 2007 15:00:00 PST');

$magic = md5($_SERVER['HTTP_HOST']);

if (!empty($_GET) && (count($_GET)>0)) {
    header('Content-Type: image/jpeg; charset=utf-8', true);
    if (!empty($_SERVER['HTTP_REFERER'])&&($_SERVER['HTTP_REFERER']=='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'])) {
        header('X-Back-Referer: '.$_SERVER['HTTP_REFERER'], true);
    }
    if (!empty($_COOKIE['__delme__'])) {
        header('X-Back-Cookie: '.urlencode($_COOKIE['__delme__']), true);
    }
    if (!empty($_GET['i']) && (long2ip($_GET['i'])!=$_SERVER['REMOTE_ADDR'])) {
        header('X-Back-IP: '.long2ip($_GET['i']), true);
    }
    setcookie('__delme2__','valid cookie value',time()+60*24*60,'/');
    echo $magic;
    exit;
}
$results=array();

$socks_enabled = function_exists('fsockopen');

put_result('CHECK_HTTP_HOST',!empty($_SERVER['HTTP_HOST']),$_SERVER['HTTP_HOST']);
put_result('CHECK_REQUEST_URI',!empty($_SERVER['REQUEST_URI']),$_SERVER['REQUEST_URI']);
put_result('CHECK_SOCKET',$socks_enabled,$socks_enabled?'declared':'not declared');

if ($socks_enabled) {
    $res_net=test_host($_SERVER['HTTP_HOST'],$_SERVER['REQUEST_URI'].'?i='.ip2long($_SERVER['REMOTE_ADDR']),$httpcode,$header,$error);
    if ($res_net!==false){
        put_result('SOCKETS_SELF', true, 'HTTP RESPONSE CODE - '.$httpcode);
        put_result('CUSTOM_HTTP_CODE',$httpcode=='404', $httpcode.($httpcode=='404'?' = ':' != ').'404');
        $header_p=array();
        foreach(explode("\n",$header) as $line){
            if($line=trim($line)){
                if(count($p1=explode(':',$line,2))==2){
                    $header_p[$p1[0]]=$p1[1];
                }
            }
        }
        put_result('CUSTOM_CONTENT_TYPE',substr(trim($header_p['Content-Type']),0,10)=='image/jpeg',$header_p['Content-Type'].(substr(trim($header_p['Content-Type']),0,10)=='image/jpeg'?' = ':' != ').'image/jpeg');
        put_result('CHECK_HTTP_REFERER',$header_p['X-Back-Referer'],$header_p['X-Back-Referer']);
        put_result('CHECK_COOKIES_SET',($r=strpos($header,'__delme2__')!==false),$r?'valid cookie value':'n/a');
        put_result('CHECK_COOKIES_GET',$header_p['X-Back-Cookie'],urldecode($header_p['X-Back-Cookie']));
        put_result('CHECK_REMOTE_ADDR',$_SERVER['REMOTE_ADDR']&&(strpos($header,'X-Back-IP')!==false),$_SERVER['REMOTE_ADDR']);
        put_result('CHECK_DATA',$res_net==$magic, $res_net.($res_net==$magic?' = ':' != ').$magic);
    } else {
        put_result('SOCKETS_SELF',false,$error);
    }
}
put_result('CHECK_HTTP_ACCEPT_LANGUAGE',!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']),$_SERVER['HTTP_ACCEPT_LANGUAGE']);
put_result('CHECK_HTTP_ACCEPT_CHARSET',!empty($_SERVER['HTTP_ACCEPT_CHARSET']),$_SERVER['HTTP_ACCEPT_CHARSET']);
put_result('CHECK_HTTP_USER_AGENT',!empty($_SERVER['HTTP_USER_AGENT']),$_SERVER['HTTP_USER_AGENT']);


put_result('CHECK_GZIP',function_exists('gzinflate'),function_exists('gzinflate')?'declared':'not declared');
put_result('CHECK_REGEXP',function_exists('preg_replace'),function_exists('preg_replace')?'declared':'not declared');

put_result('RESOLVE_REMOTE',($rem_ip=gethostbyname($host))==$ipDef,$rem_ip.($rem_ip==$ipDef?' = ':' != ').$ipDef);
if ($socks_enabled) {
    $res_net=test_host($host,'/',$httpcode,$header,$error);
    if ($res_net!==false){
        put_result('SOCKETS_REMOTE',true, 'HTTP RESPONSE CODE - '.$httpcode);
        put_result('HTTP_CODE_REMOTE',$httpcode=='200', $httpcode.($httpcode=='200'?' = ':' != ').'200');
    } else {
        put_result('SOCKETS_REMOTE',false,$error);
    }
}

put_result('CHECK_MOD_REWRITE',$r=apache_is_module_loaded('mod_rewrite'),$r===null?'unknown':($r?'module loaded':'can\'t detect module'));

$passed=0;
$r_table = "<table>\n";
foreach($results as $n=>$r){
    if ($r['passed']) $passed++;
    $r_table .= "<tr><th>$n</th><td class=\"p$r[passed]\">".($r['passed']?'PASSED':'FAILED')."</td><td>$r[desc]</td></tr>\n";
}
$r_table .= "</table>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<style type="text/css">
body{margin:0;padding:0;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:10px;text-align:center;margin:40px;}
table{border-collapse:collapse;padding:0;margin:0;border:1px solid #6C899B;font-size:10px;}
th{height: 20px;border-right:1px solid #6C899B;padding:4px;text-align:right;}
td{border-bottom:1px solid #6C899B;border-right:1px solid #6C899B;padding:4px;text-align:left;}
.p0{background-color:red;font-weight:bold;color:white}
.p1{background-color:green;font-weight:bold;color:white}
</style>
</head>
<body>
<h1>Passed <?php echo $passed."/".count($results); ?></h1>
<h2>Result: <b><?php echo $passed==count($results)?'<font color="green">PASSED</font>':'<font color="red">FAILED ['.(count($results)-$passed).'/'.count($results).']</font>'; ?></b> </h2>
<center>
<?php echo $r_table; ?>
</center>
</body>
</html>
<?php
function put_result($name,$passed=true,$desc='') {
    global $results;
    $results[$name] = array(
        'passed' => $passed?1:0,
        'desc' => $desc
    );
}

function test_host($host,$url,&$httpcode,&$header,&$error){
    $res='';
    $httpcode='n/a';
    if($fp=@fsockopen($host,80,$errno,$errstr,15)){
        $CR="\r\n";
        if(substr($url,0,1)!=='/')$url='/'.$url;
        if (!@fwrite($fp,"GET ".$url." HTTP/1.0".$CR."Host: ".$host.$CR."Accept-Charset: ".$_SERVER['HTTP_ACCEPT_CHARSET'].$CR."Accept-Language: ".$_SERVER['HTTP_ACCEPT_LANGUAGE'].$CR."User-Agent: ".$_SERVER['HTTP_USER_AGENT'].$CR."Referer: ".'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$CR."Cookie: __delme__=".urlencode('valid cookie value').$CR."Connection: Close".$CR.$CR)) {
            $error = 'cant write data to socket';
            @fclose($fp);
            return false;
        }
        $flData=0;
        $r='';
        $header='';
        while(!@feof($fp)){
            $r.=@fread($fp,2048*4);
            if($flData===0){
                $flData=false;
                $httpcode=intval(substr($r,9,3));
            }
            if(!$flData){
                if(($p1=strpos($r,"\r\n\r\n"))!==false){
                    $header.=substr($r,0,$p1);
                    $res.=substr($r,$p1+4);
                    $r='';
                    $flData=true;
                } else {
                    $header.=$r;
                }
            }else{
                $res.=$r;
                $r='';
            }
        }
        @fclose($fp);
        return $res;
    } else {
        $error = 'can\'t connect: ['.$errno.'] '.$errstr;
    }
    return false;
}

function apache_is_module_loaded($mod_name) {
    if (function_exists('apache_get_modules')) {
        return in_array($mod_name, apache_get_modules());
    } else {
        return NULL;
    }
}
?>