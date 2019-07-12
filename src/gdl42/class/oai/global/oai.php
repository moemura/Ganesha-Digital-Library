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
 
if (eregi("oai.php",$_SERVER['PHP_SELF'])) {
    die();
}

class oai{

	var $action;
	var $default_verb;
	var $extended_verb;
	var $parent_set;
	var $data_type;
	var $main_url;
	
	
	var $verb;
	var $token;
	var	$from;
	var	$until;
	var $limit;
	var $set;
	var $metadataPrefix;
	var $identifier;
	var $epochTime;
	var $providerSerialNumber;
	var $providerId;
	var $countRecords;
	
	
	var $oai_sync;
	var $oai_db;
	var $oai_publisher;
	var $oai_http_session;
	var $oai_sys;
	var $oai_out;
	var $oai_metadata;
	var $oai_requestQuery;
	var $oai_requestQueryUser;
	
	/**
		Konstruktor
	*/
	function oai(){
		$this->init();	
	}
	
	function init(){
		global $gdl_sync,$gdl_db,$gdl_publisher,$HTTP_SESSION_VARS,$gdl_sys,$gdl_metadata,$gdl_client,$gdl_stdout;
		
		$this->default_verb 	= array("GetRecord","Identify","ListIdentifiers","ListMetadataFormats","ListRecords","ListSets");
		$this->extended_verb	= array("ListProviders","Connect","PutListRecords","RemoteLogin","PutFileFragment","MergeFileFragments");
		
		$this->oai_sync 		= $gdl_sync;
		$this->oai_db			= $gdl_db;
		$this->oai_publisher	= $gdl_publisher;
		$this->oai_http_session	= $HTTP_SESSION_VARS;
		$this->oai_sys			= $gdl_sys;
		$this->oai_metadata		= $gdl_metadata;
		$this->oai_out			= $gdl_stdout;
	}
	
