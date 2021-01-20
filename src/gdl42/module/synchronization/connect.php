<?php
global $gdl_synchronization,$gdl_stdout,$gdl_sync, $l,$HTTP_SESSION_VARS,$gdl_harvest;
$sub_operation = isset($_GET['sub']) ? $_GET['sub'] : null;

$title = "Connecting to Target Server: <b>$gdl_sync[sync_hub_server_name]</b>";
if(strcasecmp($sub_operation,"connecting") == 0){

	$msg = '';
	$ret_val = $gdl_synchronization->sync_connection($sub_operation);
	switch($ret_val){
		case -1 :// timeout
					$main = $gdl_stdout->print_message($l['timeout'],$title);
				break;
		case 0  :// failed					
					if(!preg_match("/<table/",$gdl_synchronization->response_connection["ERROR"])){

						$msg .= "<b>"._FAILEDCONNECTION."</b>
								<br>Error code: <b>".$gdl_synchronization->response_connection["ERROR_CODE"]
								."</b><br>&nbsp;&nbsp;Message from $gdl_sync[sync_hub_server_name]: <i><b>".$gdl_synchronization->response_connection["ERROR"]."</b></i>";
						$main = $gdl_stdout->print_message($title,$msg);
					}else
						$main = $gdl_synchronization->response_connection["ERROR"];
				break;
		case 1	://success
					$xml_response = $HTTP_SESSION_VARS[sess_val_response_xml];

					$msg	= 	"<table border=\"0\">".
									"<tr colspan=\"2\"><b>"._CONNECTED."</b></tr>".
									"<tr >".
										"<td ><div align=\"left\">sessionId</div></td>".
										"<td><div align=\"left\">$HTTP_SESSION_VARS[sess_connect_sessionid]</div></td>".
									"</tr>".
									"<tr >".
										"<td><div align=\"left\">providerId</div></td>".
										"<td><div align=\"left\">$HTTP_SESSION_VARS[sess_providerId]</div></td>".
									"</tr>".
									"<tr >".
										"<td><div align=\"left\">providerNetwork</div></td>".
										"<td><div align=\"left\">$HTTP_SESSION_VARS[sess_providerNetwork]</div></td>".
									"</tr>".
								"</table>";
							
					$main = $gdl_stdout->print_message($title,$msg);
					$main .= $gdl_stdout->show_response($gdl_synchronization->response_target);
				break;
	}
	
	
}else{
				
				$html = $gdl_stdout->header_redirect(2,"./gdl.php?mod=synchronization&amp;op=connect&amp;sub=connecting");
				$html .= "<h3>$title</h3>";
				$msg = "Trying connection to <b>Target Server ($gdl_sync[sync_hub_server_name])</b>.";
				
				if ($gdl_sync['sync_use_proxy']) 
					$msg .= "<br>Using proxy: <b>$gdl_sync[sync_proxy_server_address]:$gdl_sync[sync_proxy_server_port]</b>.";
				$msg .= "<br>Please wait... ";
				
				$main = $html.$gdl_stdout->print_message(_CONNECTION,$msg);
}

$main = gdl_content_box($main,_CONNECTION);
$gdl_content->set_main($main); 
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=synchronization\">"._SYNCHRONIZATION."</a>";

?>