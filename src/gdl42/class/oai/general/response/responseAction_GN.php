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
 
 if (preg_match("/responseAction_GN.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class responseAction_GN extends responseAction{
	
	function __construct(){
		$this->init("general");
	}
	
	function responseGetRecord($identifier){
		return $this->ra_elementResponse->elementGetRecord($identifier);
	}
		
	function responseListRecords($request_query){
		return $this->ra_elementResponse->elementListRecords($request_query);
	}

	function responseConnect($request_query){
		return $this->ra_elementResponse->elementConnect($request_query);
	}
	
	function responseListProviders($request_query){
		return $this->ra_elementResponse->elementListProviders($request_query);
	}
	
	function responsePutListRecords($request_query){
		return $this->ra_elementResponse->elementPutListRecords($request_query);
	}
	
	function responsePutFileFragment($request_query){
		return $this->ra_elementResponse->elementPutFileFragment($request_query);
	}
	
	function responseMergeFileFragments($request_query){
		return $this->ra_elementResponse->elementMergeFileFragments($request_query);
	}
	
	function responseRemoteLogin($request_query){
		return $this->ra_elementResponse->elementRemoteLogin($request_query);
	}
}
?>