<?php

/***************************************************************************
    last modified		: Jan 15, 2007
    copyleft          		: (L) 2006 KMRG ITB
    email                	: mymails_supra@yahoo.co.uk (GDL 4.2 , design,programmer)
								  benirio@itb.ac.id (GDL 4.2, reviewer)
								  ismail@itb.ac.id (GDL 4.0,  design,programmer)

 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
  if (preg_match("/validation_oai.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class validation_oai{
	var $oai_out;
	var $oai_sync;
	var $oai_lastToken;
	var $oai_sys;
	var $oai_oai;
	var $oai_db;
	var $oai_errFormatter;
	var $error_code;
	var $error_msg;

	function validation_oai(){
		$this->init();
	}
	
	function init(){
		global $gdl_stdout,$gdl_sync,$gdl_sys,$gdl_db;
		
		$this->oai_out			= $gdl_stdout;
		$this->oai_sync			= $gdl_sync;
		$this->oai_sys			= $gdl_sys;
		$this->oai_db			= $gdl_db;
		$this->oai_lastToken	= 0;
		
		$this->oai_oai			= new oai();
		$this->oai_errFormatter	= new errorFormatter();
	}
	
	function init_errorMessage(){
		$errMessage	= $this->oai_errFormatter->get_errorMessage();
		$this->error_code	= $errMessage['code'];
		$this->error_msg	= $errMessage['message'];
	}
	
	function cek_init_state_CA($verb,$req_query){
		$check = 1;
		
		if(sizeof($req_query) == 0){
			$this->oai_errFormatter->error_formatter($verb,"verb");
			$check 	= 0;
		}else if(empty($verb)){
			$this->oai_errFormatter->error_format_verb();
			$check	= 0;
		}else if($req_query == "double"){
			$this->oai_errFormatter->error_formatter($verb,"verb");
			$check	= 0;
		}
		
		return $check;
	}
	
	function cek_error_result($hub_server,$error_result,$verb,$lastToken,$action){
		$result		= "";
		$err_msg	= "";
		$title		= $action;

		$lastToken = (preg_match("/^[0-9]+$/",$lastToken))?$lastToken:0;
		
		$this->oai_lastToken = $lastToken;
		//echo "Token[$verb] : $lastToken";
		
		if(!empty($error_result)){
			$opt1	= "Record not found";
			$opt2	= "The request includes illegal arguments or is missing required arguments";
			$opt3	= "No available posting file";
			
			if(preg_match('/'.$opt1.'/', $error_result) 
					|| (preg_match('/'.$opt2.'/', $error_result)  && ($verb == "GetRecord"))
					||(preg_match('/'.$opt3.'/', $error_result)  && ($verb == "PutFileFragment"))
				){
				if($lastToken > 0){
					switch($verb){
						case "ListProviders" 	:	$err_msg	= "Successfully harvest all list providers from HUB (<b>".$this->oai_sync[sync_hub_server_name]."</b>).";
													break;
													
						case "ListIdentifiers" 	:	$err_msg	= "Successfully harvest all identifiers from HUB (<b>".$this->oai_sync[sync_hub_server_name]."</b>).";
													break;
													
						case "ListRecords" 		:	$err_msg	= "Successfully harvest all records from HUB (<b>".$this->oai_sync[sync_hub_server_name]."</b>).";
													break;
													
						case "GetRecord" 		:	$err_msg	= "Successfully harvest all dummy records from HUB (<b>".$this->oai_sync[sync_hub_server_name]."</b>).";
													break;
													
						case "PutListRecords"	:	$err_msg	= "Successfully posting all records to HUB (<b>".$this->oai_sync[sync_hub_server_name]."</b>).";
													break;
						
						case "PutFileFragment"	:	$err_msg	= "Successfully posting  file at queue list to HUB (<b>".$this->oai_sync[sync_hub_server_name]."</b>).";
													break;
													
					}
					$this->oai_lastToken = 0;
				}else{
					$title 		= "ERROR MESSAGE";
					$err_msg	= "<b>".$error_result."</b>";
					$this->oai_lastToken = 0;
				}
			}else{
					$title 		= "ERROR MESSAGE";
					$err_msg	= "<b>".$error_result."</b>";
					$this->oai_lastToken = 0;
			}
			
			$result .= $this->oai_out->print_message($title,$err_msg);
		}else{
			$this->oai_lastToken++;
		}
		//echo "<br/>Token : ".$this->oai_lastToken;
		return $result;
	}
	
	function std_query_general($verb){
		// strict request
		$args['optional'] = array();
		
		
		switch($verb){
			case "Connect"				:	$args['argument'] = array("verb","providerId","providerSerialNumber","epochTime");
											break;
											
			case "ListProviders"		:	$args['argument'] 	= array("verb","PHPSESSID","limit","resumptionToken");
											break;
											
			case "PutListRecords"		:	$args['argument'] 	= array("verb","PHPSESSID","countRecords","resumptionToken");
											break;
			
								
			case "RemoteLogin"			:	$args['argument'] 	= array("verb","PHPSESSID","remoteUser","remoteName");
											break;
											
			case "PutFileFragment"		:	$args['argument'] 	= array("verb","PHPSESSID","filename");
											break;
											
			case "MergeFileFragments"	:	$args['argument'] 	= array("verb","PHPSESSID");
											break;
				
			// ******** OAI
			//
			case "ListIdentifiers"		:	$args['argument'] 	= array("verb","PHPSESSID","set","resumptionToken");
											break;
			//								
			case "Identify"				:	$args['argument'] 	= array("verb","PHPSESSID");
											break;
			//							
			case "ListMetadataFormats"	:	$args['argument']	= array("verb","PHPSESSID","identifier");
											break;
			//						
			case "ListSets"				:	$args['argument']	= array("verb","PHPSESSID");
											break;
			//								
			case "GetRecord"			:	$args['argument']	= array("verb","PHPSESSID","identifier");
											break;
																		
			case "ListRecords"			:	$args['argument'] 	= array("verb","PHPSESSID","set","from","until","limit","resumptionToken");
											break;
											
			default						:	$args['argument'] 	= array("verb");
		}
		
		return $args;
	}
	
	function std_query_dublincore($verb){
		
		$args = array();
		switch($verb){
			case "GetRecord"			:	$args['optional']	= array();//
											$args['argument']	= array("verb","identifier","metadataPrefix");
											break;
											//
			case "Identify"				:	$args['optional']	= array();//
											$args['argument']	= array("verb");
											break;
											
			case "ListIdentifiers"		:	$args['optional']	= array("from","until","set","resumptionToken");//
											$args['argument']	= array("verb","from","until","metadataPrefix","set","resumptionToken");
											break;
											
			case "ListRecords"			:	$args['optional']	= array("from","until","set","resumptionToken");//
											$args['argument']	= array("verb","from","until","metadataPrefix","set","resumptionToken");
											break;
											//
			case "ListMetadataFormats"	:	$args['optional']	= array("identifier");
											$args['argument']	= array("verb","identifier");
											break;
											
			case "ListSets"				:	$args['optional']	= array();
											$args['argument']	= array("verb");
											break;
											
			default						:	$args['optional'] = array();
											$args['argument'] = array("verb");
			
		}
		return $args;
	}
	
	function cek_valid_date_format($date){
		$cek = (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z/",$date))?true:false;
		return $cek;
	}
	
	function cek_valid_number_format($number){
		return (preg_match("/^[0-9]+$/",$number))?true:false;	
	}
	
	function cek_valid_set_format($set){
		$array_type = array("under:node:");
		$match	= 0;
		$type	= 0;
		for($i=0;($i<count($array_type)) && !$match;$i++){
			if(preg_match("/$array_type[$i]/",$set)){ 
				$match 	= 1;
				$type	= $i;
			}
		}
		
		if($match){
			// cek value
			$len_set	= strlen($set);
			$v_type		= $array_type[$type];
			$value		= substr($set,strlen($v_type));
			
			
			if(empty($value) && ($value != 0)){ $match = 0;
			}else{
				//echo "Val[$value]";
				$dbres	= $this->oai_db->select("folder","folder_id","parent = $value");
				if(@mysqli_num_rows($dbres) == 0) $match = 0;
			}
		}

		return $match;
	}
	
}
?>