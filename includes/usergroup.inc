<?php
global $site_vars;
$site_vars["class"]["usergroup"] = "db";

class usergroup extends DBObject {

	private static $db_table_name = "usergroup";
	private static $db_table_def = "create table usergroup (
		id int not null primary key auto_increment,
		name varchar (31)
		);";
	private static $db_table_init = array("insert into usergroup (name,id) values('Admin',-1)","insert into usergroup (name,id) values('Users',1)");
	private static $db_table_fields = array("id","name");
	private static $db_table_id = "id";
	
	public function DbTableDef()
	{
		return self::$db_table_def;
	}
	
	public function DbTableInit()
	{
		return self::$db_table_init;
	}
	
	public function DbTableId()
	{
		return self::$db_table_id;
	}
	function Errors()
	{
		$error = null;
		if(!ereg("^[a-zA-Z0-9@._ -]+$", $this->name))
			$error["name"] = "Invalid characters in group name.".$this->name;
		
		if(!strlen($this->name)>0)
			$errors["name"] = "A group name is required.";
		
		if(!strlen($this->name)<31)
			$errors["name"] = "Group name must be less than 31 characters.";
			
		if($error===null)
			return false;
		else return $error;
	}
	
	
	
	function Delete($id = NULL)
	{
		$u_ar = ClassFactory::ObjectArray("user",true,"where usergroup='{$this->id}'");
		if(is_array($u_ar))
		{
			foreach($u_ar as $u)
			{
				$u->Delete();
			}
		}
		parent::Delete();
	}
	
	function CleanFields()
	{
		$vals = array();
		$mcgpc = get_magic_quotes_gpc();
		$vals["name"] = strtolower($mcgpc? stripslashes($this->name):$this->name);
		foreach($vals as $f=>$v)
		{
			if (!is_numeric($v))
			{
				$vals[$f] = "'".mysql_escape_string($v)."'";
			}
		}
   
		return $vals;
	}
}
?>