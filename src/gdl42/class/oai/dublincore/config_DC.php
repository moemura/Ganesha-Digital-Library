<?php

/***************************************************************************
    last modified		: Jan 15, 2007
    copyleft          		: (L) 2006 KMRG ITB
    email                	: mymails_supra@yahoo.co.uk (GDL 4.2 , design,programmer)
								  benirio@itb.ac.id (GDL 4.2, reviewer)
								  ismail@itb.ac.id (GDL 4.0 , design,programmer)
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
 if (preg_match("/config_DC.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

include_once("oaipmh_DC.php");
include_once("oaipmp_DC.php");
include_once("validation_DC.php");
include_once("request/requestAction_DC.php");
include_once("request/requestFormatter_DC.php");
include_once("response/responseAction_DC.php");
include_once("response/elementResponse_DC.php");
?>