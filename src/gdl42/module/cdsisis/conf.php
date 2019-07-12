<?
/***************************************************************************
                         /module/cdsisis/conf.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
if (eregi("conf.php",$_SERVER['PHP_SELF'])) {
    die();
}

include "./config/system.php";

if ($gdl_sys['index_cdsisis']){
	$gdl_modul['name'] = _CDSISIS;
$gdl_menu['new'] = _NEW;
$gdl_menu['union'] = _BUILDUNIONINDEX;
$gdl_menu['final'] = _BUILDFINALUNIONINDEX;
}


?>