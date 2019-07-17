<?php
	class synchronization {
		var $response_connection;
		var $response_target;
		
		function is_connected($state=""){
		global $HTTP_SESSION_VARS,$gdl_sync;
			
			if($gdl_sync['sync_opt_script'] == "1"){
				if($state != "remote")
					$this->sync_disconnection();
				return true;
			}
			
			if (!empty($HTTP_SESSION_VARS['sess_connect_sessionid']))
				return true;
			else
				return false;
		}
		
		function make_valid_format_date($date,$opt){
			if($opt == 0)
				$default = "0000-00-00T00:00:00Z";
			
			$len = strlen($date);
			if($len != 20) return $default;
			if(!preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z/",$date))return $default;
			$f_date = substr($date,0,10);
			$f_time = substr($date,11,8);
			if($opt == 0)
				return $date;
			else
				$valid = $f_date." ".$f_time;
			
			return $valid;
		}
		
		function save_to_file($frm){
			
			$from = $frm["sync_harvest_from"];
			if(!empty($from)){
					$from = $this->make_valid_format_date($from,0);
			}
			
			$until = $frm["sync_harvest_from"];
			if(!empty($until)){
					$until = $this->make_valid_format_date($until,0);
			}
			
			
			$sync_conf = "<?
			# Automatically generated on ".date("Y-m-d H:i:s")."
		
			# ******* Repository Name
			\$gdl_sync['sync_repository_name'] 			= \"$frm[sync_repository_name]\";
			\$gdl_sync['sync_repository_id'] 			= \"$frm[sync_repository_id]\";
			
			# ******** Hub Server
			\$gdl_sync['sync_hub_server_name']  		= \"$frm[sync_hub_server_name]\";
			\$gdl_sync['sync_hub_server_port']  		= \"$frm[sync_hub_server_port]\";
		
			# ******** Proxy
			\$gdl_sync['sync_use_proxy']  				= \"$frm[sync_use_proxy]\";
			\$gdl_sync['sync_proxy_server_address']  	= \"$frm[sync_proxy_server_address]\";
			\$gdl_sync['sync_proxy_server_port']  		= \"$frm[sync_proxy_server_port]\";
		
			# ********* OAI Script for request
			\$gdl_sync['sync_oai_script']  				= \"$frm[sync_oai_script]\";
			\$gdl_sync['sync_opt_script']  				= \"$frm[sync_opt_script]\";
			\$gdl_sync['sync_harvest_node']   			= \"$frm[sync_harvest_node]\";
			\$gdl_sync['sync_harvest_from']   			= \"$from\";
			\$gdl_sync['sync_harvest_until']  			= \"$until\";
			\$gdl_sync['sync_harvest_set']  	 		= \"$frm[sync_harvest_set]\";
			
			# ********* Other options
			\$gdl_sync['sync_count_records']  = \"$frm[sync_count_records]\";
			\$gdl_sync['sync_show_response']  = \"$frm[sync_show_response]\";
			\$gdl_sync['sync_fragment_size']  = \"$frm[sync_fragment_size]\";
			
			?>";
		
			// save to file
			$fp = fopen("config/sync.php","w");
			$result=fputs($fp,$sync_conf);
			fclose($fp);
			
			return $result;
		}
		
		function save_to_repository($frm){
			global $gdl_db;
			
			$array = array("general","oai_dc");
			$script = $array[(int)$frm["sync_opt_script"]];
			$from = $frm["sync_harvest_from"];
			if(!empty($from)){
					$from 		= $this->make_valid_format_date($from,1);
					$from_updt	= "from_clause='$from',";
					$from_ins	= "from_clause,";
					$from_ins_v	= "'$from',";
			}
			
			$until = $frm["sync_harvest_until"];
			if(!empty($until)){
					$until 			= $this->make_valid_format_date($until,1);
					$until_updt		= "until_clause='$until',";
					$until_ins		= "until_clause,";
					$until_ins_v	= "'$until',";
			}
			
			if(!empty($frm['sync_repository_id'])){
				//update
				$update = "repository_name='$frm[sync_repository_name]',
						   host_url='$frm[sync_hub_server_name]',
						   port_host='$frm[sync_hub_server_port]',
						   use_proxy=$frm[sync_use_proxy],
						   proxy_address='$frm[sync_proxy_server_address]',
						   port_proxy=$frm[sync_proxy_server_port],
						   oai_script='$frm[sync_oai_script]',
						   option_prefix='$script',
						   fragmen=$frm[sync_fragment_size],
						   show_xml=$frm[sync_show_response],
						   list_set='$frm[sync_harvest_set]',
						   $from_updt
						   $until_updt
						   count_record=$frm[sync_count_records],
						   harvest_node='$frm[sync_harvest_node]'";
						   
				$gdl_db->update("repository","$update","nomor=$frm[sync_repository_id]");
			}else{
				//insert new
				$column= "repository_name,host_url,port_host,use_proxy,proxy_address,port_proxy,oai_script,
						  option_prefix,fragmen,show_xml,list_set,$from_ins $until_ins count_record,harvest_node";
				$value = "'$frm[sync_repository_name]','$frm[sync_hub_server_name]',$frm[sync_hub_server_port],$frm[sync_use_proxy],'$frm[sync_proxy_server_address]',
						  $frm[sync_proxy_server_port],'$frm[sync_oai_script]','$script',$frm[sync_fragment_size],
						  $frm[sync_show_response],'$frm[sync_harvest_set]',$from_ins_v $until_ins_v $frm[sync_count_records],'$frm[sync_harvest_node]'";
						  
				$gdl_db->insert("repository","$column","$value");
			}
			
			$err_mysql = mysqli_error($gdl_db->con);
			if(empty($err_mysql)) return 1;
			else return 0;			
		}
		
		function save_configuration($frm,$option) {

			global $gdl_db;
			
			if($option == 1){ // Make default connection operation

				$repo_id	= trim($frm["sync_repository_id"]);
				
				if(!empty($repo_id)){

					// save to file
					$result = $this->save_to_file($frm);
					// save to repository
					if($result)
						$result = $this->save_to_repository($frm);
				}else{

					$result 	= $this->save_to_repository($frm);
					
					if($result){
						$frm["sync_repository_id"]	= mysqli_insert_id($gdl_db->con);
						$result = $this->save_to_file($frm);
					}
				}
			}else
				$result = $this->save_to_repository($frm);
				
			return $result;
		}
		
		function sync_connection($status){
			global $gdl_harvest,$gdl_metadata;

			$result = $gdl_harvest->execute_verb("Connect");
			
			if(empty($result)){
				$response_xml['ERROR_CODE']	= "badReceivedConnection";
				$response_xml['ERROR']		= "No information is received from hub";
			}else if(is_array($result)){
					if(empty($result['error'])){
						$response_xml			= $result['connect_response'];
					}else{
						if($result['error'] == "TIMEOUT"){
							$response_xml['ERROR_CODE']	= "badConfigurationConnection";
							$response_xml['ERROR']		= "Please cek your configuration connection";
						}else{
							$xmldata = $gdl_metadata->read_xml("<DATA>".$result['error']."</DATA>");
							$response_xml['ERROR_CODE']	= $xmldata['ERROR_CODE'];
							$response_xml['ERROR']		= $xmldata['ERROR'];
						}
					}
			}else{
				$response_xml['ERROR_CODE']	= "unDefinedError";
				$response_xml['ERROR']		= $result;
			}
			
			$this->response_connection 	= $response_xml;
			$this->response_target 		= $result['response'];
			$status = $this->sync_connection_status($response_xml);
			
			return $status;
		}
		
		// Disconnect
		function sync_disconnection(){
			global $HTTP_SESSION_VARS,$gdl_session;
			$gdl_session->session_connect("","","");
			$this->response_connection = "";
			
		}
				// Get connection status
		function sync_connection_status($xml){
			
			global $HTTP_SESSION_VARS,$gdl_session;
			
			$sess_id	= $xml['SESSIONID'];
			$providerId	= $xml['PROVIDERID'];
			$network	= $xml['PROVIDERNETWORK'];
			
			$result 	= 0;
			if (!empty($sess_id)){
				$result 	= 1;
			} else {
				$sess_id	= "";
				$providerId	= "";
				$network	= "";
			}
			
			$gdl_session->session_connect($sess_id,$providerId,$network);
			return $result;
		}
		
		function sync_sockopen(){
			global $gdl_sync,$gdl_db;
			
			$id 			= $_SESSION['sess_Identify'];			
			$proxy_server 	= $gdl_sync['sync_proxy_server_address'];
			$proxy_port		= $gdl_sync['sync_proxy_server_port'];
			$hub_server		= $gdl_sync['sync_hub_server_name'];
			$hub_port		= $gdl_sync['sync_hub_server_port'];
			$use_proxy		= $gdl_sync['sync_use_proxy'];
			
			if(!empty($id)){
				$dbres = $gdl_db->select("repository","proxy_address,port_proxy,host_url,port_host,use_proxy","nomor = $id");
				if(mysqli_num_rows($dbres) == 1){
					$row = mysqli_fetch_row($dbres);
					$proxy_server 	= $row[0];
					$proxy_port		= $row[1];
					$hub_server		= $row[2];
					$hub_port		= $row[3];
					$use_proxy		= $row[4];
				}
			}
			
			//echo "[$id][$proxy_server][$proxy_port][$hub_server][$hub_port][$use_proxy]<br>";
			if ($use_proxy){
				if((!empty($proxy_server)) && (!empty($proxy_port))){
					$fp = @fsockopen($proxy_server,$proxy_port);
				}else
					return false;
			} else {
				if((!empty($hub_server)) && (!empty($hub_port))){
					$fp = @fsockopen($hub_server,$hub_port);
				}else
					return false;
			}
			
			$fp = ($fp === FALSE)?0:$fp;
			return $fp;
		}
		
		function update_repository_from_publisher(){
			global $gdl_db,$gdl_stdout,$gdl_sys;
			
			$token = $_GET['token'];
			$limit = $gdl_sys['perpage_publisher'];
			
			$start = $token*$limit;
			
			$dbres = $gdl_db->select("publisher","DC_PUBLISHER_ID,DC_PUBLISHER,DC_PUBLISHER_HOSTNAME","","","","$start,$limit");
			if($dbres){
				while($row = mysqli_fetch_array($dbres)){
					$id			= $row['DC_PUBLISHER_ID'];
					$repo_name	= $row['DC_PUBLISHER'];
					$base		= $row['DC_PUBLISHER_HOSTNAME'];
					
					$dbres_2 	= $gdl_db->select("repository","ID_PUBLISHER","ID_PUBLISHER LIKE '$id'");
					if(mysqli_num_rows($dbres_2) > 0){
						$gdl_db->update("repository","repository_name='$repo_name',host_url='$base',modified=current_timestamp()","where id_publisher like '$id'");
					}else{
						$gdl_db->insert("repository","repository_name,host_url,id_publisher,modified","'$repo_name','$base','$id',current_timestamp()");
					}
					
				}
			}
			
			$dbres_3 = $gdl_db->select("publisher","count(IDPUBLISHER) as total");
			if($dbres_3){
				$row = @mysqli_fetch_assoc($dbres_3);
				$total = $row["total"];
				if($total > ($start+$limit)){
					$token++;
					$url = "index.php?mod=synchronization&amp;op=option&amp;action=repo&amp;token=$token";
					$result = $gdl_stdout->header_redirect(1,$url);
				}
			}
			
			return $result;
		}
		
		function get_list($searchkey,$start,$limit) {
			global $gdl_db;
			
			$dbres = $gdl_db->select("repository","NOMOR,ID_PUBLISHER,REPOSITORY_NAME,HOST_URL,OAI_SCRIPT,OPTION_PREFIX","REPOSITORY_NAME LIKE '$searchkey%'","REPOSITORY_NAME,ID_PUBLISHER","asc,asc","$start,$limit");
			while ($rows = @mysqli_fetch_row($dbres)){
				$result[$rows[0]]['REC']	= $rows ['0'];
				if(empty($rows['1']))
					$rows[1] ="N/A";
				$result[$rows[0]]['ID']		= $rows ['1'];
				$result[$rows[0]]['NAME']	= $rows ['2'];
				$result[$rows[0]]['URL']	= $rows['3']."/".$rows[4];
				$result[$rows[0]]['PREFIX']	= $rows['5'];
			}
			return $result;
		}
		
		function delete_record_repository($id){
			global $gdl_db,$gdl_sync,$gdl_sys;
			
			$id	= (preg_match("/^[0-9]+$/",$id))?$id:"";
			
			if(!empty($id)){
				$gdl_db->delete("repository","nomor = $id");
				
				if(mysqli_affected_rows($gdl_db->con) > 0){
					$id_curr	= (int)$gdl_sync['sync_repository_id'];
					if($id == $id_curr){
						// You have deleted your default connection
						
						$failed	= false;
							
						// default hub (hub.indonesiadln.org)
						$default_hub	= $gdl_sys['sync_hub_server_name'];
						
						if(empty($default_hub)) $failed = true;
						
						if(!$failed){
							// find id your default hub in repository
							$dbres 	= $gdl_db->select("repository","nomor","host_url like '$default_hub'");
							$row = @mysqli_fetch_assoc($dbres);
							$id_hub	= (int)$row["nomor"];
							
							//echo "Step-0[$default_hub][$id_hub]<br>";
							if($id_hub <= 0) $failed = true;
						}
						
						if(!$failed){
							//echo "Step-1<br>";
							// Assign id hub
							$frm['sync_repository_id']	= "$id_hub";
							
							// Load all default setting
							foreach ($gdl_sys as $IdxFrm => $ValFrm)
									$frm[$IdxFrm]=$gdl_sys[$IdxFrm];
							
							// replace sync.php
							$this->save_to_file($frm);
							
							// update your repository 
							$this->save_to_repository($frm);
						}
						
						if($failed){
							// All previous action has fail
							// Now we will replace sync.php with blank information
							$fp = fopen("config/sync.php","w");
							$result=fputs($fp,"");
							fclose($fp);
						}
					}
				}
				
			}
			
		}
		

		function get_info_repository($id){
			global $gdl_db;
			
			if(empty($id)) return "";
			
			$array_prefix	= array("general"=>"0","oai_dc"=>"1");
			$dbres = $gdl_db->select("repository","*","nomor = $id");
			if(!$dbres) return "";
			
			$row = mysqli_fetch_array($dbres);
			
			$frm['sync_repository_name'] 		= $row['repository_name'];
			$frm['sync_repository_id'] 			= $row['nomor'];
			
			# ******** Hub Server
			$frm['sync_hub_server_name']  		= $row['host_url'];
			$frm['sync_hub_server_port']  		= $row['port_host'];
		
			# ******** Proxy
			$frm['sync_use_proxy']  			= $row['use_proxy'];
			$frm['sync_proxy_server_address']  	= $row['proxy_address'];
			$frm['sync_proxy_server_port']  	= $row['port_proxy'];
		
			# ********* OAI Script for request
			$frm['sync_oai_script']  			= $row['oai_script'];
			$frm['sync_opt_script']  			= $array_prefix[$row['option_prefix']];
		
			# ********* Other options
			$frm['sync_count_records']  		= $row['count_record'];
			$frm['sync_show_response']  		= $row['show_xml'];			
			$frm['sync_fragment_size']  		= $row['fragmen'];
			
			$frm['sync_harvest_node']   		= $row['harvest_node'];
			$f_date = substr($row['from_clause'],0,10);
			$f_time = substr($row['from_clause'],11,8);
			$frm['sync_harvest_from']   		= "$f_date"."T$f_time"."Z";
			$u_date = substr($row['until_clause'],0,10);
			$u_time = substr($row['until_clause'],11,8);
			$frm['sync_harvest_until']  		= "$u_date"."T$u_time"."Z";
			$frm['sync_harvest_set']  	 		= $row['list_set'];
			
			return $frm;
		}
		
		function get_total_repository($searckey){
			global $gdl_db;
			
			$dbres = $gdl_db->select("repository","count(*) as total","repository_name like '$searckey%'");
			$row = mysqli_fetch_assoc($dbres);
			$total = (int)$row["total"];
			
			return $total;
		}
		
		function update_queue_job($frm){
			global $gdl_db,$gdl_harvest;
			
			$publisher	= $gdl_harvest->get_current_publisher();
			if($publisher == null) return null;
			
			$job = array();
			foreach($frm as $index => $value)
				if($index != "submit") array_push($job,$index);
						
			$count = count($job);
			for($i=0;$i<$count;$i++){
				$dbres = $gdl_db->select("queue","no","path like '$job[$i]' and dc_publisher_id like '$publisher' ");
				
				if(@mysqli_num_rows($dbres) == 0)
					$gdl_db->insert("queue","path,datemodified,DC_PUBLISHER_ID","'$job[$i]',current_timestamp(),'$publisher'");
			}
			
		}
		
		function get_list_queue($folder,$start="",$limit=""){
			global $gdl_db,$gdl_harvest;
			
			$result = array();
			$publisher = $gdl_harvest->get_current_publisher();
			
			if(!empty($start) && !empty($limit) && ($publisher != null))
				$fetch = "$start,$limit";
				
			$dbres = $gdl_db->select("queue","no,path,status","path like '$folder%' and status not like 'success'  and dc_publisher_id = '$publisher' ","path","asc",$fetch);
			if($dbres){
				while($rows = @mysqli_fetch_row($dbres)){
					$result[$rows[0]]['NO']			= $rows ['0'];
					$result[$rows[0]]['PATH']		= $rows ['1'];
					$result[$rows[0]]['STATUS']		= $rows ['2'];
				}
			}
			return $result;
		}
		
		function get_total_queue(){
			global $gdl_db,$gdl_harvest;
			
			$publisher = $gdl_harvest->get_current_publisher();

			if($publisher == null) return 0;
			
			$dbres = $gdl_db->select("queue","count(path) as total","status not like 'success'  and dc_publisher_id = '$publisher' ");
			if($dbres){
				$row = @mysqli_fetch_assoc($dbres);
				return $row["total"];
			}
			return 0;
		}
		
		function get_total_queue_finish_job(){
			global $gdl_db,$gdl_harvest;
			
			$publisher = $gdl_harvest->get_current_publisher();

			if($publisher == null) return 0;
			
			$dbres = $gdl_db->select("queue","count(path) as total","(status like 'success'  or status like 'failed') and dc_publisher_id = '$publisher' ");
			if($dbres){
				$row = @mysqli_fetch_assoc($dbres);
				return $row["total"];
			}
			return 0;
		}
		
		function get_list_queue_finish_job(){
			global $gdl_db,$gdl_harvest;
			
			$result = array();
			$publisher = $gdl_harvest->get_current_publisher();
			
			if($publisher != null){
				
				$dbres = $gdl_db->select("queue","no,path,status","(status like 'success'  or status like 'failed') and dc_publisher_id = '$publisher' ","path","asc");
				if($dbres){
					while($rows = @mysqli_fetch_row($dbres)){
						$result[$rows[0]]['NO']					= $rows ['0'];
						$result[$rows[0]]['PATH']				= $rows ['1'];
						$result[$rows[0]]['STATUS']		= $rows ['2'];
					}
				}
			}
			return $result;
		}
		
		
		function delete_queue($record){
			global $gdl_db,$gdl_harvest;
			
			// get temp folder
			$dbres 			= $gdl_db->select("queue","temp_folder","no = '$record'");
			$row = @mysqli_fetch_assoc($dbres);
			$temp_folder	= $row["temp_folder"];
			if($dbres){
				$option = $gdl_harvest->harvest_formatRequest;
				if($option == "general"){
					$gdl_harvest->harvest_oaipmh->gn_requestAction->clean_temporaryPostingFile_by_tempFolder($temp_folder);
				}
			}
						
			$gdl_db->delete("queue","no = '$record'");
		}
		
		function re_queue($record){
			global $gdl_db;
			$gdl_db->update("queue","status = 'queue'","no = '$record'");
		}
		
		
		function clean_inbox(){
			global $gdl_stdout,$gdl_db;
			
			$dbres	= $gdl_db->select("inbox","count(identifier) as total");
			$row 	= @mysqli_fetch_assoc($dbres);
			$total	= (int)$row["total"];
			
			$header = "<b>Clean Inbox</b>";
			if($total <= 0)
				$message = "<b>Inbox is empty</b>";
			else{
				$gdl_db->delete("inbox");
				if(mysqli_affected_rows($gdl_db->con) > 0)
					$message = "<b>Successfully clean inbox</b>";
				else
					$message = "<b>Cleaning  inbox failed</b>";
			}
			
			return $result = $gdl_stdout->print_message($header,$message);
		}
		
		function clean_outbox($status){
			global $gdl_stdout,$gdl_db;
			
			$dbres	= $gdl_db->select("outbox","count(identifier) as total");
			$row 	= @mysqli_fetch_assoc($dbres);
			$total	= (int)$row["total"];
			
			if(!empty($status)){
				$status_deleted = " with status is <i>$status</i>";
				$status				= str_replace("%","",$status);
			}
				
			$header = "<b>Clean Outbox</b>";
			if($total <= 0)
				$message = "<b>Outbox is empty</b>";
			else{
				$gdl_db->delete("outbox","folder = '$status' ");
				if(mysqli_affected_rows($gdl_db->con) > 0)
					$message = "<b>Successfully clean outbox $status_deleted</b>";
				else
					$message = "<b>Cleaning  outbox failed  $status_deleted</b>";
			}
			
			return $result = $gdl_stdout->print_message($header,$message);
		}
		
		function get_list_status_outbox(){
			global $gdl_db;
			
			$result = array();
			$dbres = $gdl_db->select("outbox","folder, count(folder) as total","","","","","status");
			
			if($dbres){
				while($rows = @mysqli_fetch_row($dbres)){
					$result[$rows[0]]['STATUS']	= $rows ['0'];
					$result[$rows[0]]['COUNT']		= $rows ['1'];
				}
			}
			return $result;
		}
		
		function extract_identifier_from_metadata($url_redirect){
			global $gdl_sync,$gdl_publisher,$gdl_db,$gdl_stdout;
		
			$token	= $_GET['token'];
			$token	= (preg_match("/^[0-9]+$/",$token))?$token:0;
			$limit		= $gdl_sync['sync_count_records'];
			$limit	= (preg_match("/^[0-9]+$/",$limit))?$limit:10;
			
			$cursor		= $token*$limit;
			$publisher	= $gdl_publisher['id'];
			$dbres	= $gdl_db->select("metadata","identifier","identifier like '$publisher-%' and status IS NULL and identifier NOT LIKE '%<%'","","","$cursor,$limit");
			
			$header = "<b>Extract identifier from metadata</b>";
			if(mysqli_num_rows($dbres) == 0) {
				if($token  > 0)
					$message = "<b>Successfully extract identifier from metadata that correspondent with your publisher </b>";
				else
					$message = "Empty metadata that correspondent with your publisher !! </b>";
					
				$result	= $gdl_stdout->print_message($header,$message);
			}else{
				while($row = mysqli_fetch_row($dbres)){
						$field = "type,identifier,status,folder,datemodified";
						$value = "'metadata','$row[0]','new','outbox',now()";
						$gdl_db->insert("outbox",$field,$value);
				}
				
				$token++;
				$message = "Please wait ................... (round <b>$token)</b>";
				$redirect = $gdl_stdout->header_redirect(1,$url_redirect."&amp;token=$token");
				$result	= $gdl_stdout->print_message($header,$message).$redirect;
			}
			
			return $result;
		}

	};

?>