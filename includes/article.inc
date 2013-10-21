<?php
global $site_vars;
$site_vars["class"]["article"] = "db";


class article extends DBObject {
	private static $db_table_name = "article";
	private static $db_table_def = 
"create table article
(
	id		int(4) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
	caseid	varchar(16),
	courtid	varchar(16),
	title		varchar(127),
	court		varchar(127),
	judge		varchar(255),
	download	int,
	date		int,
	keywords	text,
	restricted	tinyint,
	cat		int,
	content	MEDIUMTEXT
)";


	private static $db_table_init = array();
	private static $db_table_fields = array("id","caseid","courtid","title","court","judge","date","keywords","restricted","cat","content","download","article_date");
	private static $db_table_id = "id";
	
	private $obj_usergroup;
	public $save_password;
	
	function __construct($vars = null)
	{
		parent::__construct($vars);
		$this->save_password=true;
		$this->obj_usergroup = null;
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
		
		if(!strlen($this->title)>0)
		{
			$errors["title"] = "You must give a title.";
		}

		if($errors===null)
			return false;
		else return $errors;
	}
    
    function RTEContent()
    {
        return str_replace(array("\r\n","'"), array("\\\n",""),$this->content);
    
    }
	
/*	function CleanFields()
	{
		$vals = array();
		$mcgpc = get_magic_quotes_gpc();
		$vals["name"] = $mcgpc? stripslashes($this->name):$this->name;
		$vals["login"] = $mcgpc? stripslashes($this->login):$this->login;
		$vals["email"] = $mcgpc? stripslashes($this->email):$this->email;
		$vals["usergroup"] = $mcgpc? stripslashes($this->usergroup):$this->usergroup;
		foreach($vals as $f=>$v)
		{
			$vals[$f] = "'".mysql_escape_string($v)."'";
		}
		if($this->save_password)
		{
			$vals["password"] = $mcgpc? stripslashes($this->password):$this->password;
			$vals["password"] = "PASSWORD('$vals[password]')";
  		}
		return $vals;
	}*/
}
?>