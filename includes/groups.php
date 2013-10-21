<?php
  include ('../includes/includes.php');
  include ('../includes/templist.php');
$template = new SiteTemplate();

Authorise($template,"index.php","Admin");

$page = "<h1>User Admin</h1><p><a href='index.php'>Admin Page</a></p>";

$action = isset($_POST["action"]) ? $_POST["action"] : (isset($_GET["action"]) ? $_GET["action"] : false);
$group = isset($_POST["group"]) ? $_POST["group"] : (isset($_GET["group"]) ? $_GET["group"] : false);
$user = isset($_POST["user"]) ? $_POST["user"] : (isset($_GET["user"]) ? $_GET["user"] : false);
	
$error = false;
switch($action)
{
	case "askdeletegroup":
		$usergroup = ClassFactory::ObjectNew("usergroup",$group);
		$page .= "<p>Are you sure you want to delete the group: <b>{$usergroup->name}</b>? This will also delete all the members accounts in the group.</p>";
		$page .= "<form name='groups' action='groups.php' method='POST'><input type='hidden' name='group' value='{$group}'>";
		$page .= "<input type='submit' name='action' value='Delete Group'> <a href='groups.php'>Cancel</a>";
		$page .= "</form>";
			
	break;
	
	case "Delete Group":
		$usergroup = ClassFactory::ObjectNew("usergroup",$group);
		$usergroup->Delete();
		$page .= "<p><b>Group deleted!</b> <a href='groups.php'>Click Here</a> to go back to the group list.</p>";
	break;
	
	case "askdeleteuser":
		$oUser = ClassFactory::ObjectNew("user",$user);
		$page .= "<p>Are you sure you want to delete the user: <b>{$oUser->name} ({$oUser->login})</b></p>";
		$page .= "<form name='groups' action='groups.php' method='POST'><input type='hidden' name='user' value='{$user}'>";
		$page .= "<input type='submit' name='action' value='Delete User'> <a href='groups.php?action=showusers&group={$oUser->usergroup}'>Cancel</a>";
		$page .= "</form>";
			
	break;
	
	case "Delete User":
		$oUser = ClassFactory::ObjectNew("user",$user);
		$oUser->Delete();
		$page .= "<p><b>User deleted!</b> <a href='groups.php?action=showusers&group={$oUser->usergroup}'>Click Here</a> to go back to the group list.</p>";
	break;
	
	case "Save Group":
		$usergroup = new usergroup($_POST);
		if(($error = $usergroup->Errors())==false)
		{
			$usergroup->Save();
			$page .= "<p><b>Group saved!</b> <a href='groups.php'>Click Here</a> to go back to the group list.</p>";
		} else {
			$page .= "<p><b>Error saving group!</b> Please check the details and try again.</p>";
			$page .= ShowGroupForm($usergroup,$error);
		}
	break;
	
	case "Save User":
		$vars = $_POST;
		$user = new User($vars);
		if($user->id)
			$user->save_password = false;
		else 
			if($_POST["password"]!=$_POST["rpassword"])
				$error["rpassword"] = "Passwords do not match.";
		$error = $user->Errors();
		if($error==false)
		{
			$res = $user->Save();
			//echo $res->Message();
			$page .= "<p><b>User saved!</b> <a href='groups.php?action=showusers&group={$user->usergroup}'>Click Here</a> to go back to the group list.</p>";
		} else {
			$page .= "<p><b>Error saving user!</b> Please check the details and try again.</p>";
			$page .= ShowUserForm($_POST["usergroup"],$user,$error);
		}
	break;
	
	case "changepassword":
		$oUser = ClassFactory::ObjectNew("user",$user);
		$page .= ShowPasswordForm($oUser);
	break;
	
	case "Change Password":
		$oUser = ClassFactory::ObjectNew("user",$user);
		if($_POST["pass"] == $_POST["passr"])
		{
			$oUser->password = $_POST["pass"];
			$oUser->Save();
			$page .= "<p><b>Password changed!</b> <a href='groups.php?action=showusers&group={$oUser->usergroup}'>Click Here</a> to go back to the group list.</p>";
		} else {
			$page .= ShowPasswordForm($uUser,"Passwords do not match.");
		}
	break;
	
	case "showusers":
		$page .= ShowUserList($group);
	break;

	case "edituser":
		$oUser = ClassFactory::ObjectNew("user",$user);
		$page .= ShowUserForm($group, $oUser);
	break;

	case "editgroup":
		$usergroup = ClassFactory::ObjectNew("usergroup",$group);
		$page .= ShowGroupForm($usergroup);
	break;

	case "newgroup":
		$page .= ShowGroupForm();
	break;
	
	case "newuser":
		$page .= ShowUserForm($group);
	break;
	
	default:
		$page .= ShowGroupList();
	break;
}


$head = $template->do_header();
$foot = $template->do_footer();
echo $head.$page.$foot;

