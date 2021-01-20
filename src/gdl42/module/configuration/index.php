<?php

/***************************************************************************
                         /module/configuration/index.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/server.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$_SESSION['DINAMIC_TITLE'] = _CONFIGURATION;
require_once("./module/configuration/function.php");
$main = _CONFIGURATIONMAIN;
$main = gdl_content_box($main,_CONFIGURATION);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=configuration\">"._CONFIGURATION."</a>";
?>