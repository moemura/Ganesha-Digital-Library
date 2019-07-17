<?php
if (preg_match("/import.php/i",$_SERVER['PHP_SELF'])) {
    die();
}


class import{

	// Import providers
	function providers($data){
		global $gdl_sync,$gdl_metadata,$gdl_db;
		
		$xml 		= $gdl_metadata->read_xml($data);
	//	$gdl_sync	= $this->providers_xml_to_sync($xml);
		
		$id 		= $xml[DC_PUBLISHER_ID];
		$datemod 	= $xml[DC_PUBLISHER_DATEMODIFIED];
		
		// check

		$dbres=$gdl_db->select("publisher","DC_PUBLISHER_DATEMODIFIED","DC_PUBLISHER_ID='".$id."'");

		if (@mysqli_num_rows($dbres)>0){
			$row = mysqli_fetch_assoc($dbres);
			$mydatemod = $row["DC_PUBLISHER_DATEMODIFIED"];
			if ($datemod > $mydatemod){
				// update
				$message	= $this->publisher_edit($id,$xml);
				$status		= "updated";
			} else {
				// uptodate
				$status = "*";
			}
		} else {
				// add
				$this->publisher_add($xml);
				$status = "new";
		}

		
		$result['error']	= $message;
		$result['id']		= $id;
		$result['status'] 	= $status;
		
		return $result;
	}
	
	// Extract providers xml
	function extract_providers($data){
		global $gdl_metadata;
	
		$arr_data 		= explode("\n",$data);	
		$results[size] 	= strlen($data);
		
		$no = 0;
		
		while(list($key,$val) = each($arr_data)){
			
			$val = trim($val);
			
			// check start boundaries
			if (preg_match("/<record>/",$val)) {
				$metadata_start = 1;
				continue;
			}
			
			// check stop boundaries
			
			// -- for metadata
			if (preg_match("/</record>/",$val)){
				$no++;
				$metadata_start 	= 0;
				
				$metadata_string 	= $gdl_metadata->clear_badchars($metadata_string);
				$metadata_buff 		= "<record>$metadata_string</record>";
				$metadata_string 	= "";
				
				// import to database
				$import_result = $this->providers($metadata_buff);
				$received_id .= "<br/>&nbsp;&nbsp;$import_result[id] ($import_result[status]); ";
			}
			
	
			// catch the data
			if ($metadata_start == 1){
				$metadata_string .= $val."\n";
			}
			
			// *** resumption token
			if (preg_match("/<resumptionToken/",$val)) $resumption_start = 1;
			if ($resumption_start == 1) $resumption_string .= $val."\n";
			if (preg_match("/</resumptionToken>/",$val)){
				$resumption_start = 0;
				$resumption_string	= $gdl_metadata->xmlheading()."<dc>$resumption_string</dc>";
				$resumption_xml		= $gdl_metadata->read_xml($resumption_string);
				$results[token]		= $resumption_xml['RESUMPTIONTOKEN'];
				$results[total]		= $resumption_xml["RESUMPTIONTOKEN_COMPLETELISTSIZE"];
				$results[cursor]	= $resumption_xml["RESUMPTIONTOKEN_CURSOR"];
			}
			
		}
		
		if (empty($received_id)) $received_id = "Providers are uptodate.";	
		$results['count'] 		= $no;
		$results[identifiers] 	= $received_id;
		
		return $results;
	}

	// Extract providers xml
	function extract_identify($data,$id){
		global $gdl_metadata,$gdl_db;
		$array		= array("http","https","ftp");
		
		//echo "Data [$data]";
		$xml 		= $gdl_metadata->read_xml($data);
		
		$repository	= $xml['REPOSITORYNAME'];
		$base		= $xml['BASEURL'];
		$admin		= $xml['ADMINEMAIL'];
		$version	= $xml['PROTOCOLVERSION'];

		$arr_sHost	= explode("/",$base);
		$script		= array_pop($arr_sHost);
		$count		= count($arr_sHost);
		$buff		= trim(str_replace(":","",$arr_sHost[0]));
		if(in_array($buff,$array)){
			for($i=0,$d=2;$d<$count;$i++,$d++)
				$arr_bHost[$i] = $arr_sHost[$d];
			$host = implode("/",$arr_bHost);
		}

		$dbres 		= $gdl_db->update("repository","repository_name='$repository',host_url='$host',oai_script='$script',protocol_version = '$version',admin_email='$admin'","nomor ='$id'");
		$results['error']		 = mysqli_error($gdl_db->con);
		return $results;
	}
	
