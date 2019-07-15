<?php
/***************************************************************************
    copyright            : (C) 2007 Arif Suprabowo, KMRG ITB
    email                : mymails_supra@yahoo.co.uk
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/

if (preg_match("/conf.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$gdl_modul['name'] 	= ""._LIVECD;
$gdl_menu['folder']	= _FOLDERCHOICE;
$gdl_menu['job']	= _JOBVIEW;
$gdl_menu['export']	= _EXPORTFILE;


?>