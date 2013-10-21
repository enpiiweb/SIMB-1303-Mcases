<?php
global $relroot,$fileroot;
require_once("includes/includes.php");
$topimage = null;
$template = new SiteTemplate();
$auth = Auth("Admin");
$template->otherdata['authorised'] = $auth;
$ref = isset($_REQUEST["ref"])? $_REQUEST["ref"] : null;
$parent = isset($_REQUEST["parent"])? $_REQUEST["parent"] : null;
//if($parent)
//	$parent = explode("/",$parent);
if(ereg("^[a-zA-Z0-9_-]+$",$ref))
{
	$page = page::FromRef($ref);
	if($page instanceof page)
	{
		
		$DBI = new DbInterface();
		/*if(!$page->parent)
		{
			$query = "select ref,title,url from page where parent='$ref' order by id";
			$res = $DBI->DbSelect($query);
			if($res->Success())
			{
				while($row = $res->GetRow())
				{
					$template->otherdata['submenu'][$row["ref"]]["title"] = $row["title"];
					$template->otherdata['submenu'][$row["ref"]]["url"] = $row['url']? $row["url"]:"pages/".$ref."/".$row["ref"]."/";
					$template->otherdata['submenu'][$row["ref"]]["isurl"] = $row['url']? true : false;
				}
			}
		
		} else {
			if(file_exists($fileroot."images/top800x190/".$page->parent."_".$page->ref.".jpg"))
			{
				$topimage = $page->parent."_".$page->ref;
			}
			if(!$page->grandparent)
			{
				$query = "select ref,title,url from page where parent='$page->parent' order by id";
				$res = $DBI->DbSelect($query);
				if($res->Success())
				{
					while($row = $res->GetRow())
					{
						$template->otherdata['submenu'][$row["ref"]]["title"] = $row["title"];
						$template->otherdata['submenu'][$row["ref"]]["url"] = $row["url"]? $row["url"]:"pages/".$page->parent."/".$row["ref"]."/";
						$template->otherdata['submenu'][$row["ref"]]["isurl"] = $row['url']? true : false;
					}
				}
				$query = "select ref,title,url from page where parent='$ref' order by id";
				$res = $DBI->DbSelect($query);
				if($res->Success())
				{
					while($row = $res->GetRow())
					{
						$template->otherdata['subsubmenu'][$row["ref"]]["title"] = $row["title"];
						$template->otherdata['subsubmenu'][$row["ref"]]["url"] = $row["url"]? $row["url"]:"pages/".$page->parent."/".$ref."/".$row["ref"]."/";
						$template->otherdata['subsubmenu'][$row["ref"]]["isurl"] = $row['url']? true : false;
					}
				}
			
			} else {
				$query = "select ref,title,url from page where parent='$page->grandparent' order by id";
				$res = $DBI->DbSelect($query);
				if($res->Success())
				{
					while($row = $res->GetRow())
					{
						$template->otherdata['submenu'][$row["ref"]]["title"] = $row["title"];
						$template->otherdata['submenu'][$row["ref"]]["url"] = $row["url"]? $row["url"]:"pages/".$page->grandparent."/".$row["ref"]."/";
						$template->otherdata['submenu'][$row["ref"]]["isurl"] = $row['url']? true : false;
					}
				}
				$query = "select ref,title,url from page where parent='$page->parent' order by id";
				$res = $DBI->DbSelect($query);
				if($res->Success())
				{
					while($row = $res->GetRow())
					{
						$template->otherdata['subsubmenu'][$row["ref"]]["title"] = $row["title"];
						$template->otherdata['subsubmenu'][$row["ref"]]["url"] = $row["url"]? $row["url"]:"pages/".$page->grandparent."/".$page->parent."/".$row["ref"]."/";
						$template->otherdata['subsubmenu'][$row["ref"]]["isurl"] = $row['url']? true : false;
					}
				}
			}
		}*/
		$title = $page->title;
		$body = $page->PrintData($auth);
		//if(!$topimage)
		//	$topimage = $page->grandparent? $page->grandparent: ($page->parent? $page->parent : $page->ref);
	} else {
		$title = null;
		//$topimage = $parent? $parent : $ref;
		$body = "<p>This page has not yet been written</p>";
		if($auth)
			$body .= "<p><a href='{$relroot}admin/page.php?ref=$ref&parent=$parent'>Write it now.</a></p>";
	}
	
	$template->otherdata['topimage'] = $topimage.".jpg";

} else {
		$title = null;
	$body = "<p>Page not found.</p>";
}
$head = $template->do_header($title);
$foot = $template->do_footer();
echo $head.$body.$foot;
?>