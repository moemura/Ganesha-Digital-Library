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
 
 if (eregi("requestAction_GN.php",$_SERVER['PHP_SELF'])) {
    die();
}

class requestAction_GN extends requestAction {
	var $req_posting_file_info;
	
	function requestAction_GN(){
		$this->init("general");
	}

	function requestListRecords(){
		
		$this->ra_verb		= "ListRecords";
		$response 			= $this->get_response_from_hub();
		$response_data		= $response['response_hub'];
		
		if(eregi("TIMEOUT",$response_data)){
			$result = $this->error_handle($response_data);
		}else{
			if (!empty($response_data[xmldata])){
				$result 			= $this->ra_import->extract_record($response_data[xmldata],"inbox","general");
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
		
		if(eregi("TIMEOUT",$response_data)){
			$result['error']	= $response_data;
		}else{
			if (!empty($response_data[xmldata])){
				$result 			= $this->ra_import->extract_record($response_data[xmldata],"inbox","general");
			} else {
				$result['error']	= $response_data['error'];
			}
			
			// response
			$result['response'] = $response_data['all'];
		}
		
		return $result;
	}

	function requestConnect(){
		
		$this->ra_verb		= "Connect";
		$response 			= $this->get_response_from_hub();
		$response_data		= $response['response_hub'];
		
		if(eregi("TIMEOUT",$response_data)){
			$result['error']	= $response_data;
		}else{
			if (!empty($response_data['xmldata'])){
				$xmldata = $this->ra_metadata->read_xml($response_data['xmldata']);
				$result['connect_response']	= $xmldata;
			} else {
				$result['error']	= $response_data['error'];
			}
			
			// response
			$result['response'] = $response_data['all'];
		}
		
		return $result;
	}
	
	function requestListProviders(){
		global $gdl_oaipmp,$gdl_stdout,$gdl_import;
		
		$this->ra_verb	= "ListProviders";
		$response 		= $this->get_response_from_hub();
		$response_data	= $response['response_hub'];

		if(eregi("TIMEOUT",$response_data)){
			$result['error']	= $response_data;
		}else{
			if (!empty($response_data[xmldata])){
				$result				= $this->ra_import->extract_providers($response_data[xmldata]);
			} else {
				$result['error']	= $response_data['error'];
			}
			
			// response
			$result['response'] = $response_data['all'];
		}
		
		return $result;
	}
	
	function requestPutListRecords(){
		global $HTTP_SESSION_VARS;
		
		$this->ra_verb		= "PutListRecords";
		$response 			= $this->get_response_from_hub();
		$response_data		= $response['response_hub'];

		$this->box_statistic("outbox");
		if(eregi("TIMEOUT",$response_data)){
			$result['error']	= $response_data;
		}else{
			if (!empty($response_data[xmldata])){
				
				// get identifiers
				if(eregi("<header>",$response_data['xmldata'])){
					$xmldata		= $this->ra_metadata->readXML($response_data['xmldata']);
					$result['size']	= 0;
					$result['count']= 0;
					if(is_array($xmldata)){
						$c_record	= count($xmldata['HEADER.IDENTIFIERS']);
						for($i=0; $i<$c_record; $i++){
							$p_identifier	= $xmldata['HEADER.IDENTIFIERS'][$i];	
							$p_size			= (int)$xmldata['HEADER.SIZE'][$i];
							$p_status		= $xmldata['HEADER.STATUS'][$i];
							$p_size			= (ereg("^[0-9]+$",$p_size))?$p_size:0;
							
							if(!empty($p_identifier)){
								$result['size']	+= $p_size;
								$result['count']++;
								$identifiers	.= "<br/>&nbsp;&nbsp;$p_identifier ($p_status)";
								$this->update_outbox(trim($p_identifier),"success","sent");
							}
						}
					}
					
				}else{
					
					$xml 	= $this->ra_metadata->readXML($response_data[xmldata]);	
					
					$list_identifier	= $xml['IDENTIFIERS'][0];
					$arr_identifier		= explode(";",$list_identifier);
					$c_identifier		= count($arr_identifier);
					if($c_identifier > 0){
						for($i=0;$i<$c_identifier;$i++){
							
							$buffer	= trim($arr_identifier[$i]);
							if(!empty($buffer)){
								$identifiers	.= "<br/>&nbsp;&nbsp;$buffer";
								$buff_arr		= explode("(",$buffer);
								$this->update_outbox(trim($buff_arr[0]),"success","sent");
							}
						}
					}else $identifiers = trim($list_identifier);
						
					
					$result['size'] 		= $xml[SIZE][0];
					$result['count'] 		= $xml[COUNTRECORDS][0];
					
					if (empty($result[count])) $result[count] = "0";
					
					if (!empty($xml[ERROR][0])) $result[error] = "<br/>Warning: ".$xml[ERROR][0];
					
				}
				
				if(empty($identifiers)){
					$result['count']		= 0;
					$result['identifiers'] 	= "Metadata are uptodate";
				}else{
					$pos_A	= strpos($response_data['all'],"<request");
					$pos_B	= strpos($response_data['all'],"</request>");
					$substr	= substr($response_data['all'],$pos_A,$pos_B-$pos_A+10);
					
					$xml	= $this->ra_metadata->readXML("<DATA>$substr</DATA>");
						
					$result['total'] 		= $HTTP_SESSION_VARS['sess_outbox_stats']['FOLDER']['outbox'];
					$result['token']		= $xml['REQUEST.RESUMPTIONTOKEN'][0]+1;
					$result['identifiers'] 	= $identifiers;	
				}
				$result['response'] 	= $response_data['all'];
				
			} else {
				$result['error']	= $response_data['error'];
			}
		}
		
		return $result;
	}
	
	function requestPutFileFragment(){
		$this->ra_verb		= "PutFileFragment";
		$response 			= $this->get_response_from_hub();
		$response_data	= $response['response_hub'];
		
		if(eregi("TIMEOUT",$response_data)){
			$result['error']	= $response_data;
		}else{
			if (!empty($response_data[xmldata])){
					$xml 	= $this->ra_metadata->readXML($response_data[xmldata]);
					//foreach($xml as $index => $value) echo "$index ==> $value <br/>";
					
					$status							= trim($xml['STATUS'][0]);
					if($status == "success"){
						$result['counter']			= 1;
						$result['identifiers']			= $xml['FILENAME'][0];
						$result['size']					= $xml['FILESIZE'][0];
						$result['count']				= $this->req_posting_file_info['c_fragment'];
						$result['no_fragment']	= $this->req_posting_file_info['no_fragment'];
						$result['next_job']			= "PutFileFragment";
						$result['type']					= "PutFileFragment";
						
						$this->update_log_posting();
					}else{ 
						$updt_queue 		    = "status = 'failed'";
						$result['error']				= "Failed Posting File Fragments.";
						$this->update_status_boxQueue($updt_queue);
					}
					
				} else {
					$result['error']	= $response_data['error'];
			}
			
			// response
			$result['response'] = $response_data['all'];
		}
		
		return $result;
	}
	
	function requestMergeFileFragments(){
		$this->ra_verb		= "MergeFileFragments";
		$response 			= $this->get_response_from_hub();
		$response_data		= $response['response_hub'];
		
		if(eregi("TIMEOUT",$response_data)){
			$result['error']	= $response_data;
		}else{
			if (!empty($response_data[xmldata])){
					$xml 	= $this->ra_metadata->readXML($response_data[xmldata]);
					//echo "xmldata : $response_data[xmldata] <br/>";
					//foreach($xml as $index => $value) echo "$index =0=> $value[0] <br/>";
					
					$status							= trim($xml['STATUS'][0]);
					
					if($status == "success"){
					$arr_fragment 				= explode(",",$xml['FRAGMENTS'][0]);
					$result['filename']			= $xml['FILENAME'][0];
					$result['count']				= $xml['COUNT'][0];
					$result['size']					= $xml['SIZE'][0];
					$result['identifiers']			= implode("<br/>&nbsp;",$arr_fragment);
					$result['next_job']			= "PutFileFragment";
					$result['type']					= "MergeFileFragments";
					
					if(empty($this->req_posting_file_info['file_fragment']))
						$this->req_posting_file_info['file_fragment'] = "%$result[filename]";
						
					$updt_queue 				= "status = 'success'";
					$this->clean_temporaryPostingFile();
					}else{ 
						$updt_queue 			= "status = 'failed'";
						$result['error']				= "Failed Merge File Fragments.";
					};
					
					$this->update_status_boxQueue($updt_queue);
			} else {
				$result['error']	= $response_data['error'];
			}
			
			// response
			$result['response'] = $response_data['all'];
		}
		
		return $result;
	}
	
	function requestRemoteLogin($user_id,$username){
		
		$this->ra_verb		= "RemoteLogin";
		
		$this->ra_requestFormatter->remoteLogin = array("user"=>$user_id,"name"=>$username);
		
		$response 			= $this->get_response_from_hub();
		$response_data		= $response['response_hub'];
				
		if(eregi("TIMEOUT",$response_data)){
			$result['error']	= $response_data;
		}else{
			if (!empty($response_data[xmldata])){
				//$result[]			= $this->ra_import->extract_providers($response_data[xmldata]);
			} else {
				$result['error']	= $response_data['error'];
			}
			
			// response
			$result['response'] = $response_data['all'];
		}
		
		return $result;
	}
	
	
	
	function box_statistic($boxname){
		global $HTTP_SESSION_VARS;
	
		// total box
		$dbres		= $this->ra_db->select($boxname,"COUNT(*) as TOTAL");
		if ($dbres){
			$total = @mysql_result($dbres,0,"TOTAL");
			$box_stats[TOTAL] = $total;
		}
		// total per folder
		$dbres = $this->ra_db->select("$boxname b,metadata m","b.FOLDER,count(b.folder) as TOTAL","b.status <> 'deleted' AND m.IDENTIFIER = b.IDENTIFIER","","","","b.Folder");
		if ($dbres){
			while ($row = @mysql_fetch_array($dbres)){
				$folder = $row['FOLDER'];
				$total2 = $row['TOTAL'];
				$box_stats[FOLDER][$folder] = $total2;
			}
		}
		$sessname = "sess_".$boxname."_stats";
			
		// save into session
		session_register($sessname);
		$HTTP_SESSION_VARS[$sessname] = $box_stats;
	}

	function update_outbox($identifier,$status,$folder){
		
		switch($status){
			case "new"		:	$this->ra_db->insert("outbox","`TYPE`,`IDENTIFIER`,`STATUS`,`FOLDER`,`DATEMODIFIED`","'metadata','$identifier','new','$folder',current_timestamp()");
								break;
			case "success"	:	$this->ra_db->update("outbox","`STATUS`='$status',`FOLDER`='sent',`DATEMODIFIED`=current_timestamp()","IDENTIFIER like '$identifier'");
								break;
		}
	}
	
	function update_status_boxQueue($status){
					//echo "<br/>Update status == $status <br/>";
					$publisher	= $this->ra_requestFormatter->get_current_publisherTarget();
					$file_fragment	= $this->req_posting_file_info['file_fragment'];
					$path	= implode("/",explode("=",$file_fragment));
					$this->ra_requestFormatter->set_status_queue_box("update",$status,$publisher,$path);

	}
	
	function get_option_verb_postingFile(){
		$rs_request = $this->ra_requestFormatter->request_file_Job();
		//foreach($rs_request as $index => $value) echo "--x--$index ==> $value <br/>";
			
		if($rs_request == null){
			$result['error'] = "Failed to determine kind of operation, <i>neither post file fragment nor merge file fragment </i>";
		}else if($rs_result['status'] == "empty"){
			$result['error']	= "No file to posting";
		}else if(!empty($rs_request['error'])){
			$result['error']	= $rs_request['error'];
		}else{
			$this->req_posting_file_info	= $rs_request;
			$result['status'] = $rs_request['status'];
		}
		
		return $result;
	}
	
	function update_log_posting(){
		$filename 			= $this->req_posting_file_info['filename'];
		$file_posting 		= $this->req_posting_file_info['file_posting'];
		$file_fragment 	= $this->req_posting_file_info['file_fragment'];
		$no_fragment 	= $this->req_posting_file_info['no_fragment'];
		$c_fragment		= $this->req_posting_file_info['c_fragment'];
		$temp_folder 	= $this->req_posting_file_info['tmp_folder'];
		
		$log_file			= 	"$temp_folder/job.log";
		$no_fragment++;
		$content			= 	"$filename\n".
									"$file_posting\n".
									"$file_fragment\n".
									"$no_fragment\n".
									"$c_fragment\n".
									"$temp_folder";
									//echo "<br/>Content-RA ; $content  [".$this->req_posting_file_info['no_fragment']."]<br/>";
		$fp = fopen($log_file,"w");
		fwrite($fp,$content);
		fclose($fp);
		
	}
	
	function clean_temporaryPostingFile(){
			$publisher	= $this->ra_requestFormatter->get_current_publisherTarget();
			$file_fragment	= $this->req_posting_file_info['file_fragment'];
			
			$path	= implode("/",explode("=",$file_fragment));
			
			$dbres = $this->ra_db->select("queue","temp_folder","path like '$path'  and  DC_PUBLISHER_ID  = '$publisher' ");
			$temp_folder	= trim(@mysql_result($dbres,0,"temp_folder"));
			
			if(!empty($temp_folder))
					if(file_exists($temp_folder)){
						$this->clean_temporaryPostingFile_by_tempFolder($temp_folder);	
					}
					
	}
	
	function clean_temporaryPostingFile_by_tempFolder($temp_folder){
		if(!empty($temp_folder)){
					if($h_dir	= @opendir($temp_folder)){
							
							while($entry = readdir($h_dir)){
									if(($entry != ".") && ($entry != ".."))
											unlink("$temp_folder/$entry");
							}				
							closedir($h_dir);
							
							do{
								@rmdir($temp_folder);
								$arr_node	= explode("/",$temp_folder);
								array_pop($arr_node);
								$temp_folder	= implode("/",$arr_node);
							}while(count($arr_node) > 3);
							
					}
			}
	}
	
}
?>