<?php

if (preg_match("/search.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class search{
	var $result=array();
	var $error;
	var $hit;
	var $runtime;
	var $treshold;
	
	function cmd($start,$str_query){
		// Function to generate search command
		global $gdl_sys,$schema;
		
		require_once ("./class/indexing.php");
		$sw = new indexing(); 
		$swishe = $sw->indexing_var();
				
		if (empty($start)) $start = 1;
		
		if ($gdl_sys['os'] == "freebsd") {
			$str_cmd = "/usr/local/bin/swish-e -f $swishe[idx] -b $start -w \"$str_query\"";
		} else if ($gdl_sys['os'] == "debian") {
			$str_cmd = "/usr/bin/swish-e -f $swishe[idx] -b $start -w \"$str_query\"";
		} else { 
			$str_cmd = "$swishe[bin] -f $swishe[idx] -b $start -w \"$str_query\"";
		}

		$cmd = `$str_cmd`;

		return $this->process($cmd,$start,$q);
		
	}
	
	function query_quick($q){
		// Function for quick search
		$str = $this->clean_query($q);
		if (preg_match("/\"/",$q)){
			$query = "dc = $str";
		} else {
			$query = "dc = ($str)";
		}
		return $query;
	}

	
	// Function for advanced search
	function query_advanced($schema="",$frm){
		if (is_array($frm)){
			foreach ($frm[q] as $key => $val) {
				if (!empty($val)){
					$metaname = $frm[tag][$key];
					$word .= " $bool $metaname = ($val) ";
					$bool = $frm[bol][$key];
				}
			}
		}
		if ($schema <> "dc") $word = "schema = ($schema) AND $word ";
		return $word;
	}

	// Function to clean up query
	function clean_query($q){
		$str = str_replace(";","",$q);
		$str = str_replace("&","",$str);
		$str = str_replace("\"","\\\"",$str);
		return $str;
	}

	// function to process search result
	function process($cmdres,$start="",$q=""){
		global $gdl_auth,$gdl_sys;
		
		$res = explode("\n",$cmdres);

		if (empty($start)) $start = 1;
		$stop = $gdl_sys['perpage_browse'] ;
		
		for ($i = 0;$i< sizeof($res);$i++){
			
			$f = explode(" ",$res[$i]);
			$s = explode("\"",$res[$i]);
			
			if (preg_match("/no results/i",$res[$i])){
				$this->error = $q;
				$noresult = true;
			} elseif ($f[0] == "err:"){
				$this->error = "<blockquote><b>$res[$i]</b></blockquote>";
				$noresult = true;
			} elseif (($f[0] != "#")&&($f[2] != "")){
				$id_num = $id_num + 1;
				
				if($this->treshold == -1)
					$stop = $id_num;

				if ($id_num <= $stop) {
					// Get ID 
					$id = $f[1];
					$id = stripslashes($id);
					$id = str_replace("/","",$id);;
					$result[]=$id;
				}
			} else {
				
				if (preg_match("/hits/i",$res[$i])) $this->hit= $f[4];
				if (preg_match("/run time/i",$res[$i]))$this->runtime= $f[3];
	
			}
		}
				
		if ($noresult==false){
			$this->result=$result;
			return true;
		} else {
			return false;
		}

	}

	// Generate form
	function generate_form($schema=""){
		global $gdl_form,$gdl_content;
		
		include ("./module/search/conf.php");
		include ("./config/type.php");
		
		// tabs navigation
		$menu_tabs = $this->get_tabs($schema);
		
		if (($schema=="")or($schema=="dc")){
			
			// search all schema filtered by type
			$option['all'] = _ALLMETADATA;
			foreach ($gdl_type as $key => $val) {
				$option[$key] = "$val";
			}

			$gdl_form->set_name("search");
			$gdl_form->action="./gdl.php?mod=search";
			$gdl_form->add_field(array(
						"type"=>"hidden",
						"name"=>"s",
						"value"=>"dc"));
			$gdl_form->add_field(array(
						"type"=>"title",
						"text"=>$menu_tabs));	
			$gdl_form->add_field(array(
						"type"=>"text",
						"name"=>"keyword",
						"text"=>_KEYWORD,
						"size"=>40));
			$gdl_form->add_field(array(
						"type"=>"select",
						"name"=>"type",
						"option"=>$option,
						"text"=>_METADATATYPE));

			$gdl_form->add_button(array(
						"type"=>"submit",
						"name"=>"submit",
						"value"=>_OK));
			$gdl_form->add_button(array(
						"type"=>"reset",
						"name"=>"reset",
						"value"=>_CANCEL));
			$form = $gdl_form->generate("100px");
			
		}else{
			
			
			// get meta tag
			$field = 0;
						
			foreach ($search_tab as $key => $val) {
				$tabs[$key] = "$val[schema]";
				if ($key==$schema){					
					foreach ($val as $metatag => $metaname) {
						if ($metatag <> "schema") {
							$sel_option .= "<option value=\"$metatag\">$field $metaname</option>\n";
							//$field = $field + 1 ;
							$field++;		
						}
					}
				}
			}

			
			$form = "<form method=\"post\" action=\"./gdl.php?mod=search\">\n";
			$form .= "<table class=\"form\">\n";
			$form .= "<input type=\"hidden\" name=\"s\" value=\"$schema\"/>\n";
			$form .= "<tr class=\"bg2\">";
			$form .= "<th class=\"title\"><b>$menu_tabs<b></th>";
			$form .= "</tr>\n";
			
			// generate item
			for ($i = $field; $i >=1; $i--) {
				$option = preg_replace("/>$i/"," selected=\"selected\">$i",$sel_option);
				for ($j = $field; $j >= 0; $j--) {
					$option = preg_replace("/>$j/",">",$option);
				}
				
				$form 	.= "<tr class=\"bg1\">";
				$form   .= "<td><select name=\"frm[tag][]\">"
						."$option</select>\n"
						."<input name=\"frm[q][]\" type=\"text\" size=\"40\"/>\n"
						."<select name=\"frm[bol][]\">\n"
						."<option value=\"AND\">"._AND."</option>\n"
						."<option value=\"OR\">"._OR."</option>\n"
						."</select></td></tr>\n";
			}
			$button = "<input name=\"submit\" type=\"submit\" id=\"submit\" value=\"Cari\"/>&nbsp;"
				 ."&nbsp;&nbsp;<input name=\"reset\" type=\"reset\" id=\"reset\" value=\"Reset\"/>&nbsp;";
			$form .= "<tr class=\"bg3\">";
			$form .= "<td class=\"button\">$button</td>";
			$form .= "</tr>\n";
			$form .= "</table>";
			$form .= "</form>";
			
			$style = "table{\n"
				."width: 99%;\n"
				."margin: 0px;\n"
				."padding: 0px;\n"
				."}\n";
			$gdl_content->set_style($style);
			
		}
		
		$form	= "<div class=\"state_offline_close\">$form</div>";
		$form 	= gdl_content_box($form,_ADVANCESEARCH);
		return $form;
	}
	
	function get_tabs($selected=""){
		
		include ("./module/search/conf.php");
		
		if ($selected=="") $selected=="dc";
		if ($selected=="dc"){$main .= "<b>"._SEARCHALL."</b>";
		}else{ $main .= "<a href=\"./gdl.php?mod=search&amp;schema=dc\">"._SEARCHALL."</a>";}
		
		
		foreach ($search_tab as $key => $val) {
			foreach ($val as $keyl => $vall) {
				if ($key1=="schema"){
					if ($selected==$key){	
						$main .=" | <b>$val1</b>";
					}else{
						$main .=" | <a href=\"./gdl.php?mod=search&amp;schema=$key\">$val1</a>";
					}
				}
			}
		}
		return $main;
	}
	
	function mark_term($string,$term){
		$term = urldecode($term);
		$term = stripslashes($term);
		$term = str_replace("\"","",$term);
		$term = str_replace("(","",$term);
		$term = str_replace(")","",$term);
		$term = str_replace("\$","",$term);
		$term = str_replace("*","",$term);
		$term = str_replace("=","",$term);
		$term = preg_replace("/ or /i"," ",$term);
		$term = preg_replace("/ and /i"," ",$term);
		$term = preg_replace("/ not /i"," ",$term);
		$term = preg_replace("/title/i"," ",$term);
		$term = preg_replace("/keywd/i"," ",$term);
		$term = preg_replace("/dbname/i"," ",$term);
	
		// strip tag number
		$term = preg_replace("/\/\d+\w+/"," ",$term);
		
		$red1 = "<font color=\"#FF0000\">";
		$red2 = "</font>";
	
		$term = explode(" ",$term);
		for ($i=0;$i<sizeof($term);$i++){
			if (!empty($term[$i])){
				// string base
				$string = preg_replace("/$term[$i]/i","$red1\\0$red2",$string);
			}
		}
		return $string;
	}

	function search_by_db($start, $str_query) {
		global $gdl_db, $gdl_sys;

		//$str_query = "schema = (dc_document) OR   title = (judul)  OR description = (deskrupsi)  OR subject_keywords = (subject)  OR type = (tipe)  OR creator = (pencipta)";
		//echo "Q: $str_query <br/>\n";
		$mapping_field = array('schema'=>'r.schema',
							   'title'=>'r.title',
							   'description'=>'r.description',
							   'subject_keywords'=>'r.keywords',
							   'type'=>'r.type',
							   'creator'=>'r.creator',
							   'orgname'=>'r.orgname',
							   'fullname'=>'r.fullname',
							   'experience'=>'r.experience',
							   'expertise'=>'r.expertise',
							   'interest'=>'r.interest',
							   'address'=>'r.address',
							   'name'=>'r.name');

		$pool_and = array();
		$pool_or  = array();
		$arr = explode("AND", $str_query);

		$sql_where = "";
		$and_option = true;
		if (count($arr) == 1) {
			$and_option = false;
			$arr = explode("OR", $str_query);
		}

		//echo "\n-------AND-----------------------".count($arr)."\n";
		//print_r($arr);
		//echo "\n------------------------------\n";
		$c_arr = count($arr);
		$opt = $this->extract_query_option($arr[0]);
		if ($c_arr == 1) {
			$filter = empty($opt[1])? " IS NOT NULL" : "LIKE '%$opt[1]%'";
			foreach($mapping_field as $key => $val) {
				if (!empty($sql_where)) {
					$sql_where .= " OR ";
				}
				$sql_where .= "$val $filter";
			}
		} else if (($c_arr == 2) && ($opt[0] == "dc")) {
			$filter = empty($opt[1])? " IS NOT NULL" : "LIKE '%$opt[1]%'";
			$opt2 = $this->extract_query_option($arr[1]);

			foreach($mapping_field as $key => $val) {
				if (($val != "type") && ($val != "schema")) {
					if (!empty($sql_where)) {
						$sql_where .= " OR ";
					}
					$sql_where .= "$val $filter";
				} else if ($val == "type") {
					$sql_where .= " AND type LIKE "."'".$opt2[1]."'";
				}
			}
		} else {
			foreach($arr as $key => $value) {
				$delimiter = ($and_option) ? "OR" : "AND";
				$pool = explode($delimiter, $value);
				if (count($pool) == 1) {
					$pool[0] = trim($pool[0]);
					$opt = $this->extract_query_option($pool[0]);
					if (!empty($sql_where)) {
						$sql_where .= ($and_option) ? " AND " : "OR";
					}
					$sql_where .= $mapping_field[$opt[0]]." LIKE "."'%$opt[1]%'";
				} else {
					for($i = 0; $i < count($pool); $i++) {
						$pool[$i] = trim($pool[$i]);
						if (($i == 0) && $and_option && !empty($sql_where)) {
							$sql_where .= " AND ";
						} else if (!empty($sql_where)) {
							$sql_where .= " OR ";
						}
						$opt = $this->extract_query_option($pool[$i]);
						$sql_where .= $mapping_field[$opt[0]]." LIKE "."'%$opt[1]%'";
					}
				}
			}
		}

		$result = $gdl_db->select("index_record r","count(r.identifier) as jumlah", $sql_where);
		if ($row = @mysqli_fetch_row($result)) {
			$start	   = $start - 1;
			$num_row   = $row[0];
			$page_size = $gdl_sys['perpage_browse'];
			$result = $gdl_db->select("index_record r","r.identifier",$sql_where,"","","$start, $page_size",$groupby="");
			$pool = array();
			while($row = @mysqli_fetch_row($result)) {
				array_push($pool, $row[0]);
			}

			$this->hit 		= $num_row;
			$this->result	= $pool;
			//echo "---> $num_row :: ".count($pool)." :: ".$sql_where." <----";
			return (count($pool) > 0);
		}
		//$result = $gdl_db->select("index_record","identifier",$sql_where,"","","",$groupby="");
		//echo "\n====== SQL-0 ==== \n";
		//echo $sql_where;
		//echo "\n====== SQL-1 ==== \n";

		return false;
	}

	function extract_query_option($input) {
		$option = array();
		$arr = explode("=", $input);
		if (is_array($arr)) {
			foreach ($arr as $key => $value) {
				$value = trim($value);
				if (!empty($value)) {
					array_push($option, $value);
				}
			}
		}
		if (count($option) == 2) {
			$value = $option[1];
			$strlen = strlen($value);
			$value = substr($value, 0, $strlen - 1);
			$option[1] = substr($value, 1, $strlen - 2);
		}
		return $option;
	}
}

?>