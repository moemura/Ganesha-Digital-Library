<?php

/***************************************************************************
    copyright            : (C) 2007 Arif Suprabowo, KMRG ITB
    email                : mymails_supra@yahoo.co.uk
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/

if (preg_match("/folder.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/liveCD/function.php");

$_SESSION['DINAMIC_TITLE'] = _LIVECD." | "._FOLDERCHOICE;

$url	= "./gdl.php?mod=liveCD&amp;op=folder";

$check	= checkSupportedTheme($url);

$main = '';
if(!$check['status']){
	$main	= $check['message'];
}else{
	$main	.= "<br/><br/>".box_folder($url);
	$url	= "./gdl.php?mod=liveCD&amp;op=job";
	$main	.= "<br/><br/>".box_job($url);
}

$main 	= gdl_content_box($main,_FOLDERCHOICE);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=liveCD\">"._LIVECD."</a>";
?>
