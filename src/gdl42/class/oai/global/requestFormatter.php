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
 if (preg_match("/requestFormatter.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class requestFormatter{
	var $rf_sync;
	var $rf_sys;
	var $rf_publisher;
	var $rf_db;
	var $rf_metadata;
		
	var $verb;
	var $token;
	var	$from;
	var	$until;
	var $limit;
	var $set;
	var $metadataPrefix;
	var $identifier;
	var $epochTime;
	var $providerSerialNumber;
	var $providerId;
	var $countRecords;
	var $remoteLogin;
	var $filename;
	
	function requestFormatter(){
		$this->init();
	}
	
	function init(){
		global $gdl_sys, $gdl_publisher, $gdl_db, $gdl_sync,$gdl_metadata;
		
		$this->rf_sync		= $gdl_sync;
		$this->rf_sys		= $gdl_sys;
		$this->rf_publisher	= $gdl_publisher;
		$this->rf_db		= $gdl_db;
		$this->rf_metadata	= $gdl_metadata;
	}
	
	
	//
	function init_request_argument($array){
		global $HTTP_SESSION_VARS;
		
		$count = count($array);
		for($i=0;$i<$count;$i++){
			switch($array[$i]){
				case "resumptionToken"		: 	if(empty($this->token)) {
													$this->token = $_GET['resumptionToken'];
													$this->token = (empty($this->token))?0:$this->token;
												}
												break;
												
				case "from"	 				: 	if(empty($this->from)){
													$this->from = $_GET['from'];
													
													if(empty($this->from))
														$this->from = trim($this->rf_sync['sync_harvest_from']);
												}
												break;
												
				case "until"	 			: 	if(empty($this->until)){
													$this->until = $_GET['until'];
													
													if(empty($this->until))
														$this->until = trim($this->rf_sync['sync_harvest_until']);
												}
												break;
												
				case "limit"	 			: 	$this->limit = $_GET['from'];
												if(empty($this->limit))
													$this->limit = $this->rf_sync['sync_count_records'];
												break;
												
				case "set"	 				: 	$this->set 	= $_GET['set'];
												$set_no		= (int)$this->rf_sync['sync_harvest_set'];

												if(empty($this->set) && !empty($set_no) && ($set_no > 0)){
													$id		= $this->rf_sync['sync_repository_id'];
													$dbres	= $this->rf_db->select("Set","spec","nomor = $id");
													
													if($dbres){
														mysqli_data_seek($dbres, $set_no-1);
														$row = mysqli_fetch_assoc($dbres);
														$set 	= $row["spec"];
														$node	= $this->rf_sync['sync_harvest_node'];
														if(!empty($set)){
															if(empty($node)) $node = 0;
															$this->set = $set.":".$node;
															$this->parent_set = $set;
														}
													}
												}
												
												if(empty($this->set) && ($this->metadataPrefix == "general")){
													$this->set = "under:node:0";
												}
												break;
												
				case "metadataPrefix"		:	if(empty($this->metadataPrefix)){
													$this->metadataPrefix = $_GET['metadataPrefix'];
													
													if(empty($this->metadataPrefix))
														if($this->rf_sync['sync_opt_script'] != "0")
															$this->metadataPrefix = "oai_dc";
														else
															$this->metadataPrefix = "general";
												}
												
												if($this->metadataPrefix != "oai_dc") $this->metadataPrefix = "general";
												break;
												
				case "identifier" 			: 	$this->identifier = $_GET['identifier'];
							   					break;

				case "epochTime"			: 	$this->epochTime = date("U");
												break;

				case "providerSerialNumber" :	if(empty($this->epochTime))$this->epochTime = date("U");
												$this->providerSerialNumber = md5($this->rf_publisher[serialno]."-".$this->epochTime);
												$this->providerSerialNumber = urlencode($this->providerSerialNumber);
												break;

				case "providerId"			:	$this->providerId = $this->rf_publisher['id'];
												break;

				case "PHPSESSID"			:   $this->PHPSESSID=$HTTP_SESSION_VARS['sess_connect_sessionid'];
												break;

				case "countRecords"			:	if(empty($this->countRecords)){
													$cRecords = $_GET['countRecords'];
													$this->countRecords	= (isset($cRecords))?$cRecords:0;
												}
												break;
			}
		}
	}
	
	//
	function build_request($array){	
		$result = "";
		$count 	= count($array);
		
		for($i=0;$i<$count;$i++){
			$args = $array[$i];
			//echo "ARG : $args [".$this->token."]<br/>\n";
			switch($args){
				case "verb"					:	$result = "verb=".$this->verb.$result;
												break;
												
				case "resumptionToken"		: 	$result .= $this->request_resumptionToken();
												break;
												
				case "from"	 				: 	if($this->metadataPrefix == "oai_dc"){
													if(!empty($this->from))
														if(!preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z/",$this->from))
															$result .= "&from=".$this->from;
												}else
													$result .= (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z/",$this->from))?"&from=":"&from=".$this->from;
													
												break;
												
				case "until"	 			: 	if($this->metadataPrefix == "oai_dc"){
													if(!empty($this->until))
														if(!preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z/",$this->until))
															$result .= "&until=".$this->until;
												}else
													$result .= (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z/",$this->until))?"&until=":"&until=".$this->until;
													
												break;

				case "limit"	 			: 	$result .= "&limit=".$this->limit;
												break;

				case "set"	 				: 	$result .= $this->request_set();
												break;

				case "metadataPrefix"		:	$result .= "&metadataPrefix=".$this->metadataPrefix;
												break;

				case "identifier" 			:	$result .= "&identifier=".$this->identifier;
												break;

				case "epochTime"			: 	$result .= "&epochTime=".$this->epochTime;
												break;

				case "providerSerialNumber" :	$result .= "&providerSerialNumber=".$this->providerSerialNumber;
												break;

				case "providerId"			:	$result .= "&providerId=".$this->providerId;
												break;

				case "PHPSESSID"			:   $result .= "&PHPSESSID=".$this->PHPSESSID;
												break;

				case "countRecords"			:	$result .= "&countRecords=".$this->countRecords;
												break;
				
				case "remoteUser"			:	$result	.= "&remoteUser=".$this->remoteLogin['user'];
												break;
												
				case "remoteName"			:	$result	.= "&remoteName=".$this->remoteLogin['name'];
												break;
				
				case "filename"				:  $result .= "&filename=".$this->filename;
			}
		}

		return $result;
	}
	
	function request_set(){
		if($this->metadataPrefix == "oai_dc"){
			if(!empty($this->set))
				$query	= "&set=".$this->set;
		}else{
			$query	= "&set=".$this->set;
		}
		return $query;
	}
	
	function request_resumptionToken(){
		//echo "TOKEN : ".$this->metadataPrefix." :: ".$this->token."<br/>\n";
		if($this->metadataPrefix == "oai_dc"){
			if(($this->token != 0) || preg_match("/oai_dc/",$this->token))
				$query	= "&resumptionToken=".$this->token;
		}else{
			$query	= "&resumptionToken=".$this->token;
		}
		return $query;
	}

}

?>