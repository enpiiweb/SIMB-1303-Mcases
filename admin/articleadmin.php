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
$al = isset($_GET['alpha']) ? $_GET['alpha'] : "A";
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$page = $page-1;
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : false;
$download = isset($_REQUEST['download']) ? $_REQUEST['download'] : false;
$alpha = null;
for($i = 65;$i<91;$i++)
	$alpha[] = chr($i);

$db = new DBInterface();

switch($action)
{
  case 'add':
  echo "OK";
		$title = isset($_POST['title']) ? $_POST['title'] : false;
		$caseid = isset($_POST['caseid']) ? $_POST['caseid'] : false;
		$courtid = isset($_POST['courtid']) ? $_POST['courtid'] : false;
		$court = isset($_POST['court']) ? $_POST['court'] : false;
		$restricted = isset($_POST['restricted']) ? $_POST['restricted'] : false;
		$judge = isset($_POST['judge']) ? $_POST['judge'] : false;
		$day = isset($_POST['day']) ? $_POST['day'] : false;
		$month = isset($_POST['month']) ? $_POST['month'] : false;
		$year = isset($_POST['year']) ? $_POST['year'] : false;
		$keywords = isset($_POST['keywords']) ? $_POST['keywords'] : false;
		$content = isset($_POST['content']) ? $_POST['content'] : false;
		$cat = isset($_POST['cat']) ? $_POST['cat'] : false;
		$download = isset($_POST['download']) ? $_POST['download'] : false;

		//if (!empty($title) && !empty($caseid) && !empty($courtid) && !empty($court) && !empty($judge) && !empty($download))
		if (!empty($title))
		{
			/* Old Date Code
			$date = mktime(0,0,0, $month, $day, $year);
			if (!$date)
				$date = mktime();
				*/
			if ($month < 10) $month = "0".$month;
			if ($day < 10) $day = "0".$day;
			$date = $year."-".$month."-".$day." 00:00:00";
			
			$sql = "INSERT INTO article
							SET
								title = '" . db_input($title) . "',
								caseid = '" . db_input($caseid) . "',
								courtid = '" . db_input($courtid) . "',
								court = '" . db_input($court) . "',
								judge = '" . db_input($judge) . "',
								restricted = '" . db_input($restricted) . "',
								article_date = '" . db_input($date) . "',
								keywords = '" . db_input($keywords) . "',
								content = '" . db_input($content) . "',
								download = '" . db_input($download) . "',
								cat = '" . db_input($cat) . "'";
			$db->DBUpdate($sql);
		}
		else
		{
			if (!empty($title)) $error['title'] = "You must enter a title.";
			if (!empty($caseid)) $error['file'] = "You must enter a case id.";
			if (!empty($courtid)) $error['file'] = "You must enter a court id.";
			if (!empty($court)) $error['file'] = "You must enter a court.";
			if (!empty($judge)) $error['file'] = "You must enter a judge's name.";
			if (!empty($download)) $error['file'] = "You must select a download.";
			$smarty->assign('error', $error);
		}
		break;
  case 'edit':
		if (!empty($id))
		{
			$sql = "SELECT
								id, title, caseid, courtid, court, judge, cat, restricted,
								article_date, keywords, content, download
							FROM article WHERE id = '" . db_input($id) . "'";
			$result = $db->DBSelect($sql);
			$row = $result->GetRow();
			$row['RTEcontent'] = str_replace(array("\r\n","'"), array("\\\n",""), $row['content']);
			$row['year'] = substr($row['article_date'], 0, 4);
			$row['month'] = substr($row['article_date'], 5, 2);
			$row['day'] = substr($row['article_date'], 8, 2);
			$smarty->assign("current_article",$row);
		}
  	break;
  case 'save':
		$title = isset($_POST['title']) ? $_POST['title'] : false;
		$caseid = isset($_POST['caseid']) ? $_POST['caseid'] : false;
		$courtid = isset($_POST['courtid']) ? $_POST['courtid'] : false;
		$court = isset($_POST['court']) ? $_POST['court'] : false;
		$restricted = isset($_POST['restricted']) ? $_POST['restricted'] : false;
		$judge = isset($_POST['judge']) ? $_POST['judge'] : false;
		$day = isset($_POST['day']) ? $_POST['day'] : false;
		$month = isset($_POST['month']) ? $_POST['month'] : false;
		$year = isset($_POST['year']) ? $_POST['year'] : false;
		$keywords = isset($_POST['keywords']) ? $_POST['keywords'] : false;
		$content = isset($_POST['content']) ? $_POST['content'] : false;
		$cat = isset($_POST['cat']) ? $_POST['cat'] : false;
		$download = isset($_POST['download']) ? $_POST['download'] : false;
		//echo "$title - $caseid - $courtid - $court - $restricted - $judge - $day - $month - $year - $keywords - $content - $cat - $download";

		//if (!empty($id) && !empty($title) && !empty($caseid) && !empty($courtid) && !empty($court) && !empty($judge) && !empty($download))
		if (!empty($id) && !empty($title))
		{
			/* Old Date Code
			$date = mktime(0,0,0, $month, $day, $year);
			if (!$date)
				$date = mktime();
				*/
			if ($month < 10) $month = "0".$month;
			if ($day < 10) $day = "0".$day;
			$date = $year."-".$month."-".$day." 00:00:00";
			
			$sql = "UPDATE article
							SET
								title = '" . db_input($title) . "',
								caseid = '" . db_input($caseid) . "',
								courtid = '" . db_input($courtid) . "',
								court = '" . db_input($court) . "',
								judge = '" . db_input($judge) . "',
								restricted = '" . db_input($restricted) . "',
								article_date = '" . db_input($date) . "',
								keywords = '" . db_input($keywords) . "',
								content = '" . db_input($content) . "',
								download = '" . db_input($download) . "',
								cat = '" . db_input($cat) . "'
							WHERE id = '" . db_input($id) . "'";
			$db->DBUpdate($sql);
		}
		else
		{
			if (!empty($id)) $error['id'] = "You must select an article to edit.";
			if (!empty($title)) $error['title'] = "You must enter a title.";
			if (!empty($caseid)) $error['file'] = "You must enter a case id.";
			if (!empty($courtid)) $error['file'] = "You must enter a court id.";
			if (!empty($court)) $error['file'] = "You must enter a court name.";
			if (!empty($judge)) $error['file'] = "You must enter a judge name.";
			if (!empty($download)) $error['file'] = "You must select a download.";
			$smarty->assign('error', $error);
		}
  	break;
  case 'delete':
		if (!empty($id))
		{
			$sql = "DELETE FROM article WHERE id = '" . db_input($id) . "'";
			$db->DBUpdate($sql);
		}
		break;
}

