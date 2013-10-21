<?php
class DBInterface {

	private $username;
	private $password;
	private $server;
	private $database;
	private $dblink;
	private $connected;

	private $last_erno;
	
	function __construct($connect = true) {
		if(function_exists("GetDbCodes"))
		{
			$db = GetDbCodes();
			$this->username = $db["user"];
			$this->password = $db["pass"];
			$this->server = $db["server"];
			$this->database = $db["database"];
			$this->connected = false;
			$this->last_erno = false;
		} else {
			$error = "Unable to load database connection information.";
			throw new Exception($error);
		}
		if($connect)
			$this->DbConnect();
	}

	function __destruct() {
		$this->DbDisconnect();
	}

	function DbConnect() {
		$this->dblink=mysql_connect($this->server, $this->username, $this->password);
		
		if($this->dblink===false)
			throw new Exception('I cannot connect to the database because: ' . mysql_error());
		if(!mysql_select_db ($this->database))
			throw new Exception('I cannot select the database because: ' . mysql_error());
		global $current_num_of_dbcons;
		if(isset($current_num_of_dbcons))
		{
			$current_num_of_dbcons++;
		} else $current_num_of_dbcons = 1;
		//	AddCheck("db_connected".$current_num_of_dbcons, "connection success!<br>".print_trace()."<br>");
		$this->connected = true;
		return new DBResult(true);
	}

	function DbDisconnect() {
		//if($this->connected)
		//	mysql_close($this->dblink);
	}
	
	function GetNumRows($table)
	{
		$query = "select Count(*) as c from $table";
		$res = $this->DbSelect($query);
		if($res->Success())
		{
			return $res->GetResult("c");
		
		} else return false;
	
	}
	
	function GetTables()
	{
		$query = "SHOW TABLES";
		$res = $this->DbSelect($query);
		if($res->Success())
		{
			return $this->MakeArray($res, "Tables_in_".$this->database,"Tables_in_".$this->database);
		
		} else return false;
	
	}
	
	function ClearTable($table)
	{
		$query = "delete from $table";
		$res = $this->DbUpdate($query);
		return $res->Success();
	}
	
	function DbSelect($query) {
		if($this->connected == false)
			$this->DbConnect();
		$res = mysql_query($query);
		//mysql_close();
		$num = @mysql_num_rows($res);
		$erno = mysql_errno($this->dblink);
		if($erno!=$this->last_erno and $erno or $num==0)
		{
			$success = false;
			if($erno==$this->last_erno or !$erno)
			{
				$this->last_erno = $erno;
				$message = $erno . ": " . mysql_error($this->dblink);
			} else {
				$message = "No rows returned";
			}
		} else {
			$success = true;
			$message = "";
		}
		return new DBResult($success, $res, $message, $query, $num);
	}
	
	function DbUpdate($query) {
		if($this->connected == false)
			$this->DbConnect();
		$res = mysql_query($query);
		//mysql_close();
		$num = @mysql_affected_rows($this->dblink);
		$erno = mysql_errno($this->dblink);
		if($erno!=$this->last_erno and $erno)
		{
			$success = false;
			$this->last_erno = $erno;
			$message = $erno . ": " . mysql_error($this->dblink);
		} else {
			$success = true;
			$message = "";
		}
		return new DBResult($success, $res, $message, $query, $num);
	}
	
	function DBLastInsertId()
	{
	   return mysql_insert_id($this->dblink);
	
	}
	
	function TableExists($table)
	{
		$query = "show table status like '$table'";
		$res = $this->DbSelect($query);
		if($res->Success()==false)
			return false;
		else return true;
	}
	
	function DropTable($table)
	{
		if($this->TableExists($table))
		{
			$query = "drop table $table";
			$this->DbUpdate($query);
			return true;
		} else 
			return false;
	}
	
	
	function CreateTable($table)
	{
		global $sitetables, $tablesetup;

		if($this->TableExists($table))
			return true;
		GetTableDefinitions();
		if(!isset($sitetables[$table]))
			return false;
		
		$query = $sitetables[$table];
		$this->DbUpdate($query);
		for($i=0;isset($tablesetup[$table.$i]);$i++)
		{
			$query = $tablesetup[$table.$i];
			$this->DbUpdate($query);
		}
		return true;
	}
	
	function MakeArray($res, $field, $val=false,$object = false) {
		if(get_class($res)!="DBResult")
			return "Not a result ".get_class($res);
		if($res->Success()==false)
			return false;
		$result = $res->Result();
		$array = null;
		while($row = mysql_fetch_assoc($result))
		{
			$key = "";
			if(is_array($field))
			{
				foreach($field as $f)
				{
					if($key!="")
						$key = $key."_";
					$key = $key.$row[$f];
				}
			} else
				$key = $row[$field];
			if($object===false)
			{
				if($val!==false)
				{
                    $array[$key] = $row[$val];
                }
				else 
				{
                    if($field!==false)
                        $array[$key] = $row;
                    else
                        $array[] = $row;
                }
					
			}
			else {
			 
		         $obj = ClassFactory::ObjectNew($val,$row);
				$array[$key] = $obj;
				
			}
		
		}
		return $array;
	}



}
?>