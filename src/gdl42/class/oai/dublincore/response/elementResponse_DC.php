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
 
 if (preg_match("/elementResponse_DC.php/i",$_SERVER['PHP_SELF'])) {
    die();
}


class elementResponse_DC extends elementResponse{
	
	function elementResponse_DC(){
		$this->init();
	}

	function elementGetRecord($identifier){
		
		$dbres 		= $this->er_db->select("metadata","STATUS, IDENTIFIER, DATE_MODIFIED as DATEMODIFIED, TYPE, XML_DATA","identifier LIKE '$identifier'");
		$row		= @mysql_fetch_array($dbres);

		$header   	= $this->element_header($row['STATUS'],$row['IDENTIFIER'],$row['DATEMODIFIED'],$row['TYPE']);
		$dataXML	= $this->er_metadata->readXML($row['XML_DATA']);
		
		
		$metadata	= $this->er_metadata->convert_metadata_general_to_oai_dc($dataXML);

		$record 	= "<record>\n $header $metadata </record>\n";
		$element	= "<GetRecord>\n$record</GetRecord>\n";

		return $element;
	}

	function elementListRecords($request_query){
		$set				= $request_query['set'];		
		$resumptionToken	= $request_query['resumptionToken'];
		$from				= $request_query['from'];
		$until				= $request_query['until'];
		
		if (ereg("under:node:",$set)){
			
			$node 		= substr($set,11);
			$path 		= $this->node_path($node);

			$element 	= $this->get_records($path,$resumptionToken,$from,$until,$request_query);
			
		} else {

			$path 		= $this->node_path(0);
			$element 	= $this->get_records($path,$resumptionToken,$from,$until,$request_query);

		}
		return $element;
	}

	function get_records($path,$token,$from="",$until="",$request_query=""){
		
		$id_repository	= $this->er_publisher['id'];
		$limit			= $this->er_sys["sync_count_records"];

		$limit 	= (empty($limit))?20:$limit;
		$token	= (empty($token))?0:$token;
		//$limit=1;
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
		$dbres		= $this->get_resultSetRecordFromDatabase($cursor,$limit,$selective);

		if ($dbres){
			if (@mysql_num_rows($dbres)>0){
				while($row = @mysql_fetch_array($dbres)){ 
					$header   = $this->element_header($row[STATUS],$row[IDENTIFIER],$row[DATEMODIFIED],$row[TYPE]);
					
					if ($row[STATUS] != "deleted"){
						if($row[PREFIX] == "general"){
							$dataXML	= $this->er_metadata->readXML($row[XMLDATA]);
							$metadata 	= $this->er_metadata->convert_metadata_general_to_oai_dc($dataXML);
						}else{
							$metadata = $this->element_metadata($row[XMLDATA]);
						}
					}

					$record = "\n<record> $header $metadata </record>\n";
					$records .= $record;
				}

				// get resumption token
				$total 				= $this->get_total_identifiers($selective);
				$next_cursor		= $this->get_nextCursor($token,$limit,$total);
				$dbres				= $this->get_resultSetRecordFromDatabase($next_cursor,$limit,$selective);
				$c_next_record		= @mysql_num_rows($dbres);
				$resumption_token 	= $this->element_resumptiontoken($token, $limit,$total,"oai_dc",$c_next_record,$request_query);

				$element = "\n<ListRecords> $records $resumption_token </ListRecords>\n";

			} else {
				$element = $this->error_element("noRecordsMatch","Record not found");
			}
		} else {
			$element = $this->error_element("dbError",mysql_error());
		}
		
		return $element;
	}
	
	function get_resultSetRecordFromDatabase($cursor,$limit,$selective){

		//$this->er_db->print_script = true;
		$dbres	= $this->er_db->select("metadata ",
								  "IDENTIFIER, 
										DATE_MODIFIED as DATEMODIFIED, 
										TYPE, 
										STATUS, 
										XML_DATA as XMLDATA,
										PREFIX",$selective,
									"DATE_MODIFIED",
									"DESC",
									"$cursor,$limit");

		return $dbres;
	}
	
	// element metadata
	function element_metadata($xmldata){
		return "\n<metadata>$xmldata</metadata>\n";     
	}
}
?>