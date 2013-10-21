<?php

#----- Put all filenames from /casesum folder into array





define('CASESUMDIR', dirname(__FILE__) . '/casesum');

$file_list = array();

if (is_dir(CASESUMDIR)){

	$regex = '<(^|\D)(\d\d\d\d)\D>';

	if ($dir = opendir(CASESUMDIR)){

		while (($file = readdir($dir)) !== false){

			if (preg_match($regex, $file, $matches)){

				$file_list[(integer) $matches[2]] = "/casesum/$file";

			}

		}

		closedir($dir);

	}

}

ksort($file_list);



#----- Do smarty things...



require_once("includes/includes.php");

$smarty = new SiteTemplate();



$action = isset($_GET['action']) ? $_GET['action'] : false;

$id = isset($_GET['id']) ? $_GET['id'] : false;



$smarty->assign('files', $file_list);

$smarty->assign("main_displayfile",'casesum.html');

AssignCategories($smarty);

$smarty->display('main.tpl');

?>

