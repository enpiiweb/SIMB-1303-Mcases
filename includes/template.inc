<?php
global $fileroot;

class SiteTemplate
{
	public $smarty;

	function __construct()
	{
		if(!isset($_SESSION))
			session_start();
		global $fileroot, $siteroot, $relroot;
		$this -> smarty = new smarty();
		$globals = array("siteroot"=>$siteroot,"relroot"=>$relroot);
		$this->assign("smartyglobal",$globals);
	}
	
	function assign($var,$value)
	{
		return $this->smarty->assign($var,$value);
	}
	
	function display($template)
	{
		return $this->smarty->display($template);
	}
}

function AddCheck($name, $value)
{
	if(isset($_SESSION["debugchecks"]))
	{
		if(is_array($_SESSION["debugchecks"]))
		{
			if(isset($_SESSION["debugchecks"][$name]))
			{
				$_SESSION["debugchecks"][$name] = $value;
			}
			else {
				$_SESSION["debugchecks"] = $_SESSION["debugchecks"] + array($name => $value);
			}
		} else {
			$_SESSION["debugchecks"] = array($name => $value);
		}
	}
	else 
		$_SESSION["debugchecks"] = array($name => $value);
}

function DeleteCheck($name)
{
	if(isset($_SESSION["debugchecks"]))
		if(isset($_SESSION["debugchecks"][$name]))
			unset($_SESSION["debugchecks"][$name]);


}

function PrintChecks()
{
	$page = "";
	if(isset($_GET["deletechecks"]))
	{
		if($_GET["deletechecks"]==1)
		{
			if(isset($_SESSION["debugchecks"]))
				unset($_SESSION["debugchecks"]);
		}
	}
	
	if(isset($_SESSION["debugchecks"]))
	{
		if(is_array($_SESSION["debugchecks"]))
		{
			$page .= "<p><ul>";
			foreach($_SESSION["debugchecks"] as $n => $v)
			{
				
				$page .= "<li><b>$n </b>$v</li>";
			
			}
			$page .= "</ul></p><p><a href='?deletechecks=1'>Delete Checks</a></p>";
		}
	}
	return $page;	
}


function AssignCategories(& $smarty)
{
	$cats = ClassFactory::ObjectArray('cat', false, " ORDER BY name DESC");
	$smarty->assign('categories', $cats);
}

?>