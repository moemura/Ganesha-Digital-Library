<?php
if (preg_match("/step2.php/i",$_SERVER['PHP_SELF'])) die();

$schema = $_GET['s'];

if (!isset($schema)){
	$id = $_GET['id'];
	if ($id==""){
		$schema="dc_document";
	}else{
		// edit metadata
		$_SESSION['gdl_property'] = $gdl_metadata->get_property($id);
		$frm = $gdl_metadata->read($id);
		$schema = $frm['TYPE_SCHEMA'];
	}
	include ("./module/upload/function.php");
	$main .= current_state();
}

$main .= $gdl_metadata->generate_form($schema);
$main = gdl_content_box($main,_STEP2);
$gdl_content->set_main($main);
$gdl_folder->set_path($_SESSION['gdl_node']);

?>