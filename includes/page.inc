<?php
$site_vars["class"]["page"] = "db";

class page extends DBObject {
	public static $db_table_name = "page";
	public static $db_table_def = "create table page (
id int not null primary key auto_increment,
url varchar(255),
parent varchar(31),
grandparent varchar(31),
ref varchar(31) not null,
title varchar(31),
left_text text,
right_text text,
image varchar(127),
imageside varchar(1)
);";
	public static $db_table_fields = array("id","ref","title","parent","left_text","right_text","image","imageside","url","grandparent");
	public static $db_table_id = "id";
	public static $db_table_init = array();
	
	function __construct($vars = null)
	{
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
	
	function Errors()
	{
		$error = null;
		
		if(!$this->ref and !$this->id)
		{
			$this->ref = strtolower(ereg_replace("[^a-zA-Z0-9_-]","",$this->title));
			if(strlen($this->ref) > 0)
			{
				$c = ClassFactory::ObjectCount("page","where ref='$this->ref'");
				if($c > 0)
				{
					$oldref = $this->ref;
					$i = 1;
					while($c)
					{
						$this->ref = $oldref.$i;
						$c = ClassFactory::ObjectCount("page","where ref='$oldref$i'");
						$i++;
					}
				}
			} else {
				$error["title"] = "Not a valid title. Please make sure the title contains at least one letter or number.";
			}
		}
		
		
		
		if(strlen($this->title)<1)
			$error["title"] = "You must supply a page title.";
		
		if(strlen($this->ref)<1)
			$error["ref"] = "You must supply a page reference.";
			
		return $error;
	}
	
	function FromRef($ref, $DBI = null)
	{
		if(!ereg("^[0-9a-zA-Z_-]+$",$ref))
			return null;
			
		if(!$DBI instanceof DbInterface)
			$DBI = new DbInterface();
		
		$query = "select * from page where ref='$ref'";
		$res = $DBI->DbSelect($query);
		if($res->Success())
			return new page($res->GetRow());
		else return null;
	}
	
	function MakeHtml($text)
	{
		$find = array("\n");
		$replace = array("<br>");
		return str_replace($find,$replace,$text);
	}
	
	function Img($left = true)
	{
		global $relroot,$fileroot;
		if($this->image)
		{
			if(file_exists($fileroot."images/curved_squares/".$this->image))
			{
				return '<img src="'.$relroot.'images/curved_squares/'.$this->image.'" style="position:relative;margin-'.($left? "right" : "left").':-40px;margin-top:10px;">';
			}
		}
		return false;
	}
	
	function PrintData($is_admin = false)
	{
		global $relroot;
		/*$img = $this->Img(false);
		$page = '<table width="100%" cellpadding="0" cellspacing="0">
          <tr>
          <td style="width:40px;">&nbsp;</td>
            <td align="left" valign="top" width="'.($img? "1%" : "365px").'">';
		*/
		$page .= $this->MakeHtml($this->left_text);
         /*  
        if($this->imageside != "r")
			$page .= $img;
        $page .= '</td>
            <td style="width:45px;">&nbsp;</td>
            <td align="left" valign="top" class="body_text_right">';
            
		$page .= $this->MakeHtml($this->right_text);       
		
        if($this->imageside == "r")
			$page .= $img;
			     
        $page .= '</td>
          <td style="width:40px;">&nbsp;</td>
            </tr>
            </tr></table>';
		*/
		if($is_admin)
		{
			$page .= "<br clear='all'><a href='{$relroot}admin/page.php?ref={$this->ref}'>Edit</a>";
		//	if($this->parent)
				$page .= "<br clear='all'><a href='{$relroot}admin/page.php?ref={$this->ref}&action=delete'>Delete</a>";
		//	if(!$this->grandparent)
		//		$page .= "<br clear='all'><a href='{$relroot}admin/page.php?parent={$this->ref}&gp={$this->parent}'>Create Sub-page</a>";
		}	
		return $page;
	}
	
	function UploadImage($field)
	{
		include_once("ImageResizeClass.php");
		global $fileroot;
		if(isset($_FILES[$field]))
		{
			if(eregi(".+\.jpe?g", $_FILES[$field]["name"]))
			{
				$newname = $fileroot."images/curved_squares/".$_FILES[$field]["name"];
				if(file_exists($fileroot."images/curved_squares/".$_FILES[$field]["name"]))
					@unlink($fileroot."images/curved_squares/".$_FILES[$field]["name"]);
				if(file_exists($fileroot."images/curved_squares/t_".$_FILES[$field]["name"]))
					@unlink($fileroot."images/curved_squares/t_".$_FILES[$field]["name"]);
				@copy($_FILES[$field]["tmp_name"],$newname);
				$objResize = new ImageResizeJpeg($_FILES[$field]["tmp_name"],$fileroot."images/curved_squares/t_".$_FILES[$field]["name"],200,200 );
				$objResize->getResizedImage();
				return true;
			}
			$this->image = $_FILES[$field]["name"];
			
		}
		return false;
	}
	
	function GetParents()
	{
		if(!$this->parent)
			return null;
		$cur = $this->parent;
		$DBI = new DbInterface();
		$count = 0;
		while($count<10)
		{
			$query = "select parent,title,ref from page where ref='$cur'";
			$res = $DBI->DbSelect($query);
			if(!$res->Success())
				break;
			$cur = $res->GetResult("parent");
			$parents[$res->GetResult("ref")] = $res->GetResult("title");
			if(!$cur)
				break;
			$count++;
		}
		return $parents;
	}
}
?>