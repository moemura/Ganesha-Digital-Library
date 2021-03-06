<?php

if (preg_match("/update.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

global $gdl_folksonomy;
require_once("./module/folksonomy/function.php");

$sub 	= isset($_GET['sub']) ? $_GET['sub'] : null;
$del	= isset($_GET['del']) ? $_GET['del'] : null;
if(isset($del) && ($del == "confirm")){
	$gdl_folksonomy->delete_tokenFolksonomy();
}

$refresh = '';
if(isset($sub)){
	switch($sub){
		case "reset" 	:
						$gdl_folksonomy->reset_stopword();
						break;
		case "update"	:
						$refresh = $gdl_folksonomy->update_folksonomy();
						break;
		case "clean"	:
						$refresh = $gdl_folksonomy->clean_stopwordToken();
						break;
	}
}
$main	=  "<p>".display_navigator_update()."</p><br><br>";
$main	.= display_DistribusiFolksonomy();
$main	.= $gdl_folksonomy->show_box_folksonomy();
$main	.= $refresh;
$main 	=  gdl_content_box($main,_FOLKSONOMYMANAGEMENT);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=folksonomy\">"._FOLKSONOMY."</a>";
?>