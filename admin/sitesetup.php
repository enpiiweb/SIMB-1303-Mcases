<?php
require_once("../includes/includes.php");


//Authorise($smarty,"index.php","Admin");

	$smarty = new SiteTemplate;
	
	$action = isset($_POST["action"])? $_POST["action"] : (isset($_GET["action"])? $_GET["action"] : false);
	$class = isset($_POST["class"])? $_POST["class"] : (isset($_GET["class"])? $_GET["class"] : false);
	$class = ereg("^[a-zA-Z0-9_-]{1,30}$",$class)? $class : false;
	$page = "<h1>Site Setup</h1>";
	
	switch($action)
	{
		case "maketable":
			$DBI = new DBInterface();
			eval("\$query = $class::DbTableDef();");
			$res = $DBI->DBUpdate($query);
			eval("\$inits = $class::DbTableInit();");
			if(is_array($inits))
			{
				foreach($inits as $query)
					$res = $DBI->DBUpdate($query);
			}
			$page .= $res->message()."<p><b>Table created.</b><br><br><a href='sitesetup.php'>Click Here</a> to return to the admin page</p>";
		break;
		
		case "droptable":
			$DBI = new DBInterface();
			$res = $DBI->DropTable($class);
			$page .= "<p><b>Table dropped.</b><br><br><a href='sitesetup.php'>Click Here</a> to return to the admin page</p>";
		break;
		
		case "resettable":
			eval("\$table = $class::DbTableName();");
			$DBI = new DBInterface();
			$query = "delete from $table";
			$res = $DBI->DBUpdate($query);
			eval("\$inits = $class::DbTableInit();");
			if(is_array($inits))
			{
				foreach($inits as $query)
					$res = $DBI->DBUpdate($query);
			}
			$page .= "<p><b>Table reset.</b><br><br><a href='sitesetup.php'>Click Here</a> to return to the admin page</p>";
		break;
		
		default:
			$page = do_admin_menu();
		break;
	}

//print $page;
$smarty->assign("main_displaydata",$page);
$smarty->display('main.tpl');


function do_admin_menu()
{
	global $site_vars;
	
	$DBI = new DBInterface();
	if(!isset($site_vars["class"]))
		return "<p>No table classes are currently loaded</p>";
	
	if(!is_array($site_vars["class"]))
		return "<p>No table classes are currently loaded</p>";
	
	$page = "<table class='sitetable'>\n";
	$page .= "<thead><tr><td>Class</td><td>Actions</td></tr></thead>\n";
	$page .= "<tbody>\n";
	foreach($site_vars["class"] as $class=>$type)
	{
		$page .= "<tr><td>$class</td><td>";
		if(!$DBI->TableExists($class))
		{
			$page .= "<a href='sitesetup.php?action=maketable&class=$class'>Create</a>";
		} else {
			$page .= "<a href='sitesetup.php?action=resettable&class=$class'>Reset</a>";
			$page .= " <a href='sitesetup.php?action=droptable&class=$class'>Drop</a>";
		}
		$page .= "</td></tr>\n";
	}
	$page .= "</tbody></table>\n";
	
	return $page;
}

?>