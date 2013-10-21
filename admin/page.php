<?php

  include ('../includes/includes.php');
  // The shopping cart needs sessions, so start one
$template = new SiteTemplate();
global $siteroot, $relroot,$fileroot;

Authorise($template,"index.php","Admin",admin_login_form());

$page = "<div class='adminmenu'><a href='index.php'>Admin Menu</a></div>";

$ref = isset($_REQUEST["ref"])? $_REQUEST["ref"] : "";
$action = isset($_REQUEST["action"])? $_REQUEST["action"] : false;
$errors = false;

switch($action)
{
	case "Save":
		$b = new page($_POST);
		if($_POST["pagetype"]=="page")
		{
			$b->url = "";
		} else {
			switch($_POST["urlfrom"])
			{
				case "text":
				
				break;
				case "upload":
					if(is_array($_FILES["uploadurl"]))
					{
						if(@file_exists($fileroot."pagefiles/".$_FILES["uploadurl"]["name"]))
						{
							@unlink($fileroot."pagefiles/".$_FILES["uploadurl"]["name"]);
						}
						@copy($_FILES["uploadurl"]["tmp_name"],$fileroot."pagefiles/".$_FILES["uploadurl"]["name"]);
						$b->url = "pagefiles/".$_FILES["uploadurl"]["name"];
					}
				break;
				case "list":
					$b->url = $_POST["selecturl"];
				break;
			}
		}
		if($errors = $b->Errors())
		{
			$page .= PageForm($b, $errors);
		} else {
			if($_POST["image"]=="new")
			{
				$b->UploadImage("uploadimage");
			}
			$res = $b->Save();
			if($res->Success()!==false)
			{
				if($b->url)
				{
					$url = $relroot.$b->url;
				} else {
					$url = "{$relroot}pages/";
					$url .= $b->grandparent? "$b->grandparent/":"";
					$url .= $b->parent? "$b->parent/":"";
					$url .= "$b->ref/";
				}
				$page .= "<p>Page successfully saved. <br><a href='$url'>Click Here</a> to view it.</p>";
			} else {
				$page .= "<p>Unable to save page. Please review the information and try again.</p>";
				$page .= PageForm($b);
			}
		}
	break;
	
	case "delete":
		$b = page::FromRef($ref);
		if(get_class($b)=="page")
		{
			$page .= "<p>Are you sure you want to delete the page: <b>{$b->title}</b>?<br><a href='page.php?action=reallydelete&ref={$b->ref}'>Delete</a> <a href='{$relroot}page/'>Cancel</a></p>";
		} else {
			$page .= "<p>Page not found.</p>";
		}
	break;
	
	case "reallydelete":
		$b = page::FromRef($ref);
		if(get_class($b)=="page")
		{
			$b->Delete();
			$page .= "<p>Page deleted.</p>";
		} else {
			$page .= "<p>Page not found.</p>";
		}
	break;
	
	default:
		$b = page::FromRef($ref);
		if($b instanceof page)
		{
			$edit = true;
		} else {
			$b = new page(array("ref" => $ref));
		}
		$page .= PageForm($b);
	break;
}

$head = $template->do_header();
$foot =  $template->do_footer();

echo $head;
echo $page;
echo $foot;



