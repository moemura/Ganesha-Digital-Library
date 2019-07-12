<?php

/***************************************************************************
                           /module/accesslog/index.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/

if (eregi("index.php",$_SERVER['PHP_SELF'])) {
    die();
}

$_SESSION['DINAMIC_TITLE'] = _ACCESSLOG;
$submit=$_POST["submit"];
$frm=$_POST["frm"];

require_once("./module/accesslog/function.php");
if (!empty($submit)) {
	if(write_configuration())
		$main.=_ACCESSLOGSUCCESS;
	else
		$main.=_ACCESSLOGFAILED;
}

$main .= "<p>"._ACCESSHEADER."</p>";
$main .= "<p>".display_configuration()."</p>";
$main = gdl_content_box($main,_ACCESSLOG);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=accesslog\">"._ACCESSLOG."</a>";

?>
