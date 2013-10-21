<?php
class years
{
	var $allowed = array('name');
	function add($params)
	{
		$params = buildQueryList(limitArray($this->allowed, $params));
		mysql_query("INSERT INTO cat SET $params") or die(mysql_error());
		return mysql_insert_id();
	}
	
	function remove($id)
	{
		$parts = new parts;
		foreach($parts->getAll($id) as $key => $value)
		{
			$parts->remove($value['id']);
		}
		mysql_query("DELETE FROM download WHERE id = '" . addslashes($id) . "'") or die(mysql_error());
		return true;
	}
	
	function edit($id, $params)
	{
		$params = buildQueryList(limitArray($this->allowed, $params));
		mysql_query("UPDATE cat SET $params WHERE id = '" . addslashes($id) . "'") or die(mysql_error());
		return mysql_affected_rows();
	}
	
	function get($id)
	{
		$result = mysql_query("SELECT * FROM cat WHERE id = '" . addslashes($id) . "'") or die(mysql_error());
		return mysql_fetch_assoc($result);
	}
	
	function getAll()
	{
		$result = mysql_query("SELECT * FROM cat ORDER BY name") or die(mysql_error());
		$rows = array();
		while($row = mysql_fetch_assoc($result))
		{
			$rows[] = $row;
		}
		return $rows;
	}
}

class parts
{
	function add($year, $params)
	{
		$params = buildQueryList(array_merge(array('id' => $year), limitArray($this->allowed, $params)));
		mysql_query("INSERT INTO download SET $params") or die(mysql_error());
		return mysql_insert_id();
	}
	
	function remove()
	{
	
	}
	
	function edit()
	{
	
	}
	
	function get()
	{
	
	}
	
	function getAll($year = NULL)
	{
	
	}
}

class cases
{
	function add()
	{
	
	}
	
	function remove()
	{
	
	}
	
	function edit()
	{
	
	}
	
	function get($id)
	{
	
	}
	
	function getAll($year = NULL, $part = NULL)
	{
	
	}
}

function limitArray($allowed, $data)
{
	foreach($data as $key => $value)
	{
		if(!in_array($key, $allowed))
			unset($data[$key]);
	}
	
	return $data;
}

function buildQueryList($array)
{
	foreach($array as $key => $value)
	{
		$array[$key] = "$key = '" . addslashes($value) . "'";
	}
	
	return join(', ', $array);
}
?>