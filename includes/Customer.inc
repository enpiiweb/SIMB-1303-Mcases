<?php
global $site_vars;
$site_vars["class"]["Customer"] = "db";
/*
NOTES:

Customer->status values:
-999
-2	-	Error Online
-1	-	Declined Online
0	-	Unpaid Fax


*/

class Customer extends DBObject {

	private static $db_table_name = "Customer";
	private static $db_table_def = "create table Customer (
id int unsigned not null primary key auto_increment,
status int default -999,
user int unsigned,
fname varchar (127),
lname varchar (127),
address varchar (127),
suburb varchar (127),
state varchar (127),
country varchar (127),
pcode varchar (127),
phone varchar (127),
mobile varchar (127),
fax varchar (127),
password varchar(15),
email varchar(127),
readterms int(1) unsigned
);";

	private static $db_table_init = array();
	private static $db_table_fields = array("id","readterms","password","user","country","mobile","status","fname","lname","address","suburb","state","pcode","phone","fax","email");
	private static $db_table_id = "id";
		
	function __construct($vars = null)
	{
	
//		print_r($vars);
		if ( empty( $vars['status'] ) )
		{
//			print "<br>statuts was empty<br>";
			$vars['status'] = -999;
		}
//		print "<br>In Customer class constructor<br>";
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
	
	function CreateUser()
	{
		$user = new user();
		$user->usergroup = 1;
		$user->name = $this->fname." ".$this->lname;
		$user->login = $this->email;
		$user->email = $this->email;
		$user->password = $this->password;
		$res = $user->Save();
		return $user->id;
	}
	
	function SendEmail($login,$purchase)
	{
		return mail($to, $subject, $message, $headers);
	}
	
	function Errors()
	{
		$errors = null;

		
		if(!strlen($this->fname)>0)
		{	$errors["fname"] = "A first name is required.";	}

		if(!strlen($this->lname)>0)
		{	$errors["lname"] = "A surname is required.";	}
		
		if(!strlen($this->address)>0)
		{	$errors["address"] = "An address is required.";	}

		if(!strlen($this->suburb)>0)
		{	$errors["suburb"] = "A suburb is required.";	}

		if(!strlen($this->state)>0)
		{	$errors["state"] = "A state is required.";	}

		if(!strlen($this->pcode)>0)
		{	$errors["pcode"] = "A pcode is required.";	}
		
		if(!strlen($this->password)>0)
		{	$errors["password"] = "A password is required";	}

		if(!strlen($this->email)>0)
		{	$errors["email"] = "An email address is required";	}

		$customers = ClassFactory::ObjectArray('Customer',true, "where email='{$this->email}' and status='0'");
		if ( is_array($customers) )
		{	$errors['email'] = "This email address is already in use";  	}

		
		if($errors===null)
			return false;
		else return $errors;
	}

	function StatusMessage($status = "NAN")
	{
		$messages = array(
				-2	=>	"Error Online",
				-1	=>  "Declined Online",
				0	=>  "Unpaid Fax",
				1	=>  "Unpaid Online",
				2	=>  "Paid Fax",
				3	=>  "Paid Online");
		
		if($status!=="NAN")
		{
			if(isset($messages[$status]))
				return $messages[$status];
			else
				return "Invalid Status $status";
		} else {
			return $messages;
		}
	}
}
?>