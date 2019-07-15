<?php
if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) die();

$_SESSION['DINAMIC_TITLE'] = "Upload Data";
$node = $_SESSION['gdl_node'];
$member_node=$gdl_folder->check_folder("Member",0);

if (!ereg("err",$member_node)) {
		$mydocs_node=$gdl_folder->check_folder($gdl_session->user_id,$member_node);
	}
	
if ($node==0) {
		if (!ereg("err",$mydocs_node))
			$node=$mydocs_node;	
}
if (!ereg("err",$node) && ($node > 0)) {
	if (($gdl_session->group_id=="Editor" && $mydocs_node==$node) || ($gdl_session->group_id <> "Editor")) {
	
		$_SESSION['gdl_node'] = $node;
		
		include ("./module/upload/function.php");
		include ("./schema/lang/".$gdl_content->language.".php");
		include ("./schema/upload/selection.php");
		
		// save folder property to session
		$property['folder'] = $node;
		$property['owner'] = $gdl_session->user_id;
		$_SESSION['gdl_property'] = $property;

		$main = current_state();

		$main .= "<h3>"._WHATSCHEMA."</h3>\n";
		$main .= "<p>$content</p>\n";
		$main = gdl_content_box($main,_STEP1);
	} else {
		$main.=_DIRECTORYERROR;
		$main = gdl_content_box($main,"Upload Metadata");
	}
} else
{
	$main.=_DIRECTORYERROR;
	$main = gdl_content_box($main,"Upload Metadata");
}
$gdl_content->set_main($main);
$gdl_folder->set_path($node);

?>