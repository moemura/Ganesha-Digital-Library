<?php
if (eregi("relation.php",$_SERVER['PHP_SELF'])) die();

include("./module/migration/conf.php");

$title = _MIGRATION." "._RELATION;

if (file_exists("./files/misc/relation.lck")){
	$main = "<p>"._LOCK." <b>./files/misc/relation.lck</b></p>";
}else{

	if (!isset($_GET['page'])){
		
		$url = "./gdl.php?mod=migration&amp;op=relation&amp;page=go";
		$gdl_content->set_meta("<META HTTP-EQUIV=Refresh CONTENT=\"2; URL=$url\">");
		$main = "<p>"._TRYCONNECT;
		@mysql_connect($db_source['host'], $db_source['uname'], $db_source['password']);
		@mysql_select_db($db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select count(identifier) as total from relation";
		$dbsource = @mysql_query($str_sql);
		$main .= "</p>\n";
		$main .= "<p>Total : ".@mysql_result($dbsource ,0,"total")." "._RELATION."</p>\n ";
		$main .= "<p>"._PLEASEWAIT."</p>\n";
	} else {
		
		@mysql_connect($db_source['host'], $db_source['uname'], $db_source['password']);
		@mysql_select_db($db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select identifier,datemodified,no,hasfilename,haspart,haspath,hasformat,hassize,hasuri,hasnote from relation";
		$dbsource = @mysql_query($str_sql);
		
		if ($dbsource) {
		require_once ("./class/db.php");
		$db = new database();

		$num = 1;
		while ($rows = mysql_fetch_row($dbsource)){
			$str_sql = "'".$rows[0]."','".$rows[1]."',".$rows[2].",'".$rows[3]."','".$rows[4]."','".$rows[5]."','".$rows[6]."','".$rows[7]."','".$rows[8]."','".$rows[9]."'";
			$db->insert("relation","identifier,date_modified,no,name,part,path,format,size,uri,note","$str_sql");
			$main .= "--> $num. $rows[0] : $rows[3]<br/>\n";
			$num ++;
		}
		$main .= "--> $num. EOF\n";
		
		// lock file
		$lckfile = "./files/misc/relation.lck";
		$fp = fopen($lckfile,w);
		if ($fp){
			$lckdate = date("Y-m-d h:i:s");
			fputs($fp,$lckdate);
			fclose($fp);
			$main .= "<p>"._NOWLOCK." <b>./files/misc/relation.lck</b></p>";
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