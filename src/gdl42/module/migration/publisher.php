<?php
if (preg_match("/publisher.php/i",$_SERVER['PHP_SELF'])) die();


include("./module/migration/conf.php");

$title = _MIGRATION." "._FILE;

if (file_exists("./files/misc/publisher.lck")){
	$main = "<p>"._LOCK." <b>./files/misc/publisher.lck</b></p>";
}else{

	if (!isset($_GET['page'])){
		
		$url = "./gdl.php?mod=migration&amp;op=publisher&amp;page=go";
		$gdl_content->set_meta("<META HTTP-EQUIV=Refresh CONTENT=\"2; URL=$url\">");
		$main = "<p>"._TRYCONNECT;
		$con = @mysqli_connect($db_source['host'], $db_source['uname'], $db_source['password'], $db_source['name']);
		//@mysqli_select_db($con, $db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select count(idpublisher) as total from publisher";
		$dbsource = @mysqli_query($con, $str_sql);
		$row = @mysqli_fetch_assoc($dbres);
		$main .= "</p>\n";
		$main .= "<p>Total : ".$row["total"]." "._FILE."</p>\n ";
		$main .= "<p><b>"._PLEASEWAIT."</b></p>\n";
	} else {
		
		$con = @mysqli_connect($db_source['host'], $db_source['uname'], $db_source['password'], $db_source['name']);
		//@mysqli_select_db($con, $db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select * from publisher";
		$dbsource = @mysqli_query($con, $str_sql);
		
		if ($dbsource) {
		require_once ("./class/db.php");
		$db = new database();
		
		$num = 1;
		while ($rows = mysqli_fetch_row($dbsource)){
			$column="";
			$count_row = count($rows);
			
			$str_sql = $rows[0];
			for($i=1;$i<$count_row;$i++)
				$str_sql .= ",'".$rows[$i]."'";

			$db->insert("publisher","$column","$str_sql");
			$main .= "--> $num. $rows[1] : $rows[5]<br/>\n";
			$num ++;
		}
		$main .= "--> $num. EOF\n";
		
		// lock file
		$lckfile = "./files/misc/publisher.lck";
		$fp = fopen($lckfile,w);
		if ($fp){
			$lckdate = date("Y-m-d h:i:s");
			fputs($fp,$lckdate);
			fclose($fp);
			$main .= "<p>"._NOWLOCK."</p>";
		}
		else {
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