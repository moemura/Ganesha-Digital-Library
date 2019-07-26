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
 
 if (preg_match("/requestAction_DC.php/i",$_SERVER['PHP_SELF'])) {
    die();
}


class requestAction_DC extends requestAction {
	
	function __construct(){
		$this->init("oai_dc");
	}

	function requestListRecords(){
		
		$this->ra_verb		= "ListRecords";
		$response 			= $this->get_response_from_hub();
		$response_data		= $response['response_hub'];
		
		if(preg_match("/TIMEOUT/i",$response_data)){
			$result = $this->error_handle($response_data);
		}else{
			if (!empty($response_data[xmldata])){
				$result 			= $this->ra_import->extract_record($response_data[xmldata],"inbox","oai_dc");
			} else {
				$result = $this->error_handle($response_data);
			}
			
			// response
			$result['response'] = $response_data['all'];
		}
		return $result;
	}

	function requestGetRecord($id){
		
		$this->ra_verb		= "GetRecord";
		$response 			= $this->get_response_from_hub($id);
		$response_data		= $response['response_hub'];
		
		if(preg_match("/TIMEOUT/i",$response_data)){
			$result['error']	= $response_data;
		}else{
			if (!empty($response_data[xmldata])){
				$result 			= $this->ra_import->extract_record($response_data[xmldata],"inbox","oai_dc");
			} else {
				$result['error']	= $response_data['error'];
			}
			
			// response
			$result['response'] = $response_data['all'];
		}
		
		return $result;
	}

}
?>