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
 
 if (preg_match("/requestFormatter_DC.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class requestFormatter_DC extends requestFormatter {

	function __construct(){
		$this->init();
	}
	
	function request_to_hub($verb,$id_repository=""){
		global $HTTP_SESSION_VARS;

		$lastToken				= $_SESSION['LastToken'];
		//echo "LAST $lastToken <br/>\n";
		if($verb != "GetRecord")
			$_SESSION['LastToken']	= 0;
		
		$hub_server				= $this->rf_sync['sync_hub_server_name'];
		$script_oai				= $this->rf_sync['sync_oai_script'];
		$this->verb				= $verb;
		
		$this->metadataPrefix = "oai_dc";
		
		// start request
		switch($verb){

			case "ListRecords"			:	$HEADER	= $this->requestFormatter_ListRecords($hub_server,$script_oai,$lastToken);
											break;
										
			case "GetRecord"			:	$HEADER	= $this->requestFormatter_GetRecord($hub_server,$script_oai,$id_repository);
											break;
										//
			case "ListIdentifiers" 		:	$HEADER	= $this->requestFormatter_ListIdentifiers($hub_server,$script_oai,$lastToken);
											break;
										//
			case "Identify"				:	$HEADER	= $this->requestFormatter_Identify($id_repository);
											break;
										
			case "ListSets"				:	$HEADER	= $this->requestFormatter_ListSets($id_repository);
											break;
										
			case "ListMetadataFormats"	:	$HEADER	= $this->requestFormatter_ListMetadataFormats($hub_server,$script_oai,$id_repository);
											break;
										
			default						:	$HEADER	=	"error";
											break;
		}

		$HTTP_SESSION_VARS[sess_val_request_xml]	= $REQUEST;
		//echo "Header_DC : [$HEADER]<br/>\n";
		return $HEADER;
	}
	
	
	function requestFormatter_Identify($identify){
		
		$dbres		= $this->rf_db->select("repository","host_url,oai_script","nomor = $identify");
		
		if(@mysqli_num_rows($dbres) == 1){
				$row 	= mysqli_fetch_row($dbres);
				$hub 	= trim($row[0]);
				$script	= trim($row[1]);
				if(!empty($hub) && !empty($script)){
					$URI = "http://$hub/$script?verb=Identify";
					$HEADER = "GET $URI HTTP/1.0\r\n\r\n";
				}
		}
		
		if(empty($HEADER))
			$HEADER	=	"error";
		
		return $HEADER;
	}
	
	function requestFormatter_ListRecords($hub_server,$script_oai,$lastToken){
		
		$array = array("verb","from","until","metadataPrefix","set");
		if((int)$lastToken != 0)
			array_push($array,"resumptionToken");

		$this->init_request_argument($array);
		
		$REQUEST = $this->build_request($array);
		$URI = "http://$hub_server/$script_oai?$REQUEST";										
		$HEADER = "GET $URI HTTP/1.0\r\n\r\n";
		
		return $HEADER;
	}
	
	function requestFormatter_GetRecord($hub_server,$script_oai,$identifier){
		
		$REQUEST = "verb=GetRecord&identifier=$identifier&metadataPrefix=oai_dc";
		
		$URI = "http://$hub_server/$script_oai?$REQUEST";										
		$HEADER = "GET $URI HTTP/1.0\r\n\r\n";
		
		return $HEADER;
	}
	
	function requestFormatter_ListIdentifiers($hub_server,$script_oai,$lastToken){
		
		$array = array("verb","from","until","metadataPrefix","set");
		
		$lastToken = "$lastToken";
		//echo "PREFIX : ".$this->metadataPrefix." :: $lastToken<br/>\n";
		if($lastToken != "0"){
			if($this->metadataPrefix == "oai_dc")
				$array = array("verb","resumptionToken");
			else
				array_push($array,"resumptionToken");
		}
		
		$this->init_request_argument($array);
		
		$REQUEST = $this-> build_request($array);
		$URI = "http://$hub_server/$script_oai?$REQUEST";
		$HEADER = "GET $URI HTTP/1.0\r\n\r\n";
				//  "Accept-Encoding: deflate\r\n\r\n";
		
		return $HEADER;
	}
	
	function requestFormatter_ListSets($identify){
		
		$dbres		= $this->rf_db->select("repository","host_url,oai_script","nomor=$identify");
		
		if(@mysqli_num_rows($dbres) == 1){
				$row 	= @mysqli_fetch_row($dbres);
				$hub 	= trim($row[0]);
				$script	= trim($row[1]);
				if(!empty($hub) && !empty($script)){
					$URI = "http://$hub/$script?verb=ListSets";
					$HEADER = "GET $URI HTTP/1.0\r\n\r\n";
				}
		}	
		if(empty($HEADER))
			$HEADER	=	"error";
		
		return $HEADER;
	}
	
	function requestFormatter_ListMetadataFormats($hub_server,$script_oai,$identify){
		
		if(!empty($identify))
			$buffer		= "&identifier=$identify";
		
		$REQUEST	= "verb=ListMetadataFormats$buffer";
		$URI 		= "http://$hub_server/$script_oai?$REQUEST";
		$HEADER 	= "GET $URI HTTP/1.0\r\n\r\n";
		
		return $HEADER;
	}
	
}
?>