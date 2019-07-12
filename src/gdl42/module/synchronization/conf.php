<?php
/***************************************************************************
                          conf.php  -  configuration file for home module
                             -------------------
    begin                : Oct 4, 2006
    copyright            : (C) 2006 Beni Rio Hermanto, KMRG ITB
    email                : benirio@kmrg.itb.ac.id

 ***************************************************************************/
if (eregi("conf.php",$_SERVER['PHP_SELF'])) {
    die();
}

$gdl_modul['name']		= _SYNCHRONIZATION;
$gdl_menu['export']		= _EXPORT." Metadata";
$gdl_menu['import']		= _IMPORT." Metadata";
$gdl_menu['download'] 	= _DOWNLOADMETADATA;
$gdl_menu['option']		= _CONFIGURATION;
$gdl_menu['connect']	= _CONNECTION;
$gdl_menu['disconnect']	= _DISCONNECTION;
$gdl_menu['harvest']	= _HARVESTING;
$gdl_menu['posting']	= _POSTING;
?>