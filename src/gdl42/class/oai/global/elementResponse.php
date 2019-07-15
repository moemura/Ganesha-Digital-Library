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
 
 if (preg_match("/elementResponse.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class elementResponse{
	
	var $er_publisher;
	var $er_db;
	var $er_sys;
	var $er_sync;
	var $er_metadata;
	var $er_session;
	var $er_import;
	
	function elementResponse(){
		
	}
	
	function init(){
		global $gdl_publisher,$gdl_db,$gdl_sys,$gdl_sync,$gdl_metadata,$gdl_session,$gdl_import;
				
		$this->er_publisher		= $gdl_publisher;
		$this->er_db			= $gdl_db;
		$this->er_sys			= $gdl_sys;
		$this->er_sync			= $gdl_sync;
		$this->er_metadata		= $gdl_metadata;
		$this->er_session		= $gdl_session;
		$this->er_import		= $gdl_import;
	}
	
	// Get node info
	function node_path($node){
		
		$dbres	= $this->er_db->select("folder","PATH","folder_id = '$node'");
		
		if ($dbres){
			if (@mysql_num_rows($dbres)>0) {
				$path = @mysql_result($dbres,0,"PATH")."$node/";
				return $path;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	function get_total_identifiers($selective_query){

		$dbres	= $this->er_db->select("metadata ","COUNT(IDENTIFIER) as TOTAL","$selective_query");
									
		if ($dbres){
			if (@mysql_num_rows($dbres)>0) $total = @mysql_result($dbres,0,"TOTAL");
			return $total;
		} else {
			return 0;
		}
	}
	
	function build_element_metadataFormat($prefix,$schema,$namespace){
			$xml = "<metadataFormat>\n";
			if(empty($prefix))
				$xml.= "<metadataPrefix/>\n";
			else
				$xml.= "<metadataPrefix>$prefix</metadataPrefix>\n";
			if(empty($schema))
				$xml.= "<schema/>\n";
			else
				$xml.= "<schema>$schema</schema>\n";
			if(empty($schema))
				$xml.= "<metadataNamespace/>\n";
			else
				$xml.= "<metadataNamespace>$namespace</metadataNamespace>\n";
			
			$xml.= "</metadataFormat>\n";
			
			return $xml;
	}
	
	function elementListSets_description($desc){
		$xml.="<setDescription>\n";
	      $xml.="<oai_dc:dc ".
	      			"xmlns:oai_dc=\"http://www.openarchives.org/OAI/2.0/oai_dc/\" ".
	      			"xmlns:dc=\"http://purl.org/dc/elements/1.1/\" ".
	      			"xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" ".
	      			"xsi:schemaLocation=\"http://www.openarchives.org/OAI/2.0/oai_dc/ ".
	      			"http://www.openarchives.org/OAI/2.0/oai_dc.xsd\">\n";
	          $xml.="<dc:description>$desc</dc:description>\n";
	       $xml.="</oai_dc:dc>\n";
	    $xml.="</setDescription>\n";

		return $xml;
	}

		// get identifiers from database
	function get_identifiers($path,$token,$from="",$until="",$metadataPrefix="",$request_query=""){
		
		$id_repository	= $this->er_publisher['id'];
		$limit			= $this->er_sys["sync_count_records"];

		$limit 	= (empty($limit))?20:$limit;
		$token	= (empty($token))?0:$token;
				
		$cursor = $token * $limit;
		
		$filter_identifier	= (empty($id_repository))?"":"AND IDENTIFIER LIKE '$id_repository-%'";
		$filter_identifier = ($this->er_sys["role"] == "HUB")?"":$filter_identifier;
		
		if(!empty($from)){
			$date = substr($from,0,10);
			$time = substr($from,11,8);
			$from_rec = "DATE_MODIFIED >= '$date $time'";
		}
		
		if(!empty($until)){
			$date = substr($until,0,10);
			$time = substr($until,11,8);
			$until_rec = "DATE_MODIFIED <= '$date $time'";
		}
		
		if(!empty($from_rec) && !empty($until_rec))
			$sorting = " AND ".$from_rec." AND ".$until_rec;
		else if(!empty($from_rec))
			$sorting = " AND ".$from_rec;
		else if(!empty($until_rec))
			$sorting = " AND ".$until_rec;

		$selective	= "PATH LIKE '$path%' $sorting $filter_identifier";
		//echo "$selective";
		$dbres		= $this->get_resultSetIdentifiersFromDatabase($cursor,$limit,$selective);
   
		if ($dbres){
			if (@mysql_num_rows($dbres)>0){
				while($row = @mysql_fetch_array($dbres))
					$header .= $this->element_header($row[STATUS],$row[IDENTIFIER],$row[DATEMODIFIED],$row[TYPE]);

				// get resumption token
				$total 				= $this->get_total_identifiers($selective);
				if($metadataPrefix == "oai_dc"){
					$next_cursor		= $this->get_nextCursor($token,$limit,$total);
					$dbres				= $this->get_resultSetRecordFromDatabase($next_cursor,$limit,$selective);
					$c_next_record		= @mysql_num_rows($dbres);
					$resumption_token 	= $this->element_resumptiontoken($token, $limit,$total,"oai_dc",$c_next_record,$request_query);
				}else
					$resumption_token 	= $this->element_resumptiontoken($token,$limit,$total);
				
				$element = "<ListIdentifiers> $header $resumption_token </ListIdentifiers>\n";
	  
			} else {
				$element = $this->error_element("noRecordsMatch","Record not found");
			}
		} else {
			$element = $this->error_element("dbError",mysql_error());
		}
		return $element;
	}
	
	
	function get_resultSetIdentifiersFromDatabase($cursor,$limit,$selective){
		//$this->er_db->print_script = true;
		$dbres		= $this->er_db->select("metadata",
								  "distinct IDENTIFIER, 
										DATE_MODIFIED as DATEMODIFIED, 
										TYPE, 
										STATUS",
								   		$selective,
								   "DATE_MODIFIED",
								   "DESC",
								   "$cursor,$limit");

		return $dbres;
	}
	
	function element_header($status,$identifier,$datestamp,$setSpec){

		if ($status == "deleted") 
			$element_status = "\n<status>deleted</status>\n";
	
		$header = "\n<header>$element_status\n".
						"<identifier>$identifier</identifier>\n".
						"<datestamp>$datestamp</datestamp>\n".
						"<setSpec>$setSpec</setSpec>\n".
				 "</header>\n";

		return $header;
	}
	
	function error_element($error_code,$error_message){
		return "<error code=\"$error_code\">$error_message</error>\n";
	}
	
	function element_resumptiontoken($token,$limit,$total,$metadataPrefix="",$num_next_record="",$request_query=""){

		$cursor2	= $this->get_nextCursor($token-1,$limit,$total);
		$token2 	= $token + 1;
		
		if ($cursor2 <= $total){
			if($metadataPrefix == "oai_dc"){
				$num_next_record	= empty($num_next_record)?0:$num_next_record;
				$resumption	= $this->resumptionToken_metadataPrefix_dublincore($total,$cursor2,$token2,$num_next_record,$request_query);
			}else{
				$resumption	= $this->resumptionToken_metadataPrefix_general($total,$cursor2,$token2);
			}
		}
		
		return $resumption;
	}
	
	function get_nextCursor($token,$limit,$total){
		$cursor			= $token * $limit;
		$nextCursor		= $cursor + $limit;
		
		if ($nextCursor > $total) 
			$nextCursor = $total;
		
		return $nextCursor;
	}
	
	function resumptionToken_metadataPrefix_general($total,$cursor,$nextToken){
			$resumption = "<resumptionToken ".
								"completeListSize=\"$total\" ".
								"cursor=\"$cursor\">$nextToken".
							"</resumptionToken>\n";
			return $resumption;
	}
	
	function resumptionToken_metadataPrefix_dublincore($total,$cursor,$nextToken,$num_next_record,$request_query){
		if($num_next_record > 0){
			$from		= $request_query['from'];
			$until		= $request_query['until'];
			$set		= $request_query['set'];

			$from		= empty($from)?"0":$from;
			$until		= empty($until)?"0":$until;
			$node 		= substr($set,11);
			$node		= empty($node)?"0":$node;
			
			$str_token	= "$from::$until::$node::$nextToken::oai_dc";
			
			$resumption = "<resumptionToken ".
								"completeListSize=\"$total\" ".
								"cursor=\"$cursor\">$str_token".
							"</resumptionToken>\n";
		}else{
			$resumption = "<resumptionToken ".
								"completeListSize=\"$total\" ".
								"cursor=\"$cursor\" />\n";
		}
		
		return $resumption;
	}
	
	function elementIdentify(){
		
		$resstr = $this->er_db->select("metadata","DATE_MODIFIED as DATEMODIFIED","","DATE_MODIFIED","desc","0,1");
		
		$id_repository	= $this->er_publisher['id'];
		$def_script		= $this->er_sys['sync_oai_script'];
		$server			= $this->er_publisher['hostname'];
		
		if($resstr){
			$rowres 	= @mysql_fetch_array ($resstr);	
			$lastdate 	= substr ($rowres['DATEMODIFIED'], 0, 10);
			$lasttime 	= substr ($rowres['DATEMODIFIED'], 11, 8);
			$time_stamp	= $lastdate."T".$lasttime."Z";
		}
		
		$dbres = $this->er_db->select("repository","oai_script","id_publisher like '$id_repository'");
		
		if($dbres){
			$row = @mysql_fetch_row($dbres);
			$script = $row[0];
		}
		
		$script = (empty($script))?$def_script:$script;
		
		$uri = "xmlns=\"http://www.openarchives.org/OAI/2.0/oai-identifier\"\n".
			   "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n".
			   "xsi:schemaLocation=\"http://www.openarchives.org/OAI/2.0/oai-identifier\n".
			   "http://www.openarchives.org/OAI/2.0/oai-identifier.xsd\"";
		
		$publisher	= $this->er_publisher['publisher'];
		$admin		= $this->er_publisher['admin'];
		
		
		$xml = "<repositoryName>$publisher</repositoryName>\n";
		$xml.= "<baseURL>http://$server/$script</baseURL>\n";
		$xml.= "<protocolVersion>2.0</protocolVersion>\n";
		$xml.= "<adminEmail>$admin</adminEmail>\n";
		$xml.= "<earlistDatestamp>$time_stamp</earlistDatestamp>\n";
		$xml.= "<deletedRecord>transient</deletedRecord>\n";
		$xml.= "<granularity>YYYY-MM-DDThh:mm:ssZ</granularity>\n";
		$xml.= "<compression>deflated</compression>\n";
		$xml.= "<description>\n";
			$xml.="<oai-identifier $uri>\n";
				$xml.="<sampleIdentifier>itb-dist-salma-2000-Lilik-moral</sampleIdentifier>\n";
			$xml.="</oai-identifier>\n";
		$xml.= "</description>\n";
		
		return "<Identify>\n$xml</Identify>\n";
	}
	
	function elementListMetadataFormats($identifier=""){

		if(empty($identifier)){
			
			$xml = $this->build_element_metadataFormat("oai_dc",
													 "http://www.openarchives.org/OAI/2.0/oai_dc.xsd",
													 "http://www.openarchives.org/OAI/2.0/oai_dc/");
													 
			$xml.= $this->build_element_metadataFormat("","general","ITB");

			$element = "<ListMetadataFormats>$xml</ListMetadataFormats>";
			
		}else{
			
			$dbres = $this->er_db->select("metadata","prefix","identifier like '$identifier'");
			
			$row = @mysql_fetch_row($dbres);
			
			$xml = ($row[0] == "general")?
						$this->build_element_metadataFormat("","general","ITB"):
						$this->build_element_metadataFormat("oai_dc",
															 "http://www.openarchives.org/OAI/2.0/oai_dc.xsd",
															 "http://www.openarchives.org/OAI/2.0/oai_dc/");
			
			$element = "<ListMetadataFormats>$xml</ListMetadataFormats>";
						
		}
		
		return "$element\n";
	}
	
	function elementListSets(){
		$xml = "<ListSets>\n";
			$xml.= "<set>\n";
				$xml.= "<setSpec>under:node</setSpec>\n";
				$xml.= "<setName>Under node directory</setName>\n";
				$desc= "Harvest all record under id node (null or \"0\" has mean that all record in your target will be harvested)";
				$xml.= $this->elementListSets_description($desc);
			$xml.= "</set>\n";
		$xml.= "</ListSets>\n";
		
		return $xml;
	}
	
	function elementListIdentifiers($request_query,$metadataPrefix=""){
		$set				= $request_query['set'];		
		$resumptionToken	= $request_query['resumptionToken'];
		$from				= $request_query['from'];
		$until				= $request_query['until'];
		
		if (ereg("under:node:",$set)){
			
			$node 		= substr($set,11);
			$path 		= $this->node_path($node);

			$element 	= $this->get_identifiers($path,$resumptionToken,$from,$until,$metadataPrefix,$request_query);
			
		} else {

			$path 		= $this->node_path(0);
			$element 	= $this->get_identifiers($path,$resumptionToken,$from,$until,$metadataPrefix,$request_query);

		}
		return $element;
	}
	

}

?>