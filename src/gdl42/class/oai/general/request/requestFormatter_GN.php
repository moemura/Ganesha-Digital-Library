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
 
 if (preg_match("/requestFormatter_GN.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class requestFormatter_GN extends requestFormatter {
	
	function requestFormatter_GN(){
		$this->init();
	}
	
	function request_to_hub($verb,$id_repository=""){
		global $HTTP_SESSION_VARS,$gdl_sync;

		$lastToken		= (int)$_SESSION['LastToken'];
		
		if($gdl_sync['sync_hub_server_name'] == $this->rf_sync['sync_hub_server_name']){
			$hub_server		= $this->rf_sync['sync_hub_server_name'];
			$script_oai		= $this->rf_sync['sync_oai_script'];
		}else{
			$hub_server		= $gdl_sync['sync_hub_server_name'];
			$script_oai		= $gdl_sync['sync_oai_script'];
		}
		$this->verb		= $verb;
		
		$lastToken		= (!ereg("^[0-9]+$",$lastToken))?0:$lastToken;
		
		$this->metadataPrefix = "general";
		
		// start request
		switch($verb){

			/* OAI standard */
			case "ListRecords"			:	$HEADER	= $this->requestFormatter_ListRecords($hub_server,$script_oai,$lastToken);
											break;
										
			case "GetRecord"			:	$HEADER	= $this->requestFormatter_GetRecord($hub_server,$script_oai,$id_repository);
											break;
										//
			case "ListIdentifiers" 		:	$HEADER	= $this->requestFormatter_ListIdentifiers($hub_server,$script_oai,$lastToken);
											break;
										//
			case "Identify"				:	$HEADER	= $this->requestFormatter_Identify($id_repository);
											break;
										
			case "ListSets"				:	$HEADER	= $this->requestFormatter_ListSets($id_repository);
											break;
										
			case "ListMetadataFormats"	:	$HEADER	= $this->requestFormatter_ListMetadataFormats($hub_server,$script_oai,$id_repository);
											break;
			
			/* Extended verb */								
			case "Connect"				:	$HEADER	= $this->requestFormatter_Connect($hub_server,$script_oai);
											break;
			
			case "ListProviders"		:	$HEADER	= $this->requestFormatter_ListProviders($hub_server,$script_oai);
											break;
											
			case "PutListRecords"		:	$HEADER	= $this->requestFormatter_PutListRecords($hub_server,$script_oai);
											break;
			
			case "PutFileFragment"		:	$HEADER	= $this->requestFormatter_PutFileFragment($hub_server,$script_oai);
											break;
			
			case "MergeFileFragments"		:	$HEADER	= $this->requestFormatter_MergeFileFragments($hub_server,$script_oai);
											break;
											
			case "RemoteLogin"			:	$HEADER	= $this->requestFormatter_RemoteLogin($hub_server,$script_oai);
											break;
											
			default						:	$HEADER	=	"error";
											break;
		}

		$HTTP_SESSION_VARS[sess_val_request_xml]	= $REQUEST;
		//echo "Header_GN : [$HEADER]";
		return $HEADER;
	}
	
	
	function requestFormatter_Identify($identify){
		
		$dbres		= $this->rf_db->select("repository","host_url,oai_script","nomor = $identify");
		
		if(@mysql_num_rows($dbres) == 1){
				$row 	= mysql_fetch_row($dbres);
				$hub 	= trim($row[0]);
				$script	= trim($row[1]);
				if(!empty($hub) && !empty($script)){
					
					$array	= array("verb","PHPSESSID");
					
					$this->init_request_argument($array);
					$REQUEST = $this->build_request($array);
					
					$URI 	= "http://$hub/$script?$REQUEST";
					$HEADER = "GET $URI HTTP/1.0\r\n\r\n";
				}
		}
		
		if(empty($HEADER))
			$HEADER	=	"error";
		
		return $HEADER;
	}
	
	function requestFormatter_ListRecords($hub_server,$script_oai,$lastToken){
		
		$array = array("verb","PHPSESSID","from","until","set","limit","resumptionToken");

		$this->init_request_argument($array);
		
		$REQUEST = $this->build_request($array);
		$URI = "http://$hub_server/$script_oai?$REQUEST";										
		$HEADER = "GET $URI HTTP/1.0\r\n\r\n";
		
		return $HEADER;
	}
	
	function requestFormatter_GetRecord($hub_server,$script_oai,$identifier){
		
		$array = array("verb","PHPSESSID");
		
		$this->init_request_argument($array);
		$REQUEST = $this->build_request($array);
		
		if(!empty($identifier))
			$REQUEST .= "&identifier=$identifier";
			
		$URI = "http://$hub_server/$script_oai?$REQUEST";										
		$HEADER = "GET $URI HTTP/1.0\r\n\r\n";
		
		return $HEADER;
	}
	
	function requestFormatter_ListIdentifiers($hub_server,$script_oai,$lastToken){

		$array = array("verb","PHPSESSID","set","resumptionToken");
		
		$this->init_request_argument($array);
		$REQUEST = $this-> build_request($array);
		
		$URI = "http://$hub_server/$script_oai?$REQUEST";
		$HEADER = "GET $URI HTTP/1.0\r\n\r\n";
		
		return $HEADER;
	}
	
	function requestFormatter_ListSets($identify){

		$dbres		= $this->rf_db->select("repository","host_url,oai_script","nomor=$identify");

		if(@mysql_num_rows($dbres) == 1){
				$row 	= @mysql_fetch_row($dbres);
				$hub 	= trim($row[0]);
				$script	= trim($row[1]);
				if(!empty($hub) && !empty($script)){
					$array	= array("verb","PHPSESSID");
					
					$this->init_request_argument($array);
					$REQUEST = $this-> build_request($array);
		
					$URI 	= "http://$hub/$script?$REQUEST";
					$HEADER = "GET $URI HTTP/1.0\r\n\r\n";
				}
		}	
		if(empty($HEADER))
			$HEADER	=	"error";
		
		return $HEADER;
	}
	
	function requestFormatter_ListMetadataFormats($hub_server,$script_oai,$identify){
		
		$array		= array("verb","PHPSESSID");
		
		$this->init_request_argument($array);
		$REQUEST 	= $this-> build_request($array);

		if(!empty($identify))
			$REQUEST .= "&identifier=$identify";
			
		$URI 		= "http://$hub_server/$script_oai?$REQUEST";
		$HEADER 	= "GET $URI HTTP/1.0\r\n\r\n";
		
		return $HEADER;
	}
	
	
	function requestFormatter_Connect($hub_server,$script){
		
		$array = array("verb","epochTime","providerId","providerSerialNumber");	
		
		$this->init_request_argument($array);			
		$REQUEST 	= $this->build_request($array);
		
		$URI 		= "http://$hub_server/$script?$REQUEST";
		$HEADER 	= "GET $URI HTTP/1.0\r\n\r\n";
		
		return $HEADER;
	}
	
	function requestFormatter_ListProviders($hub_server,$script){
		$array = array("verb","PHPSESSID","limit","resumptionToken");
		
		$this->init_request_argument($array);
		$REQUEST 	= $this-> build_request($array);		 
		
		$URI 		= "http://$hub_server/$script?$REQUEST";
		$HEADER 	= "GET $URI HTTP/1.0\r\n\r\n";
		
		return $HEADER;
	}
	
	function requestFormatter_PutListRecords($hub_server,$script){
		$array 			= 	array("verb","PHPSESSID","countRecords","resumptionToken");
		
		$request_data 	= $this->request_data_PutListRecords();
		$DATA 			= "data=".urlencode($request_data);
		$LEN			= strlen($DATA);
		
		$this->init_request_argument($array);
		$REQUEST 		= $this-> build_request($array);
				
		$URI 			= "http://$hub_server/$script?$REQUEST";
		$HEADER 		= "POST $URI HTTP/1.0\r\n";
		$HEADER 		.= "Content-type: application/x-www-form-urlencoded\r\n"; 
		$HEADER 		.= "Content-length: $LEN\r\n\r\n"; 
		
		$HEADER			= $HEADER.$DATA;
		
		return $HEADER;
	}
	
	
	function requestFormatter_PutFileFragment($hub_server,$script_oai){
		$array		= array("verb","PHPSESSID","filename");
		
		$request_data 	= $this->request_file_Job();
		$file_posting	= $request_data['file_posting'];
		$file_posting	= str_replace("/","=",$file_posting);
		$this->filename	= "$file_posting=$request_data[no_fragment]";
		
		$this->init_request_argument($array);
		$REQUEST 		= $this-> build_request($array);
		
		# URI
		$URI 			= "http://$hub_server/$script_oai?$REQUEST";


		# boundary 
		srand((double)microtime()*1000000);
		$boundary = "---------------------------".substr(md5(rand(0,32000)),0,10);
		
		$file_type			= $request_data['file_type'];
		$file_content		= $request_data['data'];
		
		$data = "--$boundary\r\n";
		$data .= "Content-Disposition: form-data; name=\"FILE\"; filename=\"".$this->filename."\"\r\n";
		$data .= "Content-Type: $file_type\r\n\r\n";
		$data .= "$file_content\r\n";
		$data .= "--$boundary--\r\n\r\n";

	
	$HEADER  = "POST $URI HTTP/1.0\r\n";
	$HEADER .= "Content-Type: multipart/form-data; boundary=$boundary\r\n";
	$HEADER .= "Content-Length: ".strlen($data)."\r\n\r\n";
	
	$HEADER .= $data;

	return $HEADER;		
	}
	
	function requestFormatter_MergeFileFragments($hub_server,$script_oai){
		$array		= array("verb","PHPSESSID");
		
		$this->init_request_argument($array);
		$REQUEST 		= $this-> build_request($array);
				
		$URI 			= "http://$hub_server/$script_oai?$REQUEST";
		
		// generate request 
		$request_data 	= $this->request_file_Job();
		$file_posting	= $request_data['file_posting'];
		$file_posting	= (ereg("files/",$file_posting))?$file_posting:"files/$file_posting";
		$request_data 	= $this->request_data_MergeFileFragments($request_data['c_fragment'],$file_posting);
		
		$DATA = "data=".urlencode($request_data);
			
		$HEADER = "POST $URI HTTP/1.0\r\n";
		$HEADER .= "Content-type: application/x-www-form-urlencoded\r\n"; 
		$HEADER .= "Content-length: " . strlen($DATA) . "\r\n\r\n"; 
		
		$HEADER .= $DATA;
		
		return $HEADER;
	}
	
	function requestFormatter_RemoteLogin($hub_server,$script){
		$array			= array("verb","PHPSESSID","remoteUser","remoteName");
		
		$this->init_request_argument($array);
		$REQUEST 	= $this-> build_request($array);		 
		
		$URI 		= "http://$hub_server/$script?$REQUEST";
		$HEADER 	= "GET $URI HTTP/1.0\r\n\r\n";
		
		return $HEADER;
	}
	
	
	function request_data_PutListRecords(){
		
		$limit 	= $this->rf_sync['sync_count_records'];
		
		$limit	= (!ereg("^[0-9]+$",$limit))?10:$limit;

		// get metadata	
		
		//$this->rf_db->print_script = true;
		$dbres	= $this->rf_db->select("metadata m,outbox o",
										  "m.*,
										  	o.status,
											o.DATEMODIFIED",
										  "m.IDENTIFIER = o.IDENTIFIER AND o.FOLDER = 'outbox'",
										  "",
										  "",
										  "0,$limit"
										 );
	//$this->rf_db->print_script = false;
		$count	= 0;
		if (@mysql_num_rows($dbres) > 0){
			while ($row = @mysql_fetch_array($dbres)){
				// generate elements
				//echo "HEAD : ".$row['identifier']."<br/>\n";
				
				$row['identifier']	= $this->clean_identifier($row['identifier']);
				$row['xml_data']	= str_replace("<br>","<br/>",$row['xml_data']);

				$element_header 	= $this->element_header_oaipmp($row['identifier'],$row['status'],$row['DATEMODIFIED']);
				$element_metadata 	= $this->element_metadata_oaipmp($row['xml_data']);
				$element_record 	= $this->element_record_oaipmp($element_header,$element_metadata);
				
				$records .= $element_record;
				$count++;
			}
			
			$this->countRecords	= $count;
			$request_elements 	= $this->element_PutListRecords_oaipmp($records);
			
			// generate request 
			$request_data 		= $this->rf_metadata->generate_request_oaipmp($request_elements);
		}
		//echo "REQ[$request_data]<br>";
		return $request_data;		
	}
	
	function clean_identifier($identifier){
		$bad_token = array("<b>","<i>","<u>","<br>","</b>","</i>","</u>","<br/>","<b","<i","<u","b>","i>","u>");
		$c	= count($bad_token);
		
		for($i=0;$i<$c;$i++){
			$identifier = eregi_replace($bad_token[$i],"",$identifier);
		}
		
		return $identifier;
	}
	function element_header_oaipmp($identifier,$status,$datestamp){
	
		$header = "<header>\n".
						"<identifier>$identifier</identifier>\n".
						"<status>$status</status>\n".
						"<datestamp>$datestamp</datestamp>\n".
				"</header>\n";
	
		return $header;
	}
	
	// element metadata
	function element_metadata_oaipmp($xmldata){
	
		$metadata = "<metadata>\n".
						"$xmldata \n".
					"</metadata>\n";

		return $metadata;      
	}
	
	// element record
	function element_record_oaipmp($header,$metadata){
		return " <record>\n$header $metadata</record>\n";
	}

	function element_PutListRecords_oaipmp($element_records){
		return "  <PutListRecords>\n$element_records</PutListRecords>\n";
	}

	// sebelum memakai fungsi ini untuk dipakai dalam eksekusi verb
	// PutFileFragment atau MergeFileFragments, lakukan pemanggilan
	// fungsi ini dahulu untuk mengetahui jenis eksekusi yang perlu dilakukan
	// untuk menangani pengiriman file
	
	function request_file_Job(){
		
		$curr_publisher = $this->get_current_publisherTarget();

		if($curr_publisher == null) return null;
		
		$dbres = $this->rf_db->select("queue","no,path,status,temp_folder","dc_publisher_id = '$curr_publisher' and status <> 'success' and status <> 'failed'","","","0,1");

		if(@mysql_num_rows($dbres) != 1) {
				$result['error']="No available posting file ";
				return $result;
		};
		
		$path				= trim(@mysql_result($dbres,0,"path"));
		$status 			= trim(@mysql_result($dbres,0,"status"));
		$temp_folder		= trim(@mysql_result($dbres,0,"temp_folder"));
		
		if(empty($path) || empty($status) ) return null;
		if(($status == "fragmented") && empty($temp_folder)) return null;
		
		if($status == "queue"){ //echo "QUEUE-0-RF<br/>";
			 $size_fragment = trim($this->rf_sync['sync_fragment_size']);
			 
			 $size_fragment = (ereg("^[0-9]+$",$size_fragment) && ($size_fragment > 10000))?$size_fragment:10000;
			 
			 // cek file
			 $fp = fopen("$path","r");
			 if($fp){//echo "QUEUE-1-RF<br/>";
			 	$rs_fragment = $this->create_fragment($fp,$path,$curr_publisher,$size_fragment);
				if($rs_fragment != null){//echo "QUEUE-2-RF<br/>";
						// ubah status menjadi fragmented
						$this->set_status_queue_box("update","status = 'fragmented', temp_folder = '$rs_fragment[tmp_folder]'",$curr_publisher,$path);
						
						$result = $rs_fragment;	
						$result['status']	= "fragmented";
						$success = 1;
				}
			 }
		}else if($status == "fragmented"){
				$success = 1;
				
				// baca  log
				$arr_content = file("$temp_folder/job.log");
				for($i=0;$i<count($arr_content);$i++)
					$arr_content[$i]	= trim($arr_content[$i]);
					
				//bandingkan nomor fragment  dengan maksimum fragment
				if((int)$arr_content[3] <= (int)$arr_content[4]){
					$no_fragment 	= $arr_content[3];
					$file_fragment	= "$temp_folder/$arr_content[2]=$no_fragment";
					
					// baca file fragment 
					$fp = fopen("$file_fragment","r");
						$data	= fread($fp,filesize($file_fragment));
					fclose($fp);
					

					$result['filename'] 			= $arr_content[0];
					$result['file_posting']		= $arr_content[1];
					$result['file_fragment']	= $arr_content[2];
					$result['no_fragment']	= $arr_content[3];
					$result['c_fragment']		= $arr_content[4];
					$result['tmp_folder']		= $temp_folder;
					$result['data']				= $data;
					$result['status']				= "fragmented";
					$result['file_type']			= filetype("$file_fragment");
					
				}else{
					// lakukan eksekusi merge
					$result['status'] = "merge";
					$this->set_status_queue_box("update","status = 'merge'",$curr_publisher,$path);
				}
				
		}else if ($status == "merge"){
				$success = 1;
				
				// baca log
				$arr_content = file("$temp_folder/job.log");
				for($i=0;$i<count($arr_content);$i++)
					$arr_content[$i]	= trim($arr_content[$i]);
				
					$result['filename'] 			= $arr_content[0];
					$result['file_posting']		= $arr_content[1];
					$result['file_fragment']	= $arr_content[2];
					$result['no_fragment']	= $arr_content[3];
					$result['c_fragment']		= $arr_content[4];
					$result['tmp_folder']		= $temp_folder;
					$result['status']				= "merge";
		}
		
		 if($success != 1){
			 	$this->set_status_queue_box("update","status = 'failed'",$curr_publisher,$path);
				return null;
		}
		
		return $result;
	}


	function create_fragment($fp,$path,$publisher,$size_fragment){
		$repo_dir = trim($this->rf_sys["repository_dir"]);
		if(empty($repo_dir)){
			fclose($fp);
			return null;
		}
		
		$arr_repo_dir		= explode("/",$repo_dir);
		$parent_dir	= $arr_repo_dir[0];
		
		// convert path
		$path = str_replace("/","=",$path);
		
		// cek and make temporary folder
		$arr_folder_tmp = array("$parent_dir","tmp","posting","$publisher","$path");
		for($i=0;$i<5;$i++){
			$tmp_path = ($i==0)?$arr_folder_tmp[0]:"$tmp_path/$arr_folder_tmp[$i]";
			if(!file_exists($tmp_path)){
				mkdir($tmp_path,0777);
			}
		}
		
		// make fragment file
		$file_fragment_master = "$tmp_path/$path=";
		$failed = 0;
		while(!feof($fp) && !$failed){
			$no++;
			$filename = $file_fragment_master.$no;
			if($fp_fragment = @fopen($filename,"w")){
					$f_read = fread($fp,$size_fragment);
					fwrite($fp_fragment,$f_read);
					fclose($fp_fragment);
			} else $failed = 1;
		}
		fclose($fp);
		
		if($failed) return null;
		
		// write file log job
		$arr_path = explode("=",$path);
		array_shift($arr_path);
		
		$file_posting 		= implode("/",$arr_path); 		// disk1/0/xxxxx.ext
		$file_master		= array_pop($arr_path);			// xxxxx.ext
		$file_fragment	= $path;							// disk1=0=xxxxx.ext
		$no_to_post		= 1;
		$c_fragment		= $no;

		$content = 	"$file_master\n".
							"$file_posting\n".
							"$file_fragment\n".
							"$no_to_post\n".
							"$c_fragment";
		
		// tulis log 
		$fp = fopen("$tmp_path/job.log","w");
		fwrite($fp,$content);
		fclose($fp);
		
		$result['filename'] 			= $file_master;
		$result['file_posting']		= $file_posting;
		$result['file_fragment']	= $file_fragment;
		$result['no_fragment']	= $no_to_post;
		$result['c_fragment']		= $c_fragment;
		$result['tmp_folder']		= $tmp_path;
		
		return $result;
	}
	
	function set_status_queue_box($action,$i_change,$publisher,$path){

			if($action == "update"){
			$this->rf_db->update("queue",$i_change,"path like '$path' and dc_publisher_id = '$publisher'");
			}else{
				$this->rf_db->delete("queue","path=$path and dc_publisher_id = '$publisher'");
			}
	}
	
	function get_current_publisherTarget(){
		$id = trim($this->rf_sync['sync_repository_id']);
		
		if(empty($id)) return null;
		
		$dbres = $this->rf_db->select("repository m,publisher p","m.id_publisher as curr_publisher","m.nomor = $id and m.id_publisher = p.dc_publisher_id");
		
		if(@mysql_num_rows($dbres)  != 1) return null;
		
		$curr_publisher = @mysql_result($dbres,0,"curr_publisher");
		
		return $curr_publisher;
	}
	
	function request_data_MergeFileFragments($count,$filename){
			$request_element = "<MergeFileFragments>".
													"<count>$count</count>".
													"<filename>$filename</filename>". 
											"</MergeFileFragments>";
											
	 return  $this->rf_metadata->generate_request_oaipmp($request_element);
	}
	
}
?>