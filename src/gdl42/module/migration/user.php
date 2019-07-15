<?php
if (preg_match("/user.php/i",$_SERVER['PHP_SELF'])) die();


include("./module/migration/conf.php");

$title = _MIGRATION." "._FILE;

if (file_exists("./files/misc/user.lck")){
	$main = "<p>"._LOCK." <b>./files/misc/user.lck</b></p>";
}else{

	if (!isset($_GET['page'])){
		
		$url = "./gdl.php?mod=migration&amp;op=user&amp;page=go";
		$gdl_content->set_meta("<META HTTP-EQUIV=Refresh CONTENT=\"2; URL=$url\">");
		$main = "<p>"._TRYCONNECT;
		@mysql_connect($db_source['host'], $db_source['uname'], $db_source['password']);
		@mysql_select_db($db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select count(UID) as total from user";
		$dbsource = @mysql_query($str_sql);
		$main .= "</p>\n";
		$main .= "<p>Total : ".@mysql_result($dbsource ,0,"total")." "._FILE."</p>\n ";
		$main .= "<p><b>"._PLEASEWAIT."</b></p>\n";
	} else {
		
		@mysql_connect($db_source['host'], $db_source['uname'], $db_source['password']);
		@mysql_select_db($db_source['name']) or $gdl_content->set_error("Unable to select source database","Error Connection");
		$str_sql = "select u.email,u.password,u.confirm,u.gid,u.full_name,u.datestamp,u.validation,u.address,u.city,u.country,u.institution,u.job from user u";
		$dbsource = @mysql_query($str_sql);

		
		if ($dbsource) {
		
			require_once ("./class/db.php");
			$db = new database();
		
			$num = 1;	

			while ($rows = mysql_fetch_row($dbsource)){
				if ($rows[3]==1) 
					$group_id="admin";
				elseif ($rows[3]==2)
					$group_id="CKO";
				elseif ($rows[3]==3)
					$group_id="Editor";
				elseif ($rows[3]==4)
					$group_id="User";			

				$column="user_id,password,active,group_id,name,date_modified,validation,address,city,country,institution,job";
				$str_sql = "'".$rows[0]."','".$rows[1]."',".$rows[2].",'".$group_id."','".$rows[4]."','".$rows[5]."','".$rows[6]."','".$rows[7]."','".$rows[8]."','".$rows[9]."','".$rows[10]."','".$rows[11]."'";
				if ($gdl_session->user_id <> $rows[0])
					$db->insert("user","$column","$str_sql");
				$main .= "--> $num. $rows[0] : $rows[4]<br/>\n";
				$num ++;
			}
			$main .= "--> $num. EOF\n";
			$db->insert("user","user_id,group_id,name","'public','public','Public'");
			// lock file
			$lckfile = "./files/misc/user.lck";
			$fp = fopen($lckfile,w);
			if ($fp){
				$lckdate = date("Y-m-d h:i:s");
				fputs($fp,$lckdate);
				fclose($fp);
				$main .= "<p>"._NOWLOCK." <b>./files/misc/user.lck</b></p>";
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