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
 if (eregi("responseAction.php",$_SERVER['PHP_SELF'])) {
    die();
}

class responseAction{
	var $ra_elementResponse;
	var $ra_metadata;
	
	function responseAction(){
		
	}
	
	function init($option){
		global $gdl_metadata;
		
		switch($option){
			case "oai_dc"	: $this->ra_elementResponse	= new elementResponse_DC();
							  break;
			case "general"	: $this->ra_elementResponse	= new elementResponse_GN();
							  break;
		}
	}
	
	function responseIdentify(){
		return $this->ra_elementResponse->elementIdentify();
	}
	
	function responseListMetadataFormats($identifier){
		return $this->ra_elementResponse->elementListMetadataFormats($identifier);
	}
	
	function responseListSets(){
		return $this->ra_elementResponse->elementListSets();
	}
	
	function responseListIdentifiers($request_query,$metadataPrefix=""){
		return $this->ra_elementResponse->elementListIdentifiers($request_query,$metadataPrefix);
	}

}

?>