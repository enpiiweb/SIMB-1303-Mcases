<?php

include ('../includes/includes.php');

$smarty = new SiteTemplate();
global $relroot;

AssignCategories($smarty);
Authorise($smarty,"index.php","Admin");

$smarty->assign('main_displayfile', 'index.tpl');
$smarty->display('main.tpl');


?>