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
 
 if (preg_match("/elementResponse_GN.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class elementResponse_GN extends elementResponse {
	
	function elementResponse_GN(){
		$this->init();
	}
	
	function elementGetRecord($identifier){
		
		$dbres 	= $this->er_db->select("metadata","STATUS, IDENTIFIER, DATE_MODIFIED as DATEMODIFIED, TYPE, XML_DATA","identifier LIKE '$identifier'");
		
		$row	= @mysql_fetch_array($dbres);
		if($row != FALSE){
			$header   	= $this->element_header($row['STATUS'],$row['IDENTIFIER'],$row['DATEMODIFIED'],$row['TYPE']);
			$metadata	= $this->element_metadata($row['XML_DATA']);
	
			$record 	= "<record>\n $header $metadata </record>\n";
			$element	= "<GetRecord>\n$record</GetRecord>\n";
		}else{
			$hub_server	= $this->er_sync['sync_hub_server_name'];
			$element 	= $this->error_element("idDoesNotExist","No matching identifier in $hub_server repository");
		}
		
		return $element;
	}

	function elementListRecords($request_query){
		$set				= $request_query['set'];		
		$resumptionToken	= $request_query['resumptionToken'];
		$from				= $request_query['from'];
		$until				= $request_query['until'];
		$limit				= $request_query['limit'];
		
		if (preg_match("/under:node:/",$set)){
			
			$node 		= substr($set,11);
			$path 		= $this->node_path($node);

			$element 	= $this->get_records($path,$resumptionToken,$from,$until,$limit);
			
		} else {

			$path 		= $this->node_path(0);
			$element 	= $this->get_records($path,$resumptionToken,$from,$until,$limit);

		}
		return $element;
	}

	function elementConnect($request_query){
		global $_SERVER;
		
		$providerId				= $request_query['providerId'];
		$providerSerialNumber	= $request_query['providerSerialNumber'];
		$epochTime				= $request_query['epochTime'];
		
		// Check epochTime
		// if more than 1 day, it is invalid
		$now 		= date("U");
		$delta 		= $now - $epochTime;
		$oneday 	= (24 * 60 * 60);
		
		if ($delta >= $oneday){
			
			$error_code		= "badEpochTime";
			$error_msg		= "epochTime is longer than 1 day from repository epochTime $now.";
			$element 		= $this->error_element($error_code,$error_msg);
			
			return $element;
		}
		
		$dbres	= $this->er_db->select("publisher","DC_PUBLISHER_SERIALNO, DC_PUBLISHER_NETWORK","DC_PUBLISHER_ID='$providerId'");
		
		if ($dbres){
			if (@mysql_num_rows($dbres)>0){
				
				$SerialNumber 		= trim(@mysql_result($dbres,0,"DC_PUBLISHER_SERIALNO"));
				$providerNetwork 	= @mysql_result($dbres,0,"DC_PUBLISHER_NETWORK");
				$mdString 			= "$SerialNumber-$epochTime";
				$validSerialNumber  = md5($mdString);
				
				// check serial number
				if (empty($SerialNumber)) {
					
					$error_code		= "badSerialNumber";
					$error_msg		= "Invalid providerSerialNumber.";
					$element 		= $this->error_element($error_code,$error_msg);

				} elseif ($providerSerialNumber == $validSerialNumber){
					
					// success, create session
					$sess_id		= $this->er_session->oaipmp_create_session($providerId,$providerNetwork);
					$element		= $this->build_connectResponse($providerId,$providerNetwork,$sess_id);
					
				} else {
					
					$error_code		= "badSerialNumber";
					$error_msg		= "Invalid providerSerialNumber.";
					$element 		= $this->error_element($error_code,$error_msg);

				}
				
			} else {
				
				$error_code		= "noRecordsMatch";
				$error_msg		= "providerId $providerId is not found.";
				$element 		= $this->error_element($error_code,$error_msg);

			}
		} else {
			
			$error_code		= "systemError";
			$error_msg		= @mysql_error();
			$element 		= $this->error_element($error_code,$error_msg);

		}
		
		return $element;
	}
	
	function elementListProviders($request_query){
		
		global $HTTP_SESSION_VARS;
	
		$limit				= $request_query['limit'];
		$resumptionToken	= $request_query['resumptionToken'];
		
		$cursor 			= $limit * $resumptionToken;
		
		$selective	= "DC_PUBLISHER_HUBSERVER <> '$HTTP_SESSION_VARS[sess_providerId]'";
		$dbres		= $this->er_db->select("publisher","*",$selective,"","","$cursor,$limit");
		if ($dbres){
			if (@mysql_num_rows($dbres)>0){
	
				while ($row = @mysql_fetch_array($dbres)){
					
					$pubid = $row['DC_PUBLISHER_ID'];
					
					// generate element
					while (list($key,$val) = each($row)){
						if (!is_numeric($key)){
							$elm = strtolower($key);
	
							$value = $val;
							
							// generate element
							$record .= "<$elm>$value</$elm>\n";
						}			
					}
					
					$list .= "<record>$record</record>\n";
					
					// reset record
					$record = "";
				}
				
				// resumption token
				$total 				= $this->total_providers($selective);
				$resumption_token 	= $this->element_resumptiontoken($resumptionToken, $limit,$total);
				
				// generate element ListProviders
				
				$element = "<ListProviders>$list $resumption_token</ListProviders>\n";
			} else {
				
				$element = $this->error_element("noRecordsMatch","Record not found");
				
			}		
		} else {
			
			$element = $this->error_element("dbError","Our repository has failed fetch record from database");
			
		}
		
		return $element;
	}
	
	function elementPutListRecords($request_query){
		global $HTTP_SESSION_VARS;
		
		$p_providerId		= $HTTP_SESSION_VARS['sess_providerId'];
		$p_countRecords		= $request_query['countRecords'];


		$data = $_POST['data'];
		$data = stripslashes(urldecode($data));
		
			// extract the records
		$put_result 	= $this->er_import->extract_record($data,"inbox","general","posting");
		
		// generate response
		$p_count			= $put_result['count'];
		$p_elementPosting	= $put_result['post_element'];
		
		if(is_array($p_elementPosting)){
			$num_element 	= count($p_elementPosting);

			for($id_element = 0; $id_element < $num_element;$id_element++){
				$sub_element	= $p_elementPosting[$id_element];
				$sub_identifier	= $sub_element['identifier'];
				$sub_size		= $sub_element['size'];
				$sub_status		= $sub_element['status'];
				
				$p_element	.= $this->format_responsePutListRecords($sub_identifier,$sub_size,$sub_status);

			}
			$element			= $this->build_putListRecordsResponse($p_providerId,$p_count,$p_countRecords,$p_element);
		}
		
		return $element;
	}
	
	function elementPutFileFragment($request_query){
		global $HTTP_SESSION_VARS,$HTTP_POST_FILES;
				
		if (is_uploaded_file($HTTP_POST_FILES[FILE][tmp_name])){
			$filename 			= $HTTP_POST_FILES[FILE][name];
			$filesize 			= $HTTP_POST_FILES[FILE][size];
			$p_providerId	= $HTTP_SESSION_VARS['sess_providerId'];
			$dest_path 		= $this->node_dir($filename,$p_providerId);

			$destination 		= "$dest_path[temp_path]/$dest_path[filename]";
			
			$filestatus =  (move_uploaded_file($HTTP_POST_FILES[FILE][tmp_name],$destination))?"success":"failed";

		}else $filestatus = "no_upload";
		
		if($filestatus == "failed"){
			$element = $this->error_element("badMoveUploadFile","Our repository could not move your posting file to temporary destination");
		}else if($filestatus == "no_upload"){
			$element = $this->error_element("badPostingFile","Our repository did not receive posting file");
		}else{
			$element = $this->build_elementPutFileFragment($filename,$filesize,$filestatus);
		}
		
		return $element;
	}
	
	function elementMergeFileFragments($request_query){
		global $HTTP_SESSION_VARS;
		
		$p_providerId		= $HTTP_SESSION_VARS['sess_providerId'];

		$data = $_POST['data'];
		$data = stripslashes(urldecode($data));
		
		$xml 		= $this->er_metadata->readXML($data);
		
		//echo "\n [DATA-x] [$data] \n\n";
		//foreach($xml as $index => $value)
			//$posting .= "\n ====x==> $index ---> $value[0] \n";
						
		$filename 	= $xml['MERGEFILEFRAGMENTS.FILENAME'][0];
		$count 		= $xml['MERGEFILEFRAGMENTS.COUNT'][0];
		
		$merge		= $this->merge_file($filename,$count,$p_providerId);
		
		if(empty($merge['error'])){
			$element = $this->build_elementMergeFileFragments($filename,$count,$merge['fragments'],$merge['size'],$merge['status']);
		}else $element = $merge['error'];
		
		//$x_file	= "tes-".date("U").".txt";
		//$fp = fopen("files/tmp/receive/$x_file","w");
		//fwrite($fp,$posting."\n\n$element");
		//fclose($fp);
		
		return $element;
	}
	
	function elementRemoteLogin($request_query){
		$user_id	= $request_query['remoteUser'];
		$username	= urldecode($request_query['remoteName']);
		
		if(!empty($user_id) && !empty($username)){
			$element	= $this->build_response_remote($user_id,$username);
			//setcookie("Cookie_Remote","Ngetes cookie");
		}else{
			$element	= $this->error_element("badUser","Our repository received bad signature user");
		}
		
		return $element;
	}
		
	function build_response_remote($remote_user,$remote_name){
		global $HTTP_SESSION_VARS;
		
		$sess_id			= $HTTP_SESSION_VARS['session_client'];
		$providerId			= $HTTP_SESSION_VARS['sess_providerId'];
		$providerNetwork	= $HTTP_SESSION_VARS['sess_providerNetwork'];
		$start_session		= $HTTP_SESSION_VARS['sess_client_start'];
		$remote_name		= urlencode($remote_name);
		
		$epochTime		= date("U");
		$signature		= $this->er_session->give_signatureRemoteLogin($sess_id,$remote_user);
		$md_signature	= $this->er_session->give_mdSignature($signature,$epochTime);
		
		$element = 	"<RemoteLogin>\n".
						"<Signature>$signature</Signature>\n".
						"<EpochTime>$epochTime</EpochTime>\n".
						"<MdSignature>$md_signature</MdSignature>\n".
						"<providerId>$providerId</providerId>\n".
						"<providerNetwork>$providerNetwork</providerNetwork>\n".
						"<startSession>$start_session</startSession>\n".
						"<remoteUser>$remote_user</remoteUser>\n".
						"<remoteName>$remote_name</remoteName>\n".
					"</RemoteLogin>";
		return $element;
	}
	
	function get_records($path,$token,$from="",$until="",$limit){
		
		$id_repository	= $this->er_publisher['id'];
		
		$limit 	= (empty($limit))?20:$limit;
		$token	= (empty($token))?0:$token;
				
		$cursor = $token * $limit;
		
		$filter_identifier	= (empty($id_repository))?"":"AND IDENTIFIER LIKE '$id_repository-%'";
		$filter_identifier	= ($this->er_sys["role"] == "HUB")?"":$filter_identifier;
		
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
			
		$selective	= "PREFIX LIKE 'general' AND PATH LIKE '$path%' $sorting $filter_identifier";
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
									
		if ($dbres){
			if (@mysql_num_rows($dbres)>0){
				while($row = @mysql_fetch_array($dbres)){ 
					$header   = $this->element_header($row[STATUS],$row[IDENTIFIER],$row[DATEMODIFIED],$row[TYPE]);
					
					if ($row[STATUS] != "deleted"){
						$metadata = $this->element_metadata($row[XMLDATA]);
					}
								
					$record = "\n<record> $header $metadata </record>\n";
					$records .= $record;
				}
				
				// get resumption token
				$total = $this->get_total_identifiers($selective);
				$resumption_token = $this->element_resumptiontoken($token, $limit,$total);
				
				$element = "\n<ListRecords>\n $records \n $resumption_token \n</ListRecords>\n";
	  
			} else {
				$element = $this->error_element("noRecordsMatch","Record not found");
			}
		} else {
			$element = $this->error_element("dbError",mysql_error());
		}
		
		return $element;
	}
	
	// element metadata
	function element_metadata($xmldata){
		return "\n<metadata>\n $xmldata \n</metadata>\n";     
	}
	
	function build_connectResponse($providerId,$providerNetwork,$sess_id){
		
		$connect = "\n<Connect>\n".
						"<sessionId>$sess_id</sessionId>\n".
						"<providerId>$providerId</providerId>\n".
						"<providerNetwork>$providerNetwork</providerNetwork>\n".
					"</Connect>\n";
					
		return $connect;
	}
	
	function total_providers($selective){

		$dbres	= $this->er_db->select("publisher","COUNT(*) as TOTAL",$selective);
		
		if ($dbres){
			if (@mysql_num_rows($dbres)>0){
				$total = @mysql_result($dbres,0,"TOTAL");
			} else {
				$total = 0;
			}
		} else {
			$total = 0;
		}
	
		return $total;
	}
	
	function build_putListRecordsResponse($providerId,$count,$countRecords,$element){
	
		if (empty($providerId)){
			$error[0] = $this->error_element("badSession","Disconnected from the hub server. Please reconnect.");
		}
		
		if ($countRecords != $count){
			$error[1] = $this->error_element("badCountRecords","Number of records received ($count) by hub server doesn't match with countRecords ($countRecords).");
		}
		
		if(empty($element)){
			$putlistrecords	= $this->error_element("noRecords","Record not found");
		}else if(empty($error[0]) && empty($error[1])){
			$putlistrecords	= "\n<PutListRecords>\n$element</PutListRecords>\n";	
		}else {	
			$putlistrecords	= "$error[0] $error[1]";
		}
		
		return $putlistrecords;
	}

	function format_responsePutListRecords($identifier,$size,$status){
		$element = "\n<header>\n".
						"<identifiers>$identifier</identifiers>\n".
						"<size>$size</size>\n".
						"<status>$status</status>\n".
					"</header>\n";
		return $element;
	}
	
	// element PutFileFragment
	function build_elementPutFileFragment($name,$size,$status){
			$element = "\n<PutFileFragment>\n".
									"<filename>$name</filename>\n".
									"<filesize>$size</filesize>\n".
									"<status>$status</status>\n".
								"</PutFileFragment>\n";
	 return $element;
	}
	
	// element response MergeFileFragments
	function build_elementMergeFileFragments($filename,$count,$fragments,$size,$status){		
	$element = "\n<MergeFileFragments>\n".
									"<filename>$filename</filename>\n".
									"<count>$count</count>\n".
									"<fragments>$fragments</fragments>\n".
									"<size>$size</size>\n".
									"<status>$status</status>\n".
							"</MergeFileFragments>\n";
	 return $element;
	}
	
	function merge_file($filename,$count,$publisherId){
		
		$parent_repo	= array_shift(explode("/",$this->er_sys['repository_dir']));
		$fname 			= str_replace("files/","",$filename);
		$f_fragment		= str_replace("/","=",$fname);
		$tmpname 		= str_replace("/","=",$fname);
		$nodedir 		= "$parent_repo/$publisherId";
		$tmpdir 		= "$parent_repo/tmp/receive/$publisherId/$tmpname";
		
		//$print .= "Temporary ; $tmpdir";
		if (file_exists($tmpdir)){
			
			// cek folder destination
			$dest 			= $nodedir."/".$fname;
			$arr_path		= explode("/",$dest);
			$filename 		= array_pop($arr_path);
			$c_arr_path		= count($arr_path);
			
			for($i=0;$i<$c_arr_path;$i++){
					$path_dest = ($i==0)?$arr_path[0]:"$path_dest/$arr_path[$i]";
					if(!file_exists($path_dest))
						mkdir($path_dest,0777);
					$print .= "\nPath_DEST : $path_dest \n";
			}
			
			// assembly fragment file in tmp folder
			$outfile	= "$path_dest/$filename";
			$infile	= "$tmpdir/$f_fragment";
			
			//$print .= "\n\n [OUT]$outfile\n\n";
			$fd 				= fopen($outfile,"w");
			$success 		= true;
			$total_size	= 0;
			

			for($i=1;($i<$count+1) && $success;$i++){
				
					$tmp_file = "$infile=$i";
					//$print .= "\n\n\nTMP_FILE :$tmp_file\n\n\n";
					if(file_exists("$tmp_file")){
						$size	= filesize($tmp_file);
						$total_size += $size;
						//$print .= "\n\nSize : $size\n\n";
						$f_frag	= fopen($tmp_file,"r");
							$data = fread($f_frag,$size);
							fwrite($fd,$data);
						fclose($f_frag);
						
						$frag_name	= basename($tmp_file);
						$fragments 	= ($i==1)?$frag_name:"$fragments, $frag_name";
						
						$counter++;
					
					}else{ $success = false;}
					
				}
			fclose($fd);

			//$x_file	= "tes-2-".date("U").".txt";
			//$fp = fopen("files/tmp/receive/$x_file","w");
			//fwrite($fp,$print);
			//fclose($fp);
		
			if ($counter == $count){
				$merge['fragments'] 	= $fragments;
				$merge['status'] 		= "success";
				$merge['size'] 			= $total_size;
				$this->clean_temporaryReceiveFile_by_tempFolder($tmpdir);
			} else {
				$merge['error'] 			= $this->error_element("badMerging","Please re posting again if you would like.");
			}
			
		}
		return $merge;
	}

	function node_dir($file,$publisherId){
		
		$path_repo	= $this->er_sys['repository_dir'];
		$parent_path_repo = array_shift(explode("/",$path_repo));
		$arr_path_file	= explode("=",$file);
		
		// remove file/..... or files/.....
		if(preg_match("/file/",$arr_path_file[0])) array_shift($arr_path_file);
		
		
		$no_fragment	= array_pop($arr_path_file);
		$tmp_folder_file	= implode("=",$arr_path_file);
		$filename			= array_pop($arr_path_file);
		//echo "\n\nFile-node_dir : $file \n\n\n";
		//echo "\n\nFolder-node_dir : $tmp_folder_file \n\n\n";
		//echo "\n\nFilename-node_dir : $filename \n\n\n";
		
		$path_temp	= array($parent_path_repo,"tmp","receive",$publisherId,$tmp_folder_file);
		
		$c_path_temp	= count($path_temp);
		for($i=0;$i<$c_path_temp;$i++){
				$node_dir_tmp	= ($i==0)?$path_temp[0]:"$node_dir_tmp/$path_temp[$i]";
				//echo "Node : $node_dir_tmp <br/>";
				if (!file_exists($node_dir_tmp)){
						mkdir ($node_dir_tmp,0777);
				}
		}
		
		$node['temp_path']	= $node_dir_tmp;
		$node['filename']		= $tmp_folder_file;
		
		if(!empty($no_fragment)) {$node['filename']		= "$tmp_folder_file=$no_fragment";}
		
		return $node;
	}

	function clean_temporaryReceiveFile_by_tempFolder($temp_folder){
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