	// Extract ListSets xml
	function extract_ListSets($data,$id){
		global $gdl_metadata,$gdl_db;

		$xml 		= $gdl_metadata->readXML($data);
		
		$set_spec	= $xml['SET.SETSPEC'];
		$set_name	= $xml['SET.SETNAME'];
		$oai_desc	= $xml['OAI_DC:DC.DC:DESCRIPTION'];
		$count		= count($set_spec);			
		if($count > 0){

			$dbres = $gdl_db->delete("Set","nomor=$id");
			
			for($i=0;$i<$count;$i++){
				$set = $set_spec[$i];
				$name= $set_name[$i];
				$desc= $oai_desc[$i];
				
				$gdl_db->insert("Set","nomor,spec,name,description,modified","'$id','$set','$name','$desc',current_timestamp()");
				$db_err = mysqli_error($gdl_db->con);
				if(!empty($db_err)){
					$error .= "Failed insert [$set] : ".mysqli_error($gdl_db->con)."<br/>";
				}
			}
		}
		$results['error'] = $error;
		return $results;
	}
	
	
	function pre_processing_extract_record($data){
	
		$array	= array();
		$array2	= array();
		$loop	= 50;
		$i=0; $x=0;
		$str_record		= "record";
		$str_header		= "header";
		$str_metadata	= "metadata";
		$str_token		= "resumptionToken";
		
		while(($x < $loop) && !empty($data)){
			$pos_A	= strpos($data,"<$str_record>");
			$pos_B	= strpos($data,"</$str_record>");

			if(($pos_A === FALSE) || ($pos_B === FALSE)){
				$start_token	= strpos($data,"<$str_token");
				$end_token		= strpos($data,"</$str_token>")+3+strlen($str_token);

				if(($start_token === FALSE) || ($end_token === FALSE)){ 
				}else
					$result['resumptionToken']	=substr($data,$start_token,$end_token-$start_token);
				
				$data = "";
			}else{
				$end	= $pos_B + 3 + strlen($str_record);
				$sub	= substr($data,$pos_A,$end - $pos_A);
				$array[$i]	= $sub;
				$i++;
				$data	= substr($data,$end);
			}
			$x++;
		}
		$c_array	= count($array);
		if($c_array > 0){
			$x=0;
			for($i=0;$i<$c_array;$i++){
				$sub_record	= $array[$i];
				//echo "Sub Record : $sub_record <br/>";
								
				$start_header	= strpos($sub_record,"<$str_header>")+2+strlen($str_header);
				$end_header		= strpos($sub_record,"</$str_header>");
				$start_metadata	= strpos($sub_record,"<$str_metadata>")+2+strlen($str_metadata);
				$end_metadata	= strpos($sub_record,"</$str_metadata>");
				//echo "\n<br/>[$start_header][$end_header][$start_metadata][$end_metadata]<br/>\n";
				$element['header']	= trim(substr($sub_record,$start_header,$end_header-$start_header));
				$element['metadata']= trim(substr($sub_record,$start_metadata,$end_metadata-$start_metadata));
				//echo "Header :=> $element[header] <br/>\n";
				//echo "Element :=> $element[metadata] <br/>\n";
				if(!empty($element['header']) && !empty($element['metadata'])){
					$array2[$x]	= $element;
					$x++;
				}
			}
			/*
			for($i=0; $i< count($array2);$i++){
				$element	= $array2[$i];
				echo "Header 	: $element[header] <br/>\n";
				echo "Metadata	: $element[metadata]<br/>\n";
			}*/

		}

		$result['metadata']	= $array2;
		return $result;
	}
	
	function extract_record($data,$box,$optPrefix="",$methods=""){
		global $gdl_metadata;
		
		$arr_posting	= array();
		$results[size] 	= strlen($data);
		$rs_pre			= $this->pre_processing_extract_record($data);
		
		$rs_metadata			= $rs_pre['metadata'];
		$str_resumptionToken	= $rs_pre['resumptionToken'];

		$c_metadata	= count($rs_metadata);

		for($i=0;$i<$c_metadata;$i++){
			$element = $rs_metadata[$i];
			
			// header handle
			$header	 	= $element['header'];
			$xml 		= $gdl_metadata->readXML("<header>$header</header>");
			
			// get header information
			$identifier 	= $xml["IDENTIFIER"][0];
			$datestamp 		= $xml["DATESTAMP"][0];
			$status 		= $xml["STATUS"][0];
			$setSpec 		= $xml["SETSPEC"][0];
			
			// compare datestamp
			$need_update 	= $this->is_record_need_update($identifier,$datestamp,$box,$optPrefix);
			
			if ($status == "deleted"){
				// update database
				$gdl_metadata->delete($identifier);
				$received_id .= "$identifier (deleted); ";
			}
			
			// metadata handle
			$metadata	= $element['metadata'];
			
			$post_status		= "";
			$metadata_string 	= $gdl_metadata->clear_badchars($metadata);

			// import to database
			if ($need_update[update]){
				$import_result 	=  $this->import_metadata($identifier,$metadata_string,$need_update[status],$box,$optPrefix,$setSpec);
				$received_id 	.= "<br/>&nbsp;&nbsp;$identifier ($import_result[status]);\n";
				$post_status	= $import_result['status'];
				
			} else {
			
				$received_id 	.= "<br/>&nbsp;&nbsp;$identifier (*);\n";
				$post_status	= "*";
			}
			
			if($methods == "posting"){
				$post_identifier	= $identifier;
				$size_metadata		= strlen($metadata);
				
				$element_post		= array("identifier"=>$post_identifier,
											"size"=>$size_metadata,
											"status"=>$post_status);
											
				array_push($arr_posting,$element_post);
			}
		}
				
		if(!empty($str_resumptionToken)){
			//$resumption_string 	= $gdl_metadata->xmlheading()."<dc>$resumption_string</dc>";
			$resumption_xml 	= $gdl_metadata->readXML($str_resumptionToken);
			if(!is_array($resumption_xml))
				$resumption_xml 	= $gdl_metadata->readXML("<DC>".$str_resumptionToken."</DC>");
				
			//foreach($resumption_xml as $index => $value)
			//	echo "--xx-> $index : $value <br/>\n";
				
			$results['token'] 	= $resumption_xml['RESUMPTIONTOKEN'][0];
			$results['total'] 	= $resumption_xml["RESUMPTIONTOKEN.COMPLETELISTSIZE"][0];
			$results['cursor'] 	= $resumption_xml["RESUMPTIONTOKEN.CURSOR"][0];
		}
		
		if($methods != "posting"){
			if (empty($received_id)) $received_id = "Metadata are uptodate.";	
			$results['count'] 			= $c_metadata;
			$results['identifiers'] 	= $received_id;
		}else{
			$results['count']			= sizeof($arr_posting);
			$results['post_element']	= $arr_posting;
		}
		
		return $results;
	
	}
	
