<?php

/***************************************************************************
    copyright            : (C) 2007 Arif Suprabowo, KMRG ITB
    email                : mymails_supra@yahoo.co.uk
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/

if (preg_match("/job.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/liveCD/function.php");

$_SESSION['DINAMIC_TITLE'] = _LIVECD." | "._JOBVIEW;

$url	= "./gdl.php?mod=liveCD&amp;op=job";
$url2	= "./gdl.php?mod=liveCD&amp;op=export";

$main = '';
$check	= checkSupportedTheme($url);
if(!$check['status']){
	$main	= $check['message'];
}else{
	$main	.= "<br/><br/>".box_job($url);
	
	if(isset($_POST['submit']) && $_POST['submit'] == _JOBACTION){
		$main	.= "<br/><br/>".box_info_connection(-1,$url);
	}
	
	$main	.=  "<br/><br/><br/>".handle_build_liveCD($url,$url2);
}
$main 	= gdl_content_box($main,_JOBVIEW);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=liveCD\">"._LIVECD."</a>";
?>
