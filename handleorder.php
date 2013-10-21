<?php
require_once("includes/includes.php");

function db_input($input)
{
	if (get_magic_quotes_gpc())
		return $input;
	else
		return addslashes($input);
}

$smarty = new SiteTemplate;
$ewayTrxnStatus = isset($_REQUEST['ewayTrxnStatus']) ? $_REQUEST['ewayTrxnStatus'] : false;
$ewayTrxnNumber = isset($_REQUEST['ewayTrxnNumber']) ? $_REQUEST['ewayTrxnNumber'] : false;
$ewayTrxnReference = isset($_REQUEST['ewayTrxnReference']) ? $_REQUEST['ewayTrxnReference'] : false;
$eWAYresponseCode = isset($_REQUEST['eWAYresponseCode']) ? $_REQUEST['eWAYresponseCode'] : false;
$eWAYReturnAmount = isset($_REQUEST['eWAYReturnAmount']) ? $_REQUEST['eWAYReturnAmount'] : false;
$eWAYAuthCode = isset($_REQUEST['eWAYAuthCode']) ? $_REQUEST['eWAYAuthCode'] : false;
$eWAYresponseText = isset($_REQUEST['eWAYresponseText']) ? $_REQUEST['eWAYresponseText'] : false;
$eWAYoption1 = isset($_REQUEST['eWAYoption1']) ? $_REQUEST['eWAYoption1'] : false;
$db = new DBInterface();

if ($ewayTrxnStatus !== false)  {
	$sql = "INSERT INTO orders
					SET
						transaction_status = '" . db_input($ewayTrxnStatus) . "',
						transaction_number = '" . db_input($ewayTrxnNumber) . "',
						transaction_reference = '" . db_input($ewayTrxnReference) . "',
						response_code = '" . db_input($eWAYresponseCode) . "',
						response_text = '" . db_input($eWAYresponseText) . "',
						return_amount = '" . db_input($eWAYReturnAmount) . "',
						auth_code = '" . db_input($eWAYAuthCode) . "',
						option1 = '" . db_input($eWAYoption1) . "'";
	$db->DBUpdate($sql);
}
if ( $ewayTrxnStatus == 'True')  {
	$smarty->assign('main_displayfile', 'paymentsuccessful.tpl');
} else {
	echo $ewayTrxnStatus;
	$smarty->assign('main_displayfile', 'paymentfail.tpl');
}

AssignCategories($smarty);
$smarty->display('main.tpl');
?>