	function export_process() {
		global $frm,$gdl_metadata,$gdl_publisher;
		
		
		if (strlen($frm["starting_date"]) <> 10)
			$frm["starting_date"]		= "0000-00-00";
		else {
				$temp					= $frm["starting_date"];
				$frm["starting_date"]	= substr($temp,6,4)."-".substr($temp,3,2)."-".substr($temp,0,2);
				
		}
		
		$strdump						= $gdl_metadata->metadata_dump($frm["server"],$frm["publisher_id"],$frm["starting_date"]);
		
		if ($strdump)	{
		
			$end_date 	= date("Y-m-d");
			$filename 	= "files/export/metadata-".$gdl_publisher["id"];
			
			$gzfilename = "$filename.gz";
			$zp 		= gzopen($gzfilename, "w9");
	
			gzwrite($zp, $strdump);
	
			gzclose($zp);
			
			if (file_exists($gzfilename)){
				$content	=	_EXPORTSUCCESS;
				$content	.=	"<br/>"._FILENAME." : <b>$gzfilename</b>.
					<br/>"._FILESIZE." : ".filesize($gzfilename)." bytes";
			} else {
				$content	=	_EXPORTFAILED;		
			}
			
			if (preg_match('/'._EXPORTSUCCESS.'/i',$content)){
				$str_info 	= $frm["starting_date"]."--$end_date";
				$fp 		= fopen("$filename.txt","w");
				fputs($fp,$str_info);
				fclose($fp);
			}
		} else {
			$content	= _EXPORTFAILED;		
		}
		
		return $content;
		
	}
	
	function download_metadata_archive() {
		global $gdl_publisher;
	
		$fname_metadata 		= "files/export/metadata-".$gdl_publisher["id"].".gz";
		$fname_metadata_info 	= "files/export/metadata-".$gdl_publisher["id"].".txt";
		$content				= _TODOWNLOAD;
	
		if (file_exists($fname_metadata)){
			// metadata
			$content	.=	"<p><a href='".$fname_metadata."'><b>"._DOWNLOADMETADATA."</b></a> for
				".join('',file($fname_metadata_info))." (".filesize($fname_metadata)." bytes).";	
		} else {
			$content	.=	_METADATANOTARCHIVED;
		}
	
		return $content;
	}
	
	function upload_file(){
	
		global $frm,$_FILES,$gdl_sys;
		
		$content="<p>";
		if (preg_match("/gzip/i",$_FILES["archived_file"]["type"])) {
			if (preg_match("/metadata-/i",$_FILES["archived_file"]["name"])) {
				if ($_FILES["archived_file"]["size"] < $gdl_sys['sync_maxsize_gzfile']) {
					if (@is_uploaded_file($_FILES["archived_file"]["tmp_name"])) {
					  if (copy($_FILES["archived_file"]["tmp_name"],"./files/import/".$_FILES["archived_file"]["name"])) {
						@unlink("./files/import/".$_FILES["arhived_file"]["name"].".log");
						$content	.= _UPLOADFILESUCCESS;					
					   } else
						$content	.= _UPLOADFILEERROR;
					 } else
						   $content	.= _UPLOADFILEERROR;
				} else
					{
						$content	.= _UPLOADFILESIZEERROR." ".$gdl_sys['sync_maxsize_gzfile']." bytes";
					}
			} else
				$content			.= _METADATAUPLOADERROR;
		} else
			$content	.= _GZIPUPLOADERROR.$_FILES["archived_file"]["type"];
			
		$content.="</p>";
	
		return $content;
	}

	
	function delete_file($filename) {
		if (@unlink("./files/import/".$filename)) 
			$content="file <b>/files/import/".$filename."</b>"._DELETESUCCESS;
		else
			$content=_DELETEFAILED."<b>/files/import/".$filename."</b>";
		
		return $content;
	}
	
	
	
	
	
