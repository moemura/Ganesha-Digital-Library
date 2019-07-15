<?php
/***************************************************************************
                         /module/publisher/detail.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/detail.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$PUBLISHER_ID = str_replace('%20',' ',$_GET['id']);
$PUBLISHER_ID = str_replace('\\','',$PUBLISHER_ID);
$PUBLISHER_ID = str_replace('\'','',$PUBLISHER_ID);

require_once("./module/publisher/function.php");
$main = display_property($PUBLISHER_ID);
$main = gdl_content_box($main,_PUBLISHERINFORMATION);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=publisher\">"._PUBLISHER."</a>";

?>
