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
 
 if (preg_match("/config_oai.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

 
include_once("global/config_interface.php");
include_once("dublincore/config_DC.php");
include_once("general/config_GN.php");
include_once("harvest.php");
include_once("oaiFactory.php");

global $gdl_sync,$gdl_sys;

$gdl_harvest	= new harvest();

$support_dublincore	= (int)$gdl_sys["support_oai_dc"];
$verb 				= $_GET['verb'];
$php_session		= $_GET['PHPSESSID'];

if($support_dublincore == 0){
	// general service
	$option_service	= 0;
}else{
	if($verb == "Connect"){
		// general service
		$option_service	= 0;	
	}else if(isset($php_session)){
		// general service
		$option_service	= 0;
	}else{
		// dublincore service
		$option_service	= 1;
	}
}

$gdl_harvest->init("$option_service");
$result = $gdl_harvest->response_verb($verb);


echo $result;

?>