<?php

class folksonomy{

function save_configuration($frm) {

		$date = $frm['folks_start_date'];
		if(!preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z/",$date))
			$date = "0000-00-00T00:00:00Z";
		
		$sync_conf = "<?php
		# Automatically generated on ".date("Y-m-d H:i:s")."
		
		# ******** Setting Konfigurasi
		\$gdl_folks['folks_fetch_records']  	= \"$frm[folks_fetch_records]\";
		\$gdl_folks['folks_start_date']  		= \"$date\";
		\$gdl_folks['folks_show_page'] 			= \"$frm[folks_show_page]\";
		
		# ******** Option Show Folksonomy
		\$gdl_folks['folks_active_option']  	= \"$frm[folks_active_option]\";
		
		#********* Setting Frekuensi
		\$gdl_folks['folks_min_frekuensi']  	= \"$frm[folks_min_frekuensi]\";
		\$gdl_folks['folks_token_per_abjad']  	= \"$frm[folks_token_per_abjad]\";
		
		#********* Setting Font Height
		\$gdl_folks['folks_max_size_font']  	= \"$frm[folks_max_size_font]\";
		\$gdl_folks['folks_min_size_font']  	= \"$frm[folks_min_size_font]\";
	
		# ********* Setting background and font color
		\$gdl_folks['folks_bg_color']  			= \"$frm[folks_bg_color]\";
		\$gdl_folks['folks_font_color']  		= \"$frm[folks_font_color]\";
		?>";
	
		// save to file
		$fp = fopen("config/folks.php","w");
		$result=fputs($fp,$sync_conf);
		fclose($fp);
		
		return $result;
}

function get_list($table,$limit,$filter="") {
		global $gdl_db;
		
		if($table == "folksonomy"){
			$dbres = $gdl_db->select($table,"TOKEN, FREKUENSI","TOKEN LIKE '$filter%'","FREKUENSI,Token","desc,asc",$limit);
		}else
			$dbres = $gdl_db->select($table,"GARBAGE_ID as ID, TOKEN","","","",$limit);
			
		while ($rows = @mysqli_fetch_row($dbres)){				
			if($table == "folksonomy"){
				$result[$rows[0]]['TOKEN']		= $rows ['0'];
				$result[$rows[0]]['FREKUENSI']	= $rows['1'];
			}else{
				$result[$rows[0]]['ID']			= $rows ['0'];
				$result[$rows[0]]['TOKEN']		= $rows ['1'];
			}
		}
		
		return $result;
}

function getTotalStopWord(){
	global $gdl_db;
	
	$dbres = $gdl_db->select("garbagetoken","COUNT(Token) as TOTAL");
	if($rows = @mysqli_fetch_row($dbres)){
		return $rows['0'];
	}else return 0;
}

function addNewStopword($token){
	global $gdl_db;

	$token 	= trim($token);
	$token	= strtolower($token);
	if(strlen($token)>0)
		$dbres = $gdl_db->insert("garbagetoken","Token","'$token'");
	
	$error = @mysqli_error($gdl_db->con);
	if(!empty($error))
		return -1;
	if(mysqli_affected_rows($gdl_db->con) > 0){
		return 1;
	}else return 0;
}

function reset_stopword(){
	global $gdl_db;
	$dbres = $gdl_db->delete("folksonomy");
}

function update_folksonomy(){
	global $gdl_sync,$gdl_metadata,$gdl_db,$gdl_stdout,$gdl_folks;
	
	$token 			= $_GET['token'];
	$total			= $_GET['total'];
	$date_filter	= $gdl_folks['folks_start_date'];
	if(!preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z/",$date_filter)){
		$date_filter = "date_modified >= '0000-00-00 00:00:00'";
	}else{
		$date_filter = "date_modified >= '".substr($date_filter,0,10)." ".substr($date_filter,11,8)."'";
	}
	
	if(empty($token)) $token = 0;
	if(empty($total)) $total = $this->getTotalMetadata($date_filter);
	$limit	= $gdl_folks['folks_fetch_records'];
	
	if(preg_match("/^[0-9]+$/",$limit) == FALSE) $limit = 30;
	$start 	= $token*$limit;
	
	$rMetadata = $gdl_db->select("metadata","xml_data",$date_filter,"","","$start,$limit");
	$prev_keyword = "";
	while ($rows = @mysqli_fetch_row($rMetadata)){
		$rData = $gdl_metadata->readXML($rows['0']);
		if(is_array($rData)){
			$keyword = $gdl_metadata->get_value($rData,"SUBJECT.KEYWORDS","SUBJECT",0);
			if($keyword != $prev_keyword)
				$this->execute_update_box_folksonomy($keyword);
			$prev_keyword = $keyword;
		}
	}
	$stop = $start+1;
	$token++;
	if($stop < $total){
		$url = "./gdl.php?mod=folksonomy&op=update&sub=update&token=$token&total=$total";
		$refresh = $gdl_stdout->header_redirect(2,$url);
	}else{
		$url = "./gdl.php?mod=folksonomy&op=update";
		$refresh = $gdl_stdout->header_redirect(2,$url);
	}
	
	return $refresh;
}

function clean_stopwordToken(){
	global $gdl_sync,$gdl_metadata,$gdl_db,$gdl_stdout;
	
	$token 	= $_GET['token'];
	$total	= $_GET['total'];
	
	if(empty($token)) $token = 0;
	if(empty($total)) $total = $this-> getTotalStopWord();
	$limit	= $gdl_sync['sync_count_records'];
	if(preg_match("/^[0-9]+$/",$limi) == FALSE) $limit = 20;
	$start 	= $token*$limit;
	
	$rToken = $gdl_db->select("garbagetoken","Token","","","","$start,$limit");
	while ($rows = @mysqli_fetch_row($rToken)){
			$gdl_db->delete("folksonomy","Token LIKE '$rows[0]'");
	}
	$stop = $start+1;
	$token++;
	if($stop < $total){
		$url = "./gdl.php?mod=folksonomy&op=update&sub=clean&token=$token&total=$total";
		$refresh = $gdl_stdout->header_redirect(2,$url);
	}else{
		$url = "./gdl.php?mod=folksonomy&op=update";
		$refresh = $gdl_stdout->header_redirect(2,$url);
	}
	
	return $refresh;
}

function execute_update_box_folksonomy($str_keyword){
	$arr_explode = $this->replace_karakter($str_keyword);
	$this->update_box_folksonomy($arr_explode);
}

function update_box_folksonomy($array){
	global $gdl_import;
	
	
	if(!empty($array)){
		if(is_array($array)){
			$count = count($array);
			for($i=0;$i<$count;$i++){
				$word 	= trim($array[$i]);
				if(!empty($word)){
					$word	= ucfirst($word);
					if(preg_match("/[:alpha]/",$word) == TRUE)
						$gdl_import->update_token_folksonomy($word);
				}
			}
			
		}else {
			$word = trim($array);
			if(!empty($word)){
				if(preg_match("/[:alnum]/",$word) == TRUE)
					$gdl_import->update_token_folksonomy($word);
			}
		}
		
	}
}

function replace_karakter($kalimat){
	
	$arr 		= array(".",",","(",")",";"," ","\n");
	$arr2		= array("p");
	$count 		= count($arr);
	$result		= array();
	$kalimat 	= trim($kalimat);
	if(empty($kalimat)) return $result;
	
	
	for($i=0;$i<count($arr);$i++){
		if($arr[$i] == "'")
			$replace = "";
		else
			$replace = "\t";
		$kalimat = str_replace($arr[$i],$replace,$kalimat);
	}

	if(preg_match("/.*>/",$kalimat)){
		$kalimat	= str_replace(" ","\t",$kalimat);
		$kalimat	= str_replace(">","\t>\t",$kalimat);
		$kalimat	= str_replace("<","\t<\t",$kalimat);
		$array 		= explode("\t",$kalimat);

		for($i=0;$i<count($array);$i++){
			$word = trim($array[$i]);
			if(empty($word)) continue;
			
			if($word == "<"){
				$pass = 1;
				continue;
			}else if($word == ">"){
				$pass = 0;
				continue;
			}else if(!$pass){
				array_push($result,$word);
			}
		}
	}else $result = explode("\t",$kalimat);
	
	return $result;
}

function getTotalMetadata($date_filter){
	global $gdl_db;
	$dbres = $gdl_db->select("metadata","COUNT(IDENTIFIER) as TOTAL",$date_filter);
	
	if($rows = @mysqli_fetch_row($dbres)){
		return $rows['0'];
	}else return 0;
}

function getTotalFolksonomy($filter){
	global $gdl_db;
	$dbres = $gdl_db->select("folksonomy","COUNT(TOKEN) as TOTAL","TOKEN LIKE '$filter%'");
	
	if($rows = @mysqli_fetch_row($dbres)){
		return $rows['0'];
	}else return 0;
}

function delete_tokenFolksonomy(){
	global $gdl_db;
	
	$id = $_GET['id'];
	$id = trim($id);
	if(strlen($id)>0)
		$dbres = $gdl_db->delete("folksonomy","Token LIKE '$id'");
}

function delete_tokenStopword(){
	global $gdl_db;
	
	$id = $_GET['id'];
	$id = trim($id);
	if(strlen($id)>0)
		$dbres = $gdl_db->delete("garbagetoken","GARBAGE_ID LIKE '$id'");
}


function box_folksonomy(){
	global $gdl_db,$gdl_folks;
	
	$limit 		= $gdl_folks['folks_token_per_abjad'];
	$min_font	= $gdl_folks['folks_min_size_font'];
	$max_font	= $gdl_folks['folks_max_size_font'];
	$deviasi	= $max_font - $min_font;
	$fColor		= $gdl_folks['folks_font_color'];
	$bColor		= $gdl_folks['folks_bg_color'];
	$center		= $min_font + ceil($deviasi/2);
	$min_hz		= $gdl_folks['folks_min_frekuensi'];
	
	if(substr($fColor,0,1) != "#")
		$fColor = "#".$fColor;
	if(substr($bColor,0,1) != "#")
		$bColor = "#".$bColor;

	if(preg_match("/^[0-9]+$/",$min_hz) == FALSE) $min_hz = 30;
	for($i='A';$i<'Z';$i++){

		$tables		= $gdl_db->tables("folksonomy");
		/*
		$query		= "SELECT count(g.Token),sum(g.Frekuensi),max(g.Frekuensi) FROM "
						."(select * from $tables g where Token like '$i%' "
						."AND g.Frekuensi >= '$min_hz' order by Frekuensi desc limit 0,$limit) g";
		*/
		
		//modified by benirio kalo pake query yang atas ada error
		$query = "SELECT count(Token), sum(Frekuensi), max(Frekuensi) "
					."FROM ".$tables." WHERE Token like '".$i."%'"
					." AND Frekuensi >='".$min_hz."' order by Frekuensi desc limit 0,".$limit;
		$dbres	= @mysqli_query($gdl_db->con, $query);
		
		if($rows = @mysqli_fetch_row($dbres)){
			$count_record		= $rows['0'];
			$count_frekuensi	= $rows['1'];
			$max_hz				= $rows['2'];
			
			if($max_hz == NULL)	continue;
			
			$faktor				= ceil(($deviasi*$count_frekuensi)/$max_hz);

			if($count_record > 0){
				/*
				$query = "SELECT g.Token,g.Frekuensi"
						." FROM "
						."(SELECT * FROM $tables t "
						."WHERE Token like '$i%' and t.Frekuensi >= $min_hz "
						."ORDER BY Frekuensi DESC,Token ASC limit 0,$limit)"
						." g order by g.Token asc";
				
				*/
				//modified by benirio, kalo pake query yang atas ada error
				$query = 	"SELECT Token, Frekuensi FROM ".$tables." WHERE Token like '".$i."%' "
							."AND Frekuensi >= '".$min_hz."' ORDER BY Frekuensi DESC, Token limit 0,".$limit;
				
							
				$dbres	= mysqli_query($gdl_db->con, $query);
				
				$pool_abjad = null;
				if(@mysqli_num_rows($dbres) > 0){
					while($rows = @mysqli_fetch_row($dbres)){
						$pool_abjad[$rows['0']] = $rows['1'];
					}
					ksort($pool_abjad);
				}
				
				$dev	= ceil($deviasi/$count_record);

				if(is_array($pool_abjad))
				foreach($pool_abjad as $word => $hz){
				 	//$hz		= $rows['1'];	
					
					$delta	= ($hz/$count_frekuensi)*$faktor;
					$height = $min_font+$delta; 

					$style = "\"text-decoration:none;color:$fColor;font-size:$height"."px\"";
					
					//$word	= $rows[0];
//					$word 	= htmlspecialchars(nl2br($rows[0]),ENT_QUOTES);
//					$word	= addslashes($word);
					if($height > $center)
						$token = 	"<strong>".
										"<a href=\"./gdl.php?mod=search&amp;action=folks&amp;keyword=$word\" style=$style>".
											"$word".
										"</a>".
									"</strong>";
					else
						$token =	"<a href=\"./gdl.php?mod=search&amp;action=folks&amp;keyword=$word\" style=$style>".
										$word.
									"</a>";
					
					$str_folksonomy .= "$token\n";
				}
			}
			
		}
		
	}
	
	if(!empty($str_folksonomy)){

			$result = "<div><table  style=\"background-color:$bColor\">
							<tr>
								<td>
									$str_folksonomy
								</td>
							</tr>
					   </table></div>";
	}
	
	return $result;
}

function show_box_folksonomy(){
	global $gdl_folks;
	
	if($gdl_folks['folks_active_option'] == 1){
		return $this->box_folksonomy();
	}else return "";
}


function get_range_date(){
	global $gdl_db;
	
	$start_date = "0000-00-00T00:00:00Z";
	$end_date 	= "0000-00-00T00:00:00Z";
	
	$dbres = $gdl_db->select("metadata","min(date_modified) as start_date");
	if($dbres){
		$row = @mysqli_fetch_assoc($dbres);
		$date = $row["start_date"];
		if($date != "0000-00-00 00:00:00"){
			$start_date = substr($date,0,10)."T".substr($date,11,8)."Z";
		}
	}
	$dbres = $gdl_db->select("metadata","max(date_modified) as end_date");
	if($dbres){
		$row = @mysqli_fetch_assoc($dbres);
		$date = $row["end_date"];
		if($date != "0000-00-00 00:00:00"){
			$end_date = substr($date,0,10)."T".substr($date,11,8)."Z";
		}
	}
	
	$result[from] 	= $start_date;
	$result[until]	= $end_date;
	
	return $result;
}

}
?>
