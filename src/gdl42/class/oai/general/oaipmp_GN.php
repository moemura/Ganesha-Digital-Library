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
 
 if (eregi("oaipmp_GN.php",$_SERVER['PHP_SELF'])) {
    die();
}

class oaipmp_GN extends oai {
	var $gn_responseAction;
	
	function oaipmp_GN(){
		$this->init();
		$this->init_GN();	
	}
	
	function init_GN(){
		$this->gn_responseAction	= new responseAction_GN();
	}
	
	function response_general($verb){
		$this->verb	= $verb;
		switch ($verb){
			
			/* OAI standard */
			case "Identify"				: $result 		= $this->response_Identify();
							  	  		  break;
							  	  		  
			case "ListMetadataFormats"	: $identifier	= $this->oai_requestQuery['identifier'];
										  $result 		= $this->response_ListMetadataFormats($identifier);
										  break;
										  
			case "ListSets"				: $result		= $this->response_ListSets();
										  break;
										  
			case "ListIdentifiers"		: $result		= $this->response_ListIdentifiers($this->oai_requestQuery);
										  break;
			
			case "GetRecord"			: $identifier	= $this->oai_requestQuery['identifier'];
										  $result 		= $this->response_GetRecord($identifier);
										  break;
				  
			case "ListRecords"			: $result		= $this->response_ListRecords($this->oai_requestQuery);
										  break;

			
			/*Additional verb*/
			case "Connect"				: $result		= $this->response_Connect($this->oai_requestQuery);
										  break;
			
			case "ListProviders"		: $result		= $this->response_ListProviders($this->oai_requestQuery);	
										  break;
										  
			case "PutListRecords"		: $result		= $this->response_PutListRecords($this->oai_requestQuery);
										  break;
										  
			case "RemoteLogin"			: $result		= $this->response_RemoteLogin($this->oai_requestQuery);
										  break;
										  
			case "PutFileFragment"		:	$result		= $this->response_PutFileFragment($this->oai_requestQuery);
											break;
											
			case "MergeFileFragments"	:	$result		= $this->response_MergeFileFragments($this->oai_requestQuery);
											break;
		}
		
		return $result;
	}
	
	function response_Identify(){
		$element 	= $this->gn_responseAction->responseIdentify();
		return $this->format_response($element);
	}
	
	function response_ListMetadataFormats($identifier){
		$element	= $this->gn_responseAction->responseListMetadataFormats($identifier);
		return $this->format_response($element);
	}
	
	function response_GetRecord($identifier){
		$element	= $this->gn_responseAction->responseGetRecord($identifier);
		return $this->format_response($element);
	}
	
	function response_ListSets(){
		$element	= $this->gn_responseAction->responseListSets();
		return $this->format_response($element);
	}
	
	function response_ListIdentifiers($request_query){
		$element	= $this->gn_responseAction->responseListIdentifiers($request_query);
		return $this->format_response($element);
	}
	
	function response_ListRecords($request_query){
		$element	= $this->gn_responseAction->responseListRecords($request_query);
		return $this->format_response($element);
	}
	
	
	function response_Connect($request_query){
		$element	= $this->gn_responseAction->responseConnect($request_query);
		return $this->format_response($element);
	}
	
	function response_ListProviders($request_query){
		$element	= $this->gn_responseAction->responseListProviders($request_query);
		return $this->format_response($element);
	}
	
	function response_PutListRecords($request_query){
		$element	= $this->gn_responseAction->responsePutListRecords($request_query);
		return $this->format_response($element);
	}
	
	function response_PutFileFragment($request_query){
		$element	= $this->gn_responseAction->responsePutFileFragment($request_query);
		return $this->format_response($element);
	}
	
	function response_MergeFileFragments($request_query){
		$element	= $this->gn_responseAction->responseMergeFileFragments($request_query);
		return $this->format_response($element);
	}
	
	function response_RemoteLogin($request_query){
		$element	= $this->gn_responseAction->responseRemoteLogin($request_query);
		return $this->format_response($element);
	}
}
?>