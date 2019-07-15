<?php
if (preg_match("/metadata.php/i",$_SERVER['PHP_SELF'])) die();

include("./module/migration/conf.php");

$title = _MIGRATION. " "._METADATA;

if (file_exists("./files/misc/metadata.lck")){
	$main = "<p>"._LOCK." <b>./files/misc/metadata.lck</b></p>";
}else{

	if (!isset($_GET['page'])){
		
		$url = "./gdl.php?mod=migration&amp;op=metadata&amp;page=go";
		$gdl_content->set_meta("<META HTTP-EQUIV=Refresh CONTENT=\"2; URL=$url\">");
		$main = "<p>"._TRYCONNECT;
		@mysql_connect($db_source['host'], $db_source['uname'], $db_source['password']);
		@mysql_select_db($db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select count(identifier) as total from metadata";
		$dbsource = @mysql_query($str_sql);
		$main .= "</p>\n";
		$main .= "<p>Total : ".@mysql_result($dbsource ,0,"total")." "._METADATA."</p>\n ";
		$main .= "<p><b>"._PLEASEWAIT."</b></p>\n";
	} else {
		
		@mysql_connect($db_source['host'], $db_source['uname'], $db_source['password']);
		@mysql_select_db($db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select m.identifier,f.parent,m.type,x.xmldata,m.datemodified,m.owner,f.path from metadata m, metadata_xml x, folder_tree f where m.identifier=x.identifier and m.identifier=f.identifier";
		$dbsource = @mysql_query($str_sql);
		
		if ($dbsource) {
		require_once ("./class/db.php");
		$db = new database();
		

		$num = 1;
		while ($rows = mysql_fetch_row($dbsource)){
		
			$arr_own = explode("@",$rows[5]);
			if (is_array($arr_own)){ $owner = $arr_own[0];
			}else{ $owner = $rows[5]; }
			
			$len =strlen($rows[6])-1;
			$path = substr($rows[6], 0, $len);
			$str_sql = "'".$rows[0]."',".$rows[1].",'".$rows[2]."','".addslashes($rows[3])."','".$rows[4]."','$owner','$path'";
			if (preg_match("/disc/",$rows[2])) {
				if (!empty($rows[3]) && !empty($rows[0])) {
					$id=explode("@",$rows[0]);
					$xml=$gdl_metadata->read_xml($rows[3]);
					$db->insert("comment","`date`,identifier,user_id,name,email,subject,comment","'".date("Y-m-d",$id[1])."','".$id[0]."','".addslashes($owner)."','".addslashes($gdl_metadata->get_value($xml,"CREATOR"))."','".addslashes($gdl_metadata->get_value($xml,"CREATOR_EMAIL"))."','".addslashes($gdl_metadata->get_value($xml,"TITLE"))."','".addslashes($gdl_metadata->get_value($xml,"DESCRIPTION"))."'");
				}
			}				
			$db->insert("metadata","identifier,folder,type,xml_data,date_modified,owner,path,prefix","$str_sql,'general'");
			$main .= "--> $num. $rows[0]<br/>\n";
			$num ++;
		}
		$main .= "--> $num. EOF\n";
		
		// count content of folder
		$dbres = $db->select("folder","folder_id");
		while ($rows = mysql_fetch_array($dbres)){
				$count = $gdl_folder->content_count($rows[0]);
				$db->update("folder","count=$count","folder_id=$rows[0]");
		}
		
		// lock file
		$lckfile = "./files/misc/metadata.lck";
		$fp = fopen($lckfile,w);
		if ($fp){
			$lckdate = date("Y-m-d h:i:s");
			fputs($fp,$lckdate);
			fclose($fp);
			$main .= "<p>"._NOWLOCK." <b>./files/misc/metadata.lck</b></p>";
		} else {
			$main .= "Failed to create lock file: $lckfile.";
		}
		}
		
	}
}

$main = gdl_content_box($main,$title);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=migration\">"._MIGRATION."</a>";
?>