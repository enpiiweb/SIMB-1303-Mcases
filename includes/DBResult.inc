<?php
class DBResult {

	private $success;
	private $result;
	private $message;
	public $query;
	public $altered;

	function __construct($success, $result=null, $message = "", $query = "", $altered = 0)
	{
		if($success===false)
			$this->success = false;
		else $this->success = $success;
		$this->query = $query;
		$this->result = $result;
		$this->message = $message;
		$this->altered = $altered;
	}
	
	function Success()
	{
		return $this->success;
	}
	
	function Result()
	{
		return $this->result;
	
	}
	
	function Message()
	{
		return $this->message;
	
	}
	
	function GetRow()
	{
		if($this->success)
			return mysql_fetch_assoc($this->result);
        else return false;
	
	}
	
	function GetResult($field, $row=0)
	{
		if($this->success)
			return mysql_result($this->result,$row,$field);
		else return false;
	}

}



?>