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

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : false;
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : false;
$cat = isset($_REQUEST['cat']) ? $_REQUEST['cat'] : false;
$db = new DBInterface();

switch($action)
{
  case 'add':
		$title = isset($_POST['title']) ? $_POST['title'] : false;
		$restricted = isset($_POST['restricted']) ? $_POST['restricted'] : 0;
		$day = isset($_POST['day']) ? $_POST['day'] : false;
		$month = isset($_POST['month']) ? $_POST['month'] : false;
		$year = isset($_POST['year']) ? $_POST['year'] : false;
		$description = isset($_POST['description']) ? $_POST['description'] : false;
		$cat = isset($_POST['cat']) ? $_POST['cat'] : false;

		if (!empty($title) && !empty($cat) && isset($_FILES['file']) && !empty($_FILES['file']['name']))
		{
			$date = mktime(0,0,0, $month, $day, $year);
			if (!$date)
				$date = mktime();
			$sql = "INSERT INTO download
							SET
								file = '" . db_input($_FILES['file']['name']) . "',
								date = '" . db_input($date) . "',
								title = '" . db_input($title) . "',
								description = '" . db_input($description) . "',
								cat = '" . db_input($cat) . "',
								restricted = '" . db_input($restricted) . "'";
			$db->DBUpdate($sql);
			$temp_id = $db->DBLastInsertId();

			$newname = $fileroot . "download/" . $cat . "/" . $temp_id ."_" . $_FILES['file']['name'];
			if(!is_dir($fileroot . "download/" . $cat))
				mkdir($fileroot . "download/" . $cat, 0777);
			if (file_exists($newname))
				@unlink($newname);
			copy($_FILES['file']['tmp_name'], $newname);
			@chmod($newname,0777);
			$sql = "UPDATE download
							SET file = '" . db_input($temp_id . "_" . $_FILES['file']['name']) . "'
							WHERE id = '" . $temp_id . "'";
			$db->DBUpdate($sql);
		}
		else
		{
			if (empty($title)) $error['title'] = "You must enter a title.";
			if (empty($cat)) $error['cat'] = "You must choose a category.";
			if (empty($file)) $error['file'] = "You must choose a file to upload.";
			$smarty->assign('error', $error);
			$temp_download['title'] = $title;
			$temp_download['restricted'] = $restricted;
			$temp_download['day'] = $day;
			$temp_download['month'] = $month;
			$temp_download['year'] = $year;
			$temp_download['description'] = $description;
			$temp_download['cat'] = $cat;
			$smarty->assign("current_download",$temp_download);
		}
		break;
  case 'edit':
		if (!empty($id))
		{
			$sql = "SELECT id, file, date, title, description, cat, restricted FROM download WHERE id = '" . db_input($id) . "'";
			$result = $db->DBSelect($sql);
			$row = $result->GetRow();
			$smarty->assign("current_download",$row);
		}
		break;
  case 'update':
		$title = isset($_POST['title']) ? $_POST['title'] : false;
		$restricted = isset($_POST['restricted']) ? $_POST['restricted'] : 0;
		$day = isset($_POST['day']) ? $_POST['day'] : false;
		$month = isset($_POST['month']) ? $_POST['month'] : false;
		$year = isset($_POST['year']) ? $_POST['year'] : false;
		$description = isset($_POST['description']) ? $_POST['description'] : false;
		$cat = isset($_POST['cat']) ? $_POST['cat'] : false;
		$oldcat = isset($_POST['oldcat']) ? $_POST['oldcat'] : false;
		$oldfile = isset($_POST['oldfile']) ? $_POST['oldfile'] : false;

		if (!empty($title) && !empty($cat) && !empty($id))
		{
			$date = mktime(0,0,0, $month, $day, $year);
			if (!$date)
				$date = mktime();
			$sql = "UPDATE download
							SET
								date = '" . db_input($date) . "',
								title = '" . db_input($title) . "',
								description = '" . db_input($description) . "',
								cat = '" . db_input($cat) . "',
								restricted = '" . db_input($restricted) . "'
							WHERE id = '" . db_input($id) . "'";
			$db->DBUpdate($sql);

			if (isset($_FILES['file']) && !empty($_FILES['file']['name']))
			{
				$oldname = $fileroot . "download/" . $oldcat . "/" . $oldfile;
				$newname = $fileroot . "download/" . $cat . "/" . $id ."_" . $_FILES['file']['name'];
				if (file_exists($oldname))
					@unlink($oldname);
				if (file_exists($newname))
					@unlink($newname);
				@copy($_FILES['file']['tmp_name'], $newname);
				@chmod($newname,0777);
				$sql = "UPDATE download
								SET file = '" . db_input($id . "_" . $_FILES['file']['name']) . "'
								WHERE id = '" . $id . "'";
				$db->DBUpdate($sql);
			}
			elseif ($cat != $oldcat)
			{
				$oldname = $fileroot . "download/" . $oldcat . "/" . $oldfile;
				$newname = $fileroot . "download/" . $cat . "/" . $oldfile;
				@rename($oldname, $newname);
			}
		}
		else
		{
			if (empty($title)) $error['title'] = "You must enter a title.";
			if (empty($cat)) $error['cat'] = "You must choose a category.";
			if (empty($id)) $error['id'] = "You must select a record to edit.";
			$smarty->assign('error', $error);
			$temp_download['id'] = $id;
			$temp_download['title'] = $title;
			$temp_download['restricted'] = $restricted;
			$temp_download['day'] = $day;
			$temp_download['month'] = $month;
			$temp_download['year'] = $year;
			$temp_download['description'] = $description;
			$temp_download['cat'] = $oldcat;
			$temp_download['file'] = $oldfile;
			$smarty->assign("current_download",$temp_download);
		}
  	break;
  case 'delete':
		if (!empty($id))
		{
			$sql = "SELECT file, cat FROM download WHERE id = '" . db_input($id) . "'";
			$result = $db->DBSelect($sql);
			if ($result->altered > 0)
			{
				$row = $result->GetRow();
				$oldname = $fileroot . "download/" . $row['cat'] . "/" . $row['file'];
				if (file_exists($oldname))
					@unlink($oldname);
			}
			$sql = "DELETE FROM download WHERE id = '" . db_input($id) . "'";
			$db->DBUpdate($sql);
			$sql = "DELETE FROM article WHERE download = '" . db_input($id) . "'";
			$db->DBUpdate($sql);
		}
		break;
}

$sql = "SELECT * FROM download";
if (!empty($cat))
	$sql .= " WHERE cat = '" . db_input($cat) . "'";
$sql .= " ORDER BY title ASC";
$raw_downloads = $db->DBSelect($sql);
$downloads = array();
while ($row = $raw_downloads->GetRow())
	$downloads[] = $row;
//echo "<pre>";print_r($downloads);echo "</pre>";
$smarty->assign("main_displayfile",'downloadadmin.tpl');
$smarty->assign("downloadlist_download",$downloads);

$sql = "SELECT id, name FROM cat ORDER BY name ASC";
$raw_cats = $db->DBSelect($sql);
$cats = array();
while ($row = $raw_cats->GetRow())
{
	if ($row['id'] == $cat)
		$smarty->assign('current_cat', $row);
	$cats[] = $row;
}

$smarty->assign('downloadform_cats', $cats);
$smarty->assign('downloadform_days', daysArray());
$smarty->assign('downloadform_months', monthsArray());
$smarty->assign('downloadform_years', yearsArray());

$smarty->display('main.tpl');

function yearsArray()
{
	for ($i = 1950; $i < 2020; $i++)
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