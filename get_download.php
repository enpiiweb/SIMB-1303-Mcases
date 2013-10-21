<?php
include ('includes/includes.php');
global $relroot;
$file = isset($_REQUEST["file"])? $_REQUEST["file"] : false;
$cat = isset($_REQUEST["cat"])? $_REQUEST["cat"] : false;


$error = process_download($cat."/".$file);



if($error)
{
	header("HTTP/1.0 404 Not Found");
	$smarty = new SiteTemplate();
	$smarty->assign("main_displayfile",'downloaderror.tpl');
	$smarty->assign("downloaderror_error",$error);
	AssignCategories($smarty);
	$smarty->display("main.tpl");
}

function readfile_chunked($filename,$retbytes=true) {
   $chunksize = 1*(1024*1024); // how many bytes per chunk
   $buffer = '';
   $cnt =0;
   // $handle = fopen($filename, 'rb');
   $handle = fopen($filename, 'rb');
   if ($handle === false) {
       return false;
   }
   while (!feof($handle)) {
       $buffer = fread($handle, $chunksize);
       echo $buffer;
       flush();
       if ($retbytes) {
           $cnt += strlen($buffer);
       }
   }
       $status = fclose($handle);
   if ($retbytes && $status) {
       return $cnt; // return num. bytes delivered like readfile() does.
   }
   return $status;

}

function process_download($file)
{
	global $fileroot,$siteroot;
	
	session_start();
	if ( !Auth() )
	{
		return "You must be logged in";
	}
	
	if(!$file)
		return "Invalid download file specified.";
		
	$fd = $fileroot."download/".$file;
	
	
	if(strpos($file,"..")!==false or ereg("[^/0-9a-zA-Z_.-]",$file))
	{
		return "Unable to find file.";
	}
	
	if(!@file_exists($fd))
	{
		return "File not found.";
	}
	
	
	if(filetype($fd)!="file")
	{
		return "Unable to locate file.";
	}
	
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Content-type: application/octet-stream");
	header("Content-Transfer-Encoding: Binary");
	header("Content-length: ".filesize($fd));
	header("Content-Disposition: attachment; filename=$file");
	header("Content-Description: MCases Download");
	readfile_chunked($fd);
	return false;
}