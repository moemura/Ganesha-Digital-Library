<?php

/***************************************************************************
                         /module/install/conf.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/conf.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

if (!file_exists("./files/misc/install.lck"))
	$gdl_modul['name'] = _INSTALLATION;
//$gdl_menu['$gdl_op'] = _NAME;

?>