	//
	function last_datemodified($box){
		$result	= "";
		$dbres 	= $this->oai_db->select($box,"datemodified","","datemodified","desc","0,1");
		if ($dbres){
			if (@mysql_num_rows($dbres)>0){
				$result['datemodified'] = @mysql_result($dbres,0,"DATEMODIFIED");
			} else {
				$result['datemodified'] = 0;
			}
		} else {
			$result['datemodified'] = 0;
			$result['error']		= "Date modified query error : ".mysql_error();
		}
		return $result;
	}
	//
	function get_request_parameter(){
		$query	= $_SERVER['QUERY_STRING'];
		$array 	= explode("&",$query);
		$args	= array();
		$double	= false;
		
		foreach($array as $val){
			$pos = strpos($val,"=");
			if (is_integer($pos)){
				$arg 	= substr($val,0,$pos);
				$value 	= substr($val,$pos+1);
				if(!empty($args[$arg])){
					$double = true;
					break;	
				}
				$args[$arg] = $value;
			}
		}
		
		if($double) return "double";
		return $args;
	}
	//
	function get_argument_request($arr_param){
		$args	= array();
		
		$i 		= 0;
		if(is_array($arr_param))
			foreach($arr_param as $index => $value){
				$args[$i] = $index;
				$i++;
			}
		
		return $args;
	}

	
		// value for boxname is inbox or outbox
	function Show_Status_Processing($data,$boxname,$identifiers){
		$title 	= $this->action;
		$token	= $this->token;
		
		$limit	= $this->oai_sync['sync_count_records'];
		
		if(empty($data[size])) $data[size]=0;
		
		//foreach ($data as $index => $value)
		//	echo "[$index] === [$value] <br>";
		
		if ($this->data_type == "file"){
			
			$post_desc = "<p><font size=1>
				<b>Filename:</b> $data[identifiers]
				<br/><b>Fragment(s):</b> $data[filename] 
				<br/><b>Size:</b> ".ceil($data[size]/1000)." K bytes
				<br/><b>Status:</b> $data[status]
				</font>";
			
		} else {
			
			if(is_array($data['connect_response'])){
				$data_koneksi	= "";
				foreach($data['connect_response'] as $index => $value)
					$data_koneksi .= "$index			: $value\n<br/>";
					
				$post_desc	= "<p><font size=1>$data_koneksi</font></p>";
				
			}else if(!eregi("Repository",$title)){
			
				$count_dummy	= (int)$data['dummy_count'];

				if($count_dummy > 0)
					$msg_dummy = "<b>Please be patient, there are $count_dummy record again to be harvested. </b><br/>";
				
				if(!empty($data['type']))
					$this->verb = $data['type'];	
				
				if($this->verb == "PutFileFragment"){
					$next_job	= trim($data['next_job']);
					if(!empty($next_job))
						$str_refresh = $this->oai_out->header_redirect(1,$this->main_url."&amp;verb=$next_job&amp;sub=1");
						
					$no_fragment 	= $data['no_fragment'];
					$c_fragment		= $data['count'];
										
					$bar	= "Posting file fragment $no_fragment from $c_fragment fragment(s) file.";
					
					$post_desc = "<p><font size=1>
						$identifiers".
						"<b>Successfully $action: $data[counter] records, size $data[size] bytes.</b><br/>".
						"<b>File Fragment(s):</b>".
						" $data[identifiers] <br/>".
						"<b>$bar</b>".
						"</font>$str_refresh";
				}else if($this->verb == "MergeFileFragments"){
				
					$next_job	= trim($data['next_job']);
					if(!empty($next_job))
						$str_refresh = $this->oai_out->header_redirect(1,$this->main_url."&amp;verb=$next_job&amp;sub=1");

					$post_desc = "<p><font size=1>
						$identifiers".
						"<b>Successfully merge file on repository target , size $data[size] bytes.</b><br/>".
						"<b>Filename : $data[filename]</b><br/>".
						"<b>Total Fragment : $data[count]</b><br/>".
						"<b>File Fragment(s):</b>".
						" <br/>&nbsp;$data[identifiers] <br/>".
						"</font>$str_refresh";
				}else{

					$count_record		= empty($data['count'])?0:$data['count'];
					$data_identifiers	= empty($data['identifiers'])?"metadata uptodate":$data['identifiers'];
					$post_desc = "<p><font size=1>
						$identifiers".
						"<b>Successfully $action: $count_record records, size $data[size] bytes.</b><br/>".
						"$msg_dummy".
						"<b>Metadata ID(s):</b> $data_identifiers ".
						"</font>";
					}
			}else{
				$post_desc = "<p><font size=1>
					<b>...::: Successfully update repository :::...</b>
					</font>";
			}
				
		}
		
		if (is_array($data)){

			if (!empty($data['total'])){

				$total = $data['total'];
				$count = $data['count'];
				$token = $data['token'];
			}
			
		} else {
			
			$sessname = "sess_".$boxname."_stats";
			$total = $this->oai_http_session[$sessname][FOLDER][$boxname];
			
		}
	
		$start = ($token-1)*$limit +1;
		$stop = $start + $limit -1;
		$deviasi_A = $stop - $start + 1;
		
		if($deviasi_A != $count){
			$lcount	= $_GET['lcount'];
			
			if($lcount > $count){
				$start 	= $total - $count + 1;
				$stop	= $total;
			}else{
				$start = ($token-1)*$count +1;
				$stop = $start + $count -1;
			}
		}
		
		if ($total <= $stop){
			$stop = $total;
		}
		
		if($start > $stop){
			$start	= $stop - $count;
			$count	= 0;
		}
		
		if($count > 0){
			$str_refresh = $this->oai_out->header_redirect(2,$this->main_url."&amp;verb=".$this->verb."&amp;resumptionToken=$token&amp;lcount=$count");
			$result .= $this->oai_out->print_message($title,$post_desc." <br/><strong>Harvesting : $start - $stop of $total</strong>");
			$result	.= $str_refresh;

		}else{
			
			$result .= $this->oai_out->print_message($title,$post_desc);
			if($this->verb == "PutListRecords"){
					$str_refresh = $this->oai_out->header_redirect(4,$this->main_url."&amp;verb=ListRecords");
					$result	.= $str_refresh;
			}

		}
		$result .= $this->oai_out->show_response($data['response']);
		return $result;
	}

	function format_response($element){
		//foreach ($this->oai_requestQuery as $index => $value) echo "$value ";
		$response	= $this->oai_metadata->generate_response_oaipmh($this->verb,$this->oai_requestQueryUser,$element,"oai_dc");
		return $response;
	}
	
}

?>