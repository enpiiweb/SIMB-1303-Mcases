<?php
    global $siteroot, $debug_on, $fileroot, $sitename, $standard_template, $relroot;
    $debug_on = true;
    $standard_template = "standard";
    $sitename = "Magistrates Cases";
    $siteroot = "http://192.168.1.101/simb/1303_mcases/";
    $fileroot = dirname(__FILE__).'/../';
    $relroot = "/";
    function GetDbCodes()
    {
    	$db = array(
		"user" => "root",
		"pass" => "",
		"database" => "simb_1303_mcases",
		"server" => "localhost"
		);
		
		return $db;
	}
	
	function GetAdminEmail()
	{
    	$email = array(
   			"Veridon" => "andrew@veridon.com.au"
		);
		
		return $email;
	}


function sample_sess_open($save_path, $session_name) 
{
  echo "Session is opening<bR>".print_trace();
  
  return(true);
}

function sample_sess_close() 
{
  echo "Session is closing<bR>";
  return(true);
}

function sample_sess_read($id) 
{
  echo "Session is reading<bR>";

}

function sample_sess_write($id, $sess_data) 
{
  echo "Session is writing<bR>";
}

function sample_sess_destroy($id) 
{
  echo "Session is destroyed<bR>";
}

/*********************************************
* WARNING - You will need to implement some *
* sort of garbage collection routine here.  *
*********************************************/
function sample_sess_gc($maxlifetime) 
{
  echo "Session is garbage collected<bR>";
}

//session_set_save_handler("sample_sess_open", "sample_sess_close", "sample_sess_read", "sample_sess_write", "sample_sess_destroy", "sample_sess_gc");
function print_trace()
{
	$tr = "";
	foreach(debug_backtrace() as $b)
		$tr .= "--&gt; $b[function] <b>in</b>$b[file]<br>";
		
	return $tr;
}
?>