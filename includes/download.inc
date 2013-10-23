<?php
//include_once("ImageResizeFactory.php");
$site_vars["class"]["download"] = "db";

class download extends DBObject {

	private static $db_table_name = "download";
	private static $db_table_def = "create table download (
id int unsigned not null primary key auto_increment,
file varchar(255),
date int,
title varchar(255),
description text,
restricted int unsigned,
cat int unsigned
);";
	private static $db_table_init = array();
	private static $db_table_fields = array("id","file","date","title","description","cat","restricted");
	private static $db_table_id = "id";
	
	public static function DbTableFields()
	{
		return self::$db_table_fields;
	}
	
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
		
		if(strlen($this->title)<1)
		{	$error["title"] = "You must supply a download title.";	}

		return $error;
	}
	
	function DeleteFile()
	{
		global $fileroot;
		if($this->file)
		{
			$file = $fileroot."download/$this->cat/".$this->file;
			if(file_exists($file))
				@unlink($file);
		}
	}
	
	function Delete($id = NULL)
	{
		if(parent::Delete() > 0)
			$this->DeleteFile();
		return true;
	}
	
	
	function MoveFile($newcat)
	{
		global $fileroot;
		$old = $fileroot."download/$this->cat/".$this->file;
		$new = $fileroot."download/$newcat/".$this->file;
		if(file_exists($old))
		{
			if(file_exists($new))
				@unlink($new);
			@rename($old, $new);
			$this->cat = $newcat;
			return true;
		}
		return false;
	}
	
	function UploadFile($field,$cat)
	{
		global $fileroot;
		$this->DeleteFile();
		if(isset($_FILES[$field]))
		{
			$newname = $fileroot."download/$cat/{$this->id}_".$_FILES[$field]["name"];
			if(file_exists($newname))
				@unlink($newname);
			@copy($_FILES[$field]["tmp_name"], $newname);
			@chmod($newname,0777);
			$this->file = "{$this->id}_".$_FILES[$field]["name"];
			$this->cat = $cat;
			return true;
		
			
		}
		return false;
	}	
	
	function FileLink()
	{
		global $relroot;
		if($this->file)
			return $relroot."download/$this->cat/$this->file";
		else return false;
	}
	
	function FileSize()
	{
		global $relroot,$fileroot;
		if(file_exists($fileroot."download/$this->cat/$this->file"))
			return filesize($fileroot."download/$this->cat/$this->file");
		else return false;
	}
}
?>