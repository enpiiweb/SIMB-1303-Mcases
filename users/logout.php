<?php
session_start();
	$_SESSION["auth"] = null;
	$_SESSION["cart"] = null;

require_once("../includes/includes.php");

global $siteroot;
global $fileroot;

$smarty = new SiteTemplate;
//$smarty->template_dir = "";
//$smarty->compile_dir = "/home/mcases/public_html/templates_c";

$smarty->assign("main_displaydata",'<p>Logout completed.</p>');


$smarty->display($fileroot . 'templates/main.tpl');