<?php
require_once("../includes/includes.php");

$smarty = new SiteTemplate();

AssignCategories($smarty);
Authorise($smarty,"index.php","Admin");


$function = isset($_GET['function']) ? $_GET['function'] : false;
$id = isset($_GET['id']) ? $_GET['id'] : false;


switch($function)
{
  case 'confirmdelete':
	$cat = ClassFactory::ObjectNew('cat',$id);
	if ($cat instanceof cat)
	{
		$smarty->assign("displayfile","confirm.tpl");
		$smarty->assign("question", "Are you sure yo want to delete ".$cat->name.".");
		$smarty->assign("yes", "editcat.php?function=delete&id={$cat->id}");
		$smarty->assign("no", "catadmin.php");
	}
	else
	{
		$smarty->assign("displayfile","displaymessage.tpl");
		$smarty->assign("return","catadmin.php");
		$smarty->assign("message","A problem has occurred");
	}
	break;
  case 'delete':
	$cat = ClassFactory::ObjectNew('cat',$id);
	if ($cat instanceof cat)
	{
		$cat->Delete();
		$smarty->assign('displayfile','displaymessage.tpl');
		$smarty->assign("return","catadmin.php");
		$smarty->assign('message',"The cat was deleted from the database");
	}
	else
	{
		$smarty->assign("displayfile","displaymessage.tpl");
		$smarty->assign("return","catadmin.php");
		$smarty->assign("message","A problem has occurred");
	}
	break;
  case 'new':
  	$smarty->assign("typeoptions", array(0 => 'Hidden', 1 => 'Display', 2 => 'Checkout') );
	$smarty->assign("displayfile",'catform.tpl');
	$smarty->assign("cat", new cat() );
	$smarty->assign("action","savenew");
  	break;
  case 'edit':
	$cat = ClassFactory::ObjectNew('cat',$id);
	if ($cat instanceof cat)
	{	
	  	$smarty->assign("typeoptions", array(0 => 'Hidden', 1 => 'Display', 2 => 'Checkout') );
		$smarty->assign("displayfile",'catform.tpl');
		$smarty->assign("action","update");
		$smarty->assign("cat", $cat);
	}
	else
	{
		$smarty->assign("displayfile","displaymessage.tpl");
		$smarty->assign("return","catadmin.php");
		$smarty->assign("message","A problem has occurred");
	}
	break;
  case 'update':
  	$cat = new cat($_POST);
	if ($cat instanceof cat)
	{	
	  	$result = $cat->Save();
		$smarty->assign("displayfile","displaymessage.tpl");
		$smarty->assign("return","catadmin.php");
		$smarty->assign("message","Update Complete");
	}
	else
	{
		$smarty->assign("displayfile","displaymessage.tpl");
		$smarty->assign("return","catadmin.php");
		$smarty->assign("message","A problem has occurred");
	}
  	break;
  case 'savenew':
  	$cat = new cat($_POST);
	if ($cat instanceof cat)
	{	
	  	$result = $cat->Save();
		$smarty->assign("displayfile","displaymessage.tpl");
		$smarty->assign("return","catadmin.php");
		$smarty->assign("message","Insert Complete");
	}
	else
	{
		$smarty->assign("displayfile","displaymessage.tpl");
		$smarty->assign("return","catadmin.php");
		$smarty->assign("message","A problem has occurred");
	}
	break;
	
}
	
$smarty->display('main.tpl');


?>