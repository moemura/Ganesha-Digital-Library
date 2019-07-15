<?php

/***************************************************************************
                         /module/request/index.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$_SESSION['DINAMIC_TITLE'] = _USERREQUEST;
$searchkey = $_POST['searchkey'];
if (!$searchkey)
	$searchkey=$_GET['searchkey'];
require_once("./module/request/function.php");
$main = "<p>".search_request_form ()."</p>\n";

$main .= display_request($searchkey);
$main = gdl_content_box($main,_USERREQUEST);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=request\">"._USERREQUEST."</a>";

?>
