<?php
if (preg_match("/folder.php/i",$_SERVER['PHP_SELF'])) die();

include("./module/migration/conf.php");

$title = _MIGRATION. " "._FOLDER;

if (file_exists("./files/misc/folder.lck")){
	$main = "<p>"._LOCK." <b>./files/misc/folder.lck</b></p>";
}else{

	if (!isset($_GET['page'])){
		
		$url = "./gdl.php?mod=migration&amp;op=folder&amp;page=go";
		$gdl_content->set_meta("<META HTTP-EQUIV=Refresh CONTENT=\"2; URL=$url\">");
		$main = "<p>"._TRYCONNECT;
		@mysql_connect($db_source['host'], $db_source['uname'], $db_source['password']);
		@mysql_select_db($db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select count(node) as total from folder";
		$dbsource = @mysql_query($str_sql);
		$main .= "</p>\n";
		$main .= "<p>Total : ".@mysql_result($dbsource ,0,"total")." "._FOLDER."</p>\n ";
		$main .= "<p><b>"._PLEASEWAIT."</b></p>\n";
	} else {
		
		@mysql_connect($db_source['host'], $db_source['uname'], $db_source['password']);
		@mysql_select_db($db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select f.node,f.name,f.datemodified,t.parent,t.path from folder f, folder_tree t where f.node=t.node";
		$dbsource = @mysql_query($str_sql);
		
		if ($dbsource) {
		require_once ("./class/db.php");
		$db = new database();
		
		$db->delete("folder");
		$num = 1;
		while ($rows = mysql_fetch_row($dbsource)){
			$len =strlen($rows[4])-1;
			$path = substr($rows[4], 0, $len);
			$str_sql = $rows[0].",".$rows[3].",'$path','".$rows[1]."','".$rows[2]."'";
			$db->insert("folder","folder_id,parent,path,name,date_modified","$str_sql");
			$main .= "--> $num. $rows[1] ($rows[0])<br/>\n";
			$num ++;
		}
		$main .= "--> $num. EOF</p>\n";
		
		// lock file
		$lckfile = "./files/misc/folder.lck";
		$fp = fopen($lckfile,w);
		if ($fp){
			$lckdate = date("Y-m-d h:i:s");
			fputs($fp,$lckdate);
			fclose($fp);
			$main .= "<p>"._NOWLOCK." <b>./files/misc/folder.lck</b></p>";
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