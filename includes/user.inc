<?php
global $site_vars;
$site_vars["class"]["user"] = "db";


class user extends DBObject {

	private static $db_table_name = "user";
	private static $db_table_def = "create table user (
id int unsigned not null primary key auto_increment,
name varchar (127),
email varchar (127),
login varchar (127),
usergroup int,
password varchar (64)
);";
	private static $db_table_init = array("insert into user (name,login,usergroup,password) values('Administrator','admin',-1,'21232f297a57a5a743894a0e4a801fc3')");
	private static $db_table_fields = array("id","name","email","login","usergroup","password");
	private static $db_table_id = "id";
	
	private $obj_usergroup;
	public $save_password;
	
	function __construct($vars = null)
	{
		parent::__construct($vars);
		$this->save_password=true;
		$this->obj_usergroup = null;
	}
	
	public function Groupname()
	{
		if($this->obj_usergroup===null)
		{
			if($this->GetGroup())
			{
				return $this->obj_usergroup->name;
			}
		} else {
			if($this->obj_usergroup instanceof usergroup)
				return $this->obj_usergroup->name;
		}
		return false;
	}
	
	private function GetGroup()
	{
		$this->obj_usergroup = ClassFactory::ObjectNew("usergroup",$this->usergroup);
		return ($this->obj_usergroup instanceof usergroup);
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
		
		if(!strlen($this->login)>0)
			$errors["login"] = "A user login is required.";
		
		if(strlen($this->login)>127)
			$errors["login"] = "User login must be less than 127 characters.";
		
		if((!strlen($this->password)>0) and $this->save_password)
			$errors["password"] = "Password is required.";
			
		
		if(strlen($this->password)>15 and $this->save_password)
		{	$errors["password"] = "Password must be less than 15 characters.$this->save_password";	}
		
		if(!$errors)
		{
			$uc = ClassFactory::ObjectCount("user","where login='$this->login' and id<>'$this->id'");
			if($uc>0)
				$errors["login"] = "This login has already been used.";
		}
		
		//if($errors)
		//	print_r($errors);
		if($errors===null)
			return false;
		else return $errors;
	}

	function Delete()
	{
		$id = $this->id;
		$res = parent::Delete();
		if($res->Success())
		{
			$DBI = new DBInterface();
			$query =  "delete from DownloadAuth where user='$id'";
			$DBI->DbUpdate($query);
			$query =  "update Customer set user=NULL where user='$id'";
			$DBI->DbUpdate($query);
		}
	}
	
	
	function CleanFields()
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
			$vals["password"] = "'".md5($vals['password'])."'";
  		}
		return $vals;
	}
}
?>