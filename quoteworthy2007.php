<?php
require_once("includes/includes.php");
$smarty = new SiteTemplate();

$action = isset($_GET['action']) ? $_GET['action'] : false;
$id = isset($_GET['id']) ? $_GET['id'] : false;



$smarty->assign("main_displayfile",'quoteworthy2007.html');
AssignCategories($smarty);
$smarty->display('main.tpl');
?>