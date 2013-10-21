<?php
  include ('../includes/includes.php');
$template = new SiteTemplate();

Authorise($template,"index.php","Admin",admin_login_form());

$page = "<h1>Download Admin</h1><p><a href='index.php'>Admin Page</a></p>";

$view = isset($_REQUEST["view"]) ? $_REQUEST["view"] : false;

switch($view)
{
	case "customer":
		$page .= CustomerView();
	break;
	
	default:
		$page .= FileView();
	break;
}

$head = $template->do_header();
$foot = $template->do_footer();
echo $head.$page.$foot;


function CustomerView()
{
	$customer = isset($_REQUEST["customer"]) ? $_REQUEST["customer"] : false;
	$action = isset($_REQUEST["customeraction"]) ? $_REQUEST["customeraction"] : false;
	if(!$customer)
		return "<p>No customer specified</p>";
	$c = ClassFactory::ObjectNew("Customer",$customer);
	if(get_class($c)!="Customer")
		return "<p>Invalid customer specified.</p>";
	switch($action)
	{
		case "Save Downloads":
			$dla = new DownloadAuth($_POST);
			$res = $dla->Save();
			$page = "<p><b>Downloads Saved</b><br><a href='customer.php'>Go to customer admin.</a></p>";
		break;
		
		default:
			$page = CustomerDownloads($c);
		break;
	}
	return $page;
}

function CustomerDownloads($c)
{
	if(!$c->user)
	{
		return "<p><b>This customer does not have a user account.</b></p><p><a href='customer.php'>Click Here</a> to return to the customer admin page.</p>";
	}
	$page = "<form name='downloads' method='POST' action='downloads.php'><h3>$c->cfname $c->clname</h3><h4>Downloads Remaining</h4>";
	$dl_ar = ClassFactory::ObjectArray("DownloadAuth",true,"where user='$c->user' and file='MKONE.exe'");
	$page .= "<p>-1 indicates unlimited downloads.</p>";
	if(is_array($dl_ar))
	{
		$dla = current($dl_ar);
		$page .= "<p><b>$dla->file</b> <input type='hidden' name='file' value='MKONE.exe'><input type='hidden' name='id' value='$dla->id'><input name='downloads' value='".$dla->downloads."' size=2 maxlength=4> Downloads remaining.</p>";
		$page .= "<p><input type='submit' name='customeraction' value='Save Downloads'></p>";
		
	} else {
		$page .= "<p><b>MKONE.exe</b> <input type='hidden' name='file' value='MKONE.exe'><input name='downloads' value='0' size=2 maxlength=4> Downloads remaining.</p>";
		$page .= "<input type='submit' name='customeraction' value='Save Downloads'>";
	}
	$page .= "<input type='hidden' name='customer' value='$c->id'><input type='hidden' name='user' value='$c->user'><input type='hidden' name='view' value='customer'></form>";
	return $page;
}

function FileView()
{
	return "fileview";
}




?>