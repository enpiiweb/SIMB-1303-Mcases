<?php

class EmailCenter {
	
	 function SendTemplateEmail($template,$assigndata,$to,$from,$headers = "")
	 {
	 	$templatefolder = "templates";
	 	$template_file = $_SERVER["DOCUMENT_ROOT"].$templatefolder."/$template.tpl";
	 	if(file_exists($template_file))
	 	{
	 		$headers .= "From: $from" . "\r\n" . "Reply-To: $from" . "\r\n";
			$smarty = new Smarty();
			$smarty->assign("emaildata",$assigndata);
			$message = $smarty->fetch($template_file);
			$firstnl = strpos($message,"^");
			$subject = rtrim(substr($message,0,$firstnl));
			$message = ltrim(strstr($message,"^"),"^\n\r");
			mail($to,$subject,$message,$headers);
	 	} else {
	 		return false;
	 	}
	 }

}