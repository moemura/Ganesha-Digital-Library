<?php

if (eregi("repeater.php",$_SERVER['PHP_SELF'])) {
    die();
}

class repeater{
	
	var $header = array();
	var $item = array();
	var $footer = array();
	var $colwidth = array();
	
	function generate($width=""){
		global $gdl_content;
		
		// generate style
		if ($width=="") $width="99%";
		$colwidth=$this->colwidth;
		$id=substr(microtime(),4,2);
		$style = "table.t$id{\n"
				."width: $width;\n"
				."margin: 0px;\n"
				."padding: 0px;\n"
				."}\n";
		if (!empty($colwidth)){
			while (list($key, $val) = each($colwidth)) {
				if ($val<>""){
					$style .= "th.c$id$key{\n"
						."width: $val;\n"
						."}\n";
				}
			}
		}	
		
		$gdl_content->set_style($style);
		
		$main = "<table class=\"t$id\">\n";
		// generate header
		if(!empty($this->header)){
			$main .= "<tr>";
			while (list($key, $val) = each($this->header)) {
				if(empty($colwidth[$key])){
					$main .= "<th>$val</th>";
				}else{
					$main .= "<th class=\"c$id$key\">$val</th>";
				}
			}
			$main .= "</tr>\n";
		}
		
		// generate item
		$num = 1;
		if (is_array($this->item)) {
		while (list($itemkey, $itemval) = each($this->item)) {
			if ($num % 2 == 0){
				$main .= "<tr class=\"bg2\">";
		   	}else{
				$main .= "<tr class=\"bg1\">";
		   	}
			
			while (list($key, $val) = each($itemval)) {
				$main .= "<td>$val</td>";
			}
			
			$main .= "</tr>\n";
			$num ++;
		}
		}
		
		// generate footer
		if (!empty($this->footer)){
			$main .= "<tr>";
			while (list($key, $val) = each($this->footer)) {
				$main .= "<td>$val</td>";
			}
			$main .= "</tr>\n";
		}
		$main .= "</table>\n";
		return $main;
	}
}

?>