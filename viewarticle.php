<?php

require_once("includes/includes.php");

$smarty = new SiteTemplate();

$action = isset($_GET['action']) ? $_GET['action'] : false;
$id = isset($_GET['id']) ? $_GET['id'] : false;

switch($action)
{
  case 'viewdetails':
  	if ( Auth() )
  	{
  		$smarty->assign('articledetail_loggedin', 'yes');
  	}
  	$article = ClassFactory::ObjectNew('article',$id);
	

		$article->day = substr($article->article_date, 8, 2);
		$article->month = substr($article->article_date, 5, 2);
		$article->year = substr($article->article_date, 2, 2);


	
  	$download = ClassFactory::ObjectNew('download',$article->download);
	$smarty->assign("articledetail_download", $download );
	$smarty->assign("main_displayfile",'articledetail.tpl');
	$smarty->assign("articledetail_article", $article );
	break;
}

AssignCategories($smarty);
$smarty->display('main.tpl');


?>