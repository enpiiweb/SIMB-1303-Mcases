<?php





require_once("includes/includes.php");

global $siteroot;

$smarty = new SiteTemplate;



$action = isset($_GET['action']) ? $_GET['action'] : false;

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : false;





switch($action)

{

  case 'add':

  	$customer = new Customer;

	$smarty->assign('customerform_customer', $customer);

	$smarty->assign('main_displayfile', 'customerform_c.tpl');

	break;

/*  case 'edit':

  	$customer = ClassFactory::ObjectNew('customer',$id);

	$smarty->assign('customerform_customer', $customer);

	$smarty->assign('main_displayfile', 'customerform.tpl');

  	break;*/

  case 'save':



  	$customer = new Customer($_POST);

	$errors = $customer->Errors();



	if ($_POST['password'] != $_POST['confirmpassword'])

	{

		$errors['confirmpassword'] = "Passwords do not match.";

	}



/* zcoda: Filtering spammers. Modify if new ones apear.

*/





	if (strpos($_POST['address'], 'http://') !== false){

		$errors['address'] = "This must be your street address, not internet address.";

	}





/* end zcoda */



	if ($errors)

	{



		$smarty->assign('customerform_errors', $errors);

		$smarty->assign('customerform_customer', $customer);

		$smarty->assign('main_displayfile', 'customerform_c.tpl');

		break;

	}



	if ($customer instanceof Customer)

	{

	  	$result = $customer->Save();

		$ewayCustomerID		= "17816440";

		$ewayTotalAmount	= "25000";

		$return_url		= $siteroot."handlegateway.php";

		$site_title		= "Magistrates Cases";





	  	$smarty->assign("ewayCustomerID", $ewayCustomerID);

	  	$smarty->assign("ewayTotalAmount", $ewayTotalAmount);

	  	$smarty->assign("cust_firstname", $customer->fname);

	  	$smarty->assign("cust_lastname", $customer->lname);

	  	$smarty->assign("cust_email", $customer->email);

	  	$smarty->assign("cust_address", "$customer->address $customer->suburb $customer->state");

	  	$smarty->assign("cust_postcode", $customer->pcode);

	  	$smarty->assign("cust_invoiceref", $customer->id);

	  	$smarty->assign("invc_descript", "Magistrates Cases joining fee");

	  	$smarty->assign("return_url", $return_url);

	  	$smarty->assign("site_title", $site_title);



	  	$smarty->assign("customer", $customer);

		$smarty->assign("main_displayfile","signupsuccessful.tpl");

	}

	else

	{

		$smarty->assign("main_displayfile","displaymessage.tpl");

		$smarty->assign("displaymessage_return","singup.php");

		$smarty->assign("displaymessage_message","A problem has occurred");

	}

  	break;





}





AssignCategories($smarty);

$smarty->display('main.tpl');



function yearsArray()

{

	for ($i = 1969; $i < 2015; $i++)

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

