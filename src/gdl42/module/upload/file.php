<?php
if (preg_match("/file.php/i",$_SERVER['PHP_SELF'])) die();

require_once ("./module/browse/function.php");
require_once ("./module/browse/lang/".$gdl_content->language.".php");

$del = $_GET['del'];

if (isset($del)){

	// delete part of file relation
	$dbres = $gdl_db->select("relation","identifier","relation_id=$del");
	$row = @mysqli_fetch_assoc($dbres);
	$id = $row["identifier"];
	if ($gdl_file->delete_relation($del,$id)){
		header("Location:./gdl.php?mod=browse&op=read&id=$id");
	}else{
		$gdl_content->set_error(_UPLOADFAIL,_ERROR,"upload.file.delete_relation");
	}	
	
}else{

	// upload / edit file relation
	
	$id = $_POST['id'];
	$relation = $_POST['relation'];
	$action=false;

	while (list($key, $val) = each($relation)){
		if(!empty($val)) $action=true;
	}
	
	if ($action==false){
	
		// upload new file
		if ($gdl_form->upload=="file"){
			if ($gdl_form->verification($frm)){
				if ($gdl_file->upload($id)){
					header("Location:./gdl.php?mod=browse&op=read&id=$id");
				}else{
					$gdl_content->set_error(_UPLOADFAIL,_ERROR,"upload.file");
				}	
			}
		}else{
			// user refresh this page
			header("Location:./gdl.php?mod=browse&op=read&id=$id");
		}
		
	}else{
	
		// it has existing file ************
		
		// directory
		$arr_id = explode("-",$id);
		$no = $arr_id[(sizeof($arr_id))-1];
		$homedir  = "./$gdl_sys[repository_dir]/".ceil($no/$gdl_sys['metadata_per_dir']);
		
		if (!file_exists($homedir)){
			mkdir ($homedir,0755);
		}

		$count = $_POST['count'];
		
		for ($i = 1; $i <= $count; $i++) {
			if ($relation[$i]==""){
				
				// new upload file
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
				$fpart = preg_replace("/ /","",strtolower($fpart));
				
				$frm['RELATION_HASPART'] = "$id-$i-$fpart";
				$frm['RELATION_HASPATH'] = "$homedir/".$frm['RELATION_HASPART'];
				$frm['RELATION_HASURI'] = "/download.php?file=".$frm['RELATION_HASPART'];
				
				if (move_uploaded_file($_FILES['fname']['tmp_name'][$i],$frm['RELATION_HASPATH'])==false){
					$gdl_content->set_error(_PATHNOTEXIST,_UPLOADFAIL,"file.edit_upload_file");
				}
				
				// generate xml data relation external
				$frm['TYPE_SCHEMA'] = "relation";
				
				
				// input data to table relation
				$rel_field = "identifier,date_modified,no,name,part,path,format,size,uri,note";
				$rel_value = "'$id','$frm[RELATION_DATEMODIFIED]',$i,";
				$rel_value .= "'".$frm['RELATION_HASFILENAME'] ."','".$frm['RELATION_HASPART']."','".$frm['RELATION_HASPATH']."',";
				$rel_value .= "'".$frm['RELATION_HASFORMAT'] ."','".$frm['RELATION_HASSIZE']."','".$frm['RELATION_HASURI']."','".$frm['RELATION_HASNOTE']."'";
				$gdl_db->insert("relation",$rel_field,$rel_value);
				$id=mysqli_insert_id($gdl_db->con);
				$frm['RELATION_HASURI'] = "/download.php?id=".$id;
				$gdl_db->update("relation","uri='".$frm['RELATION_HASURI']."'","relation_id=$id");
				$xmlrela .= $gdl_metadata->generate_xml($frm);
			}else{
			
				// update file relation
				$file_name = $_FILES['fname']['name'][$i];
				$dbres = $gdl_db->select("relation","*","relation_id=$relation[$i]");
				$row = @mysqli_fetch_assoc($dbres);
				$frm['RELATION_NO'] = $row["no"];
				$frm['RELATION_DATEMODIFIED'] = date("Y-m-d H:i:s");
				$frm['RELATION_HASFILENAME'] = $row["name"];
				$frm['RELATION_HASFORMAT'] = $row["format"];
				$frm['RELATION_HASSIZE'] = $row["size"];
				$frm['RELATION_HASNOTE'] = $row["note"];
				$frm['RELATION_HASPART'] = $row["part"];
				$frm['RELATION_HASPATH'] = $row["path"];
				$frm['RELATION_HASURI'] = $row["uri"];
				
				if (!empty($file_name)){
					// update files// delete old file
					if (file_exists($frm['RELATION_HASPATH']))	unlink ("$frm[RELATION_HASPATH]");
					
					$frm['RELATION_NO'] = $i;
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
					$fpart = preg_replace("/ /","",strtolower($fpart));
					
					$frm['RELATION_HASPART'] = "$id-$i-$fpart";
					$frm['RELATION_HASPATH'] = "$homedir/".$frm['RELATION_HASPART'];
					$frm['RELATION_HASURI'] = "/download.php?id=".$relation[$i];
					
					if (move_uploaded_file($_FILES['fname']['tmp_name'][$i],$frm['RELATION_HASPATH'])==false){
						$gdl_content->set_error(_PATHNOTEXIST,_UPLOADFAIL,"file.edit_upload_file");
					}
					
					// update relation table
					$gdl_db->update("relation","uri='$frm[RELATION_HASURI]',size='$frm[RELATION_HASSIZE]',format='$frm[RELATION_HASFORMAT]',path='$frm[RELATION_HASPATH]',part='$frm[RELATION_HASPART]',name='$frm[RELATION_HASFILENAME]',no=$frm[RELATION_NO],date_modified='$frm[RELATION_DATEMODIFIED]',note='".$_POST['desc'][$i]."'","relation_id=$relation[$i]");
					
				}else{
					// just update description
					$gdl_db->update("relation","date_modified='$frm[RELATION_DATEMODIFIED]',note='".$_POST['desc'][$i]."'","relation_id=$relation[$i]");
					$frm['RELATION_HASNOTE'] = $_POST['desc'][$i];
				}
				// generate xml data relation external
				$frm['TYPE_SCHEMA'] = "relation";
				$xmlrela .= $gdl_metadata->generate_xml($frm);
			}
		}
		// update metadata relation
		$gdl_metadata->update_relation($id,$count,$xmlrela);
		
		// display metadata
		$frm = $gdl_metadata->read($id);
		$main = "<p class=\"box\">$frm[TITLE]</p>\n";
		$main .= "<p>".display_metadata($frm)."</p>";
		$main .= "<p>".display_contact($frm)."</p>";
		$main = gdl_content_box($main,_STEP3);

	}
}
$gdl_content->set_main($main);
$gdl_folder->set_path($_SESSION['gdl_node']);
?>