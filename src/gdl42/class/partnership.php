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
 
class partnership{
	
	var $remote_result;
	
	function is_remoted(){
		global $HTTP_SESSION_VARS;
		return (empty($HTTP_SESSION_VARS['sess_remote_sessionid']))?false:true;
	}
	
	function execute_remoteLogin($id_record){
		global $gdl_db,$gdl_stdout,$gdl_metadata,$gdl_sync,$gdl_session,$gdl_publisher;
				
		$dbres	= $gdl_db->select("publisher","DC_PUBLISHER_ID","IDPUBLISHER = $id_record");
		$pub_id	= @mysql_result($dbres,0,"DC_PUBLISHER_ID");
		$remote	= 0;
		if(!empty($pub_id)){
			$dbres		= $gdl_db->select("repository","host_url,oai_script","id_publisher like '$pub_id'");
			$hub_server	= @mysql_result($dbres,0,"host_url");
			$script		= @mysql_result($dbres,0,"oai_script");
			
//			echo "[$hub_server][$script][$pub_id]";
			if(!empty($hub_server) && !empty($script)){
				$remote	= $this->remoteLogin($hub_server,$script);
			}
		}
		
		$title	= "Remote login";
//		echo "Remote : $remote";
		if($remote){
								
			$response	= $this->remote_result['response'];
			$pos_A	= strpos($response,"<RemoteLogin>");
			$pos_B	= strpos($response,"</RemoteLogin>");
			
			$xml_response	= substr($response,$pos_A,$pos_B-$pos_A+14);
			$xmldata		= $gdl_metadata->readXML("<DATA>$xml_response</DATA>");

			$sess_id	= $xmldata['REMOTELOGIN.SIGNATURE'][0];
			$providerId	= $xmldata['REMOTELOGIN.PROVIDERID'][0];
			$network	= $xmldata['REMOTELOGIN.PROVIDERNETWORK'][0];
			$start_sess	= $xmldata['REMOTELOGIN.STARTSESSION'][0];
			$user_id	= $xmldata['REMOTELOGIN.REMOTEUSER'][0];
			$username	= urldecode($xmldata['REMOTELOGIN.REMOTENAME'][0]);
			
			if(	empty($sess_id) || empty($providerId) || 
				empty($network) || empty($start_sess) || 
				empty($user_id) || empty($username)) $remote = false;
			
			if(!$remote){
				$msg	= "<b>Unable remote login to your repository target. Partner does not suport remote login</b>";
			}else{
				$d1	= "<div align=\"left\">";
				$d2	= "</div>";
				$hub_server	= $gdl_sync['sync_hub_server_name'];
				
				$gdl_session->session_remote($sess_id,$gdl_session->user_id,$gdl_session->user_name,$gdl_publisher['id']);
				$hub_server	= (ereg("http",$hub_server))?$hub_server:"http://$hub_server";
				$redirect 	= $this->form_Posting_InfoRemoteLogin($hub_server,$xmldata);

				$msg		= "<table>\n".
									"<tr colspan=\"2\">Remote Login Information</tr>".
									"<tr ><td>$d1 Signature $d2</td><td>$d1 $sess_id $d2</td></tr>".
									"<tr ><td>$d1 Start session $d2</td><td>$d1 $start_sess $d2</td></tr>".
									"<tr ><td>$d1 Provider ID $d2</td><td>$d1 $providerId $d2</td></tr>".
									"<tr ><td>$d1 Network $d2</td><td>$d1 $network $d2</td></tr>".
									"<tr ><td>$d1 User Login $d2</td><td>$d1 $user_id $d2</td></tr>".
									"<tr ><td>$d1 Username $d2</td><td>$d1 $username $d2</td></tr>".
							  "</table>" ;
				
				$msg		.= "<br/>$redirect";
			}
		}else{
			$msg	= "Unable remote login to your repository target";
		}
		
		return $gdl_stdout->print_message($title,$msg).$refresh;
	}
	
	function form_Posting_InfoRemoteLogin($redirect,$xmldata){
		global $gdl_session;
		
		$signature		= $xmldata['REMOTELOGIN.SIGNATURE'][0];
		$epochTime		= $xmldata['REMOTELOGIN.EPOCHTIME'][0];
		$md_signature	= $xmldata['REMOTELOGIN.MDSIGNATURE'][0];
		$remote_session	= $gdl_session->remote_session;
		$user_id		= $xmldata['REMOTELOGIN.REMOTEUSER'][0];
		$username		= urldecode($xmldata['REMOTELOGIN.REMOTENAME'][0]);
		$user_signature	= $gdl_session->user_signature;
		
		$code = "\n<form action=\"$redirect\" method=\"post\" enctype=\"application/x-www-form-urlencoded\" >\n".
					"<label>Click </label>\n".
					"<input type=\"submit\" name=\"relog[action]\" value=\"Here\" />\n".
					"<label>to redirect  to your repository target (<b>$redirect</b>)</label>\n".
					"<input type=\"hidden\" name=\"relog[remote_signature]\" value=\"$signature\" />\n".
					"<input type=\"hidden\" name=\"relog[epochTime]\" value=\"$epochTime\" />\n".
					"<input type=\"hidden\" name=\"relog[md_signature]\" value=\"$md_signature\" />\n".
					"<input type=\"hidden\" name=\"relog[remote_session]\" value=\"$remote_session\" />\n".
					"<input type=\"hidden\" name=\"relog[user_id]\" value=\"$user_id\" />\n".
					"<input type=\"hidden\" name=\"relog[username]\" value=\"$username\" />\n".
					"<input type=\"hidden\" name=\"relog[user_signature]\" value=\"$user_signature\" />\n".
				"</form>\n";
		return $code;
	}
	
	function remoteLogin($hub_server,$script_oai){
		global $gdl_session,$gdl_sync;
		
		// Cek apakah user merupakan remote user dari publisher lain
		if(!empty($_COOKIE['sess_remote_user'])){
			$remoteUser	= $_COOKIE['sess_remote_user'];
			$remoteName	= $_COOKIE['sess_remote_name'];
		}else{
			$remoteUser	= $gdl_session->user_id;
			$remoteName	= $gdl_session->user_name;
		}
		
		$gdl_sync['sync_hub_server_name']	= $hub_server;
		$gdl_sync['sync_oai_script']		= $script_oai;
		
		$connection	= $this->sync_remote();
		
		$remote	= 0;
		if($connection == 1){ 
			$remote_sync		= $this->sync_remoteUser($remoteUser,$remoteName);
			if($remote_sync == 1) $remote = 1;
		}
		
		return $remote;
	}
	

	function sync_remoteUser($remoteUser,$remoteName){
		global $gdl_harvest;

		$gdl_harvest->harvest_remoteLogin['user']	= $remoteUser;
		$gdl_harvest->harvest_remoteLogin['name']	= $remoteName;
		
		$rs_remote 	= $gdl_harvest->execute_verb("RemoteLogin");
		$this->remote_result	= $rs_remote;
		return (empty($rs_remote['error']))?1:0;
	}
	
	function sync_remote(){
		global $gdl_harvest,$gdl_metadata;
		
		$gdl_harvest->init(0);
		$rs_remote 	= $gdl_harvest->execute_verb("Connect");
		$remote		= 0;
			
		if(empty($result['error'])){
			$response_xml	= $rs_remote['connect_response'];
			$this->sync_remote_status($response_xml);
			$remote	= 1;
		}
		return $remote;
	}
	
	function sync_remote_status($xml){
			
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
	
}

?>