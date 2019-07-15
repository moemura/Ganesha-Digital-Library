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
 
 if (preg_match("/validation_GN.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class validation_GN extends validation_oai {
	
	// Compare argument
	// verb merupakan verb yang direquest
	// req_query dari request query
	function check_arguments($verb,$req_query){
		$check 	= 1;
		
		$check = $this->cek_init_state_CA($verb,$req_query);
		
		if(!in_array($verb,$this->oai_oai->default_verb) && $check && !in_array($verb,$this->oai_oai->extended_verb)){
			$this->oai_errFormatter->error_format_verb();
			$check	= 0;
		}
		
		if($check){
			$std_query	= $this->std_query_general($verb);
			$check 		= $this->compare_arguments($std_query,$req_query,$verb);
		}
		
		$this->init_errorMessage();
		
		return ($check)?1:0;
	}
	
	// Compare argument
	// std_query dari standard query
	// req_query dari request query
	// element array arg_query terdiri dari argument dan standard
	function compare_arguments($std_query,$req_query,$verb){
		$compare = 1;
		
		$arg_query = $this->oai_oai->get_argument_request($req_query);
		if(empty($std_query['argument'])){
			$this->oai_errFormatter->error_formatter("","");
			$compare = 0;
		};

		if($compare){
			
			if(sizeof($arg_query) <= sizeof($std_query['argument'])){
				$diff = array_diff($std_query['argument'],$arg_query);
			}else
				$diff = array_diff($arg_query,$std_query['argument']);
			
			if(sizeof($diff) > 0){
					
					$buffering = $diff;
					$val = array_pop($buffering);

					if((count($diff) == 1) && ($val == "until")){
						// no action	
					}else{
						$this->oai_errFormatter->error_formatter("","argument");
						$compare	= 0;
					}
			}else{
				//cek session
				
				if($verb != "Connect"){
					$compare = $this->cek_session_request(trim($req_query['PHPSESSID']));
				}
			}
			
		}else
			$compare = $this->value_validation_request($verb,$arg_query,$req_query);
		
		return $compare;
	}
	
	function cek_session_request($sess_id){
		global $HTTP_SESSION_VARS;
		
		if(empty($sess_id) || ($sess_id == "0")){
			$this->oai_errFormatter->error_formatter("","session");
			return false;
		}
		$session_client	= $HTTP_SESSION_VARS['session_client'];
		$check = ($sess_id == $session_client)?true:false; // only for development
		
		if(!$check)
			$this->oai_errFormatter->error_formatter("","session");
		
		return $check;
	}
	
	function value_validation_request($verb,$argument_query,$request_query){
		$stop 	= 0;
		$i		= 0;
		$case	= "";
		$count 	= count($argument_query);
		do{
			switch($argument_query[$i]){
				case "verb"				: 	// pass
											break;
				case "identifier"		:	$case		= "record";
											$identifier = $request_query['identifier'];
											if(isset($identifier)){
												$dbres = $this->oai_db->select("metadata","identifier","identifier like '$identifier'");
												if(@mysql_num_rows($dbres) != 1)
													$stop = 1;
											}else
												$stop = 1;
											break;
											
				case "metadataPrefix"	:	$case		= "format";
											if(($request_query['metadataPrefix'] != "general") || (!empty($request_query['metadataPrefix']))) $stop = 1;
											break;
											
				case "from"				:	$case		= "date";
											$additional	= "from";
											if(!$this->cek_valid_date_format($request_query['from']))$stop=1;
											break;
											
				case "until"			:	$case		= "date";
											$additional	= "until";
											if(!$this->cek_valid_date_format($request_query['until']))$stop=1;
											break;
											
				case "set"				:	$case		= "set";
											if(!$this->cek_valid_set_format($request_query['set']))$stop=1;
											break;
											
				case "resumptionToken"	:	$case		= "token";
											if(!$this->cek_valid_number_format($request_query['resumptionToken']))$stop=1;
											break;
				
			}
			$i++;
		}while(!$stop && ($i<$count));
		
		if($stop) $this->oai_errFormatter->error_formatter($verb,$case,$additional);
		return ($stop)?0:1;
	}
	
}
?>