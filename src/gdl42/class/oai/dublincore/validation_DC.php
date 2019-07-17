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
 
 if (preg_match("/validation_DC.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class validation_DC extends  validation_oai {
	var $rs_extractResumptionToken;
	
	
	// Compare argument
	// verb merupakan verb yang direquest
	// req_query dari request query
	function check_arguments($verb,$req_query){
		$check	= 1;
		
		$check = $this->cek_init_state_CA($verb,$req_query);
		
		if(!in_array($verb,$this->oai_oai->default_verb) && $check){
			$this->oai_errFormatter->error_format_verb();
			$check	= 0;
		}
		
		if($check){
			$this->error_code	= "";
			$this->error_msg	= "";
	
			$std_query	= $this->std_query_dublincore($verb);
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
		$array_exclusive = array("ListIdentifiers","ListRecords");
		
		$arg_query = $this->oai_oai->get_argument_request($req_query);
		if(empty($std_query['argument'])){
			$this->oai_errFormatter->error_formatter("","");
			return 0;
		};
				
		//if(sizeof($arg_query)>= sizeof($std_query['argument'])){
		
		$diff = array_diff($arg_query,$std_query['argument']);
			
		/*}else{
			if(in_array($verb,$array_exclusive)){
					 //echo "".count($arg_query)." ".count($std_query['argument']);
					if((in_array("verb",$arg_query)) && (in_array("resumptionToken",$arg_query))){
						$diff = array_diff($arg_query,$std_query['argument']);	
					}else
						$diff = array_diff($std_query['argument'],$arg_query);
					
			}else
					$diff = array_diff($std_query['argument'],$arg_query);
		}
		*/
		//foreach($diff as $index => $value) echo "xxx==> $index --> $value<br/>";
		
		$result = 0;
		if(sizeof($diff)> 0){
				
			$arg_opt	= $std_query['optional'];
			if(sizeof($arg_opt)> 0){
				$d_opt	= array_diff($diff,$arg_opt);
				if(sizeof($d_opt) > 0){
					$this->oai_errFormatter->error_formatter($verb,"argument");
				}else{
					$result = $this->value_validation_request($verb,$arg_query,$req_query);
				}
				
			}else{
				$this->oai_errFormatter->error_formatter($verb,"argument");
			}
			
		}else
			$result = $this->value_validation_request($verb,$arg_query,$req_query);

		
		return $result;
	}
	
	function value_validation_request($verb,$argument_query,$request_query){
		$array_parameter	= array("verb","resumptionToken","metadataPrefix","identifier","from","until","set");
		$c_parameter		= count($array_parameter);
		$arr_parameter		= array();
		for($i=0;$i<$c_parameter;$i++){
			if(in_array($array_parameter[$i],$argument_query))
				array_push($arr_parameter,$array_parameter[$i]);
		}
		
		$argument_query	= $arr_parameter;
		//foreach($argument_query as $index => $value) echo "$value ";
		
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
											if(isset($identifier) ){
												if(empty($identifier)){
													$case = "argument";
													$stop = 1;
												}else{
													$dbres = $this->oai_db->select("metadata","identifier","identifier like '$identifier'");
													if(@mysqli_num_rows($dbres) != 1)
														$stop = 1;
												}
											}else
												$stop = 1;
											break;
											
				case "metadataPrefix"	:	$case		= "format";
											if($request_query['metadataPrefix'] != "oai_dc") $stop = 1;
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
											
				case "resumptionToken"	:	$case			= "token";
											$num_parameter	= count($argument_query);
											if($num_parameter > 2){
												// check conditional exclusive resumptionToken
												$case = "exclusive";
												$stop = 1;
											}else 
												$stop = ($this->cek_formatToken_dublincore($request_query['resumptionToken']))?0:1;

											break;
				
			}
			$i++;
		}while(!$stop && ($i<$count));
		
		if($stop) $this->oai_errFormatter->error_formatter($verb,$case,$additional);
		return ($stop)?0:1;
	}
	
	function cek_formatToken_dublincore($resumptionToken){
		// format token for GDL with format metadata dublincore is
		// from:until:set:control:oai_dc
		
		$this->rs_extractResumptionToken = null;
		$failed		= false;
		$arr_node	= explode("::",$resumptionToken);
		
		// check total none. Number of node must 5
		$failed = (count($arr_node) == 5)?false:true;
		
		// format metadata must oai_dc
		if(!$failed){ $cek = 0;
			$failed	= ($arr_node[4] == "oai_dc")?false:true;
		}
		
		// control token must number
		if(!$failed){$cek = 1;
			$failed		= ($this->cek_valid_number_format($arr_node[3]))?false:true;
			$num_token	= (int)$arr_node[3];
			$failed 	= ($num_token > 0)?false:true;
		}
		
		// control set must number
		if(!$failed){$cek = 2;
			$failed = ($this->cek_valid_number_format($arr_node[2]))?false:true;
		}
		
		if(!$failed){$cek = 3;
			$arr_node[2]	= "under:node:$arr_node[2]";
		}
		
		// check format until parameter
		if(!$failed){
			// parameter until has format is 0
			$failed = ($this->cek_valid_number_format($arr_node[1]))?false:true;
			
			if(!$failed){$cek = 4;
				$failed	= ($arr_node[1] == 0)?false:true;
			}
			
			if(!$failed && (strlen($arr_node[1] > 1))){$cek = 5;
				$failed = ($this->cek_valid_date_format($arr_node[1]))?false:true;
			}
			
			if(!$failed && ($arr_node[1] == "0")){$cek = 6;
				$arr_node[1] = null;
			}
			
		}
		
		// check format from parameter
		if(!$failed){$cek = 7;
			// parameter from has format is 0
			//echo "Date-from : $arr_node[0]<br/>";
			$failed = ($this->cek_valid_number_format($arr_node[0]))?false:true;
						
			if(!$failed){$cek = 8;
				$failed	= ($arr_node[0] == "0")?false:true;
			}else if($failed && (strlen($arr_node[0] > 1))){$cek = 9;
				$failed = ($this->cek_valid_date_format($arr_node[0]))?false:true;
			}
			
			if(!$failed && ($arr_node[0] == "0")){$cek = 10;
				$arr_node[0] = null;
			}
		}
		
		//echo "Cek : $cek";
		if(!$failed)
			$this->rs_extractResumptionToken = $arr_node;
			
		return (!$failed)?true:false;
	}
}

?>