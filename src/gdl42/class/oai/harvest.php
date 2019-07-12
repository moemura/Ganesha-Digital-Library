<?php

/***************************************************************************
    last modified		: Jan 15, 2007
    copyleft          		: (L) 2006 KMRG ITB
    email                	: mymails_supra@yahoo.co.uk (GDL 4.2 , design,programmer)
								  benirio@itb.ac.id (GDL 4.2, reviewer)

 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
 if (eregi("harvest.php",$_SERVER['PHP_SELF'])) {
    die();
}

class harvest{
	var $harvest_oaipmh;
	var $harvest_oaipmp;
	var $harvest_synchronization;
	var $harvest_out;
	var $harvest_sync;
	var $harvest_formatRequest;
	var $harvest_metadata;
	var $harvest_remoteLogin;
	var $harvest_sys;
	var $main_url;
	
	function harvest(){
	}
	
	function init($option){
		global $gdl_synchronization,$gdl_sync,$gdl_stdout,$gdl_metadata,$gdl_sys;
		
		$this->harvest_formatRequest		= $this->init_format_request($option);
		
		$oai_factory 						= new oaiFactory($this->harvest_formatRequest);
		$this->harvest_oaipmh				= $oai_factory->createFactory_oaipmh();
		$this->harvest_oaipmp				= $oai_factory->createFactory_oaipmp();
		$this->harvest_synchronization		= $gdl_synchronization;
		$this->harvest_out					= $gdl_stdout;
		$this->harvest_sync					= $gdl_sync;
		$this->harvest_metadata				= $gdl_metadata;
		$this->harvest_sys					= $gdl_sys;
	}
	
	function init_format_request($option){
		$array_type_metadata = array("general","oai_dc");
		
		if($option == "0"){
			$option = $array_type_metadata[0];
		}else if($option == "1"){
			$option = $array_type_metadata[1];
		}else{
			$option = $array_type_metadata[0];
			$this->harvest_sync['sync_opt_script']	= "0";
		}
		
		return $option;
	}
	
	function operation_navigator_harvest($main_url){
		$table = "<table align='center' border='0' >".
						"<tr bgcolor=\"#6666CC\" style=\"color:#ffffff;\"  height=\"20px\">".
							"<td colspan='3' align='center'><b> Harvesting Operation</b> </td>".
						"</tr>".
						"<tr bgcolor=\"#CCCCFF\" height=\"25px\">".
							"<td align='center'><a href=\"$main_url&amp;start=1&amp;verb=ListProviders\"><b>Harvest Publisher</b></a></td>".
							"<td align='center'><a href=\"$main_url&amp;sub=0&amp;start=1&amp;verb=ListRecords\"><b>Harvest Metadata</b></a></td>".
							"<td align='center'><a href=\"$main_url&amp;action=cleanInbox\"><b>Clean Inbox</b></a></td>".
						"</tr>".
		 		"</table><br/>";
		return $table;
	}
	
	function operation_navigator_posting($main_url){
		$table = "<table align='center' border='0'>
						<tr bgcolor=\"#6666CC\" style=\"color:#ffffff;\"  height=\"20px\">
							<td colspan='2' align='center'> <b>Posting Operation</b> </td>
						</tr>
						<tr bgcolor=\"#CCCCFF\" height=\"25px\">".
						//	"<td align='center'><a href=\"$main_url&amp;sub=0&amp;start=1&amp;verb=PutListRecords\"><b>Posting List Records</b></a></td>".
							"<td align='center'><a href=\"$main_url&amp;sub=1\"><b>Posting File</b></a></td>".
							"<td align='center'><a href=\"$main_url&amp;sub=2\"><b>Outbox</b></a></td>".
						"</tr>
		 		</table><br/>";
		return $table;
	}
	
	function execute_verb($verb){
		
		$this->harvest_oaipmh->main_url = $this->main_url;
		$option	= $this->harvest_formatRequest;
		
		//echo "OPT_HARVEST-0[$option]<br/>";
		if($option != "general") $this->harvest_synchronization->sync_disconnection();
		//echo "OPT_HARVEST-1[$option]<br/>";
		
		switch($option){
			case "oai_dc"	:	$result	= $this->harvest_dublincore($verb);
								break;
			case "general"	:	$result	= $this->harvest_general($verb);
								break;
			default			:	$title		= "ATTENTION";
								$post_desc	= "<b>YOUR METADATA FORMAT DID NOT VALID</b>";
								$result 	= $this->harvest_out->print_message($title,$post_desc);	
		}

		return $result;
	}
	
	function harvest_dublincore($verb){ //echo "dublincore";
		
		if(($verb == "GetRecord") || ($verb == "ListRecords") || ($verb == "ListIdentifiers")){
			$repo_name = trim($this->harvest_sync['sync_repository_name']);
			if(empty($repo_name) || ereg("N/A",$repo_name)){
				$result	= "<b>You cannot use this operation until you have receive the repository name.</b>";
				return $result;	
			}
		}
		
		$rs_execute = $this->harvest_oaipmh->harvest_verb($verb);
		
		if(!empty($rs_execute['error-type'])){
			
			if($verb != "Connect"){
				$title	= "ATTENTION";
				$result = $this->harvest_out->print_message($title,"<strong>".$rs_execute['error']."</strong>");
			}else{
				$result['error']	= "<error code=\"badOperation\">$rs_execute[error]</error>";
			}
		}else
			$result	= $rs_execute['show'];
		
		return $result;
	}
	
	function harvest_general($verb){ //echo "V[$verb][".$this->harvest_synchronization->is_connected()."]";
		
		//echo "general";
		if($verb == "RemoteLogin")$state = "remote";
		
		if($this->harvest_synchronization->is_connected($state) || ($verb == "Connect")){
			
			$this->harvest_oaipmh->gn_remoteLogin	= $this->harvest_remoteLogin;
			
			$rs_execute = $this->harvest_oaipmh->harvest_verb($verb);
			
			if(!empty($rs_execute['error-type']))
				$result = $rs_execute['error'];
			else if(($verb == "Connect") || ($verb == "RemoteLogin"))
				$result	= $rs_execute;
			else
				$result	= $rs_execute['show'];
					
		}else{
			$title		= "ATTENTION";
			$post_desc	= "<b>YOU MUST CONNECT TO HUB SERVER</b>";
			$result 	= $this->harvest_out->print_message($title,$post_desc);
				
		}
			
		return $result;
	}
	
	function response_verb($verb){
		$option	= $this->harvest_formatRequest;

		switch($option){
			case "oai_dc"	:	$result	= $this->response_dublincore($verb);
								break;
			case "general"	:	$result	= $this->response_general($verb);
								break;
			default			:	$title		= "ATTENTION";
								$post_desc	= "<b>YOUR METADATA FORMAT DID NOT VALID</b>";
								$result 	= $this->harvest_out->print_message($title,$post_desc);	
		}

		return $result;
	}
	
	function response_dublincore($verb){
		$result			= "";
		$request_query	= $this->harvest_oaipmp->get_request_parameter();
		$cek_dublincore	= new validation_DC();
		
		$this->harvest_oaipmp->oai_requestQueryUser = $request_query;
		
		if(!$cek_dublincore->check_arguments($verb,$request_query)){
				$err_code		= $cek_dublincore->error_code;
				$err_msg		= $cek_dublincore->error_msg;
				$error_element	= $this->error_element($err_code,$err_msg);
				$result			= $this->harvest_metadata->generate_response_oaipmh($verb,$request_query,$error_element);
		}else{
				if($cek_dublincore->rs_extractResumptionToken != null){
					$arr_node	= $cek_dublincore->rs_extractResumptionToken;

					$request_query['from']				= $arr_node[0];
					$request_query['until']				= $arr_node[1];
					$request_query['set']				= $arr_node[2];
					$request_query['resumptionToken']	= $arr_node[3];
					$request_query['metadataPrefix']	= $arr_node[4];

				}
				
				$this->harvest_oaipmp->oai_requestQuery = $request_query;
				$result			= $this->harvest_oaipmp->response_dublincore($verb);
		}
		return $result;
	}
	
	function response_general($verb){
		$result			= "";
		$request_query	= $this->harvest_oaipmp->get_request_parameter();
		$cek_general	= new validation_GN();
		
		$this->harvest_oaipmp->oai_requestQueryUser = $request_query;
		
		if(!$cek_general->check_arguments($verb,$request_query)){
				$err_code		= $cek_general->error_code;
				$err_msg		= $cek_general->error_msg;
				$error_element	= $this->error_element($err_code,$err_msg);
				$result			= $this->harvest_metadata->generate_response_oaipmh($verb,$request_query,$error_element);
		}else{
			$accept_remote_login= $this->harvest_sys['remote_login'];
			if(!$accept_remote_login && (strtolower($request_query['verb']) == "remotelogin")){
				$err_code		= "blockingRequest";
				$err_msg		= "For this moment, our repository does not accept remote login request.";
				$error_element	= $this->error_element($err_code,$err_msg);
				$result			= $this->harvest_metadata->generate_response_oaipmh($verb,$request_query,$error_element);
			}else{
				$this->harvest_oaipmp->oai_requestQuery = $request_query;
				$result			= $this->harvest_oaipmp->response_general($verb);
			}
		}
		return $result;
	}
	
	function get_current_publisher(){
			$option = $this->harvest_formatRequest;
			
			switch($option){
				case "oai_dc" : $result = null;
											break;
				case "general": $result = $this->harvest_oaipmh->get_current_publisher();
											break;
			}
			
			return $result;
	}
	
	function error_element($error_code,$error_message){
		return "<error code=\"$error_code\">$error_message</error>\n";
	}
}
?>