function ShowGroupForm($usergroup = false, $error = null)
{
	$page = "";
	if(get_class($usergroup)!="usergroup")
		$usergroup = new usergroup();
		
	if($usergroup->id)
	{
		$page .= "<h2>Edit Group</h2>";
		$edit = true;
	} else {
		$page .= "<h2>New Group</h2>";
		$edit = false;
	}
	$page .= "<form name='groups' action='groups.php' method='POST'>";
	$page .= "<p><b>Group Name</b><br><input name='name' maxlength=63 value='{$usergroup->name}'>".(isset($error["name"])? "<span class='alert'>".$error["name"]."</span>" : "")."</p>";
	if($edit)
		$page .= "<input type='hidden' name='id' value='{$usergroup->id}'>";
		
	$page .= "<input type='submit' name='action' value='Save Group'> <a href='groups.php'>Cancel</a>";
	$page .= "</form>";
	return $page;
}

function ShowPasswordForm($user, $error = false)
{
	$page = "<h2>Change Password</h2>";
	$page .= "<p>enter a new password for the account: <b>{$user->name} ({$user->login})</b>?</p>";
	$page .= "<form name='user' action='groups.php' method='POST'>";
	$page .= "<p><b>New Password</b><br><input type='password' name='pass' maxlength=31>".($error? "<span class='alert'>$error</span>" : "")."</p>";
	$page .= "<p><b>Repeat Password</b><br><input type='password' name='passr' maxlength=31></p><input type='hidden' name='user' value='{$user->id}'>";
	$page .= "<input type='submit' name='action' value='Change Password'> <a href='groups.php?action=showusers&group={$user->usergroup}'>Cancel</a>";
	$page .= "</form>";
	return $page;
}

function ShowGroupList()
{
	$page = "<h2>User Groups</h2>";
	$page .= "<p><a href='groups.php?action=newgroup'>Create New Group</a></p>";
	$ug_ar = ClassFactory::ObjectArray("usergroup",true,"where id > -1 order by name");
	if(is_array($ug_ar))
	{
		foreach($ug_ar as $gid=>$g)
		{
			$page .= "<p><a href='groups.php?action=editgroup&group=$gid'>Edit</a> <a href='groups.php?action=askdeletegroup&group=$gid'>Delete</a> <a href='groups.php?action=showusers&group=$gid'>Users</a> <b>{$g->name}</b></p>";
		
		}
	} else {
		$page .= "<p>There are currently no groups.</p>";
	}
	return $page;
}

function ShowUserForm($groupid, $user = false, $error = false)
{
	$page = "";
	if(!$user instanceof user)
	{
		$user = new user();
		$user->usergroup=$groupid;
	}
	if($user->id)
	{
		$page .= "<h2>Edit User</h2>";
		$edit = true;
	} else {
		$page .= "<h2>New User</h2>";
		$edit = false;
	}


	$page .= "<form name='user' action='groups.php' method='POST'>";
	$page .= "<p><b>Name</b><br><input name='name' maxlength=127 value='{$user->name}'>".(isset($error["name"])? "<span class='alert'>".$error["name"]."</span>" : "")."</p>";
	$page .= "<p><b>Email Address</b><br><input name='email' maxlength=63 value='{$user->email}'>".(isset($error["email"])? "<span class='alert'>".$error["email"]."</span>" : "")."</p>";
	$page .= "<p><b>Login</b><br><input name='login' maxlength=63 value='{$user->login}'>".(isset($error["login"])? "<span class='alert'>".$error["login"]."</span>" : "")."</p>";
	$page .= "<input type='hidden' name='usergroup' value='{$user->usergroup}'>";
	
	if($edit)
		$page .= "<input type='hidden' name='id' value='{$user->id}'>";
	else
	{
		$page .= "<p><b>Password</b><br><input type='password' name='password' maxlength=15 value=''>".(isset($error["password"])? "<span class='alert'>".$error["password"]."</span>" : "")."</p>";
		$page .= "<p><b>Repeat Password</b><br><input type='password' name='rpassword' maxlength=15 value=''>".(isset($error["rpassword"])? "<span class='alert'>".$error["rpassword"]."</span>" : "")."</p>";
	}	
	$page .= "<input type='submit' name='action' value='Save User'> <a href='groups.php?action=showusers&group={$groupid}'>Cancel</a>";
	$page .= "</form>";
	
	return $page;
}

function ShowUserList($groupid = false)
{
	$page = "";
	if($groupid!==false)
	{
		$cond = "where usergroup='{$groupid}' order by usergroup, name";
		$usergroup = ClassFactory::ObjectNew("usergroup",$groupid);
		$page .= "<h3>$usergroup->name</h3>";
	} else {
		$cond = "order by groupid, email";
		$page .= "<h3>all Users</h3>";
	}
	$page .= "<p>";
	if($usergroup instanceof usergroup)
		$page .= "<a href='groups.php?action=newuser&group={$groupid}'>Add a user</a><br>";
	
	$page .= "<a href='groups.php'>Back to list</a></p>";
	
	$u_ar = ClassFactory::ObjectArray("user",true,$cond);
	if(is_array($u_ar))
	{
		foreach($u_ar as $uid=>$user)
		{
			$page .= "<p><a href='groups.php?action=edituser&user={$uid}&group={$groupid}'>Edit</a> <a href='groups.php?action=changepassword&user={$uid}'>Change Password</a> <a href='groups.php?action=askdeleteuser&user={$uid}'>Delete</a> <b>{$user->name} ({$user->login})</b></p>";
		}
	} else {
		$page .= "<p>No users in this group.</p>";
	}
	return $page;
}
?>