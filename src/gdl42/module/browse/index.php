<?php
/***************************************************************************
                         /module/browse/index.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, KMRG ITB
    email                : hayun@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/
 
if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) die();
$_SESSION['DINAMIC_TITLE'] = _METADATAINFOLDER;

$state		= isset($_GET['state']) ? $_GET['state'] : null;
$is_offline = ($state == "offline")?true:false;
$child		= isset($_GET['child']) ? $_GET['child'] : null;

$under_node = array();
if($is_offline){
	$arr_child	= explode(",",$child);
	$arr_child	= array_unique($arr_child);
	$stop 		= false;
	$c_arr_child= count($arr_child);
	for($i=0;($i<$c_arr_child) && !$stop;$i++){
		$stop = preg_match("/^[0-9]+$/",$arr_child[$i])?false:true;
	}
	if($stop) $is_offline = false;
	else
		$under_node = $arr_child;
}

// get node to display
$node = isset($_GET['node']) ? $_GET['node'] : null;
if (!isset($node)){
	$node = isset($_SESSION['gdl_node']) ? $_SESSION['gdl_node'] : null;
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