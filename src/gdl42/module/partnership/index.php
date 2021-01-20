<?php
if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) {
    die();
}
require_once("./module/partnership/function.php");
global $gdl_sys,$gdl_partner;

$_SESSION['DINAMIC_TITLE'] = _PARTNERSHIP;

$id_repository	= isset($_GET['remote']) ? $_GET['remote'] : null;

$info_remote ='';
if(isset($id_repository)){
	$id_repository	= (preg_match("/^[0-9]+$/",$id_repository))?(int)$id_repository:-1;
	
	if($id_repository > -1)
		$info_remote	=	$gdl_partner->execute_remoteLogin($id_repository);
}

$main	= "<br/>".search_partner_form()."<br/>";
$main	.= $info_remote."<br/>";
$main	.= box_partnership();
$main 	= gdl_content_box($main,_PARTNERSHIP);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=partnership\">"._PARTNERSHIP."</a>";
?>