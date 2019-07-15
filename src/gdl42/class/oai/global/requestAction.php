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
 
  if (preg_match("/requestAction.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class requestAction{
	var $ra_requestFormatter;
	var $ra_synchronization;
	var $ra_verb;
	var $ra_import;
	var $ra_metadata;
	var $ra_db;
	var $ra_sync;
	
	
	function requestAction(){
	}
	
	function init($option){
		global $gdl_synchronization,$gdl_import,$gdl_metadata,$gdl_db,$gdl_sync;
		
		switch($option){
			case "oai_dc"	: $this->ra_requestFormatter	= new requestFormatter_DC();
							  break;
			case "general"	: $this->ra_requestFormatter	= new requestFormatter_GN();
							  break;
		}
		
		$this->ra_synchronization	= $gdl_synchronization;
		$this->ra_import			= $gdl_import;
		$this->ra_metadata			= $gdl_metadata;
		$this->ra_db				= $gdl_db;
		$this->ra_sync				= $gdl_sync;
	}
	
	function get_response_elements($fp,$verb){
		
		$is_gzip 		= false;
		$is_read_gzip	= false;
		
		while (!feof($fp)){
			$line = fgets($fp,4028);
			$all_line	.= $line;
			//echo "Line-x[$verb] : $line <br/>\n";
				
				if($is_read_gzip)
					$line_gzip	.= $line;
				else{
					$line 	= trim($line);
					$line	= (empty($line))?"":$line."\n";
				}
				// get response
				
				if(!$is_gzip)
					$is_gzip = ereg("Content-Encoding: gzip",$line)?true:false;
				
				if($is_gzip && !$is_read_gzip){
					$is_read_gzip	= (ereg("Proxy-Connection: close",$line))?true:false;
				}
				
				if ($response_start) $response .= $line;
				if (ereg("Content-Type: ",$line)) $response_start = 1;
					
				if(!$is_read_gzip){
					if (ereg("<error code=",$line)){
							$str_error	= "error";
							$pos_A	= strpos($line,"<$str_error");
							$pos_B	= strpos($line,"</$str_error>")+3+strlen($str_error);
							$err_element = substr($line,$pos_A,$pos_B-$pos_A);
					}
									
					// get put response
					if (ereg("<$verb>",$line)){
						$pos_A	= strpos($line,"<$verb>");
						if(ereg("</$verb>",$line)){
							$pos_B	= strpos($line,"</$verb>") + strlen($verb)+3;
							
							if ($start) $put_response .= $line;
							$xmldata = substr($line,$pos_A,$pos_B-$pos_A);
							continue;
						}else{
							$start = 1;
							// Remove all information before <$verb>
							$line	= substr($line,$pos_A);
						}
					}
					
					if (ereg("</$verb>",$line)){
						// Remove all information after </$verb>
						
						$pos_B	 = strpos($line,"</$verb>") + strlen($verb)+3;
						$line	 = substr($line,0,$pos_B);
						$xmldata = $put_response.$line;
						$start = 0;
					}else if($start){
						$put_response .= $line;
					}
			}
		}
		

		if($is_gzip){

			$line_gzip 	= trim($line_gzip);
			$line_gzip 	= substr($line_gzip,10);
			$rs_gzip	= @gzinflate($line_gzip);

			$rs_gzip	= str_replace("\n","",$rs_gzip);
			$rs_gzip	= str_replace("\r","",$rs_gzip);

			if (ereg("<error code=",$rs_gzip)){
			
				$str_error	= "error";
				$pos_A	= strpos($rs_gzip,"<$str_error");
				$pos_B	= strpos($rs_gzip,"</$str_error>")+3+strlen($str_error);
				$err_element = substr($rs_gzip,$pos_A,$pos_B-$pos_A);
				
			}else{
			
				$pos_A		= (int)@strpos($rs_gzip,"<$verb>");
				$pos_B		= (int)@strpos($rs_gzip,"</$verb>") + strlen($verb)+3;
				
				if($pos_A != $pos_B){
					$xmldata = substr($rs_gzip,$pos_A,$pos_B-$pos_A);
				}
			}
		}
		
		$result['all'] = $response;
	
		// error
		if (empty($xmldata)){
				$result['error'] 		= $err_element;
				
		}else{
			$result['xmldata']		= $xmldata;
		}
		//echo "======================= Line Finish ============= \n<br>";
		return $result;
	}

	function get_response_from_hub($id_repository=""){

		$request	= $this->ra_requestFormatter->request_to_hub($this->ra_verb,$id_repository);
		
		if(!preg_match("/error/i",$request)){
			$fp			= $this->ra_synchronization->sync_sockopen();
			
			if($fp == 0){
				$result['response_hub']	= "TIMEOUT";
			}else{
				
				fputs($fp,$request);
				$result['response_hub']	= $this->get_response_elements($fp,$this->ra_verb);
				fclose($fp);
				
			}
		}else $result['response_hub']	= "TIMEOUT";
		
		return $result;
	}
	
	
	function requestIdentify($id){
		
		$this->ra_verb		= "Identify";
		$response 			= $this->get_response_from_hub($id);
		$response_data		= $response['response_hub'];

		if(preg_match("/TIMEOUT/i",$response_data)){
			$result['error']	= $response_data;
		}else{
			if (!empty($response_data[xmldata])){
				$result 			= $this->ra_import->extract_identify($response_data[xmldata],$id);
			} else {
				$result['error']	= $response_data['error'];
			}
			
			// response
			$result['response'] = $response_data['all'];
		}
		
		return $result;
	}
	
	function requestListIdentifiers(){
		
		$this->ra_verb		= "ListIdentifiers";
		$response 			= $this->get_response_from_hub();
		$response_data		= $response['response_hub'];
		if(preg_match("/TIMEOUT/i",$response_data)){
			$result = $this->error_handle($response_data);
		}else{
			if (!empty($response_data[xmldata])){
				$result 			= $this->ra_import->update_inbox($response_data[xmldata],"dummy");
			} else {
				$result = $this->error_handle($response_data);
			}
			
			// response
			$result['response'] = $response_data['all'];
		}
		
		return $result;
	}
	
	function requestListSets($id){

		$this->ra_verb		= "ListSets";
		$response 			= $this->get_response_from_hub($id);
		$response_data		= $response['response_hub'];
		
		if(preg_match("/TIMEOUT/i",$response_data)){
			$result['error']	= $response_data;
		}else{
			if (!empty($response_data[xmldata])){
				$result 			= $this->ra_import->extract_ListSets($response_data[xmldata],$id);
			} else {
				$result['error']	= $response_data['error'];
			}
			
			// response
			$result['response'] = $response_data['all'];
		}
		
		return $result;
	}
	
	function requestListMetadataFormats(){
		$this->ra_verb		= "ListMetadataFormats";
		$response 			= $this->get_response_from_hub($id);
		$response_data		= $response['response_hub'];
		
		if(preg_match("/TIMEOUT/i",$response_data)){
			$result['error']	= $response_data;
		}else{
			if (!empty($response_data[xmldata])){
				// no action
			} else {
				$result['error']	= $response_data['error'];
			}
			
			// response
			$result['response'] = $response_data['all'];
		}
		
		return $result;
	}
	
	function error_handle($response_data){

		$result['error']	= (is_array($response_data))?$response_data['error']:$reponse_data;
		$result['count']	= 0;
		$result['total']	= 0;
		$result['size']		= 0;
		$result['token']	= 0;
		
		return $result;
	}
	
}
?>