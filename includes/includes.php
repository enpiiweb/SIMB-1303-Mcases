<?php
error_reporting(E_ALL);
$fileroot = dirname(__FILE__).'/../';
//$fileroot = dirname(__FILE__).'/../';
require_once($fileroot."includes/globals.php");
require_once($fileroot."includes/EmailCenter.inc");
require_once($fileroot."includes/Smarty/Smarty.class.php");
require_once($fileroot."includes/templist.php");
require_once($fileroot."includes/ClassFactory.inc");
require_once($fileroot."includes/DBInterface.inc");
require_once($fileroot."includes/DBObject.inc");
require_once($fileroot."includes/DBResult.inc");
require_once($fileroot."includes/template.inc");

require_once($fileroot."includes/article.inc");
require_once($fileroot."includes/template.inc");
require_once($fileroot."includes/cat.inc");
require_once($fileroot."includes/download.inc");
require_once($fileroot."includes/Customer.inc");
require_once($fileroot."includes/user.inc");
require_once($fileroot."includes/usergroup.inc");
require_once($fileroot."includes/auth.php");
require_once($fileroot."includes/classes.php");
function __autoload($class_name)
{
	echo print_trace();
	try {
		global $fileroot;
		require_once  $fileroot . "includes/" . $class_name . '.inc';
	} catch(Exception $e)
	{
	
	
	}
}