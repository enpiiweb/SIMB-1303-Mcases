<?php


require_once("../includes/includes.php");


//Authorise($smarty,"index.php","Admin");

$smarty = new SiteTemplate;

$action = isset($_GET['action']) ? $_GET['action'] : false;
$id = isset($_GET['id']) ? $_GET['id'] : false;


switch($action)
{
  case 'add':
	$smarty->assign("main_displayfile",'downloadform.tpl');
	break;
	
  case 'save':
  	break;
  	
  case 'edit':
  	$download = ClassFactory::ObjectNew('download',$id)
  	break;
  	
}


$smarty->display('main.tpl');


?>