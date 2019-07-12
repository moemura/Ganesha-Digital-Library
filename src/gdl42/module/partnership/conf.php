<?php
/***************************************************************************
                          conf.php  -  configuration file for home module
                             -------------------
    begin                : Oct 16, 2006
    copyright         (C) 2006 Arif Suprabowo, KMRG ITB
    email                : mymails_supra@yahoo.co.uk

 ***************************************************************************/
if (eregi("conf.php",$_SERVER['PHP_SELF'])) {
    die();
}

if ($gdl_sys['remote_login'])
	$gdl_modul['name'] = ""._PARTNERSHIP;

?>