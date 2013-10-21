<?php
require_once("includes/includes.php");
$template = new sitetemplate();

$page = "<h2>Thankyou</h2>";



$action = isset($_REQUEST["action"])? $_REQUEST["action"] : false;


switch($action)
{
	case "Approve":
		$c = ClassFactory::ObjectNew("Customer",$_POST["customer"]);
		if($c)
		{
			$c->status=3;
			$c->Save();
			$c->CreateUser();
			$page .= "<p>Approved. You may now log in to the site and download the appplication.</p>";
		}
	break;
	
	case "Decline":
		$c = ClassFactory::ObjectNew("Customer",$_POST["customer"]);
		if($c)
		{
			$c->status=-1;
			$c->Save();
		}
		$page .= "<p>Declined. Please check your credit card details and try again.</p>";
	break;
	
	default:
		$c = ClassFactory::ObjectNew("Customer",$_POST["customer"]);
		if($c)
		{
			$c->status=-2;
			$c->Save();
		}
		$page .= "<p>Error. Please check your credit card details and try again.</p>";
	break;




}
$head = $template->do_header();
$foot = $template->do_footer();
echo $head.$page.$foot;