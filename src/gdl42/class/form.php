<?php

if (preg_match("/form.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class form{
	 
	var $method="post";
	var $action="";
	var $field=array();
	var $button=array();
	var $required="";
	var $enctype=false;
	var $upload="";
	var $column=false;
	
	function __construct(){
		$this->init();
	}
	
	function init(){
		$this->required=isset($_SESSION['gdl_required']) ? $_SESSION['gdl_required'] : null;
		$_SESSION['gdl_required']="";
		if(isset($_SESSION['gdl_upload'])){
			$this->upload=$_SESSION['gdl_upload'];
		}
	}
	
	function set_name($name){
		$_SESSION['gdl_upload'] = $name;
	}

	function add_field($newfield){
		
		$column = true;
		if (isset($newfield['column'])) $column = $newfield['column'];
		if ($column==true) $this->column=true;
		if (isset($newfield['text']) and $newfield['type']<>"title"){
		
			// khusus untuk schema metadata yg mempunyai name $frm[TITLE] dan sejenisnya
			$required = preg_replace("/\[/", "", $this->required);
			$required = trim(preg_replace("/\]/", "", $required));
			$fields_required = preg_replace("/\[/", "", $newfield['name']);
			$fields_required = trim(preg_replace("/\]/", "", $fields_required));
			
			if (!($fields_required=="" or $required=="") and isset($newfield['value']) and $newfield['value']==""){
				if (preg_match('/'.$fields_required.'/',$required)){
					$title = "<span class=\"red\">$newfield[text]</span>";
				}else{
					$title = "$newfield[text]";
				}
			}else{
					$title = "$newfield[text]";
			}
			
			if ((!empty($newfield['required']))and($newfield['required'])==true) {
				$_SESSION['gdl_required'] = $_SESSION['gdl_required']." ".$newfield['name'];
				$title .= " <span class=\"red\">*</span>";
			}
		}
		
		$new_field['column']=$column;
		
		$field = '';
		switch ($newfield['type']){
			case 'select':
				$arr = isset($newfield['option']) ? $newfield['option'] : null;
				$key_value = isset($newfield['value']) ? $newfield['value'] : null;
				$field = "<select name=\"$newfield[name]\">\n";
				if(isset($key_value) and $key_value<>""){
					$field .= "<option value=\"$key_value\">$arr[$key_value]</option>\n";
					$field .= "<option value=\"$key_value\">----------</option>\n";
				}
				if(is_array($arr))
					foreach ($arr as $key => $val) {
						$field .= "<option value=\"$key\">$val</option>\n";
					}
				$field .= "</select>";
				$new_field['type']="select";
				$new_field['title']="$title";
				$new_field['field']=$field;
				break;
			case 'radio':
				$arr = $newfield['checked'];
				$key_value = $newfield['value'];

				foreach ($arr as $key => $val) {
					if(isset($key_value) and $key_value<>"" and ($key_value == $key))
						$sign ="checked";
					else
					    $sign ="";					
					$field .= "<input type=\"radio\" name=\"$newfield[name]\" value=\"$key\" $sign/>$val";
				}
				
				$new_field['type']="radio";
				$new_field['title']="$title";
				$new_field['field']=$field;
				break;

			case 'textarea':
				$field = "<textarea name=\"$newfield[name]\"";
				if (isset($newfield['cols'])) $field .= " cols=\"$newfield[cols]\"";
				if (isset($newfield['rows'])) $field .= " rows=\"$newfield[rows]\"";
				$field .= ">";
				if (isset($newfield['value'])) $field .= "$newfield[value]";
				$field .= "</textarea>";
				$new_field['type']="textarea";
				$new_field['title']="$title";
				$new_field['field']=$field;
				break;
			case 'hidden':
				$field = "<input type=\"$newfield[type]\" name=\"$newfield[name]\"";
				if (isset($newfield['value'])) $field .= " value=\"$newfield[value]\"";
				$field .= "/>\n";
				$new_field['type']="hidden";
				$new_field['field']=$field;
				break;
			case 'title':
				$new_field['type']="title";
				$new_field['title']="$newfield[text]";
				break;
			case 'file' :
				$field = "<input type=\"$newfield[type]\" name=\"$newfield[name]\" />\n";
				$new_field['type']="file";
				$new_field['title']="$title";
				$new_field['field']=$field;
				break;
			default:
				$field = "<input type=\"$newfield[type]\" name=\"$newfield[name]\"";
				if(isset($newfield['id'])) $field .= " id=\"$newfield[id]\"";
				if (isset($newfield['value'])) $field .= " value=\"$newfield[value]\"";
				if (isset($newfield['size'])) $field .= " size=\"$newfield[size]\"";
				$field .= "/>";
				$new_field['type']="$newfield[type]";
				$new_field['title']="$title";
				$new_field['field']=$field;
				break;

		}
		$set_field=$this->field;
		$set_field[]=$new_field;
		$this->field=$set_field;
	}
	
	function add_button($newbutton){
		$column = true;
		if (isset($newbutton['column'])) $column = $newbutton['column'];
		if ($column==true) $this->column=true;
		
		$button = "<input class=\"button\" type=\"$newbutton[type]\" name=\"$newbutton[name]\"";
		if (isset($newbutton['value'])) $button .= " value=\"$newbutton[value]\"";
		if (isset($newbutton['size'])) $button .= " size=\"$newbutton[size]\"";
		if (isset($newbutton['onclick'])) $button .= " onClick=\"$newbutton[onclick]\"";
		$button .= "/>";
		
		$new_button['column'] = $column;
		$new_button['field'] = $button;
		
		$set_button=$this->button;
		$set_button[] = $new_button;
		$this->button=$set_button;
	}
		
	function generate($colwidth="",$width=""){
		global $gdl_content;
		
		if ($width=="") $width="99%";
		if ($colwidth=="") $colwidth="20%";
		
		$style = "table.form{\n"
			."width: $width;\n"
			."}\n"
			."td.label{\n"
			."width: $colwidth;\n"
			."padding-left: 20px;\n"
			."}\n"
			."td.colspan{\n"
			."padding-left: 20px;\n"
			."}\n"
			."span.red { "
			."color: #FF0000; "
			."}\n";
		$gdl_content->set_style($style);

		if ($this->enctype) {
			$html = "<form enctype=\"multipart/form-data\" method=\"".$this->method."\" action=\"".$this->action."\">\n";
		}else{
			$html = "<form method=\"".$this->method."\" action=\"".$this->action."\">\n";
		}
		
		$html .= "<table class=\"form\">\n";
		
		if($this->column==true){
			// generated form using table 2 column
			// generated field
			foreach ($this->field as $key => $val) {
				if ($val['type']=="hidden"){
					$html .= $val['field'];
				}
				if ($val['type']=="title"){
					$html .= "<tr class=\"bg2\">";
					$html .= "<th class=\"title\" colspan=\"2\"><b>$val[title]<b></th>";
					$html .= "</tr>\n";
				}elseif($val['type']<>"hidden"){
					$html .= "<tr class=\"bg1\">";
					if($val['column']==true){
						$html .= "<td class=\"label\">$val[title]</td>";
						$html .= "<td>$val[field]</td>";
					}else{
						$html .= "<td class=\"colspan\" colspan=\"2\">$val[title]<br/>";
						$html .= "$val[field]</td>";
					}
					$html .= "</tr>\n";
				}
			}
			
			// generated button
			foreach ($this->button as $key => $val) {
				if ($key==0){
					$button = "$val[field]";
				}else{
					$button .= "&nbsp; &nbsp; $val[field]";
				}
			}
				$html .= "<tr class=\"bg3\">";
				$html .= "<td class=\"button\" colspan=\"2\">$button</td>";
				$html .= "</tr>\n";
		}else{
			// generated form using table 1 column
			// generated field
			foreach ($this->field as $key => $val) {
				if ($val['type']=="title"){
					$html .= "<tr class=\"bg2\">";
					$html .= "<td class=\"title\"><b>$val[title]<b></td>";
					$html .= "</tr>\n";
				}elseif($val['type']<>"hidden"){
					// generated grid color
					$html .= "<tr class=\"bg1\">";
					$html .= "<td class=\"title\">$val[title]<br/>";
					$html .= "$val[field]</td>";
					$html .= "</tr>\n";
				}
			}
			
			// generated button
			foreach ($this->button as $key => $val) {
				if ($key==0){
					$button = "$val[field]";
				}else{
					$button .= "&nbsp; &nbsp; $val[field]";
				}
			}
			$html .= "<tr class=\"bg3\">";
			$html .= "<td class=\"button\">$button</td>";
			$html .= "</tr>\n";
		}
		$html .= "</table>\n";
		$html .="</form>\n";
		return $html;
	}
	
	function verification($values=""){

		if ($values==""){
			$_SESSION['gdl_required']="";
			$this->required="";
			return true;
		}else{

			$data = explode(" ",$this->required);
			$required="";
			foreach ($data as $key => $val) {

				if($val<>""){
					if (is_array($data)){
						// khusus untuk schema metadata yg mempunyai name $frm[TITLE] dan sejenisnya
						$arr_key = preg_replace("/frm\[/", "", $val);
						$arr_key = trim(preg_replace("/\]/", "", $arr_key));
						$temp = isset($values[$arr_key]) ? trim($values[$arr_key]) : null;
					}else{
						$temp = isset($values[$val]) ? trim($values[$val]) : null;
					}
					if($temp == ""){
						$required = "$required $val";
					}
				}
			}
			if ($required==""){
				$_SESSION['gdl_required'] = "";
				$this->required = "";
				$_SESSION['gdl_upload'] = "";
				return true;
			}else{
				$this->required = $required;
				return false;
			}
		}
	}

	function single_line ($colwidth="",$width="")
	{
		global $gdl_content;
		
		if ($width=="") $width="99%";
		if ($colwidth=="") $colwidth="20%";
		
		$style = "table.form{\n"
			."width: $width;\n"
			."}\n"
			."td.label{\n"
			."width: $colwidth;\n"
			."padding-left: 20px;\n"
			."}\n"
			."td.colspan{\n"
			."padding-left: 20px;\n"
			."}\n"
			."span.red { "
			."color: #FF0000; "
			."}\n";
		$gdl_content->set_style($style);

		if ($this->enctype) {
			$html = "<form enctype=\"multipart/form-data\" method=\"".$this->method."\" action=\"".$this->action."\">\n";
		}else{
			$html = "<form method=\"".$this->method."\" action=\"".$this->action."\">\n";
		}
		
		$html .= "<table class=\"form\">\n<tr class=\"bg1\">";
		
		
			foreach ($this->field as $key => $val) {
				$html .= "<td class=\"label\">$val[title]</td>";
				$html .= "<td>$val[field]</td>";
			}

		
		// generated button
			foreach ($this->button as $key => $val) {
				if ($key==0){
					$button = "$val[field]";
				}else{
					$button .= "&nbsp; &nbsp; $val[field]";
				}
			}
			$html .= "<td class=\"button\">$button</td>";
		
	
		$html .= "</tr>\n";
		$html .= "</table>\n";
		$html .="</form>\n";
		return $html;

	}	
}

?>