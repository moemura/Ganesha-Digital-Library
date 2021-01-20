<?php
/***************************************************************************
                         /module/migration/accesslog.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/access.php/i",$_SERVER['PHP_SELF'])) die();
include("./module/migration/conf.php");
$title = _MIGRATION. " "._ACCESSLOG2;

$main = '';
if (file_exists("./files/misc/accesslog.lck")){
	$main = "<p>"._LOCK." <b>./files/misc/accesslog.lck</b></p>";
} else {
	if (!isset($_GET['page'])){
		$url = "./gdl.php?mod=migration&amp;op=accesslog&amp;page=go";
		$gdl_content->set_meta("<META HTTP-EQUIV=Refresh CONTENT=\"2; URL=$url\">");
		$main = "<p>"._TRYCONNECT;
		$con = @mysqli_connect($db_source['host'], $db_source['uname'], $db_source['password'], $db_source['name']);
		//@mysqli_select_db($con, $db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select count(ID) as total from access_log";
		$dbsource = @mysqli_query($con, $str_sql);
		$row = @mysqli_fetch_assoc($dbsource);
		$main .= "</p>\n";
		$main .= "<p>Total : ".$row["total"]." Log</p>\n ";
		$main .= "<p><b>"._PLEASEWAIT."</b></p>\n";
	} else {
		$con = @mysqli_connect($db_source['host'], $db_source['uname'], $db_source['password'], $db_source['name']);
		//@mysqli_select_db($con, $db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select * from access_log";
		$dbsource = @mysqli_query($con, $str_sql);
		
		if ($dbsource) {
			require_once ("./class/db.php");
			$db = new database();
			
			$num = 1;
			while ($rows = mysqli_fetch_row($dbsource)){
				$str_sql = "'".$rows[0]."','".$rows[2]."','$rows[1]','".$rows[4]."','".$rows[3]."','".$rows[6]."'";
				$db->insert("log","","$str_sql");
				$num ++;
			}		
			// lock file
			$lckfile = "./files/misc/accesslog.lck";
			$fp = fopen($lckfile, 'w');
			if ($fp){
				$lckdate = date("Y-m-d h:i:s");
				fputs($fp,$lckdate);
				fclose($fp);
				$main .= "<p>"._NOWLOCK." <b>./files/misc/accesslog.lck</b></p>";
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