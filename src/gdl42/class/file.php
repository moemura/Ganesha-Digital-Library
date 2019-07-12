<?

if (eregi("file.php",$_SERVER['PHP_SELF'])) {
    die();
}

class file_relation{
	
	function upload($identifier){
		global $gdl_sys,$gdl_metadata,$gdl_session;
		
		require_once ("./class/db.php");
		$db = new database();
			
		// directory
		$arr_id = explode("-",$identifier);
		$no = $arr_id[(sizeof($arr_id))-1];
		$homedir  = "./$gdl_sys[repository_dir]/".ceil($no/$gdl_sys['metadata_per_dir']);
		
		if (!file_exists($homedir)){
			mkdir ($homedir,0755);
		}
		
		$count = $_POST['count'];
		$rel_count = 0;
		
		for ($i = 1; $i <= $count; $i++) {
			
			if ($_FILES['fname']['name'][$i]<>""){
				$rel_count = $rel_count + 1;
				
				$frm['RELATION_NO'] = $i;
				$frm['RELATION_DATEMODIFIED'] = date("Y-m-d H:i:s");
				$frm['RELATION_HASFILENAME'] = $_FILES['fname']['name'][$i];
				$frm['RELATION_HASFORMAT'] = $_FILES['fname']['type'][$i];
				$frm['RELATION_HASSIZE'] = $_FILES['fname']['size'][$i];
				$frm['RELATION_HASNOTE'] = $_POST['desc'][$i];
				
				if (strlen($frm['RELATION_HASFILENAME'])>13){
					$fext = substr($frm['RELATION_HASFILENAME'],-5);
					$fpart = substr($frm['RELATION_HASFILENAME'],0,8);
					$fpart = "$fpart-$fext";
				} else {
					$fpart = $frm['RELATION_HASFILENAME'];
				}
				$fpart = ereg_replace(" ","",strtolower($fpart));
				
				$frm['RELATION_HASPART'] = "$identifier-$i-$fpart";
				$frm['RELATION_HASPATH'] = "$homedir/".$frm['RELATION_HASPART'];
				$frm['RELATION_HASURI'] = "/download.php?file=".$frm['RELATION_HASPART'];
				
				if (move_uploaded_file($_FILES['fname']['tmp_name'][$i],$frm['RELATION_HASPATH'])==false){
					return false;
				}
				
				// generate xml data relation external
				$frm['TYPE_SCHEMA'] = "relation";
				
				// input data to table relation
				$rel_field = "identifier,date_modified,no,name,part,path,format,size,uri,note";
				$rel_value = "'$identifier','$frm[RELATION_DATEMODIFIED]',$i,";
				$rel_value .= "'".$frm['RELATION_HASFILENAME'] ."','".$frm['RELATION_HASPART']."','".$frm['RELATION_HASPATH']."',";
				$rel_value .= "'".$frm['RELATION_HASFORMAT'] ."','".$frm['RELATION_HASSIZE']."','".$frm['RELATION_HASURI']."','".$frm['RELATION_HASNOTE']."'";
				$db->insert("relation",$rel_field,$rel_value);
				$id=mysql_insert_id();
				$frm['RELATION_HASURI'] = "/download.php?id=".$id;
				$db->update("relation","uri='".$frm['RELATION_HASURI']."'","relation_id=$id");
				$xmlrela .= $gdl_metadata->generate_xml($frm);

			}
		}
		// update relation in metadata
		$gdl_metadata->update_relation($identifier,$rel_count,$xmlrela);
		return true;
	}
	
	function delete($identifier){
		global $gdl_publisher, $gdl_metadata;
		
		require_once ("./class/db.php");
		$db = new database();
		
		// get publisher
		$publisher = $gdl_metadata->get_publisher($identifier);
		$dbres = $db->select("relation","path","identifier='$identifier'");
		while ($rows = mysql_fetch_row($dbres)){
			$file_del = $rows[0];
			if ($gdl_publisher['id']<>$publisher){
				$file_del= str_replace("files/","files/$publisher/",$file_del);
			}
			if (file_exists($file_del))	unlink ("$file_del");
		}			
		// delete data relation
		$db->delete("relation","identifier='$identifier'");
		
		// update relation in metadata
		$gdl_metadata->update_relation($identifier,0);
		return true;			
	}
	
