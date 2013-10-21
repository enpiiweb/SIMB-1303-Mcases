<?php
require_once('class.inc');

class variables extends DBObject {
	var $value;
	var $description;

	function variables($vars = null)
	{
		$this->db_table = "variables";
		
		if(is_array($vars))
		{
			$this->id = isset($vars["id"])? $vars["id"] :  null;
			$this->value = isset($vars["value"])? $vars["value"] :  null;
			$this->description = isset($vars["description"])? $vars["description"] :  null;
		} else {
			if(ereg("^[a-zA-Z0-9_ .&?!$#-]+$",$vars))
			{
				$DBI = new DBInterface();
				$query = "select * from {$this->db_table} where id='{$vars}'";
				$res = $DBI->DbSelect($query);
				if($res->Success())
				{
					$ar = $res->GetRow();
					$this->id = $ar["id"];
					$this->value = $ar["value"];
					$this->description = $ar["description"];
				}
			}
		}
	}
	
	function Errors()
	{
		$error = null;
		
		if(!strlen($this->id)>0)
			$error["id"] = "<div class='alert'>Invalid variable name.</div>";
		
		
		return $error;
	}

	
	function TableDefinition()
	{
return "create table variables (
id varchar(31) not null primary key,
description varchar(255),
value text
);";

/* Some Setup fields
insert into variables (id) values ('list_info_fields');
insert into variables (id) values ('newsletter_header_standard');
insert into variables (id) values ('newsletter_footer_standard');
insert into variables (id) values ('list_title');
insert into variables (id) values ('list_from_address');



*/
	}
	
	function CleanFields()
	{
		$vals = array();
		$mcgpc = get_magic_quotes_gpc();
		
		if($this->id!==null)
			$vals["id"] = $mcgpc? stripslashes($this->id):$this->id;
		
		$vals["description"] = $mcgpc? stripslashes($this->description):$this->description;
		$vals["value"] = $mcgpc? stripslashes($this->value):$this->value;
		foreach($vals as $f=>$v)
		{
			if (!is_numeric($v))
			{
				$vals[$f] = "'".mysql_escape_string($v)."'";
			}
		}
   
		return $vals;
	}
		
	function DBFields()
	{
		return "id,value,description";
	}
}
?>