	// Add publisher
	function publisher_add($xml_data){
		global $gdl_db;

		$dbres=$gdl_db->insert("publisher","DC_PUBLISHER_ID, DC_PUBLISHER_SERIALNO, DC_PUBLISHER_TYPE, DC_PUBLISHER_APPS,
							DC_PUBLISHER, DC_PUBLISHER_ORGNAME, DC_PUBLISHER_HOSTNAME, DC_PUBLISHER_IPADDRESS,
							DC_PUBLISHER_ADMIN, DC_PUBLISHER_CKO, DC_PUBLISHER_CONTACT, DC_PUBLISHER_ADDRESS,
							DC_PUBLISHER_CITY, DC_PUBLISHER_REGION, DC_PUBLISHER_COUNTRY, DC_PUBLISHER_PHONE,
							DC_PUBLISHER_FAX, DC_PUBLISHER_CONNECTION, DC_PUBLISHER_NETWORK, DC_PUBLISHER_HUBSERVER,
							DC_PUBLISHER_DATEMODIFIED",
						"'".trim($xml_data['DC_PUBLISHER_ID'])."',
							'".trim($xml_data['DC_PUBLISHER_SERIALNO'])."',
							'$xml_data[DC_PUBLISHER_TYPE]',
							'$xml_data[DC_PUBLISHER_APPS]',
							'".addslashes($xml_data['DC_PUBLISHER'])."',
							'".addslashes($xml_data['DC_PUBLISHER_ORGNAME'])."',
							'".addslashes($xml_data['DC_PUBLISHER_HOSTNAME'])."',
							'".addslashes($xml_data['DC_PUBLISHER_IPADDRESS'])."',
							'".addslashes($xml_data['DC_PUBLISHER_ADMIN'])."',
							'".addslashes($xml_data['DC_PUBLISHER_CKO'])."',
							'".addslashes($xml_data['DC_PUBLISHER_CONTACT'])."',
							'".addslashes($xml_data['DC_PUBLISHER_ADDRESS'])."',
							'".addslashes($xml_data['DC_PUBLISHER_CITY'])."',
							'".addslashes($xml_data['DC_PUBLISHER_REGION'])."',
							'".addslashes($xml_data['DC_PUBLISHER_COUNTRY'])."',
							'".addslashes($xml_data['DC_PUBLISHER_PHONE'])."',
							'".addslashes($xml_data['DC_PUBLISHER_FAX'])."',
							'$xml_data[DC_PUBLISHER_CONNECTION]',
							'$xml_data[DC_PUBLISHER_NETWORK]',
							'$xml_data[DC_PUBLISHER_HUBSERVER]',
							'$xml_data[DC_PUBLISHER_DATEMODIFIED]'");

		if ($dbres){
			return "";
		} else {
			$message = "hub_publisher_add($id): Error ".mysqli_error($gdl_db->con);
			return $message;
		}
	}



	// update publisher
	function publisher_edit($id,$xml_data)
	{
		global $gdl_db;
		$dbres=$gdl_db->update("publisher","DC_PUBLISHER_ID = '".trim($xml_data['DC_PUBLISHER_ID'])."', 
							DC_PUBLISHER_SERIALNO = '".trim($xml_data['DC_PUBLISHER_SERIALNO'])."',
							DC_PUBLISHER_TYPE = '$xml_data[DC_PUBLISHER_TYPE]', 
							DC_PUBLISHER_APPS = '$xml_data[DC_PUBLISHER_APPS]',
							DC_PUBLISHER = '".addslashes($xml_data['DC_PUBLISHER'])."', 
							DC_PUBLISHER_ORGNAME = '".addslashes($xml_data['DC_PUBLISHER_ORGNAME'])."', 
							DC_PUBLISHER_HOSTNAME = '".addslashes($xml_data['DC_PUBLISHER_HOSTNAME'])."', 
							DC_PUBLISHER_IPADDRESS = '".addslashes($xml_data['DC_PUBLISHER_IPADDRESS'])."',
							DC_PUBLISHER_ADMIN = '".addslashes($xml_data['DC_PUBLISHER_ADMIN'])."', 
							DC_PUBLISHER_CKO = '".addslashes($xml_data['DC_PUBLISHER_CKO'])."', 
							DC_PUBLISHER_CONTACT = '".addslashes($xml_data['DC_PUBLISHER_CONTACT'])."', 
							DC_PUBLISHER_ADDRESS = '".addslashes($xml_data['DC_PUBLISHER_ADDRESS'])."',
							DC_PUBLISHER_CITY = '".addslashes($xml_data['DC_PUBLISHER_CITY'])."', 
							DC_PUBLISHER_REGION = '".addslashes($xml_data['DC_PUBLISHER_REGION'])."', 
							DC_PUBLISHER_COUNTRY = '".addslashes($xml_data['DC_PUBLISHER_COUNTRY'])."', 
							DC_PUBLISHER_PHONE = '".addslashes($xml_data['DC_PUBLISHER_PHONE'])."',
							DC_PUBLISHER_FAX = '".addslashes($xml_data['DC_PUBLISHER_FAX'])."', 
							DC_PUBLISHER_CONNECTION = '$xml_data[DC_PUBLISHER_CONNECTION]', 
							DC_PUBLISHER_NETWORK = '$xml_data[DC_PUBLISHER_NETWORK]', 
							DC_PUBLISHER_DATEMODIFIED = '$xml_data[DC_PUBLISHER_DATEMODIFIED]'",
						"DC_PUBLISHER_ID = '".$id."'");

		if ($dbres){
			return "";
		} else {
			$message = "hub_publisher_edit($id): ".mysqli_error($gdl_db->con);
			return $message;
		}
	}
	
	function import_metadata($identifier,$xmldata,$status,$box,$optPrefix,$setSpec=""){
		global $gdl_db,$gdl_publisher,$gdl_folder,$gdl_metadata,$gdl_file,$gdl_sync,$gdl_oaipmp,$gdl_folder;
		
		$xmldata	= trim($xmldata);
		$xml 		= $gdl_metadata->readXML($xmldata);
		
		if(!is_array($xml)){
				if(!empty($xmldata)){
					$xml	= $gdl_metadata->readXML("<DC>".$xmldata."</DC>");
				}
		}
		
		//foreach($xml as $index => $value)
		//	echo "XML[$identifier]--> $index ===> $value[0] <br>\n";
		
		if ($status == "new"){
			if($optPrefix == "oai_dc"){
				$hierarki 	 = str_replace($gdl_oaipmp->parent_set,"",$gdl_oaipmp->set);
				$hierarki	 = "/".substr($hierarki,1);
				$folder_info = $this->check_create_folder($gdl_sync['sync_repository_name'],$hierarki,$optPrefix,$setSpec);
			}else
				$folder_info = $this->check_create_folder($xml['PUBLISHER'][0],$xml["IDENTIFIER.HIERARCHY"][0]);
		}
		
		//foreach($folder_info as $index => $value)
			//echo "FOLDER_INFO--> $index ===> $value <br>\n";
		
		$new_hierarchy	= $gdl_folder->get_hierarchy($folder_info['parent']);
		$xmldata 	= preg_replace("/<hierarchy>*</hierarchy>/i","<hierarchy>$new_hierarchy</hierarchy>",$xmldata);
	//echo "IMPORT_METADATA ";
		switch($status){
			case "new":
				if($optPrefix == "general"){//echo "IM_1 ";
				
					// check metadata if has type discussion / disc
					$arr_identifier	= explode("@",$identifier);
					if(count($arr_identifier) > 1){
						if(preg_match("/^[0-9]+$/",$arr_identifier[1])){
							$field	= "date,identifier,user_id,name,email,subject,comment";

							$date	= addslashes($xml[DATE][0]);
							$email	= addslashes($xml['CREATOR.EMAIL'][0]);
							$creator= addslashes($xml[CREATOR][0]);
							$title	= addslashes($xml[TITLE][0]);
							$desc	= addslashes($xml[DESCRIPTION][0]);

							$value	= "'$date','$arr_identifier[0]','$email','$creator','$email','$title','$desc'";
							$selective = "identifier = '$arr_identifier[0]' and user_id = '$email' and name = '$creator' and ";
							$selective .= " email = '$email' subject='$title' and comment = '$desc'";
							
							$dbres	= $gdl_db->select("comment","identifier",$selective);
							$row = @mysqli_fetch_assoc($dbres);
							$cek_identifier = $row["identifier"];
							
							if(empty($cek_identifier))
								$gdl_db->insert("comment",$field,$value);

						}
						
					}
					//echo "IM_2 ";
					$gdl_db->insert("metadata","","'".$xml["IDENTIFIER"][0]."','".$folder_info[parent]."','".$folder_info[tree]."','".$xml["TYPE"][0]."','".addslashes($xmldata)."',NOW(),'".$xml["CONTRIBUTOR.MODIFIEDBY"][0]."',NULL,'".$optPrefix."','".$gdl_sync[sync_repository_name]."'");
					$gdl_db->insert($box,"type,identifier,status,folder,datemodified","'metadata','".$xml["IDENTIFIER"][0]."','success','received','".$xml["DATE.MODIFIED"][0]."'");				
					$this->insert_relation($xmldata);
					
				}else{//echo "IM_3 ";
					$gdl_db->insert("metadata","","'$identifier','".$folder_info[parent]."','".$folder_info[tree]."','".$xml["DC:TYPE"][0]."','".addslashes($xmldata)."',NOW(),'".$xml["DC:CONTRIBUTOR"][0]."',NULL,'$optPrefix','$gdl_sync[sync_repository_name]'");
					$gdl_db->insert($box,"type,identifier,status,folder,datemodified","'metadata','$identifier','success','received','".$xml["DC:DATE"][0]."'");				
					$this->insert_relation($xmldata);
				}
				break;
			case "update":
				if(!preg_match("/@/",$identifier)){//echo "IM_4 ";
					$gdl_db->update("metadata","date_modified='".$xml["DATE.MODIFIED"][0]."',owner='".$xml["CONTRIBUTOR.MODIFIEDBY"][0]."',xml_data='".addslashes($xmldata)."'","identifier='".$xml["IDENTIFIER"][0]."'");
					$gdl_db->update($box,"datemodified='".$xml["DATE.MODIFIED"][0]."'","identifier='".$xml["IDENTIFIER"][0]."'");
					$this->update_relation($xmldata);
				}
				//echo "IM_5 ";
				break;
			default:			
		}
	
		if (preg_match("/Duplicate/",mysqli_error($gdl_db->con))) {
			$this->import_metadata($identifier,$xmldata,"update",$box,$optPrefix);
		}
		
		$result[status] = $status;
	
		return $result;
	}

	function check_create_folder($providerId,$path,$optPrefix="",$setSpec=""){
		global $conf,$gdl_publisher,$gdl_sys,$gdl_folder,$gdl_db;
		
		// Exclusive for dublin core
		if($optPrefix == "oai_dc"){
			// Cek Available Provider Under Top
			
			if(strlen($providerId > 255))
				$providerId = substr($providerId,0,255);
				
			$dbres = $gdl_db->select("folder","folder_id,path","name like '$providerId'");
			//if($dbres){
			if(@mysqli_num_rows($dbres) == 0){
				$gdl_db->insert("folder","parent,path,name,date_modified","0,'0','$providerId',now()");
				$id = mysqli_insert_id($gdl_db->con);
				$folder_info[tree] 		= '0/'.$id."/";
			}else{
				$row = @mysqli_fetch_assoc($dbres);
				$id 					= $row["folder_id"];
				$id_path 				= $row["path"];
				$folder_info[tree] 		= $id_path."/";
			}
			//}
			$buffer_path = $path;
			$folder_info[parent] 	= $id;
			
			$setSpec	= trim($setSpec);
			if(!empty($setSpec)){
				$dbres = $gdl_db->select("folder","folder_id","name like '$setSpec'");
				if(mysqli_num_rows($dbres) == 0){
					$gdl_db->insert("folder","parent,path,name,date_modified","$folder_info[parent],'$folder_info[tree]','$setSpec',now()");
					$id_set = mysqli_insert_id($gdl_db->con);
				}else{
					$row = @mysqli_fetch_assoc($dbres);
					$id_set = $row["folder_id"];
				}
				
				$folder_info[tree] 		.= $id_set."/";
				$folder_info[parent] 	= $id_set;
			}

			return $folder_info;
		}
		
		
		$tmp_providerNetwork 	= $this->get_providerNetwork();
		$providerNetwork 		= $tmp_providerNetwork[$providerId];
		
		if (empty($providerNetwork)) $providerNetwork = "General";
		
		// strip previous IndonesiaDLN/*String path
		$path = str_replace("/IndonesiaDLN/*Institution/$providerId","",$path);
		$path = str_replace("/*Institution/$providerId","",$path);
		
		// collection path
		if ($gdl_publisher["id"] == $providerId) {
			$collection_path = $path; 			// bugs fixed here. IF/2003/04/03
		} else if ($path == "/Discussion/"){
			$collection_path = $path;
		} else {
			$collection_path = "/$gdl_sys[collection_folder]/$providerNetwork/$providerId".$path;		
		}
		
		$folders 		= explode("/",$collection_path);
		$parent_id 		= 0;
		$folder_tree 	= "0/";
		
		while (list($key,$val) = each($folders)){
			$val	= trim($val);
			if (strlen($val) == 0) continue;
			
			//echo "VAL-FOLDER[ $key ] : $val \n<br/>";
			
			$folder_node = $gdl_folder->check_folder($val,$parent_id);		
			if ($folder_node == "err") {
				$frmfolder['name']=$val;
				$frmfolder['parent']=$parent_id;
				$gdl_folder->add($frmfolder);
				$folder_node=mysqli_insert_id($gdl_db->con);
				$folder_tree .= "$folder_node/";
			} else
				$folder_tree .="$folder_node/";
				
			$parent_id = $folder_node;
		}
		
		$folder_info[tree] 		= $folder_tree;
		$folder_info[parent] 	= $parent_id;
		$folder_info[path] 		= $collection_path;
		//echo "\nCOL : $collection_path [> $buffer_path <]\n";
		return $folder_info;		
	}

	function get_providerNetwork(){
		global $gdl_db;
		$dbres	= $gdl_db->select("publisher","DC_PUBLISHER_ID,DC_PUBLISHER_NETWORK");
		
		if ($dbres){
			while ($row = mysqli_fetch_array($dbres)){
				$id = $row[DC_PUBLISHER_ID];
				$tmp_providerNetwork[$id] = $row[DC_PUBLISHER_NETWORK];
			}
			
			return $tmp_providerNetwork;
		}
	}
	
	// compare datestamp
	function is_record_need_update($identifier,$datestamp,$box,$optPrefix){
		global $gdl_db;
		$dbres=$gdl_db->select($box." box,metadata md","box.datemodified as datemodified,box.folder as folder,md.status as status,md.prefix as prefix","md.identifier='".$identifier."' and box.identifier=md.identifier");
		if ($dbres){
			if (mysqli_num_rows($dbres)>0){
				$row = @mysqli_fetch_assoc($dbres);
				$mydate = $row["datemodified"];
				$folder = $row["folder"];
				$status = $row["status"];
				$prefix = $row["prefix"];
				
				if($prefix == $optPrefix){
					if ($status == "deleted"){
						$res[update] = 1;
						$res[status] = "update";
					} else if ($folder == $box){
						$res[update] = 1;
						$res[status] = "update";
					} else if ($mydate < $datestamp){
						$res[update] = 1;
						$res[status] = "update";
					} else {
						$res[update] = 0;
						$res[status] = "uptodate";
					}
				}else{
						$res[update] = 0;
						$res[status] = "uptodate";
				}
				
			} else {
				$res[update] = 1;
				$res[status] = "new";
			}
		} else {
			$res[update] = 0;
			$res[status] = "uptodate";
		}
		
		return $res;
	}

	function metadata_from_file($filename) {
		
		$maxsize	= 2000000;
		$gzfile		= "./files/import/".$filename;
		
		if (file_exists($gzfile)) {
			$gzhandle	= gzopen($gzfile,"r");
			$count		= 0;
			$no			= 0;
			while (!gzeof($gzhandle))	{
					$xmldata.=gzgetc($gzhandle);
					if (preg_match("/</record>/",$xmldata)) {
						if (preg_match("/<dc>/",$xmldata))
							$prefix="general";
						elseif (preg_match("/<dc:/",$xmldata))
							$prefix="oai_dc";
						
						$result	= $this->extract_record($xmldata,"inbox",$prefix);						
						$count	+= $result[count];
						$xmldata="";
					}				
				}
				
			gzclose($gzhandle);	
			$content	.= "Metadata : ".$count."<br/>";		
			
			$fp 		= fopen("$gzfile.log","w");
			fputs($fp,"Done");
			fclose($fp);
			
		} else
			$content	.= "file <b>".$gzfile."</b> not found";
		return $content;
	}
	
	function cek_valid_date_format($date){
		return (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z/",$date))?1:0;
	}
	
	function cek_valid_date_format2($date){
		return (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/",$date))?1:0;
	}
	
	
	function pre_processingCompareDate($date){
		// YYYY-MM-DDThh-mm-ssZ or YYYY-MM-DD hh:mm:ss
		if($this->cek_valid_date_format($date) || $this->cek_valid_date_format2($date)){
			$datestamp_date	= substr($date,0,10);
			$datestamp_time	= substr($date,11,8);
			
			if(!preg_match("/[0-9]{2}:[0-9]{2}:[0-9]{2}/",$datestamp_time))
				$datestamp	= $datestamp_date." ".$datestamp_time;
			else
				$datestamp	= $datestamp_date;
				
		}else{
			$datestamp = $date;
		}
		
		return $datestamp;
	}
	// update inbox
	function update_inbox($xmldata,$i_status=""){
		global $gdl_metadata,$gdl_db;
		
		$xmldata2		= $xmldata;
		$xmldata		= str_replace("\n","",$xmldata);
		$xmldata		= str_replace("\r","",$xmldata);
				
		//echo "DATA-UI : $xmldata<br/>";
		
		$arr_buffer		= array();
		$xml 			= $gdl_metadata->readXML($xmldata);

		//foreach($xml as $index => $value)
			//echo "===0==> $index --> $value[0] <br/>\n";
			
		if(!is_array($xml))
			$xml		= $gdl_metadata->readXML("<DC>".$xmldata."</DC>");

		$result[size] 	= strlen($xmldata);
		$count = 0;
	
		$c_identifier = count($xml['HEADER.IDENTIFIER']);
		
		//echo "TOTAL : ".count($c_identifier)."<br/>";
		//foreach($xml as $index => $value)
			//echo "===1==> $index --> $value[0] <br/>\n";

		// identifiers

		while (list($key,$val) = each ($xml['HEADER.IDENTIFIER'])){
			$count++;
			
			$identifier = $val;
			$datestamp = $xml["HEADER.DATESTAMP"][$key];
			$setspec = $xml["HEADER.SETSPEC"][$key];
			
			// check existence
			$dbcheck	= $gdl_db->select("inbox","DATEMODIFIED,STATUS","IDENTIFIER like '$identifier'");
			
			$datestamp 	= $this->pre_processingCompareDate($datestamp);
			if ($dbcheck){
				if (mysqli_num_rows($dbcheck)>0){
					$row = mysqli_fetch_assoc($dbcheck);
					$mydate 	= $row["DATEMODIFIED"];
					$mystatus	= $row["STATUS"];

					if($mystatus == "failed") continue;

					$mydate		= $this->pre_processingCompareDate($mydate);
					//echo "==>[$mydate][$datestamp]<br/>\n";
					if($mystatus == $i_status){//echo " 1 <br/>\n";
						array_push($arr_buffer,$identifier);
						continue;
					}else if ($mydate == $datestamp){//echo " 2 <br/>\n";
						continue; 
					}else
						array_push($arr_buffer,$identifier);
						//echo " 3 <br/>\n";
						
					// update
					$status = "updated";
					$dbres	= $gdl_db->update("inbox",
											  "STATUS = 'updated',
											  		FOLDER = 'inbox',
													DATEMODIFIED = '$datestamp'",
											  "IDENTIFIER = '$identifier'"
											 );

				} else {
					
					// insert into inbox
					if(empty($status) && empty($i_status)){
					
						$status = "new";
						$dbres	= $gdl_db->insert("inbox",
												  "TYPE,
														IDENTIFIER,
														STATUS,
														FOLDER,
														DATEMODIFIED",
												  "'metadata',
														'$identifier',
														'$status',
														'inbox',
														'$datestamp'"
												);
					}else{
						$status = "dummy";
						$dbres	= $gdl_db->insert("inbox",
												  "TYPE,
														IDENTIFIER,
														STATUS,
														FOLDER,
														DATEMODIFIED",
												  "'metadata',
														'$identifier',
														'$status',
														'inbox',
														'$datestamp'"
												);
						array_push($arr_buffer,$identifier);
						//echo " 4 <br/>";
					}
				}
			}
			
			if ($dbres){
				$result['identifiers'] .= "<br/>&nbsp;&nbsp;$identifier ($status); ";
			}			
		}
		
		$result['dummy_identifier']	= $arr_buffer;
		if (empty($result[identifiers])) $result[identifiers] = "Identifiers are uptodated.";
		
		// total identifiers
		$result['total'] = $xml["LISTIDENTIFIERS.RESUMPTIONTOKEN.COMPLETELISTSIZE"][0];
		$result['total'] = empty($result['total'])?$xml["RESUMPTIONTOKEN.COMPLETELISTSIZE"][0]:$result['total'];
		

		if(empty($result['total'])){
			$str_token = "resumptionToken";
			$pos_A	= strpos($xmldata2,"<$str_token");
			$pos_B	= strpos($xmldata2,"</$str_token>") + 3 + strlen($str_token);

			$xml_token	= substr($xmldata2,$pos_A,$pos_B-$pos_A);
			$xml_token	= str_replace("\n"," ",$xml_token);
			$xml_token	= str_replace("\r"," ",$xml_token);
			//echo "<br/>\n XMLTOKEN : $xml_token \n<br/>";
			$xml 		= $gdl_metadata->readXML("<DC>".$xml_token."</DC>");
		}
		
		//foreach($xml as $index => $value)
			//echo "===2=[ $result[total] ]=> $index --> $value[0] <br/>\n";
			
		$result['token'] = $xml["LISTIDENTIFIERS.RESUMPTIONTOKEN"][0];
		$result['token'] = empty($result['token'])?$xml["RESUMPTIONTOKEN"][0]:$$result['token'];

		$result['count'] = $count;
					
		return $result;
	}

	function insert_relation($xmldata,$start_counter="") {
		global $gdl_metadata,$gdl_db;
		$xml=$gdl_metadata->readXML($xmldata);
		
		$counter= empty($start_counter)?0:(int)$start_counter;
		while($counter < $xml["RELATION.COUNT"][0]){
			$no_relation = $xml["RELATION.NO"][$counter];
			
			if(!empty($no_relation) && ($no_relation != "0")){
				$gdl_db->insert("relation","identifier,date_modified,no,name,part,path,format,size,uri,note","'".$xml["IDENTIFIER"][0]."','".$xml["RELATION.DATEMODIFIED"][$counter]."','$no_relation','".$xml["RELATION.HASFILENAME"][$counter]."','".$xml["RELATION.HASPART"][$counter]."','".$xml["RELATION.HASPATH"][$counter]."','".$xml["RELATION.HASFORMAT"][$counter]."','".$xml["RELATION.HASSIZE"][$counter]."','".$xml["RELATION.HASURI"][$counter]."','".$xml["RELATION.HASNOTE"][$counter]."'");

				if(preg_match("/Duplicate/",mysqli_error($gdl_db->con)))
					$gdl_db->update("relation",",date_modified='".$xml["RELATION.DATEMODIFIED"][$counter]."',name='".$xml["RELATION.HASFILENAME"][$counter]."',part='".$xml["RELATION.HASPART"][$counter]."',path='".$xml["RELATION.HASPATH"][$counter]."',format='".$xml["RELATION.HASFORMAT"][$counter]."',size='".$xml["RELATION.HASSIZE"][$counter]."',uri='".$xml["RELATION.HASURI"][$counter]."',note='".$xml["RELATION.HASNOTE"][$counter]."'","identifier='$identifier' AND no='$no_relation'");

			}

			$counter++;
		}
	}
	
	function delete_relation($identifier,$start_delete) {
		global $gdl_db;
		$gdl_db->delete("relation","identifier = '$identifier' and no > $start_delete");
	}
	
	function update_relation($xmldata){// echo " U_relation ";
		global $gdl_metadata,$gdl_db;
		$xml=$gdl_metadata->readXML($xmldata);
		
		$counter_newRelation 	= (int)$xml["RELATION.COUNT"][0];
		$identifier				= $xml["IDENTIFIER"][0];
		
		// cek the number previous relation
		$dbres 		= $gdl_db->select("relation","count(identifier) as total","identifier = '$identifier'");
		$row = @mysqli_fetch_assoc($dbres);
		$num_prev	= (int)$row["total"];
		
		if($num_prev == 0){//echo " U_r[1] ";
			if($counter_newRelation > 0)
				$this->insert_relation($xmldata);
		}else{
			if($num_prev == $counter_newRelation){//echo " U_r[2] ";

				$counter=0;
				for ($counter=0;$counter<$counter_newRelation;$counter++){
					$gdl_db->update("relation",",date_modified='".$xml["RELATION.DATEMODIFIED"][$counter]."',name='".$xml["RELATION.HASFILENAME"][$counter]."',part='".$xml["RELATION.HASPART"][$counter]."',path='".$xml["RELATION.HASPATH"][$counter]."',format='".$xml["RELATION.HASFORMAT"][$counter]."',size='".$xml["RELATION.HASSIZE"][$counter]."',uri='".$xml["RELATION.HASURI"][$counter]."',note='".$xml["RELATION.HASNOTE"][$counter]."'","identifier='$identifier' AND no='".$xml["RELATION.NO"][$counter]."'");
				}		

			}else if($num_prev < $counter_newRelation){//echo " U_r[3] ";
				$counter=0;
				while($counter<$counter_newRelation){
					$gdl_db->update("relation",",date_modified='".$xml["RELATION.DATEMODIFIED"][$counter]."',name='".$xml["RELATION.HASFILENAME"][$counter]."',part='".$xml["RELATION.HASPART"][$counter]."',path='".$xml["RELATION.HASPATH"][$counter]."',format='".$xml["RELATION.HASFORMAT"][$counter]."',size='".$xml["RELATION.HASSIZE"][$counter]."',uri='".$xml["RELATION.HASURI"][$counter]."',note='".$xml["RELATION.HASNOTE"][$counter]."'","identifier='$identifier' AND no='".$xml["RELATION.NO"][$counter]."'");
					$counter++;
				}
				
				$this->insert_relation($xmldata,$counter);
			}else{
				$counter=0;
				while($counter<$counter_newRelation){
					$gdl_db->update("relation",",date_modified='".$xml["RELATION.DATEMODIFIED"][$counter]."',name='".$xml["RELATION.HASFILENAME"][$counter]."',part='".$xml["RELATION.HASPART"][$counter]."',path='".$xml["RELATION.HASPATH"][$counter]."',format='".$xml["RELATION.HASFORMAT"][$counter]."',size='".$xml["RELATION.HASSIZE"][$counter]."',uri='".$xml["RELATION.HASURI"][$counter]."',note='".$xml["RELATION.HASNOTE"][$counter]."'","identifier='$identifier' AND no='".$xml["RELATION.NO"][$counter]."'");
					$counter++;
				}
				//echo " U_r[4][$counter] ";
				$this->delete_relation($identifier,$counter);
			}
		}
	}
	
	function update_token_folksonomy($word){
		global $gdl_db;
		
		$dbres = $gdl_db->select("garbagetoken","Token","Token LIKE '$word'");
		if(@mysqli_fetch_row($dbres) == FALSE){
				$w_cek	= strtolower($word);
				$dbres 	= $gdl_db->select("folksonomy","Token,Frekuensi","Token LIKE '$w_cek'");
				if($rows = @mysqli_fetch_row($dbres)){
					$num	= $rows['1']+1;
					$dbres = $gdl_db->update("folksonomy","Frekuensi=$num","Token LIKE '$word'");
				}else{
					$dbres = $gdl_db->insert("folksonomy","Token,Frekuensi","'$word',1");
				}
		}
	}
}

?>