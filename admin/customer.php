<?php


require_once("../includes/includes.php");

$smarty = new SiteTemplate;

AssignCategories($smarty);
Authorise($smarty,"index.php","Admin");


$action = isset($_GET['action']) ? $_GET['action'] : false;
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : false;


switch($action)
{
  case 'add':
  	$customer = new Customer;
	$smarty->assign('customerform_customer', $customer);
	$smarty->assign('main_displayfile', 'customerform.tpl');
	break;
  case 'edit':
  	$customer = ClassFactory::ObjectNew('customer',$id);
	$smarty->assign('customerform_customer', $customer);
	$smarty->assign('main_displayfile', 'customerform.tpl');
  	break;
  case 'save':


  	$customer = new Customer($_POST);
	$errors = $customer->Errors();

	if ($_POST['password'] != $_POST['confirmpassword'])
	{
		$errors['confirmpassword'] = "Passwords do not match";
	}

	if ($errors)
	{

		$smarty->assign('customerform_errors', $errors);
		$smarty->assign('customerform_customer', $customer);
		$smarty->assign('main_displayfile', 'customerform.tpl');
		break;
	}

	if ($customer instanceof Customer)
	{	
	  	$result = $customer->Save();
	  	
		$smarty->assign("main_displayfile","displaymessage.tpl");
		$smarty->assign("displaymessage_return","customer.php");
		$smarty->assign("displaymessage_message","Update Complete");
	}
	else
	{
		$smarty->assign("main_displayfile","displaymessage.tpl");
		$smarty->assign("displaymessage_return","customer.php");
		$smarty->assign("displaymessage_message","A problem has occurred");
	}
  	break;

  case 'confirmdelete':
  	$customer = ClassFactory::ObjectNew('Customer',$id);
	$smarty->assign("main_displayfile","customerconfirmdelete.tpl");
	$smarty->assign('customerconfirmdelete_customer', $customer);

  	break;
  case 'delete':
  	$customer = ClassFactory::ObjectNew('Customer',$id);
  	$customer->Delete();
	$smarty->assign("main_displayfile","displaymessage.tpl");
	$smarty->assign("displaymessage_return","customer.php");
	$smarty->assign("displaymessage_message","customer has been deleted");
	break;  	
  default:
  	$customer = ClassFactory::ObjectArray('Customer');
	$smarty->assign('customerlist_customer', $customer);
	$smarty->assign('main_displayfile', 'customerlist.tpl');
	break;

}


$smarty->display('main.tpl');

function yearsArray()
{
	for ($i = 1950; $i < 2020; $i++)
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