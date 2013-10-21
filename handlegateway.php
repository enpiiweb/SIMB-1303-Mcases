<?php


require_once("includes/includes.php");

$smarty = new SiteTemplate;
$ewayTrxnStatus = isset($_REQUEST['ewayTrxnStatus']) ? $_REQUEST['ewayTrxnStatus'] : false;
$ewayTrxnNumber = isset($_REQUEST['ewayTrxnNumber']) ? $_REQUEST['ewayTrxnNumber'] : false;
$ewayTrxnReference = isset($_REQUEST['ewayTrxnReference']) ? $_REQUEST['ewayTrxnReference'] : false;
$eWAYresponseCode = isset($_REQUEST['eWAYresponseCode']) ? $_REQUEST['eWAYresponseCode'] : false;
$eWAYReturnAmount = isset($_REQUEST['eWAYReturnAmount']) ? $_REQUEST['eWAYReturnAmount'] : false;
$eWAYAuthCode = isset($_REQUEST['eWAYAuthCode']) ? $_REQUEST['eWAYAuthCode'] : false;
$eWAYresponseText = isset($_REQUEST['eWAYresponseText']) ? $_REQUEST['eWAYresponseText'] : false;
$eWAYoption1 = isset($_REQUEST['eWAYoption1']) ? $_REQUEST['eWAYoption1'] : false;


if ( $ewayTrxnStatus == 'True')
{
  	$customer = ClassFactory::ObjectNew('Customer',$eWAYoption1);
  	
	$userid = $customer->CreateUser();
	$customer->user = $userid;
	$customer->Save();
	
	$smarty->assign('main_displayfile', 'paymentsuccessful.tpl');
} else {
	echo $ewayTrxnStatus;
	$smarty->assign('main_displayfile', 'paymentfail.tpl');
}


AssignCategories($smarty);
$smarty->display('main.tpl');

?>