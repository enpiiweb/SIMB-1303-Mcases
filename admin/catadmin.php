<?php
require_once("../includes/includes.php");

$smarty = new SiteTemplate;

AssignCategories($smarty);
Authorise($smarty,"index.php","Admin");

function db_input($input)
{
	if (get_magic_quotes_gpc())
		return $input;
	else
		return addslashes($input);
}

$action = isset($_GET['action']) ? $_GET['action'] : false;
$id = isset($_GET['id']) ? $_GET['id'] : false;
$db = new DBInterface();

switch($action)
{
  case 'add':
		$name = isset($_GET['name']) ? $_GET['name'] : false;
		if (!empty($name))
		{
			$sql = "INSERT INTO cat SET name = '" . db_input($name) . "'";
			$db->DBUpdate($sql);
		}
		break;
  case 'edit':
		if (!empty($id))
		{
			$sql = "SELECT id, name FROM cat WHERE id = '" . db_input($id) . "'";
			$result = $db->DBSelect($sql);
			$row = $result->GetRow();
			$smarty->assign("current_cat",$row);
		}
		break;
  case 'update':
		$name = isset($_GET['name']) ? $_GET['name'] : false;
		if (!empty($name) && !empty($id))
		{
			$sql = "UPDATE cat SET name = '" . db_input($name) . "' WHERE id = '" . db_input($id) . "'";
			$db->DBUpdate($sql);
		}
  	break;
  case 'delete':
		if (!empty($id))
		{
			$sql = "DELETE FROM cat WHERE id = '" . db_input($id) . "'";
			$db->DBUpdate($sql);
			$sql = "DELETE FROM article WHERE cat = '" . db_input($id) . "'";
			$db->DBUpdate($sql);
			$sql = "DELETE FROM download WHERE cat = '" . db_input($id) . "'";
			$db->DBUpdate($sql);
		}
		break;
}

$sql = "SELECT id, name FROM cat ORDER BY name ASC";
$raw_cats = $db->DBSelect($sql);
$cats = array();
while ($row = $raw_cats->GetRow())
	$cats[] = $row;
$smarty->assign("main_displayfile",'catadmin.tpl');
$smarty->assign("catlist_cats",$cats);

$smarty->display('main.tpl');
?>