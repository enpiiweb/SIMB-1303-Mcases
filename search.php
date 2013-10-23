<?php
ini_set('display_errors', 'On');
require_once("includes/includes.php");

$smarty = new SiteTemplate();

$action = isset($_GET['action']) ? $_GET['action'] : false;

switch($action)
{
  case 'form':
	$smarty->assign("main_displayfile",'searchform.tpl');
	$smarty->assign("searchform_years", yearsArray() );
	break;
  case 'search':
    $word2 = '';
 	$smarty->assign('articlelist_heading','Search results');
  	if( !empty($_POST['search_all']) )
  	{
		$word2 = $_POST['search_all']; // $word2 = $word;
  		$word = addslashes($_POST['search_all']);
  		$sql['all'] = "(article.title like '%$word%' or article.court like '%$word%' or article.judge like '%$word%' or article.keywords like '%$word%' or article.content like '%$word%' or article.caseid like '%$word%' or article.courtid like '%$word%')";
  	}

  	if( !empty($_POST['search_title']) )
  	{
  		$word = addslashes($_POST['search_title']);
		$word2 = (empty($word2)) ? $_POST['search_title'] : $word2;
  		$sql['title'] = "(article.title like '%$word%')";
  	}

  	if( !empty($_POST['search_court']) )
  	{
  		$word = addslashes($_POST['search_court']);
		$word2 = (empty($word2)) ? $_POST['search_court'] : $word2;
  		$sql['court'] = "(article.court like '%$word%')";
  	}

  	if( !empty($_POST['search_judge']) )
  	{
  		$word = addslashes($_POST['search_judge']);
		$word2 = (empty($word2)) ? $_POST['search_judge'] : $word2;
  		$sql['judge'] = "(article.judge like '%$word%')";
  	}

  	if( !empty($_POST['search_keywords']) )
  	{
  		$word = addslashes($_POST['search_keywords']);
		$word2 = (empty($word2)) ? $_POST['search_keywords'] : $word2;
  		$sql['keywords'] = "(article.keywords like '%$word%')";
  	}

  	if( !empty($_REQUEST['search_cat']) )
  	{
  		$word = $_REQUEST['search_cat'];
		$word2 = (empty($word2)) ? $word : $word2;
  		$sql['cat'] = "(article.cat='$word')";
  	}

  	if( !empty($_REQUEST['search_catonly']) )
  	{
  		$word = $_REQUEST['search_catonly'];
		$word2 = (empty($word2)) ? $word : $word2;
  		$cat = ClassFactory::ObjectNew("cat", $word);
  		$smarty->assign('articlelist_heading',$cat->name);
  		$sql['cat'] = "(article.cat='$word')";
  	}

  	if( !empty($_POST['search_startyear']) )
  	{
  		/*
		$epoch = mktime(0, 0, 0, 1, 1, $_POST['search_startyear'] );
  		$sql['startyear'] = "(article.date >='$epoch')";
		print date("M-d-Y", $epoch);
		*/
		$epoch = $_POST['search_startyear']."-01-01 00:00:00";
		$sql['startyear'] = "(article.article_date >= '".$epoch."')";
  	}

  	if( !empty($_POST['search_endyear']) )
  	{
		/*
  		$epoch = mktime(0, 0, 0, 1, 1, $_POST['search_endyear'] );
  		$sql['endyear'] = "(article.date <='$epoch')";
		print date("M-d-Y", $epoch);
		*/
		$epoch = $_POST['search_endyear']."-12-31 23:59:59";
		$sql['endyear'] = "(article.article_date <= '".$epoch."')";
  	}


	if (isset($sql) )
	{
		$sqljoin = "where ".join(" and ",$sql);
	}

	if (!isset($sqljoin)) $sqljoin = "";
	$articles = Classfactory::ObjectArray('article', true, $sqljoin." order by article.article_date");

if ($articles)
{
foreach($articles as $key => $value)
{
	$value->day = substr($value->article_date, 8, 2);
	$value->month = substr($value->article_date, 5, 2);
	$value->year = substr($value->article_date, 2, 2);
	$articles[$key] = $value;
	$content = strip_tags($value->content);
	$word2Loc = stripos($content, $word2);
	$words = 100;
	$word2Loc2 = $word2Loc;
	if ($word2Loc>0)
	 {
	while($words > 50 && $word2Loc2 != 0)
	{
		if($content[$word2Loc2] == ' ')
			$words--;
		$word2Loc2--;
	}
	$word2Loc2++;
	while($words > 0 && $word2Loc < strlen($content))
	{
		if($content[$word2Loc] == ' ')
			$words--;
		$word2Loc++;
	}
	 }

  	if(empty($_REQUEST['search_catonly']))
  	 {
	  $articles[$key]->sample = str_ireplace($word2, "<b>".$word2."</b>", substr($content, $word2Loc2, $word2Loc - $word2Loc2));
	 }
	else
	 {
	  $articles[$key]->sample = str_ireplace($word2, $word2, substr($content, 0, 500));
	 }
}
	$smarty->assign('search_results', "");
}else{
	$no_results_found = "No matching items found. Please refine your search.";
	$no_results_found .= "<form action='index.php' method=POST><input type='submit' value='OK'></form>";
	
	$smarty->assign('search_results', $no_results_found);
}

	$smarty->assign('articlelist_articles', $articles);
	$smarty->assign("main_displayfile",'articlelist_c.tpl');
	
	break;
}

AssignCategories($smarty);
$smarty->display('main.tpl');


function yearsArray()
{
	$years[''] = 'Any';
	for ($i = 1950; $i < 2025; $i++)
	{	$years[$i]= $i;	}
	
	return $years;
}


?>