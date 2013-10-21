<?php
require_once("includes/includes.php");
$template = new sitetemplate();

$page = "<h1>Purchase MKOne</h1>";


$script = "
function CheckForm()
{
	if(document.getElementById)
	{
		var el = document.getElementById('chb_readtearms');
		var el2 = document.getElementById('readterms_warning');
		if(el.checked)
			return true;
		else
		{
			alert('You have not accepted the teams and conditions by checking the indicated box.');
			el2.style.display='box';
		}
	}
	return false;
}

function ClickedTerms()
{
	if(document.getElementById)
	{
		var el = document.getElementById('chb_readtearms');
		var el2 = document.getElementById('readterms_warning');
		if(el.checked)
			el2.style.display='none';
		else
			el2.style.display='box';
	}
}
";


$template->AddJavaFunction($script);





$action = isset($_REQUEST["action"])? $_REQUEST["action"] : false;


switch($action)
{
	case "Pay Online Now":
		$c = new Customer($_POST);
		$c->status = 1;
		$c->Save();
		$page .= CCForm($c);
	break;
	
	case "Print Faxable Form":
		$c = new Customer($_POST);
		$c->status = 0;
		$c->Save();
		$to = "andrew@veridon.com, nicolee@mkone.com.au\r\n";
		$headers = "To: Veridon <andrew@veridon.com>, MKONE <nicolee@mkone.com.au>\r\n";
		$headers .= "From: MKONE <admin@mkone.com.au>\r\n";
		$subject = "MKONE Fax Order";
		$message = TextReceipt($c);
		mail($to, $subject, $message, $headers);
		$page .= PrintableForm($c);
	break;
	
	default:
		$c = new Customer();
		$page .= PurchaseForm($c);

	break;




}
$head = $template->do_header();
$foot = $template->do_footer();
echo $head.$page.$foot;



