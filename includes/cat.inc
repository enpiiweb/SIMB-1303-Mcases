<?php
global $site_vars;
$site_vars["class"]["cat"] = "db";


class cat extends DBObject {
	private static $db_table_name = "cat";
	private static $db_table_def = 
"create table cat
(
	id			int(4) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
	showorder		int(4) UNSIGNED NOT NULL,
	name		varchar(64) NOT NULL
)";



	private static $db_table_init = array();
	private static $db_table_fields = array("id","name");
	private static $db_table_id = "id";
	
	function __construct($vars = null)
	{
		parent::__construct($vars);
	}
		
	public static function DbTableName()
	{
		return self::$db_table_name;
	}
		
	public static function DbTableNameTest()
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
	
	public function DbTableId()
	{
		return self::$db_table_id;
	}
	
	function Errors()
	{
		$errors = null;
		
		if($errors===null)
			return false;
		else return $errors;
	}
	
	function Delete($id = null)
	{
		global $fileroot;
		if($id)
			$this->id = $id;
		$folder = $fileroot."download/$this->id/";
		if(is_dir($folder))
		{
			if ($handle = opendir('/path/to/files')) {
				while (false !== ($file = readdir($handle))) {
					if($file!="." and $file!="..")
					{
						@unlink($folder.$file);
					}
				}
			}
			@rmdir($folder);
		}
		$downloads = ClassFactory::ObjectArray("download", true);
		if(is_array($downloads))
		{
			foreach($downloads as $dl)
			{
				$dl->additionalWhere = " AND cat = $this->id";
				$dl->Delete();
			}
		}
		$articles = ClassFactory::ObjectArray("article", true);
		if(is_array($articles))
		{
			foreach($articles as $a)
			{
				$a->additionalWhere = " AND cat = $this->id";
				$a->Delete();
			}
		}
		$res = parent::Delete($id);
	}

	function Save($id = null, $DBI = NULL)
	{
		global $fileroot;
		$res = parent::Save($id);
		if($res->Success())
		{
			$folder = $fileroot."download/$this->id/";
			if(!is_dir($folder))
			{
				@mkdir($folder);
				@chmod($folder,0777);
			}
		}
		return $res;
	}
}
?>