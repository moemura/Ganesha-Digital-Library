<?php

if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function gdl_content_box($content,$title=""){
	if ($title<>"") $form .= "<p class=\"title\">$title</p>\n\n";
	$form .= "<div class=\"contentbox\">\n";
	$form .= "$content\n"
		  ."</div>\n";
	return $form;
}

function gdl_relation_box($content,$title=""){
	global $gdl_theme;
	
	if ($title == ""){
		$form = "";
	}else{
		$form = "<p class=\"title\">$title...</p>\n";
	}
	$form .= "<ul>\n";
	if (is_array($content)){
		while (list($key, $val) = each($content)) {
			$form .= "<li class=\"noline\">$val</li>\n";
		}
	}else{
		$form .= "<li>$content</li>\n";
	}
	$form .= "</ul>\n";	
	return $form;
}

?>