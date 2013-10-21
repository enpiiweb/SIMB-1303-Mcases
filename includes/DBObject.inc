<?php
require_once('DBInterface.inc');

/*-----------------------
|						|
|	  DBObject Class	|
|						|
-------------------------

The DBObject class is an abstract to be extended by classes which represent data from a database table.
Extending classes must define the functions CleanFields and Errors, and cannot overload the functions __get or __set.


DEPENDANCIES
	
	class DBInterface

PROPERTIES

	db_table_name:	Name of the table in the database.
	
	db_table_def:	The database tabe definition, to be used to create the table if needed.
	
	db_table_init:	An array of sql commands to initialise the table with some data.
					
	db_table_fields:	An array of field names used in the table;
					
	db_table_id:	The field name of the primary key for the table.
	
	table_fields:	This is an array used to store the data for the class (indexed by field names)
					
METHODS

	__construct($vars)
	
		$vars should be an array of data to initialise the object (e.g. an array of data from a row of the database).
		If this is overloaded, ensure that parent->__construct($vars) is called.
	
	
	__get, __set (cannot be implemented by extending class)
	
		These functions will capture data assignments, and ensure that only valid field names get their data set.
		All non database table info should not be set via these methods.
		
		
	Errors() (must be implemented by extending class)
		
		This function should examine $table_fields to ensure that all important fields are valid.
		It should be called before saving data to report errors.
		
		Retuns null if there are no errors
		
		returns an array indexed by field name of error descriptions (e.g. array('password'=>'Password must be 6-15 characters long')).
		
		
	CleanFields() (must be implemented by extending class)
	
		This function will iterate through the $table_fields and ensure that all data is filtered to protect from insertion attacks, and ensure type consistancy.
		Fields that are invalid should be set to null. It returns an array of values indexed by field name used by the save function.
		This is called automatically by the Save function.


	Save($id == null)
	
		This function saves the object data to the database. If there is no id given, the function will check for an id in the data,
		and if no id ios found, it will be inserted as a new row. if there is an id, the database will be updated.
		To provide additional functionality, this should be overloaded in the extending class, calling parent->Save($id).
		
		
	Delete($id = null)
		
		Similar to the save function this will delete a row from the database, given either the id or using the id of the current instance.
		This can also be overloaded, and called via parent->Delete($id) to extend functionality.
		
		
	InsertSQL, UpdateSQL (cannot be implemented by extending class)
	
		These functions generate the sql queries.
		
	
*/
	
abstract class DBObject {

	//private static $db_table_name = "";
	//private static $db_table_def = "";
	//private static $db_table_init = array();
	//private static $db_table_fields = array();
	//private static $db_table_id = "";
	public $additionalWhere;
	
	private $table_fields;
	function __construct($vars = null)
	{
		$this->table_fields = array();
		foreach($this->DbTableFields() as $f)
		{
			if(isset($vars[$f]))
			{
				$this->table_fields[$f] = $vars[$f];
			}
			else $this->table_fields[$f] = null;
		}
	}
	
	public function __get($field)
	{
		//if (array_key_exists($field,$this->table_fields)) {
			return $this->table_fields[$field];
		//} else {
		//	return null;
		//}
	}
	
	public final function __set($field, $val)
	{
		//if(array_key_exists($field,$this->table_fields))
			$this->table_fields[$field] = $val;
		//else {
		//	throw new exception("Cannot set value for ".get_class($this).". Invalid field name '$field'");
			
		//}
	}
	
	abstract public function DbTableName();
	/*{
		return self::$db_table_name;
	}*/
	
	abstract public function DbTableDef();
	/*{
		return self::$db_table_def;
	}*/
	
	abstract public function DbTableInit();
	/*{
		return self::$db_table_init;
	}*/
	
	abstract public function DbTableFields();
	/*{
		return self::$db_table_fields;
	}*/
	
	abstract public function DbTableId();
	/*{
		return self::$db_table_id;
	}*/
		
	public function Save($id = null, $DBI = null)
	{
		$update = false;
		if($id!==null)
		{
			$this->table_fields[$this->DbTableId()] = $id;
		}
		if(get_class($DBI)!="DBInterface")
			$DBI = new DBInterface();
		if($this->table_fields[$this->DbTableId()]===null)
		{
			$query = $this->InsertSql();
		} else
		{
			$query = $this->UpdateSql();
			$update = true;
		}
		//echo "SAVE ".get_class($this)."<br>$query<br>";return false;
		
		$res = $DBI->DBUpdate($query);
		
		if(!$update and $res->Success())
		{
			$this->id = $DBI->DBLastInsertId();
		}
		return $res;
	}	
		
	private final function InsertSQL()
	{
		$vals = $this->CleanFields();
		
		if(is_array($vals))
		{
			$fields = "";
			$values = "";
			$st = true;
			foreach($vals as $f=>$v)
			{

				if($st)
				{
					$st = false;
				} else {
					$fields .= ",";
					$values .= ",";
				}
				$fields .= "$f ";
				$values .= "$v ";
			}
		} else return "";
		$sql = "insert into ".$this->DbTableName()." ($fields) values ($values)";
		return $sql;	
	}
	
	private final function UpdateSQL($id = null)
	{
		$db_table_id = $this->DbTableId();
		if($id==null or !is_numeric($id))
			$id = $this->table_fields[$db_table_id];
		
		$vals = $this->CleanFields();
		
		$sql = "update ".$this->DbTableName()." set ";
		if(is_array($vals))
		{
			$st = true;
			foreach($vals as $f=>$v)
			{
				if($st)
					$st = false;
				else
					$sql .= ",";
				$sql .= "$f=$v ";
			}
		} else return "";
		$sql .= "where $db_table_id='$id'";
		return $sql;
	}
	
	public function CleanFields()
	{
		$vals = array();
		$mcgpc = get_magic_quotes_gpc();
		foreach($this->DbTableFields() as $field)
		{
			if($field!=$this->DbTableId())
			{
				if(!is_numeric($this->$field))
					$vals[$field] = $mcgpc? stripslashes($this->$field):$this->$field;
				else
					$vals[$field] = $this->$field;
			}
		}
		foreach($vals as $f=>$v)
		{
			$vals[$f] = "'".mysql_escape_string($v)."'";
		}
		
		return $vals;
	}
	
	public function Delete($id = null)
	{
		$db_table_id = $this->DbTableId();
		if($id===null)
		{
			if($this->id===null)
				return false;
			else
			{
				$id = $this->table_fields[$db_table_id];
			}
		}
		$DBI = new DBInterface();
		
		$table = $this->DbTableName();
		$query = "delete from $table where $db_table_id='$id' $this->additionalWhere";
		$res = $DBI->DBUpdate($query);
		if(!empty($this->additionalWhere))
			return mysql_affected_rows();
		return $res;
	}
}
?>