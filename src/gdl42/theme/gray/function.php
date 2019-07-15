<?php

if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function gdl_content_box($content,$title=""){
	if ($title<>"") $form .= "<h3>$title</h3>\n\n";
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

function gdl_search_box($form,$tabs="",$selected=""){

	$main = "<div class=\"hideprint\">\n";
	$main .= "<p>";
	if (($selected=="dc") or ($selected=="")){	
    	$main .= "<b>"._SEARCHALL."</b>";
   	}else{
    	$main .= "<a href=\"./gdl.php?mod=search&amp;schema=dc\">"._SEARCHALL."</a>";
   	}
   	if (is_array($tabs)){
		while (list($key,$val) = each($tabs)){
			if ($selected==$key){	
				$main .=" | <b>$val</b>";
			}else{
			 	$main .=" | <a href=\"./gdl.php?mod=search&amp;schema=$key\">$val</a>";
			}
		}
	}
	$main .="</p>\n";

	$main .= "$form\n";
	$main .= "</div>\n";
	return gdl_content_box($main,_ADVANCESEARCH);
}

?>