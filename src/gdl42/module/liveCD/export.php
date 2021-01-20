<?php

/***************************************************************************
    copyright            : (C) 2007 Arif Suprabowo, KMRG ITB
    email                : mymails_supra@yahoo.co.uk
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/

if (preg_match("/export.php/i",$_SERVER['PHP_SELF'])) {
	die();
}

require_once("./module/liveCD/function.php");

$_SESSION['DINAMIC_TITLE'] = _LIVECD." | "._EXPORTFILE;

$url	= "./gdl.php?mod=liveCD&amp;op=export";
$main	= "<br/><br/>".list_of_uploaded_file($url);

$main 	= gdl_content_box($main,_EXPORTFILE);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=liveCD\">"._LIVECD."</a>";
?>