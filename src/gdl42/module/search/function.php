<?php
if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function search_result($schema,$methods="") {
	global $gdl_content,$gdl_folder,$gdl_sys,$gdl_metadata,$gdl_isisdb;
	
	require_once ("./class/search.php");
	$search = new search();
	
	$page = $_GET['page'];
	if(isset($page)){
		$schema = $_GET['s'];
		$type = $_GET['type'];
	}else{
		if($methods != "url"){
			$schema = $_POST['s'];
			$type = $_POST['type'];
		}else{
			$schema	= "dc";
			$type	= "all";
		}
	}
	
	// get start searching
	if(isset($page)){$start = (($page * $gdl_sys['perpage_browse'])+1)- $gdl_sys['perpage_browse'];
	}else{$start = 1;}
	
	// create query string
	if ($schema=="dc"){
		// search all
		if(isset($page)){
			$query = $_GET['dc'];
		}else{
			$query = $_POST['keyword'];
		}
		
		if(empty($query) && ($methods == "url"))
			$query = $_GET['keyword'];
			
		// atribut untuk url
		$q = "dc=$query&amp;type=$type";
		// words for mark

		$str_query = $search->query_quick("$query");
		if ($type <> "all") {
			$str_query = "$str_query AND type=($type)";
		}

		$sread=$query;
		
	}else{
		// search advance
		if(isset($page)){$frm = $_GET['frm'];
		}else{$frm=$_POST['frm'];}
		
		if (is_array($frm)){
			$qfrm = $frm[q];
			foreach ($qfrm as $key => $val) {
				if (!empty($val)){
					$metaname = $frm[tag][$key];
					
					if (isset($q)){
						$q .= "&frm[bol][]=$bool&";
						$query .= " $bool ";
						$sread .= "+";
					}
					$q .= "frm[tag][]=$metaname";
					$q .= "&frm[q][]=$val";
					$query .= "$metaname=$val";
					$sread .= "$val";
					$bool = $frm[bol][$key];
				}
			}
		}
		if ($schema <> "dc") $query = "schema=$schema and $query";
		$str_query = $search->query_advanced($schema,$frm);	
	}

	$index_by_database = (int) $_SESSION['index_by_database'];
	$search_result = 0;
	if ($index_by_database == 0) {
		$search_result = $search->cmd($start,$str_query);
	} else {
		$search_result = $search->search_by_db($start, $str_query);
	}

	if($search_result){
		if(!isset($page)) $page=1;
		$total = $search->hit;
		$pages = ceil($total/$gdl_sys['perpage_browse']);
		$result = $search->result;

		include("./config/type.php");
		
		if(is_array($result))
			foreach ($result as $key => $val) {
				if ($gdl_sys['os']=="freebsd") $val = str_replace("/","", $val);
				if (preg_match("/catalog/",$val)) {
					$catalog=explode("=",$val);
					$res=$gdl_isisdb->get_record($catalog[1],$catalog[2]);
					if (preg_match("/row/",$res))
						$xml=$gdl_isisdb->get_xml_record($catalog[1],$catalog[2],$res);				
					
					$xmlarr=$gdl_metadata->read_xml($xml);
					$title=_TITLE." :".$xmlarr["TITLE"];
					$type=$xmlarr["SCHEMA"];
					$author=$xmlarr["AUTHOR"];
					$date=$xmlarr["DATE"];
					$publisher=$xmlarr["PUBLISHER"];
					$place_of_publisher=$xmlarr["PLACE_OF_PUBLISHER"];
					$description = "<span class=\"note\">".substr($date,0,10).", $type "._BY." $author<br>Publisher : $publisher, $place_of_publisher</span>";
					$meta_arr[] = "<b>$title</b><br/>$description";
				} else {
					
					$xmlmeta = $gdl_metadata->read($val);
					//$title = $xmlmeta['TITLE'];
					$title=$gdl_metadata->get_value($xmlmeta,"TITLE");
					//$type = $xmlmeta['TYPE'];
					$type=$gdl_metadata->get_value($xmlmeta,"TYPE");
					if ($gdl_metadata->get_value($xmlmeta,"RELATION_COUNT") > 0) $file = ", ".$gdl_metadata->get_value($xmlmeta,"RELATION_COUNT")." "._FILES;
					$description = "<span class=\"note\">".substr($gdl_metadata->get_value($xmlmeta,"DATE"),0,10).", $gdl_type[$type] "._BY." ".$gdl_metadata->get_value($xmlmeta,"CREATOR")."</span>";
					
					if (is_array($frm)){
						$qterm = $frm[q];
						foreach ($qterm as $key => $val) {
							if (!empty($val)){
									$title = $search->mark_term($title,$val);																						
							}
						}					
					}else{
						$title = $search->mark_term($title,$query);
					}
					
					
					$meta_arr[] = "<a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=".$gdl_metadata->get_value($xmlmeta,"IDENTIFIER")."&amp;q=$sread\"><b>$title</b></a><br/>$description";
					
				}
			}
				
		$start_idx = ($page-1) * $gdl_sys['perpage_browse'];
		if ($search->hit >= ($page * $gdl_sys['perpage_browse'])){
			$stop_idx = $page * $gdl_sys['perpage_browse'];
		}else{
			$stop_idx = $total;
		}
		$start = $start_idx + 1;
		$count = $stop_idx - $start_idx;
		
		$url = "./gdl.php?mod=search&amp;s=$schema&amp;$q&amp;";
		
		require_once("./module/browse/function.php");
		require_once("./module/browse/lang/".$gdl_content->language.".php");
		$metadata_list = gdl_metadata_list($meta_arr,$start,$count,$total,$page,$pages,$url);		
		$main = "<p class=\"box\">"._SEARCHRESULTFOR." <i>'$str_query'</i></p>";
		$main = gdl_content_box($main.$metadata_list);
		
	}else{
		// tidak ada hasil pencarian
		$main = "<p class=\"box\">"._SEARCHRESULTFOR." <i>'$query'</i></p>";
		$main = gdl_content_box($main."<p>"._SEARCHNOTFOUND."</p>");
	}
	
	$gdl_folder->set_path(0);
	return $main;
}
?>