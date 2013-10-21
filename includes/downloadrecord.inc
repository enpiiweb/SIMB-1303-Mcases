<?php
global $site_vars;
$site_vars["class"]["downloadrecord"] = "db";

class downloadrecord extends DBObject {

	private static $db_table_name = "downloadrecord";
	private static $db_table_def = "create table downloadrecord (
		id int not null primary key auto_increment,
		file varchar (63),
		time int,
		user int unsigned
		);";
	private static $db_table_init = null;
	private static $db_table_fields = array("id","file","time","user");
	private static $db_table_id = "id";	
		
	
	public function DbTableName()
	{
		return self::$db_table_name;
	}
	
	public function DbTableDef()
	{
		return self::$db_table_def;
	}
	
	public function DbTableInit()
	{
		return self::$db_table_init;
	}
	
	public function DbTableFields()
	{
		return self::$db_table_fields;
	}
	
	public function DbTableId()
	{
		return self::$db_table_id;
	}
	function Errors()
	{
		$error = null;
		//if(!ereg("^[a-zA-Z0-9@._ -]+$", $this->file))
		//	$error["file"] = "Invalid characters in group name.".$this->name;
		
			
		if($error===null)
			return false;
		else return $error;
	}
	
	
	
	function Delete()
	{
		parent::Delete();
	}
	
	function CleanFields()
	{
   		if(!$this->id)
   			$this->time = mktime();
		$vals = parent::CleanFields();
		return $vals;
	}
}
?>