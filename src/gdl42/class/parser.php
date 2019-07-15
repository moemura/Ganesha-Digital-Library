<?php

if (preg_match("/parser.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class parser {

	function read_xml($xml_data){

		// htmlspecialchars table
		$trans = get_html_translation_table(HTML_SPECIALCHARS);
		$trans = array_flip($trans);
		$xml = $this->readXML($xml_data);
		if (is_array($xml)){
			while (list($key,$val) = each($xml)){
				$elem = str_replace(".","_",$key);
				$xdata = $xml[$key][0];
				$xdata = utf8_decode($xdata);
				$xdata = str_replace("<br />"," \n",$xdata);
				$xdata = strtr($xdata,$trans);			
				$frm[$elem] = $xdata;
			}
		}
		return $frm;
	}

	function parseMol($mvalues) {
		for ($j=0; $j < count($mvalues); $j++) {
			// reset
			unset($value);
			// get the arrays
			$tag = strtoupper($mvalues[$j]["tag"]);
			$type = $mvalues[$j]["type"];
			$level = $mvalues[$j]["level"];
			$value = $mvalues[$j]["value"];
			$attributes = $mvalues[$j]["attributes"];

			switch($type){
				case "complete":
					
					if ($level > 2){
						$mytag = $this->generateTags($tags).".$tag";
					} else {
						$mytag = $tag;
					}
					
					// is attributes exist
					if (is_array($attributes)){
						while (list($key,$val) = each($attributes)){
							$xml["$mytag.$key"][] = $val;
						}
					}
					
					// is value exists
					if (!empty($value)){
						$xml[$mytag][] = $value;
					}
					break;
				case "open":
					$tags[$level] = $tag;
					break;
				case "close":
					array_pop($tags);
					break;
			}
		}
	
		return $xml;
	}

	function generateTags($tags)
	{
		reset($tags);
		
		while(list($key,$val) = each($tags)){
			if (empty($tag))
				$tag_path = $val;
			else
				$tag_path .= ".$val";
		}
		return $tag_path;
	}

	function readXML($xmldata) {
		
		$xmldata = $this->cleanupXML($xmldata);
		$xsize = strlen($xmldata);		
		$xmldata = stripslashes($xmldata);
		$parser = xml_parser_create();
		xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,1);
		xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
		$vl = &$values;
		$tg = &$tags;
		xml_parse_into_struct($parser,$xmldata,$vl,$tg);
		xml_parser_free($parser);
	
		foreach ($tags as $key=>$val) {
			$root++;
			if ($root == 1){
				$molranges = $val;
				for ($i=0; $i < count($molranges); $i+=2) {
					$offset = $molranges[$i] + 1;
					$len = $molranges[$i + 1] - $offset;
					$pmv = array_slice($values, $offset, $len);
					$xml = $this->parseMol($pmv);
				}
			} else {
				return $xml;
			}
			$root = FALSE;
		}
	}

	function cleanupXML($xmldata){
		$trans_1 = get_html_translation_table(HTML_ENTITIES);
		$trans_2 = get_html_translation_table(HTML_SPECIALCHARS);
		$trans = array_diff($trans_1,$trans_2);
		$trans = array_flip($trans);
		$data = strtr($xmldata,$trans);
		return $data;
	}

	function generate_xml($frm){
		
		// get schema
		$schema = "./schema/$frm[TYPE_SCHEMA].xml";
		if (file_exists($schema)){
			$str_meta = implode('',file($schema));
			while(list($key,$val) = each($frm)){
				$value = htmlspecialchars(nl2br(utf8_encode($val)),ENT_QUOTES);
				$str_meta = str_replace("#$key#",$value,$str_meta);
			}
			
			$str_meta = addslashes($str_meta);
			
			if ($frm['RELATION_COUNT']==0){
				// clean up empty XML
				$str_meta = ereg_replace("#[[:alpha:]]+#","",$str_meta);
				$str_meta = ereg_replace("#[[:alpha:]]+_[[:alpha:]]+#","",$str_meta);
				$str_meta = ereg_replace("#[[:alpha:]]+_[[:alpha:]]+_[[:alpha:]]+#","",$str_meta);
			}
			
			return $str_meta;
		} else {
			return 0;
		}
	}
	
}

?>