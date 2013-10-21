<?php
require_once("includes/includes.php");
$smarty = new SiteTemplate();

$price = "100.00";
$books = array();
$books[] = "Cumulative Index 1970-2012";
$books[] = "1975-76 Magistrates Cases";
$books[] = "1977-78 Magistrates Cases";
$books[] = "1979-80 Magistrates Cases";
$books[] = "1981-82 Magistrates Cases";
$books[] = "1983-84 Magistrates Cases";
$books[] = "1985-86 Magistrates Cases";
$books[] = "1987-88 Magistrates Cases";
$books[] = "1989-90 Magistrates Cases";
$books[] = "1991-92 Magistrates Cases";
$books[] = "1993-94 Magistrates Cases";
$books[] = "1995-96 Magistrates Cases";
$books[] = "1997-98 Magistrates Cases";
$books[] = "1999 Magistrates Cases";
$books[] = "2000 Magistrates Cases";
$books[] = "2001 Magistrates Cases";
$books[] = "2002 Magistrates Cases";
$books[] = "2003 Magistrates Cases";
$books[] = "2004 Magistrates Cases";
$books[] = "2005 Magistrates Cases";
$books[] = "2006 Magistrates Cases";
$books[] = "2007 Magistrates Cases";
$books[] = "2008 Magistrates Cases";
$books[] = "2009 Magistrates Cases";
$books[] = "2010 Magistrates Cases";
$books[] = "2011 Magistrates Cases";
$books[] = "2012 Magistrates Cases";

$action = isset($_POST['action']) ? $_POST['action'] : false;
$id = isset($_POST['id']) ? $_POST['id'] : false;
$site_title = "Magistrates Cases";

if ($action == "order")  {
	$order = array();
	$order_errors = array();
	$valid = true;
	$order['quantity'] = isset($_POST['quantity']) ? $_POST['quantity'] : false;
	$order['fname'] = isset($_POST['fname']) ? $_POST['fname'] : false;
	$order['lname'] = isset($_POST['lname']) ? $_POST['lname'] : false;
	$order['address'] = isset($_POST['address']) ? $_POST['address'] : false;
	$order['suburb'] = isset($_POST['suburb']) ? $_POST['suburb'] : false;
	$order['state'] = isset($_POST['state']) ? $_POST['state'] : false;
	$order['pcode'] = isset($_POST['pcode']) ? $_POST['pcode'] : false;
	$order['email'] = isset($_POST['email']) ? $_POST['email'] : false;
	$order['id'] = isset($_POST['id']) ? $_POST['id'] : false;
	$order['total_quantity'] = 0;

	foreach ($order['quantity'] as $q)  {
		if ($q < 0)  {
			$order_errors['quantity'] = "You cannot order a negative quantity.";
			$valid = false;
		}
		else if (!is_numeric($q))  {
			$order_errors['quantity'] = "The quantity must be a number.";
			$valid = false;
		}
		else
			$order['total_quantity'] += $q;
	}
	if ($valid && (!$order['quantity'] || $order['total_quantity'] < 1))  {
		$order_errors['quantity'] = "You must enter a quantity greater than zero.";
		$valid = false;
	}
	if (!$order['fname'])  {
		$order_errors['fname'] = "A first name is required.";
		$valid = false;
	}
	if (!$order['lname'])  {
		$order_errors['lname'] = "A last name is required.";
		$valid = false;
	}
	if (!$order['address'])  {
		$order_errors['address'] = "An address is required.";
		$valid = false;
	}
	if (!$order['suburb'])  {
		$order_errors['suburb'] = "A suburb is required.";
		$valid = false;
	}
	if (!$order['state'])  {
		$order_errors['state'] = "A state is required.";
		$valid = false;
	}
	if (!$order['pcode'])  {
		$order_errors['pcode'] = "A postcode is required.";
		$valid = false;
	}
	if (!$order['email'])  {
		$order_errors['email'] = "An email address is required.";
		$valid = false;
	}

	if ($valid)  {
		$summary = array();
		$order['text_summary'] = "";
		$i = 0;
		foreach ($order['quantity'] as $index => $q)  {
			if ($q > 0)  {
				$summary[$i]['book'] = $books[$index];
				$summary[$i]['quantity'] = $q;
				$order['text_summary'] .= $q . "x " . $books[$index] . ", ";
				$i++;
			}
		}
		$order['summary'] = $summary;

		$ewayCustomerID = "17816440";
		$ewayTotalAmount = $order['total_quantity'] * $price * 100;
		$order['total'] = $order['total_quantity'] * $price;
		$order['text_summary'] .= "Total=AUD" . $order['total'];
		$return_url = $siteroot . "handleorder.php";

		$smarty->assign("ewayCustomerID", $ewayCustomerID);
		$smarty->assign("ewayTotalAmount", $ewayTotalAmount);
		$smarty->assign("cust_firstname", $order['fname']);
		$smarty->assign("cust_lastname", $order['lname']);
		$smarty->assign("cust_email", $order['email']);
		$smarty->assign("cust_address", $order['address'] . " " . $order['suburb'] . " " . $order['state']);
		$smarty->assign("cust_postcode", $order['pcode']);
		$smarty->assign("cust_invoiceref", $order['id']);
		$smarty->assign("invc_descript", $order['text_summary']);
		$smarty->assign("return_url", $return_url);
		$smarty->assign("order", $order);
		$smarty->assign("main_displayfile","confirmorder.tpl");
	}
	else  {
		$smarty->assign("order", $order);
		$smarty->assign("order_errors", $order_errors);
		$smarty->assign("main_displayfile",'boundcopies.html');
	}
}
else  {
	$smarty->assign("main_displayfile",'boundcopies.html');
}

$smarty->assign("site_title", $site_title);
$smarty->assign("books",$books);
$smarty->assign("price",$price);
AssignCategories($smarty);
$smarty->display('main.tpl');
?>