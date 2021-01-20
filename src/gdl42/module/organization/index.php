<?php

/***************************************************************************
                         /module/organization/index.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$_SESSION['DINAMIC_TITLE'] = _ORGANIZATION;
require_once("./module/organization/function.php");
$organization_node=$gdl_folder->check_folder("Organization",0);
$main = '';
if (!preg_match("/err/",$organization_node)) {
	$main.=list_of_organization();
} else {
	$main.=organization_not_exist();
}

$main = gdl_content_box($main,_ORGANIZATION);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=organization\">"._ORGANIZATION."</a>";

?>
