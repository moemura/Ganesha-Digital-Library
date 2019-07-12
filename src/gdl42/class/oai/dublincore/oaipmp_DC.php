<?php

/***************************************************************************
    last modified		: Jan 15, 2007
    copyleft          	: (L) 2006 KMRG ITB
    email               : mymails_supra@yahoo.co.uk (GDL 4.2 , design,programmer)
						  benirio@kmrg.itb.ac.id (GDL 4.2, reviewer)
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
 
 if (eregi("oaipmp_DC.php",$_SERVER['PHP_SELF'])) {
    die();
}

class oaipmp_DC extends  oai {
	
	var $dc_responseAction;
	
	function oaipmp_DC(){
		$this->init();
		$this->init_DC();	
	}
	
	function init_DC(){
		$this->dc_responseAction	= new responseAction_DC();
	}
	
	function response_dublincore($verb){
		$this->verb	= $verb;
		switch ($verb){
			case "Identify"				: $result 		= $this->response_Identify();
							  	  		  break;
							  	  		  
			case "ListMetadataFormats"	: $identifier	= $this->oai_requestQuery['identifier'];
										  $result 		= $this->response_ListMetadataFormats($identifier);
										  break;
										  
			case "GetRecord"			: $identifier	= $this->oai_requestQuery['identifier'];
										  $result 		= $this->response_GetRecord($identifier);
										  break;
										  
			case "ListSets"				: $result		= $this->response_ListSets();
										  break;
										  
			case "ListIdentifiers"		: $result		= $this->response_ListIdentifiers($this->oai_requestQuery);
										  break;
			
			case "ListRecords"			: $result		= $this->response_ListRecords($this->oai_requestQuery);
										  break;
		}
		
		return $result;
	}
			
	function response_Identify(){
		$element 	= $this->dc_responseAction->responseIdentify();
		return $this->format_response($element);
	}
	
	function response_ListMetadataFormats($identifier){
		$element	= $this->dc_responseAction->responseListMetadataFormats($identifier);
		return $this->format_response($element);
	}
	
	function response_GetRecord($identifier){
		$element	= $this->dc_responseAction->responseGetRecord($identifier);
		return $this->format_response($element);
	}
	
	function response_ListSets(){
		$element	= $this->dc_responseAction->responseListSets();
		return $this->format_response($element);
	}
	
	function response_ListIdentifiers($request_query){
		$element	= $this->dc_responseAction->responseListIdentifiers($request_query,"oai_dc");
		return $this->format_response($element);
	}
	
	function response_ListRecords($request_query){
		$element	= $this->dc_responseAction->responseListRecords($request_query);
		return $this->format_response($element);
	}
	
}
?>