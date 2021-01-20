<?php

/***************************************************************************
                         /module/request/delete.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
if (preg_match("/delete.php/i",$_SERVER['PHP_SELF'])) die();

$id = isset($_GET["id"]) ? $_GET["id"] : null;

$main = '';
if (isset($id)){
	require_once("./module/request/function.php");

	$main .= delete_request($id);
	$main .= display_request();
	$main = gdl_content_box($main,_USERREQUEST);
}

$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=request\">"._USERREQUEST."</a>";
?>