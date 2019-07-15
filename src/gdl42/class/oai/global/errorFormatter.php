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
 
 if (preg_match("/errorFormatter.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class errorFormatter{
	var $error_code;
	var $error_msg;
	
	function get_errorMessage(){
		$result				= array();
		
		$result['code']		= $this->error_code;
		$result['message']	= $this->error_msg;
		
		return $result;
	}
	
	function error_formatter($verb,$type,$additional=""){
		switch($type){
			case "argument"	:	$this->error_code	= "badArgument";
								$this->error_msg	= "The request includes illegal arguments or is missing required arguments";
								break;
								
			case "token"	:	$this->error_code	= "badResumptionToken";
								$this->error_format_token($verb);
								break;
								
			case "verb"		:	$this->error_code	= "badVerb";
								$this->error_msg	= "Value of the verb argument is not a legal OAI-PMH verb, the verb argument is missing, or the verb argument is repeated.";
								break;
								
			case "metadata"	:	$this->error_code	= "cannotDisseminateFormat";
								$this->error_format_metadata($verb);
								break;
								
			case "id"		:	$this->error_code	= "idDoesNotExist";
								$this->error_format_id($verb);
								break;
								
			case "record"	:	$this->error_code	= "noRecordsMatch";
								$this->error_format_record($verb);
								break;
								
			case "format"	:	$this->error_code	= "noMetadataFormats";
								$this->error_format_format($verb);
								break;
								
			case "set"		:	$this->error_code	= "noSetHierarchy";
								$this->error_format_set($verb);
								break;
								
			case "date"		:	$this->error_code	= "invalidDate";
								$this->error_msg	= "Value for selective date ($additional) is error format";
								break;
								
			case "session"	:	$this->error_code	= "badSession";
								$this->error_msg	= "Server has lost connection. Please Connect again.";
								break;
			case "exclusive":	$this->error_code	= "badArgument";
								$this->error_msg	= "resumptionToken cannot be combined with other parameters.";
								break;
			default			: 	$this->error_code 	= "unexpectedError";
								$this->error_msg	= "Repository had unexpected error";
		}
		
	}
	
	
	function error_format_verb(){
		$this->error_code	= "badVerb";
		$this->error_msg 	= "illegal OAI verb";
	}
	
	function error_format_token($verb){
		$array = array("ListIdentifiers","ListRecords","ListSets");

		if(in_array($verb,$array))
			$this->error_msg = "The value of the resumptionToken argument is invalid or expired";
		else
			$this->error_msg = "This verb does not support resumptionToken";
	}
	
	function error_format_metadata($verb){
		$array = array("GetRecord","ListIdentifiers","ListRecords");

		if(in_array($verb,$array))
			$this->error_msg	= "The metadata format identified by the value given for the metadataPrefix argument is not supported by the item or by the repository.";
		else
			$this->error_msg 	= "This verb does not support metadataPrefix";
	}
	
	function error_format_id($verb){
		$array = array("GetRecord","ListMetadataFormats");

		if(in_array($verb,$array))
			$this->error_msg	= "The value of the identifier argument is unknown or illegal in this repository.";
		else
			$this->error_msg 	= "This verb does not support identifier";
	}
	
	function error_format_record($verb){
		$array = array("ListIdentifiers","ListRecords");

		if(in_array($verb,$array))
			$this->error_msg	= "The combination of the values of the from, until, set and metadataPrefix arguments results in an empty list.";
		else
			$this->error_msg 	= "This verb return empty record";
	}
	
	function error_format_format($verb){
		$array = array("ListMetadataFormats");

		if(in_array($verb,$array))
			$this->error_msg	= "There are no metadata formats available for the specified item.";
		else
			$this->error_msg 	= "This repository does not support your metadata format request";
	}
	
	function error_format_set($verb){
		$array = array("ListSets","ListIdentifiers","ListRecords");

		if(in_array($verb,$array))
			$this->error_msg	= "The repository does not support sets or your set argument have invalid value.";
		else
			$this->error_msg 	= "This repository does not support sets or your set argument have invalid value.";
	}
	
}

?>