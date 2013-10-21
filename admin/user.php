<?php


require_once("../includes/includes.php");

$smarty = new SiteTemplate;

AssignCategories($smarty);
Authorise($smarty,"index.php","Admin");


$action = isset($_GET['action']) ? $_GET['action'] : false;
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : false;


switch($action)
{
/*  case 'add':
  	$customer = new Customer;
	$smarty->assign('customerform_customer', $customer);
	$smarty->assign('main_displayfile', 'customerform.tpl');
	break;*/
  case 'new';
	$smarty->assign('main_displayfile', 'userform2.tpl');
  	break;
  case 'add':
	$user = new user();
	$user->usergroup = 1;
	$user->name = $_POST['nam'];
	$user->login = $_POST['log'];
	$user->email = $_POST['ema'];
	$user->password = $_POST['pas'];
	$user->usergroup = 1;
	$res = $user->Save();
  	$user = ClassFactory::ObjectArray('user');
	$smarty->assign('userlist_users', $user);
	$smarty->assign('main_displayfile', 'userlist.tpl');
  	break;
  case 'edit':
  	$user = ClassFactory::ObjectNew('user',$id);
	$smarty->assign('userform_user', $user);
	$smarty->assign('main_displayfile', 'userform.tpl');
  	break;
  case 'save':

  	$user = ClassFactory::ObjectNew('user',$id);

	$user->email = $_POST['email'];
	$user->login = $_POST['login'];


	$user->save_password = false;
	$errors = $user->Errors();

	print_r($errors);

	if ($errors)
	{

		$smarty->assign('userform_errors', $errors);
		$smarty->assign('userform_user', $user);
		$smarty->assign('main_displayfile', 'userform.tpl');
		break;
	}

	if ($user instanceof user)
	{	
//		print_r($user);
	  	$result = $user->Save();
		$smarty->assign("main_displayfile","displaymessage.tpl");
		$smarty->assign("displaymessage_return","index.php");
		$smarty->assign("displaymessage_message","Update Complete");
	}
	else
	{
		$smarty->assign("main_displayfile","displaymessage.tpl");
		$smarty->assign("displaymessage_return","index.php");
		$smarty->assign("displaymessage_message","A problem has occurred");
	}
  	break;

  case 'confirmdelete':
  	$user = ClassFactory::ObjectNew('user',$id);
	$smarty->assign("main_displayfile","userconfirmdelete.tpl");
	$smarty->assign('userconfirmdelete_user', $user);
  	break;
  case 'delete':
  	$user = ClassFactory::ObjectNew('user',$id);
  	$user->Delete();
	$smarty->assign("main_displayfile","displaymessage.tpl");
	$smarty->assign("displaymessage_return","user.php");
	$smarty->assign("displaymessage_message","user has been deleted");
	break;  	
  case 'resetpasswordform':
  	$user = ClassFactory::ObjectNew('user',$id);
	$smarty->assign('passwordform_user', $user);
	$smarty->assign('main_displayfile', 'passwordform.tpl');
	break;


  case 'resetpasswordsave':
  	$user = ClassFactory::ObjectNew('user',$id);
	$user->password = $_POST['password'];
	$user->save_password = true;
	$errors = $user->Errors();

  	print_r($errors);

	if ($errors)
	{
		$smarty->assign('passwordform_errors', $errors);
		$smarty->assign('passwordform_user', $user);
		$smarty->assign('main_displayfile', 'passwordform.tpl');
		break;
	}

	if ($user instanceof user)
	{	
	  	$result = $user->Save();
	  	
		EmailCenter::SendTemplateEmail("email_resetpassword",$user,"dev@veridon.com","dev@veridon.com");
		$smarty->assign("main_displayfile","displaymessage.tpl");
		$smarty->assign("displaymessage_return","index.php");
		$smarty->assign("displaymessage_message","Password has been changed and emailed to the user");
	}
	else
	{
		$smarty->assign("main_displayfile","displaymessage.tpl");
		$smarty->assign("displaymessage_return","index.php");
		$smarty->assign("displaymessage_message","A problem has occurred");
	}
	break;	
	
  default:
  	$user = ClassFactory::ObjectArray('user');
	$smarty->assign('userlist_users', $user);
	$smarty->assign('main_displayfile', 'userlist.tpl');
	break;

}


$smarty->display('main.tpl');

function yearsArray()
{
	for ($i = 1968; $i < 2020; $i++)
	{	$years[$i]= $i;	}
	
	return $years;
}

function monthsArray()
{
	return array (	1 => "January",	2 => "February",	3 => "March",	
				4 => "April",	5 => "May",		6 => "June",
				7 => "July",	8 => "August",	9 => "September",
				10 => "October",	11 => "November",	12 => "December");

}

function daysArray()
{
	for ($i = 1; $i < 32; $i++)
	{	$days[$i]= $i;	}
	
	return $days;
}

?>