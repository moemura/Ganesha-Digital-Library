<?php

/***************************************************************************
                         /module/cdsisis/index.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
 
if (eregi("index.php",$_SERVER['PHP_SELF'])) {
    die();
}

$_SESSION['DINAMIC_TITLE'] = _CDSISIS;
require_once("./module/cdsisis/function.php");

$main .= list_cdsisis();
$main = gdl_content_box($main,_CDSISIS);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis\">"._CDSISIS."</a>";

?>