function PurchaseForm($c, $errors = null)
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



	$page = "<form name='purchase' method='POST' action='purchase.php'>";
	
	$page .= "<table  border=0 width=100% cellspacing=0><thead><tr valign='top'><td width=50%><h3>Customer Details</h3></td><td width=50%><input type='hidden' name='invoicesame' value=1>";
	//$page .= "<h3>Invoice Details</h3><div class='note'><input type='checkbox' name='invoicesame' value=1 checked onClick='javascript:ToggleInvoice();'> (Same as customer)</div>";
	$page .= "</td></tr></thead>";
	$page .= "<tr><td>";
	
	$page .= "<table border=0><tr><td>First Name</td><td><input name='cfname' value='$c->cfname' maxlength='127' size=25>".(isset($errors["cfname"])? "<div class='error'>".$errors["cfname"]."</div>":"")."</td></tr>";
	$page .= "<table border=0><tr><td>Last Name</td><td><input name='clname' value='$c->clname' maxlength='127' size=25>".(isset($errors["clname"])? "<div class='error'>".$errors["clname"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Address</td><td><input name='caddress' value='$c->caddress' maxlength='127' size=25>".(isset($errors["caddress"])? "<div class='error'>".$errors["caddress"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Suburb</td><td><input name='csuburb' value='$c->csuburb' maxlength='127' size=25>".(isset($errors["csuburb"])? "<div class='error'>".$errors["csuburb"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>State</td><td><input name='cstate' value='$c->cstate' maxlength='127' size=25>".(isset($errors["cstate"])? "<div class='error'>".$errors["cstate"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Country</td><td>".do_select($ccountry_options,$c->ccountry,"ccountry").(isset($errors["occupation"])? "<div class='error'>".$errors["occupation"]."</div>":"")."</td></tr>";
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
	$page .= "<tr><td>Repeat Email</td><td><input name='repeatemail' value='$c->repeatemail' maxlength='127' size=35>".(isset($errors["repeatemail"])? "<div class='error'>".$errors["repeatemail"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Password</td><td><input name='password' value='".$_POST['password']."' maxlength='15' size=25>".(isset($errors["password"])? "<div class='error'>".$errors["password"]."</div>":"")."</td></tr>";
	$page .= "<tr><td>Repeat Password</td><td><input name='password2' value='".$_POST['password2']."' maxlength='15' size=25>".(isset($errors["password2"])? "<div class='error'>".$errors["password2"]."</div>":"")."</td></tr>";
	$page .= "</table>";

	$page .= "<h3>Purchase Options</h3>";
	$page .= "<p><input type='radio' name='product' value='single' checked> <b>\$189 Single User</b></p>";
	$page .= "<p><input type='radio' name='product' value='multi'> <b>\$269 Multi User</b></p>";
	$page .= "<p><input type='checkbox' disabled checked name='upgrade' value='1'> <b><span style='text-decoration:line-through;'>\$59</span> \$0 Technical Support and future upgrade</b> (Free if you buy before 31st March 2006)</p>";
	$page .= "<p style='font-size:0.8em;'><a href='mkoneterms.pdf' target='_blank'>Terms and Conditions</a></p>";
	
	$page .= "<p><div id='readterms_warning' style='display:none;color:red;'>You must accept the terms and conditions before you can purchase the MKOne software.</div>";
	$page .= "<input type='checkbox' name='readterms' id='chb_readtearms' value=1 onClick='ClickedTerms();'> <b>I have read the terms and conditions provided by the link above.</b></p>";
	//$page .= "<p><input type='submit' name='action' value='Pay Online Now'> <input type='submit' name='action' value='Print Faxable Form'></p>";
	$page .= "<p><input type='submit' name='action' value='Print Faxable Form' onClick='return CheckForm();'></p>";
	$page .= "</form>";
	return $page;
}


function PrintableForm($c)
{
	$page = "<html><head><title>MKOne Fax Order Form</title><style type='text/css'>body,td,p,div {font-size:12px;}</style></head><body><div style='width=500px'>
	<h3>MKOne Fax Order Form - FAX: 03 8711 3768</h2><h4>Customer Details</h4><table  border=0 width=500 cellspacing=0>";
	$page .= "<tr valign=top><td>";
	
	$page .= "<table border=1 cellspacing=0 cellpadding=3 width=500><tr><td>Name</td><td>$c->cfname, $c->clname</td></tr>";
	$page .= "<tr><td width=20%>Address</td><td>$c->caddress</td></tr>";
	$page .= "<tr><td>Suburb</td><td>$c->csuburb</td></tr>";
	$page .= "<tr><td>State</td><td>$c->cstate</td></tr>";	
	$page .= "<tr><td>Country</td><td>$c->ccountry</td></tr>";
	$page .= "<tr><td>Postcode</td><td>$c->cpcode</td></tr>";
	$page .= "<tr><td>Phone</td><td>$c->cphone</td></tr>";
	$page .= "<tr><td>Mobile</td><td>$c->cmobile</td></tr>";
	$page .= "<tr><td>Fax</td><td>$c->cfax</td></tr></table>";
	
	$page .= "</td></td>";
	$page .= "</tr></table><h4>Additional Information</h4><table border=1 cellspacing=0 cellpadding=3 width=500>";
	
	$page .= "<tr><td>Occupation</td><td>$c->occupation ($c->ostatus)</td></tr>";
	$page .= "<tr><td>MK Career Level</td><td>$c->mklevel ($c->mkstatus)</td></tr>";
	$page .= "<tr><td>Your Director (or Senior Director)</td><td>$c->director</td></tr>";
	$page .= "<tr><td colspan=2>Number of consultants under your direct business line (if applicable) $c->numconsultants</td></tr>";
	$page .= "<tr><td colspan=2>Average time spent working on Mary Kay business per week $c->mktime</td></tr>";
	$page .= "<tr><td>Internet Access</td><td>$c->iaccess</td></tr>";
	$page .= "<tr><td>Microsoft Windows Version</td><td>$c->wversion</td></tr>";
	$page .= "</table>";

	$page .= "<h4>Account Information</h4><table border=0 cellspacing=0 cellpadding=3 width=500";
	$page .= "<tr><td>Email</td><td>$c->email</td></tr>";
	$page .= "<tr><td>Password</td><td>".$_POST['password']."</td></tr></table>";
	$page .= "<h4>Purchase Options</h4>";
	if($_POST["product"]=="single")
	{
		$page .= "<p><b>\$189 Single User</b></p>";
	} else {
		$page .= "<p><b>\$269 Multi User</b></p>";
	}
	$page .= "<p><b><span style='text-decoration:line-through;'>\$59</span> \$0 Technical Support and future upgrade</b> (Free if you buy before 31st March 2006)</p>";
	$page .= "<p><img src='images/paymentinfo.jpg'></p></div></body></html>";
	echo $page;
	exit();
}

function TextReceipt($c)
{
	$page = "MKOne Fax Order Form
	
Name: $c->cfname, $c->clname
Address: $c->caddress
Suburb: $c->csuburb
State: $c->cstate
Country: $c->ccountry
Postcode: $c->cpcode
Phone: $c->cphone
Mobile: $c->cmobile
Fax: $c->cfax


Additional Information
Occupation: $c->occupation ($c->ostatus)
MK Career Level: $c->mklevel ($c->mkstatus)
Your Director (or Senior Director): $c->director
Number of consultants under your direct business line (if applicable): $c->numconsultants
Average time spent working on Mary Kay business per week: $c->mktime
Internet Access: $c->iaccess
Microsoft Windows Version: $c->wversion


Account Information
Email: $c->email
Password: ".$_POST['password']."


Purchase Options

";
	if($_POST["product"]=="single")
	{
		$page .= "\$189 Single User";
	} else {
		$page .= "\$269 Multi User";
	}
	$page .= "
	\$59.95 \$0 Technical Support and future upgrade (Free if you buy before 31st March 2006)";
	return $page;
}

function CCForm($c)
{
	$page = "<form name='purchase' method='POST' action='thankyou.php'>";
	$page .= "<input type='hidden' name='customer' value='$c->id'><input type='hidden' name='login' value='".$_POST['login']."'><input type='hidden' name='password' value='".$_POST['password']."'><input type='hidden' name='name' value='$c->name'><input type='hidden' name='email' value='$c->cemail'>";
	$page .= "<p>Approve Transaction?<br><input type='submit' name='action' value='Approve'> <input type='submit' name='action' value='Decline'> <a href='purchase.php'>Cancel</a></p>";
	
	$page .= "</form>";
	return $page;
}