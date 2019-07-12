<?php

/***************************************************************************
                         /module/browse/index.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, KMRG ITB
    email                : hayun@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/
 
if (eregi("index.php",$_SERVER['PHP_SELF'])) die();
$_SESSION['DINAMIC_TITLE'] = _METADATAINFOLDER;

$state		= $_GET['state'];
$is_offline = ($state == "offline")?true:false;
$child		= $_GET['child'];

if($is_offline){
	$arr_child	= explode(",",$child);
	$arr_child	= array_unique($arr_child);
	$stop 		= false;
	$c_arr_child= count($arr_child);
	for($i=0;($i<$c_arr_child) && !$stop;$i++){
		$stop = ereg("^[0-9]+$",$arr_child[$i])?false:true;
	}
	if($stop) $is_offline = false;
	else
		$under_node = $arr_child;
}

// get node to display
$node = $_GET['node'];
if (!isset($node)){
	$node = $_SESSION['gdl_node'];
	if (!isset($node)) $node=0;
}

$_SESSION['gdl_node']=$node;
require_once ("./module/browse/function.php");
$gdl_folder->set_list($node,$is_offline,$under_node);
$folder_name  = $gdl_folder->get_name($node);
$metadata = get_metadata($node);
if ($metadata <> ""){
	$metadata = gdl_content_box($metadata,_METADATAINFOLDER." $folder_name");
	$gdl_content->set_main($metadata);
}

?>