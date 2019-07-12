<?php
if (eregi("harvest.php",$_SERVER['PHP_SELF'])) {
    die();
}

class harvest{

	function operation_navigator_harvest($main_url){
		$table = "<table align='center' border='0' >".
						"<tr bgcolor=\"#6666CC\" style=\"color:#ffffff;\"  height=\"20px\">".
							"<td colspan='2' align='center'><b> Harvesting Operation</b> </td>".
						"</tr>".
						"<tr bgcolor=\"#CCCCFF\" height=\"25px\">".
							"<td align='center'><a href=\"$main_url&amp;verb=ListProviders\"><b>Harvest Publisher</b></a></td>".
							"<td align='center'><a href=\"$main_url&amp;&amp;sub=0&amp;verb=ListRecords\"><b>Harvest Metadata</b></a></td>".
						//	"<td align='center'><a href=\"$main_url&amp;verb=ListIdentifiers\"><b>Harvest Identifier</b></a></td>".
						"</tr>".
		 		"</table><br>";
		return $table;
	}
	
	function operation_navigator_posting($main_url){
		$table = "<table align='center' border='0'>
						<tr bgcolor=\"#6666CC\" style=\"color:#ffffff;\"  height=\"20px\">
							<td colspan='2' align='center'> <b>Posting Operation</b> </td>
						</tr>
						<tr bgcolor=\"#CCCCFF\" height=\"25px\">
							<td align='center'><a href=\"$main_url&amp;sub=0&amp;verb=PutListRecords\"><b>Posting List Records</b></a></td>
							<td align='center'><a href=\"$main_url&amp;sub=1\"><b>Posting File</b></a></td>
						</tr>
		 		</table><br>";
		return $table;
	}
	
	function execute_verb($verb){
		global $gdl_oaipmh,$gdl_synchronization,$gdl_stdout,$gdl_sync;

		if($gdl_sync['sync_opt_script'] != "0") $gdl_synchronization->sync_disconnection();

		if($gdl_sync['sync_opt_script'] == 0){
		
			if($gdl_synchronization->is_connected() || ($verb == "Connect")){
				$rs_execute = $gdl_oaipmh->execute_harvest_verb($verb);
				if(!empty($rs_execute['error-type'])){
					$result = $rs_execute['error'];
				}else{
					$result	= $rs_execute['show'];
				}
			}else{
				$title		= "ATTENTION";
				$post_desc	= "<b>YOU MUST CONNECT TO HUB SERVER</b>";
				$result 	= $gdl_stdout->print_message($title,$post_desc);
			}
		}else if($gdl_sync['sync_opt_script'] == 1){
			$rs_execute = $gdl_oaipmh->execute_harvest_verb($verb);
			if(!empty($rs_execute['error-type'])){
				$title	= "ATTENTION";
				$result = $gdl_stdout->print_message($title,"<strong>".$rs_execute['error']."</strong>");
			}else{
				$result	= $rs_execute['show'];
			}
		}else{
			$title		= "ATTENTION";
			$post_desc	= "<b>YOUR METADATA FORMAT DID NOT VALID</b>";
			$result 	= $gdl_stdout->print_message($title,$post_desc);			
		}
		
		return $result;
	}
	
	function response_verb($verb){
		global $gdl_oaipmp;
		$result = $gdl_oaipmp->execute_response_verb($verb);
		return $result;
	}
	
}
?>