$max = 100;


$sql = "SELECT * FROM article WHERE";
$count_sql = "SELECT count(*) as c from article WHERE";
if (!empty($download)) {
	$sql .= " download = '" . db_input($download) . "' and";
	$count_sql .= " download = '" . db_input($download) . "' and";
}
$article_sql = " (title like '$al%' or title like '".strtolower($al)."%') ORDER BY title";
$limit_sql = " limit ".$page*$max.", $max";

$raw_articles = $db->DBSelect($sql . $article_sql.$limit_sql);
$countresult = $db->DBSelect($count_sql . $article_sql);
$count = $countresult->GetRow();
$count = $count["c"];

$articles = array();
while ($row = $raw_articles->GetRow()){
	$row['year'] = substr($row['article_date'], 0, 4);
	$row['month'] = substr($row['article_date'], 5, 2);
	$row['day'] = substr($row['article_date'], 8, 2);
	$articles[] = $row;
}
//echo "<pre>";print_r($articles);echo "</pre>";
$smarty->assign("main_displayfile",'articleadmin.tpl');
$smarty->assign("articlelist_articles",$articles);

$sql = "SELECT id, name FROM cat ORDER BY name ASC";
$raw_cats = $db->DBSelect($sql);
$cats = array();
while ($row = $raw_cats->GetRow())
	$cats[] = $row;

$sql = "SELECT id, title FROM download ORDER BY title ASC";
$raw_downloads = $db->DBSelect($sql);
$downloads = array();
while ($row = $raw_downloads->GetRow())
{
	if ($row['id'] == $download)
		$smarty->assign('current_download', $row);
	$downloads[] = $row;
}

$smarty->assign('articleform_downloads', $downloads);
$smarty->assign('articleform_cats', $cats);
$smarty->assign('alpha', $alpha);
$smarty->assign('current_al', $al);
$smarty->assign('count', $count);
$smarty->assign('page', $page);
$smarty->assign('max', $max);
$smarty->assign('articleform_days', daysArray());
$smarty->assign('articleform_months', monthsArray());
$smarty->assign('articleform_years', yearsArray());

$smarty->display('main.tpl');

function yearsArray()
{
	for ($i = 1950; $i < 2025; $i++)
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