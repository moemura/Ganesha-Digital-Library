<?php

/***************************************************************************
                         /module/publisher/index.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (eregi("index.php",$_SERVER['PHP_SELF'])) {
    die();
}

$_SESSION['DINAMIC_TITLE'] = _PUBLISHERMANAGEMENT;
$searchkey = $_POST['searchkey'];
if (!$searchkey)
	$searchkey = $_GET['searchkey'];
require_once("./module/publisher/function.php");
$main = "<p>".search_publisher_form ()."</p>\n";

$main .= display_publisher($searchkey);
$main = gdl_content_box($main,_PUBLISHERMANAGEMENT);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=publisher\">"._PUBLISHER."</a>";

?>
