<?php
require_once("../includes/includes.php");
$template = new sitetemplate();

$page = "<h1>Customer Admin</h1><p><a href='index.php'>Admin Page</a></p>";


$action = isset($_REQUEST["action"])? $_REQUEST["action"] : false;
$customer = isset($_REQUEST["customer"])? $_REQUEST["customer"] : false;


switch($action)
{
	case "createuser":
		$c = ClassFactory::ObjectNew("Customer",$customer);
		$c->user = $c->CreateUser();
		$c->Save();
		$page .= "<p>User Account Created</p>";
		$page .= CustomerForm($c);
	break;
	
	case "Save":
		$c = new Customer($_POST);
		$c->status = 1;
		$c->Save();
		$page .= "<p>Customer Details Saved</p>";
		$page .= CustomerList();
	break;
		
	case "view":
		$c = ClassFactory::ObjectNew("Customer",$customer);
		$page .= CustomerDisplay($c);
	break;
	
	case "new":
		$c = new Customer();
		$page .= CustomerForm($c);
	break;

	case "edit":
		$c = ClassFactory::ObjectNew("Customer",$customer);
		$page .= CustomerForm($c);
	break;

	default:
		$page .= CustomerList();
	break;

}
$head = $template->do_header();
$foot = $template->do_footer();
echo $head.$page.$foot;