function PageForm($b, $errors = null)
{
	global $relroot,$fileroot;
	$edit = $b->id? true : false;
	$page = "<h2>".($edit? "Edit":"New")." page</h2>";
	$page .= "<form name='page' method='POST' action='page.php' enctype='multipart/form-data'>";
	$page .= "<input type='hidden' name='ref' value='$b->ref'>";
	//$b->parent = $edit? $b->parent : (isset($_REQUEST["parent"])? $_REQUEST["parent"] : "");
	//$b->grandparent = $edit? $b->grandparent : (isset($_REQUEST["gp"])? $_REQUEST["gp"] : "");
	//$page .= "<input type='hidden' name='parent' value='$b->parent'>";
	//$page .= "<input type='hidden' name='grandparent' value='$b->grandparent'>";
	$page .= "<p><b>Title</b>".(isset($errors["title"])? " <span class='alert'>".$errors["title"]."</span>":"")."<br><input name='title' maxlength=255 size=30 value='{$b->title}'></p>";
	/*if($b->parent)
	{
		$file_ar = null;
		if($dh = @opendir($fileroot."pagefiles/"))
		{
			while($file = @readdir($dh))
			{
				if($file!=".." and $file!=".")
				{
					$file_ar["pagefiles/$file"] = $file;
				}
			}
		
		}
		$page .= "<hr><h3><input type='radio' name='pagetype' value='link'".($b->url? " checked":"")."> Link page to a file</h3><div style='padding-left:20px;'>
		<p><input type='radio' name='urlfrom' value='list'".(isset($file_ar[$b->url])? " checked" : "")."> <b>Select a File</b><br>".do_select($file_ar,$b->url,"selecturl")."</p>
		<p><input type='radio' name='urlfrom' value='upload'> <b>OR - Upload File</b><br><input type='file' name='uploadurl'></p>
		<p><input type='radio' name='urlfrom' value='text'".(isset($file_ar[$b->url])? "" : " checked")."> <b>OR - Enter URL</b><br><input name='url' value='".(isset($file_ar[$b->url])? "" : $b->url)."' size=70 maxlength=255></p>";
		
		$page .= "<p><input type='submit' name='action' value='Save'></p></div><hr><h1>OR</h1><hr><h3><input type='radio' name='pagetype' value='page'".((strlen($b->url)<1)? " checked":"")."> Enter page contents</h3>";
	} else {
		$page .= "<input type='hidden' name='pagetype' value='page'>";
	}
	$page .= "<div style='padding-left:20px;'>";*/
	$page .= "<p><b>Text</b>".(isset($errors["left_text"])? " <span class='alert'>".$errors["left_text"]."</span>":"")."<br><textarea name='left_text' cols=40 rows=15>{$b->left_text}</textarea></p>";
	/*$page .= "<p><b>Right Side Text</b>".(isset($errors["right_text"])? " <span class='alert'>".$errors["right_text"]."</span>":"")."<br><textarea name='right_text' cols=40 rows=5>{$b->right_text}</textarea></p>";
	$page .= "<p><b>Image</b><br>";
	if($b->image)
		$page .= "Use Current: <input type='radio' name='image' value='$b->image' checked> <img src='{$relroot}images/curved_squares/t_$b->image'><br><br>";
	$images = GetImages();
	if(is_array($images))
	{
		$col = 0;
		$page .= "Choose Existing:<br><table border=1 cellspacing=0 cellpadding=5>";
		foreach($images as $image)
		{
			if($col==0)
			{
				$page .= "<tr valign='top'>";
			}
			if($image!=$b->image)
			{
				$page .= "<td><input type='radio' name='image' value='$image'> <img src='{$relroot}images/curved_squares/t_$image'></td>";
			}
			if($col==2)
			{
				$col=0;
				$page .= "</tr>";
			} else {
				$col++;
			}
		}
		if($col==2)
			$page .= "<td>&nbsp;</td></tr>";
		$page .= "</table><br>";
	
	}
	
	$page .= "<input type='radio' name='image' value='new'> Upload New: <input type='file' name='uploadimage'><br>";
	$page .= "<input type='radio' name='image' value='' ".(($b->image==false)? " checked":"")."> No Image<br>";
	
	$page .= "</p>";
	$page .= "<p><b>Image on: </b><br><input type='radio' name='imageside' value='r'".(($b->imageside=="r")?" checked":"")."> Right hand side<br><input type='radio' name='imageside' value='l'".(($b->imageside!="r")?" checked":"")."> Left hand side</p>";
	//$page .= "</div>";*/
	$page .= "<p><input type='submit' name='action' value='Save'>".($edit? "<input type='hidden' name='id' value='{$b->id}'>":"")."</p>";
	$page .= "</form>";
	return $page;
}

function GetImages()
{
	include_once("../includes/ImageResizeClass.php");
	global $fileroot;
	$images = null;
	if(is_dir($fileroot."images/curved_squares"))
	{
		if($handle = opendir($fileroot."images/curved_squares"))
		{
			while (false !== ($file = readdir($handle))) {
				if(ereg(".jpe?g$", $file) and !ereg("^t_",$file))
				{
					if(!file_exists($fileroot."images/curved_squares/t_".$file))
					{
						$objResize = new ImageResizeJpeg($fileroot."images/curved_squares/".$file,$fileroot."images/curved_squares/t_".$file,200,200 );
						$objResize->getResizedImage();
					}
					$images[] = $file;
				}
			}
			closedir($handle);
		} else echo "cant open";
	} else echo "NOT DIR";
	return $images;
}
?>