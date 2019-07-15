<?php
/***************************************************************************
                          conf.php  -  configuration file for home module
                             -------------------
    begin                : Oct 16, 2006
    copyright         (C) 2006 Lastiko Wibisono, KMRG ITB
    email                : if13051@students.if.itb.ac.id

 ***************************************************************************/
if (preg_match("/conf.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$gdl_modul['name'] = _CONFIGURATION;
$gdl_menu['server'] = "Server";
$gdl_menu['system'] = _SYSTEM;
?>