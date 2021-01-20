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
 
 if (preg_match("/oaipmh_GN.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class oaipmh_GN extends oai {
	
	var $gn_validationGN;
	var $gn_requestAction;
	
	var $gn_remoteLogin;
	
	function __construct(){
		$this->init();
		$this->init_GN();
	}
	
	function init_GN(){
		$this->gn_validationGN		= new validation_GN();
		$this->gn_requestAction		= new requestAction_GN();
	}
	
	function harvest_verb($verb){
		$result = array();
		if(!in_array($verb,$this->default_verb) && !in_array($verb,$this->extended_verb)){
			$result['error-type']	= "1";
			$result['error']		= "unsupported operation ($verb)";
			$_SESSION['LastToken']	= 0;
		}else{
			
			$this->data_type			= "metadata";
			$this->verb					= $verb;
			$identify					= $_SESSION['sess_Identify'];
			$_SESSION['sess_Identify']	= "";
			
			switch($verb){
				
				/* OAI standard */
				case "Identify"				:	$this->action	= "Update Repository [Identify]";
												$rs_verb		= $this->Identify($identify);
												break;
												
				case "ListIdentifiers"		:	$this->action 	= "Harvesting metadata records";
												$rs_verb		= $this->ListIdentifiers();
												break;
												
				case "ListRecords"			:	$this->action 	= "Harvesting metadata records";
												$rs_verb		= $this->ListRecords();
												break;
												
				case "ListSets"				:	$this->action	= "Update Repository[ListSets]";
												$rs_verb		= $this->ListSets($identify);
												break;
												
				case "GetRecord"			:	$this->action	= "Harvesting single record";
												$rs_verb		= $this->GetRecord($identify);
												break;
												
				case "ListMetadataFormats"	:	$this->action	= "Identify repository metadata format";
												$rs_verb		= $this->ListMetadataFormats($identify);
												break;
												

				/*Additional verb*/
				case "Connect"				: 	$this->action	= "Connection response from target (".$this->oai_sync['sync_hub_server_name'].")";
												$rs_verb		= $this->Connect();
										  	  	break;
			
				case "ListProviders"		: 	$this->action	= "Harvest list publisher";
												$rs_verb		= $this->ListProviders();	
										  	  	break;
										  
									
				case "PutListRecords"		: 	$this->action	= "Posting metadata";
												$rs_verb		= $this->PutListRecords();
										  	  	break;

				case "PutFileFragment"		:	$this->action	= "Posting file"; 
												$rs_verb		= $this->manipulationPostingFile();
												break;
												
				case "MergeFileFragments"	:	$this->action	= "Merge file";
												$rs_verb		= $this->manipulationPostingFile();
												break;
												  	  	
				case "RemoteLogin"			: 	$this->action	= "Remote login";
												$rs_verb		= $this->RemoteLogin();
										  	  	break;	
			}
			
			$hub_server = $this->oai_sync['sync_hub_server_name'];
			$err_cek	= $this->gn_validationGN->cek_error_result($hub_server, $rs_verb['error'], $verb, isset($_SESSION['LastToken']) ? $_SESSION['LastToken'] : null, $this->action);
			
			if(!empty($err_cek) && ($this->verb == "Connect"))
				$err_cek	= "";
			
			if(empty($err_cek)){
				if($this->verb == "Connect" || ($this->verb == "RemoteLogin")){
					$result	= $rs_verb;
				}else	
					$result['show']		= $this->Show_Status_Processing($rs_verb,"inbox","");
			}else{
				$result['show']		= $err_cek;
				
				if($this->verb == "PutListRecords"){
					$result['show'] .= $this->oai_out->header_redirect(4,$this->main_url."verb=ListRecords");
				}
			}
			
			$_SESSION['LastToken']	= $this->gn_validationGN->oai_lastToken;
		}
		
		return $result;
	}
	
	function Identify($id){
		return $this->gn_requestAction->requestIdentify($id);
	}
	
	function ListIdentifiers(){
		return $this->gn_requestAction->requestListIdentifiers();
	}
	
	function ListRecords(){
		return $this->gn_requestAction->requestListRecords();
	}
	
	function ListSets($id){
		return $this->gn_requestAction->requestListSets($id);
	}
	
	function GetRecord($id){
		return $this->gn_requestAction->requestGetRecord($id);
	}
	
	function ListMetadataFormats($id){
		return $this->gn_requestAction->requestListMetadataFormats($id);
	}
	
	
	function Connect(){
		return $this->gn_requestAction->requestConnect();
	}
	
	function ListProviders(){
		return $this->gn_requestAction->requestListProviders();
	}
	
	function PutListRecords(){
		return $this->gn_requestAction->requestPutListRecords();
	}
	
	function PutFileFragment(){
		return $this->gn_requestAction->requestPutFileFragment();
	}
	
	function MergeFileFragments(){
		return $this->gn_requestAction->requestMergeFileFragments();
	}
	
	function RemoteLogin(){
		$user	= $this->gn_remoteLogin['user'];
		$name	= urlencode($this->gn_remoteLogin['name']);
		return $this->gn_requestAction->requestRemoteLogin($user,$name);
	}
	
	function manipulationPostingFile(){

		$rs_status		=	$this->gn_requestAction->get_option_verb_postingFile();
		//foreach($rs_status as $index => $value) echo "$index ===> $value <br/>\n";
		
		if(empty($rs_status['error'])){
			$status		= $rs_status['status'];
			if($status == "fragmented"){ // fragmented
					$this->action	= "Posting file";
					$result	= $this->PutFileFragment();
			}else if($status == "merge"){ // merge
					$this->action	= "Merge file";
					$result	= $this->MergeFileFragments();
			}
		}else $result['error'] = $rs_status['error'];
		
		return $result;
	}
	
	function get_current_publisher(){
		return $this->gn_requestAction->ra_requestFormatter->get_current_publisherTarget();
	}
	
}
?>