function CustomerForm($c, $errors = null)
{
	$ostatus_options = array("Full Time"=>"Full Time","Part Time"=>"Part Time");
	$mklevel_options = array("Independent Beauty Consultant"=>"Independent Beauty Consultant",
"Senior Consultant"=>"Senior Consultant",
"Team Builder"=>"Team Builder",
"Team Leader"=>"Team Leader",
"Team Manager"=>"Team Manager",
"Independent Sales Director in Qualification"=>"Independent Sales Director in Qualification",
"Independent Sales Director"=>"Independent Sales Director",
"Future Executive Senior Sales Director"=>"Future Executive Senior Sales Director",
"Executive Senior Sales Director"=>"Executive Senior Sales Director",
"Elite Executive Senior Sales Director"=>"Elite Executive Senior Sales Director",
"Independent National in Qualification"=>"Independent National in Qualification",
"Independent National Sales Director"=>"Independent National Sales Director",
"Independent Senior Sales Director"=>"Independent Senior Sales Director",
"Senior Independent National Sales Director"=>"Senior Independent National Sales Director");
	$mkstatus_options = array("Full Time"=>"Full Time","Part Time"=>"Part Time");
	$iaccess_options = array("Broadband"=>"Broadband","Dial Up"=>"Dial Up","None"=>"None");
	$wversion_options = array("XP"=>"XP","ME"=>"ME","2000"=>"2000","98"=>"98");
	$ccountry_options = array("Australia"=>"Australia","New Zealand"=>"New Zealand");
	$status_options = Customer::StatusMessage();


	$page = "<form name='purchase' method='POST' action='customer.php'>";
	
	$page .= "<table  border=0 width=100% cellspacing=0><thead><tr valign='top'><td width=50%><h3>Customer Details</h3></td><td width=50%><input type='hidden' name='invoicesame' value=1>";
	//$page .= "<h3>Invoice Details</h3><div class='note'><input type='checkbox' name='invoicesame' value=1 checked onClick='javascript:ToggleInvoice();'> (Same as customer)</div>";
	$page .= "</td></tr></thead>";
	$page .= "<tr><td>";
	
	$page .= "<table border=0><tr><td>First Name</td><td><input name='cfname' value='$c->cfname' maxlength='127' size=25>".(isset($errors["cfname"])? "<div class='error'>".$errors["cfname"]."</div>":"")."</td></tr>";
	$page .= "<table border=0><tr><td>Last Name</td><td><input name='clname' value='$c->clname' maxlength='127' size=25>".(isset($errors["clname"])? "<div class='error'>".$errors["clname"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Address</td><td><input name='caddress' value='$c->caddress' maxlength='127' size=25>".(isset($errors["caddress"])? "<div class='error'>".$errors["caddress"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Suburb</td><td><input name='csuburb' value='$c->csuburb' maxlength='127' size=25>".(isset($errors["csuburb"])? "<div class='error'>".$errors["csuburb"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>State</td><td><input name='cstate' value='$c->cstate' maxlength='127' size=25>".(isset($errors["cstate"])? "<div class='error'>".$errors["cstate"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Country</td><td>".do_select($ccountry_options,$c->ccountry,"ccountry").(isset($errors["ccountry"])? "<div class='error'>".$errors["ccountry"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Postcode</td><td><input name='cpcode' value='$c->cpcode' maxlength='127' size=25>".(isset($errors["cpcode"])? "<div class='error'>".$errors["cpcode"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Phone</td><td><input name='cphone' value='$c->cphone' maxlength='127' size=25>".(isset($errors["cphone"])? "<div class='error'>".$errors["cphone"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Mobile</td><td><input name='cmobile' value='$c->cmobile' maxlength='127' size=25>".(isset($errors["cmobile"])? "<div class='error'>".$errors["cmobile"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Fax</td><td><input name='cfax' value='$c->cfax' maxlength='127' size=25>".(isset($errors["cfax"])? "<div class='error'>".$errors["cfax"]."</div>":"")."</td></tr></table>";
	
	
	$page .= "</td><td>&nbsp;";
	$page .= "</td></td></tr></table><h3>Additional Information</h3><table border=0 cellspacing=0 cellpadding=3 width=100%>";
	
	$page .= "<tr><td>Occupation</td><td><input name='occupation' value='$c->occupation' maxlength='127' size=25> ".do_select($ostatus_options,$c->ostatus,"ostatus").(isset($errors["occupation"])? "<div class='error'>".$errors["occupation"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>MK Career Level</td><td>".do_select($mklevel_options,$c->mklevel,"mklevel")." ".do_select($mkstatus_options,$c->mkstatus,"mkstatus").(isset($errors["mklevel"])? "<div class='error'>".$errors["mklevel"]."</div>":"")."</td></tr>";
	$page .= "<tr><td colspan=2>Number of consultants under your direct business line (if applicable) <input name='numconsultants' value='$c->numconsultants' maxlength='127' size=5>".(isset($errors["numconsultants"])? "<div class='error'>".$errors["numconsultants"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Your Director (or Senior Director)</td><td><input name='director' value='$c->director' maxlength='127' size=25>".(isset($errors["director"])? "<div class='error'>".$errors["director"]."</div>":"")."</td></tr>";
	$page .= "<tr><td colspan=2>Average time spent working on Mary Kay business per week <input name='mktime' value='$c->mktime' maxlength='127' size=10>".(isset($errors["mktime"])? "<div class='error'>".$errors["mktime"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Internet Access</td><td>".do_select($iaccess_options,$c->iaccess,"iaccess").(isset($errors["iaccess"])? "<div class='error'>".$errors["iaccess"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Microsoft Windows Version</td><td>".do_select($wversion_options,$c->wversion,"wversion").(isset($errors["wversion"])? "<div class='error'>".$errors["wversion"]."</div>":"")."</td></tr>";
	$page .= "</table>";

	$page .= "<h3>Account Information</h3><table border=0 cellspacing=0 cellpadding=3 width=100%>";
	$page .= "<tr><td>Email</td><td><input name='email' value='$c->email' maxlength='127' size=35>".(isset($errors["email"])? "<div class='error'>".$errors["email"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Password</td><td><input name='password' value='$c->password' maxlength='15' size=25>".(isset($errors["password"])? "<div class='error'>".$errors["password"]."</div>":"")."</td></tr>";
	$page .= "</table>";
		$page .= "<h3>Account Status</h3><table border=0 cellspacing=0 cellpadding=3 width=100%>";
		$page .= "<tr><td>Customer Status</td><td>".do_select($status_options,$c->status,"status").(isset($errors["status"])? "<div class='error'>".$errors["status"]."</div>":"")."</td></tr>";
	if($c->id)
	{
		$page .= "<tr><td>User Account</td><td>";
		$page .= ($c->user)? "<a href='users.php?action=edituser&user=$c->user&group=1'>Created</a>":"<b>No Account.</b> <a href='customer.php?action=createuser&customer=$c->id'>Create User Account</a> (this will automaticaly allow one download of MKONE.exe).";
		$page .= "</td></tr>";
	}
		$page .= "</table>";
	$page .= "<p><input type='submit' name='action' value='Save'> <a href='customer.php'>Cancel</a></p>";
	$page .= "</form>";
	return $page;
}



function CustomerDisplay($c)
{
	$page = "<p><a href='customer.php'>Return To List</a> - <a href='customer.php?action=edit&customer=$c->id'>Edit</a></p>";
	$page .= "<h4>Customer Details</h4><table border=1 cellspacing=0 cellpadding=3 width=500><tr><td>Name</td><td>$c->cfname, $c->clname</td></tr>";
	$page .= "<tr><td width=20%>Address</td><td>$c->caddress</td></tr>";
	$page .= "<tr><td>Suburb</td><td>$c->csuburb</td></tr>";
	$page .= "<tr><td>State</td><td>$c->cstate</td></tr>";
	$page .= "<tr><td>Country</td><td>$c->ccountry</td></tr>";
	$page .= "<tr><td>Postcode</td><td>$c->cpcode</td></tr>";
	$page .= "<tr><td>Phone</td><td>$c->cphone</td></tr>";
	$page .= "<tr><td>Mobile</td><td>$c->cmobile</td></tr>";
	$page .= "<tr><td>Fax</td><td>$c->cfax</td></tr></table>";
	
	$page .= "<h4>Additional Information</h4><table border=1 cellspacing=0 cellpadding=3 width=500>";
	
	$page .= "<tr><td>Occupation</td><td>$c->occupation ($c->ostatus)</td></tr>";
	$page .= "<tr><td>MK Career Level</td><td>$c->mklevel ($c->mkstatus)</td></tr>";
	$page .= "<tr><td>Your Director (or Senior Director)</td><td>$c->director</td></tr>";
	$page .= "<tr><td colspan=2>Number of consultants under your direct business line (if applicable) $c->numconsultants</td></tr>";
	$page .= "<tr><td colspan=2>Average time spent working on Mary Kay business per week $c->mktime</td></tr>";
	$page .= "<tr><td>Internet Access</td><td>$c->iaccess</td></tr>";
	$page .= "<tr><td>Microsoft Windows Version</td><td>$c->wversion</td></tr>";
	$page .= "</table>";

	$page .= "<h4>Account Information</h4><table border=0 cellspacing=0 cellpadding=3 width=500";
	$page .= "<tr><td>Email</td><td>$c->email</td></tr></table>";
	
	$page .= "<h3>Account Status</h3><table border=0 cellspacing=0 cellpadding=3 width=100%>";
	$page .= "<tr><td>Customer Status</td><td>".Customer::StatusMessage($c->status)."</td></tr>";
	$page .= "<tr><td>User Account</td><td>";
	$page .= ($c->user)? "Created.":"<b>No Account</b>.";
	$page .= "</td></tr>";
	$page .= "</table>";
	
	$page .= "<p><a href='customer.php'>Return To List</a> - <a href='customer.php?action=edit&customer=$c->id'>Edit</a></p>";
	return $page;
}

function CustomerList()
{
	$c_ar = ClassFactory::ObjectArray("Customer",true,"order by clname, cfname");
	$page = "<p><a href='customer.php?action=new'>New customer</a></p><table>";
	if(is_array($c_ar))
	{
		foreach($c_ar as $cid=>$customer)
		{
			$page .= "<tr><td>[<i>".Customer::StatusMessage($customer->status)."</i>]</td><td>$customer->clname, $customer->cfname</td><td><a href='customer.php?action=view&customer=$cid'>View</a> <a href='customer.php?action=edit&customer=$cid'>Edit</a> <a href='downloads.php?view=customer&customer=$cid'>Downloads</a></td></tr>";
		
		}
	} else {
		$page .= "<tr><td>No customers to list.</td></tr>";
	}
	$page .= "</table>";
	
	return $page;
}