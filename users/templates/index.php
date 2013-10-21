<?php
require_once("../includes/includes.php");

global $siteroot;

$smarty = new SiteTemplate;
//$smarty->template_dir = "";
//$smarty->compile_dir = "/home/mcases/public_html/templates_c";

AssignCategories($smarty);

if(Auth()) {
$smarty->assign("main_displaydata",'<p>Your login was successful.</p>');
} else {
$smarty->assign("main_displayfile",'login.tpl');

}

$smarty->display('/home/mcases/public_html/templates/main.tpl');