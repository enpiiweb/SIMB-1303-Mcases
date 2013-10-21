<?php
global $relroot,$fileroot;
require_once("includes/includes.php");
$template = new SiteTemplate();


$car = ClassFactory::ObjectArray('DownloadAuth',true,"where file='MKONE.exe'");
if(is_array($car))
{
	foreach($car as $c)
	{
		$dla = new DownloadAuth();
		$dla->user = $c->user;
		$dla->file = "MKONEUPDATE.exe";
		$dla->downloads = 1;
		$res = $dla->Save();
	}



}

$head = $template->do_header($title);
$foot = $template->do_footer();
echo $head.$body.$foot;
?>