	function delete_relation($rel_id,$identifier){
		global $gdl_publisher, $gdl_metadata, $gdl_sys, $gdl_db;
		
		require_once ("./class/db.php");
		$db = new database();
		
		// get publisher
		$publisher = $gdl_metadata->get_publisher($identifier);
		$dbres = $db->select("relation","path","relation_id=$rel_id");
		$file_del = @mysql_result($dbres,0,"path");
		
		if ($gdl_publisher['id']<>$publisher){
			$file_del= ereg_replace($file_del,"/files/","/files/$publisher/");
		}
		if (file_exists($file_del))	unlink ("$file_del");

		// delete data relation
		$db->delete("relation","relation_id=$rel_id");
		
		// arrange relation file number
		$dbres = $db->select("relation","*","identifier='$identifier'","relation_id","asc");
		$i = 0;
		while ($rows = mysql_fetch_row($dbres)){
			
			$i = $i + 1;
			$frm['RELATION_NO'] = $i;
			$frm['RELATION_DATEMODIFIED'] = $rows[2];
			$frm['RELATION_HASFILENAME'] = $rows[4];
			$frm['RELATION_HASFORMAT'] = $rows[7];
			$frm['RELATION_HASSIZE'] = $rows[8];
			$frm['RELATION_HASNOTE'] = $rows[10];
			$frm['RELATION_HASPART'] = $rows[5];
			$frm['RELATION_HASPATH'] = $rows[6];
			$frm['RELATION_HASURI'] = $rows[9];
			
			// generate xml data relation external
			$frm['TYPE_SCHEMA'] = "relation";
			$xmlrela .= $gdl_metadata->generate_xml($frm);
			
			// update no relation file
			$gdl_db->update("relation","no=$i","relation_id=$rows[0]");
			
		}
		
		// update relation in metadata
		$gdl_metadata->update_relation($identifier,$i,$xmlrela);
		return true;			
	}
	
	function get_count($identifier){
		require_once ("./class/db.php");
		$db = new database();
		$dbres = $db->select("relation","count(relation_id) as total","identifier='$identifier'");
		return @mysql_result($dbres,0,"total");
	}
	
	function get_relation($identifier){
		global $gdl_metadata, $gdl_sys, $gdl_session;
		
		$file=array();
		require_once ("./class/db.php");
		$db = new database();
		$dbres = $db->select("relation","*","identifier='$identifier'");
		while ($rows = mysql_fetch_row($dbres)){
				if ($gdl_session->user_id=="guest"){
					if ($gdl_sys['public_download']==true){
						$file[$rows[4]]['id']=$rows[0];
						$file[$rows[4]]['identifier']=$rows[1];
						$file[$rows[4]]['date']=$rows[2];
						$file[$rows[4]]['no']=$rows[3];
						$file[$rows[4]]['name']=$rows[4];
						$file[$rows[4]]['part']=$rows[5];
						$file[$rows[4]]['path']=$rows[6];
						$file[$rows[4]]['format']=$rows[7];
						$file[$rows[4]]['size']=$rows[8];
						$file[$rows[4]]['uri']=$rows[9];
						$file[$rows[4]]['note']=$rows[10];
						$file[$rows[4]]['icon']=$this->get_icon($rows[7]);
					}
				}else{
					$file[$rows[4]]['id']=$rows[0];
					$file[$rows[4]]['identifier']=$rows[1];
					$file[$rows[4]]['date']=$rows[2];
					$file[$rows[4]]['no']=$rows[3];
					$file[$rows[4]]['name']=$rows[4];
					$file[$rows[4]]['part']=$rows[5];
					$file[$rows[4]]['path']=$rows[6];
					$file[$rows[4]]['format']=$rows[7];
					$file[$rows[4]]['size']=$rows[8];
					$file[$rows[4]]['uri']=$rows[9];
					$file[$rows[4]]['note']=$rows[10];
					$file[$rows[4]]['icon']=$this->get_icon($rows[7]);
				}
			
		}
		return $file;
	}
	
	function get_icon($img){
		global $gdl_content;

		// extension
		$arext = explode("/",$img);
		$ext = $arext[sizeof($arext)-1];
		$ext = strtolower($ext);
	   	
	   	switch(strtolower($ext)){
			case "jpg":
			case "jpeg":
			case "png":
				$img_icon = "./theme/".$gdl_content->theme."/icon/photo.gif";
				break;
				
			case "gif":
			case "bmp":
			case "psd":
				$img_icon = "./theme/".$gdl_content->theme."/icon/photo.gif";
				break;
			
			case "xls":
				$img_icon = "./theme/".$gdl_content->theme."/icon/xls.gif";
				break;
			case "rtf":
			case "doc":
				$img_icon = "./theme/".$gdl_content->theme."/icon/doc.gif";
				break;
			
			case "ps":
			case "pdf":
				$img_icon = "./theme/".$gdl_content->theme."/icon/pdf.gif";
				break;
				
			case "pot":
			case "ppt":
				$img_icon = "./theme/".$gdl_content->theme."/icon/ppt.gif";
				break;
			
			case "html":
			case "htm":
				$img_icon = "./theme/".$gdl_content->theme."/icon/htm.gif";
				break;
			
			case "tgz":
			case "tar":
			case "gz":
			case "tgz":
			case "zip":
				$img_icon = "./theme/".$gdl_content->theme."/icon/zip.gif";
				break;
				
			case "mp3":
				$img_icon = "./theme/".$gdl_content->theme."/icon/mp3.gif";
				break;
				
			case "dat":
			case "asf":
				$img_icon = "./theme/".$gdl_content->theme."/icon/mplayer.gif";
				break;
			default:
				$img_icon = "./theme/".$gdl_content->theme."/icon/misc.gif";
	   	}
		return $img_icon;
	}
}

?>