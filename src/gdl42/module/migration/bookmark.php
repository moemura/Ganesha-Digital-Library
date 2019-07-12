<?php

if (eregi("bookmark.php",$_SERVER['PHP_SELF'])) die();


include("./module/migration/conf.php");

$title = _MIGRATION." "._FILE;

if (file_exists("./files/misc/bookmark.lck")){
	$main = "<p>"._LOCK." <b>./files/misc/bookmark.lck</b></p>";
}else{

	if (!isset($_GET['page'])){
		
		$url = "./gdl.php?mod=migration&amp;op=bookmark&amp;page=go";
		$gdl_content->set_meta("<META HTTP-EQUIV=Refresh CONTENT=\"2; URL=$url\">");
		$main = "<p>"._TRYCONNECT;
		@mysql_connect($db_source['host'], $db_source['uname'], $db_source['password']);
		@mysql_select_db($db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select count(id) as total from bookmark";
		$dbsource = @mysql_query($str_sql);
		$main .= "</p>\n";
		$main .= "<p>Total : ".@mysql_result($dbsource ,0,"total")." "._FILE."</p>\n ";
		$main .= "<p><b>"._PLEASEWAIT."</b></p>\n";
	} else {
		
		@mysql_connect($db_source['host'], $db_source['uname'], $db_source['password']);
		@mysql_select_db($db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select id,datestamp,user,identifier,request,response from bookmark";
		$dbsource = @mysql_query($str_sql);
		
		if ($dbsource) {
		require_once ("./class/db.php");
		$db = new database();
		
		$num = 1;
		while ($rows = mysql_fetch_row($dbsource)){
			$column="bookmark_id,time_stamp,user_id,identifier,request,response";
			$str_sql = "'".$rows[0]."','".$rows[1]."','".$rows[2]."','".$rows[3]."','".$rows[4]."','".$rows[5]."'";
			$db->insert("bookmark","$column","$str_sql");
			$main .= "--> $num. $rows[0] : $rows[2]<br/>\n";
			$num ++;
		}
		$main .= "--> $num. EOF\n";
		
		// lock file
		$lckfile = "./files/misc/bookmark.lck";
		$fp = fopen($lckfile,w);
		if ($fp){
			$lckdate = date("Y-m-d h:i:s");
			fputs($fp,$lckdate);
			fclose($fp);
			$main .= "<p>"._NOWLOCK." <b>./files/misc/bookmark.lck</b></p>";
		} else {
			$main .= "Failed to create lock file: $lckfile.";
		}
		}
	
	}
}

$main = gdl_content_box($main,$title);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=migration\">"._MIGRATION."</a>";
$gdl_folder->set_path($_SESSION['gdl_node']);
?>