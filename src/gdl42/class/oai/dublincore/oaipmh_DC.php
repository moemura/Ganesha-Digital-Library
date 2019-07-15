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
 
 if (preg_match("/oaipmh_DC.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class oaipmh_DC extends  oai {
	var $dc_validationDC;
	var $dc_requestAction;
	
	function oaipmh_DC(){
		$this->init();
		$this->init_DC();
	}
	
	function init_DC(){
		$this->dc_validationDC		= new validation_DC();
		$this->dc_requestAction		= new requestAction_DC();
	}
	
	function harvest_verb($verb){
		$result = array();
		if(!in_array($verb,$this->default_verb)){
			$result['error-type']	= "1";
			$result['error']		= "unsupported operation ($verb)";
			$_SESSION['LastToken']	= 0;
		}else{
			$this->data_type			= "metadata";
			$this->verb					= $verb;
			$identify					= $_SESSION['sess_Identify'];
			$_SESSION['sess_Identify']	= "";
			
			switch($verb){
				case "Identify"				:	$this->action	= "Update Repository [Identify]";
												$rs_verb		= $this->Identify($identify);
												break;
												
				case "ListIdentifiers"		:	$this->action 	= "Harvesting metadata records";
												$rs_verb		= $this->ListIdentifiers();
												break;
												
				case "ListRecords"			:	$this->action 	= "Harvesting metadata records";
												//$rs_verb		= $this->ListRecords();
												$rs_verb		= $this->listRecordsManipulation();
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
			}
			
			$hub_server = $this->oai_sync['sync_hub_server_name'];
			$err_cek	= $this->dc_validationDC->cek_error_result($hub_server,$rs_verb['error'],$verb,$_SESSION['LastToken'],$this->action);
			

			if(empty($err_cek)){
				if($verb == "GetRecord"){
					$rs_record		= $this->dummy_getRecord($rs_verb);
					$refresh			= $rs_record['refresh'];
					$rs_verb['dummy_count']	= $rs_record['count_dummy'];
					$this->oai_db->update("inbox","status='success',folder='received'","identifier like '$identify'");
				}else if($verb == "ListRecords"){
					$rs_record		= $this->dummy_getRecord($rs_verb);
					$refresh		= $rs_record['refresh'];
				}
				
				$result['show']		= $this->Show_Status_Processing($rs_verb,"inbox","").$refresh;
									
			}else{
				if($this->verb == "ListRecords"){
					$rs_record		= $this->dummy_getRecord($rs_verb);
					$refresh		= $rs_record['refresh'];
				}else if($verb == "GetRecord"){
					global $HTTP_SESSION_VARS;

					$this->oai_db->update("inbox","status = 'failed'","identifier = '$identify'");

					$c_list	= count($HTTP_SESSION_VARS['list_Identifiers']);
					if($c_list > 0){
						// Harvesting metadata has not accomplish
						$rs_record		= $this->dummy_getRecord($rs_verb);
						$refresh			= $rs_record['refresh'];
					}
					
				}
				
				$result['show']		= $err_cek.$refresh;
			}
		}
		
		$_SESSION['LastToken']	= $this->dc_validationDC->oai_lastToken;
		//echo "lastToken : ".$this->dc_validationDC->oai_lastToken;
		return $result;
	}
	
	function dummy_getRecord($array){
		global $HTTP_SESSION_VARS;
					
		$c_list	= count($HTTP_SESSION_VARS['list_Identifiers']);
		
		if($c_list > 0){
			$identifier = array_pop($HTTP_SESSION_VARS['list_Identifiers']);
			$_SESSION['sess_Identify']	= $identifier;
		}else{
			$c_list	= count($array['dummy_identifier']);
			if($c_list > 0){
				$identifier = array_pop($array['dummy_identifier']);
				$_SESSION['sess_Identify']	= $identifier;
			}else
				$_SESSION['sess_Identify']	= "";
		}
		$str_refresh = $this->oai_out->header_redirect(1,$this->main_url."&amp;verb=GetRecord");
		$result['refresh']		= $str_refresh;
		$result['count_dummy']	= $c_list;
		
		return $result;
	}
	
	function Identify($id){
		return $this->dc_requestAction->requestIdentify($id);
	}
	
	function ListIdentifiers(){
		return $this->dc_requestAction->requestListIdentifiers();
	}
	
	function ListRecords(){
		return $this->dc_requestAction->requestListRecords();
	}
	
	function ListSets($id){
		return $this->dc_requestAction->requestListSets($id);
	}
	
	function GetRecord($id){
		if(empty($id)){
			$result['error'] = "<error =\"badIdentifier\" >Empty Identifier. None metadata to be harvested</error>";
			return $result;
		}
		return $this->dc_requestAction->requestGetRecord($id);
	}
	
	function ListMetadataFormats($id){
		return $this->dc_requestAction->requestListMetadataFormats($id);
	}
	
	function listRecordsManipulation(){
		global $HTTP_SESSION_VARS;
		
		$treshold		= 200;
		$i_loop			= 0;
		$arr_identifier	= array();
		$_SESSION['LastToken']	= "0";
		$i_record =0;
		$loop = true;
		do{

			$rs_execute	= $this->ListIdentifiers();
			if(empty($rs_execute['error'])){
				$dummy	= $rs_execute['dummy_identifier'];
				$c_list	= count($dummy);
	
				for($i=0;$i<$c_list;$i++){
					$identifier	= trim($dummy[$i]);
					if(!empty($identifier)){
						if(!preg_match("/\(.*/",$identifier)){
							array_push($arr_identifier,$identifier);
						}
					}
				}
				
				$token = $rs_execute['token'];
				//echo "Manipulation-Token : $token <br/>\n";
				if(empty($token)){ 
					//echo " Kosong <br/>";
					$loop = false;
				}else{
					$this->dc_requestAction->ra_requestFormatter->token = $token;
					$_SESSION['LastToken']	= $token;
				}
			}else $loop = false;
			
			$i_record = count($arr_identifier);
			$i_loop++;
			//echo "Looping-N[$i_record][$token][$loop][$i_loop] <br/>\n";
			//foreach($rs_execute as $index => $value) echo "$index==>$value <br/>";
			
		}while(($i_record < $treshold) && $loop && ($i_loop < 7));
		
		if(count($arr_identifier) > 0)
			$HTTP_SESSION_VARS['list_Identifiers'] = $arr_identifier;
		else{
			$HTTP_SESSION_VARS['list_Identifiers'] = null;
			$rs_execute['total'] 	= null;
			$rs_execute['token']	= null;
			$rs_execute['count']	= null;
		}
		
		$rs_execute['token']	= 1;
		$result = $rs_execute;
		//foreach($rs_execute as $index => $value) echo "$index ==> $value <br/>\n";
		return $result;
	}
}
?>