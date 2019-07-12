<?php
/***************************************************************************
    last modified		: Jan 15, 2007
    copyleft          		: (L) 2006 KMRG ITB
    email                	: mymails_supra@yahoo.co.uk (GDL 4.2 , design,programmer)
								  benirio@itb.ac.id (GDL 4.2, reviewer)

 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
 if (eregi("global_include.php",$_SERVER['PHP_SELF'])) {
    die();
}

include_once("global/config_interface.php");
include_once("dublincore/config_DC.php");
include_once("general/config_GN.php");
include_once("harvest.php");
include_once("oaiFactory.php");

global $gdl_sync;

$gdl_harvest	= new harvest();
$opt_script		= getScriptType($gdl_sync['sync_opt_script']);
$gdl_harvest->init("$opt_script");

function getScriptType($option){
	$opt		= (int)$option;
	$val_opt	= (ereg("^[0-9]+$",$opt))?$opt:0;
	
	return ($val_opt < 2)?$val_opt:0;
}
?>