<?php
class ClassFactory {

	public static function ObjectNew($classname,$v,$id_field = false)
	{
		//	ObjectNew initialised an object with an array of data, or returns an object from the database.
		
		//	$classname:	The name of the class to create
		//	$v:			Either an array of values to initialise the class, or the id to search for in the database
		//	$id_field:	This should only be used if the id field is not 'id'
		
		if(!is_array($v))
		{
			if(ereg("^-?[0-9]+$",$v))
			{
				$DBI = new DBInterface();
				eval("\$table_name = $classname::DbTableName();");
				$fields = "*";
				if(!$id_field)
					eval("\$id_field = $classname::DbTableId();");
				$table_name = (strlen($table_name)>0)? $table_name : $classname;
				eval("\$fields = $classname::DbTableFields();");
				if(is_array($fields))
				{
					$f = "";
					foreach($fields as $field)
					{
						if($f!="")
							$f .= ",";
						$f .= $table_name.".".$field;
					}
				} else {
					$f = $fields;
				}
				//echo $query;
				$query = "select $f from $table_name where $id_field='$v'";
				$res = $DBI->DBSelect($query);
				if($res->Success())
				{
					$vars = $res->GetRow();
					//echo "SUCCESS: $query<br><br>";
				}
				else {
					//echo "FAILED: $query<br><br>";
					return false;	
				}
			
			} else {
				$vars = null;
			}
		} else {
			$vars = $v;
		}
		/*		$test = "<p>";
				foreach($vars as $n => $v)
                    $test .= "<b>$n</b> $v<br>";
                $test .= "</p>";
				AddCheck("<b>$classname</b>", $test);   */
			//	echo print_trace();
		eval("\$class = new $classname(\$vars);");
		
		return $class;
	
	}

	static function ObjectArray($class, $object = false, $condition = false)
	{
		//	ObjectArray returns an array of database information.
	
		//	$class:	 	The name of the class we are making an array of
		//	$object: 	If false, each element of the array will be an array with the database values, otherwise each element will be an object
		//	$condition:	This is what you want to come after the table name in the db select statement. e.g. "where id>12"
		$DBI = new DBInterface();
		$query = false;
		$val = false;
		$key = "id";
		eval("\$table_name = $class::DbTableName();");
		$table_name = (strlen($table_name)>0)? $table_name : $class;
		eval("\$fields = $class::DbTableFields();");
		$fields = $condition;
		if(is_array($fields))
		{
			$f = "";
			foreach($fields as $field)
			{
				if($f!="")
					$f .= ",";
				$f .= $table_name.".".$field;
			}
		} else {
			$f = '*';
		}
		$query = "select $f from $table_name";
		if($condition)
			$searchfields = " ".$condition;
		else $searchfields = "";
		
		$obj = false;
		//echo "OBJECT $class<br>$query$searchfields<br><br>";
		if($object)
		{
			$val = $class;
			$obj = true;
		}
		if($query!==false) {
			$res = $DBI->DbSelect($query.$searchfields);
			if($res->Success())
                return $DBI->MakeArray($res, $key, $val, $obj);
            else {
            	//print_r($res);
            	//return $res->Message();
                return null;
                
            }
		} else {
            	return "Query ($query) is false";
		}

	}

	function ObjectCount($class, $condition = false)
	{
	
		if($condition)
			$searchfields = " ".$condition;
		else
			$searchfields = "";
		eval("\$table_name = $class::DbTableName();");
		$table_name = (strlen($table_name)>0)? $table_name : $classname;
		$query = "select Count(*) as c from $table_name";
		$DBI = new DBInterface();
		$res = $DBI->DbSelect($query.$searchfields);
		if($res->Success())
			return $res->GetResult("c",0);
		else return false;
	}

}


?>