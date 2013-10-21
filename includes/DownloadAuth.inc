<?php
global $site_vars;
$site_vars["class"]["DownloadAuth"] = "db";

class DownloadAuth extends DBObject {

	private static $db_table_name = "DownloadAuth";
	private static $db_table_def = "create table DownloadAuth (
id int unsigned not null primary key auto_increment,
user int unsigned,
file varchar(127),
downloads int
);";

	private static $db_table_init = array();
	private static $db_table_fields = array("id","file","downloads","user");
	private static $db_table_id = "id";
		
	function __construct($vars = null)
	{
		parent::__construct($vars);
	}
	
			
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
		$errors = null;
		
		if(!strlen($this->file)>0)
			$errors["file"] = "A file name is required.";
		
		
		//if($errors)
		//	print_r($errors);
		if($errors===null)
			return false;
		else return $errors;
	}

	
}
?>