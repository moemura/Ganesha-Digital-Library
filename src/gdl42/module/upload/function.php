<?php

if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

function relation_form($count,$identifier){
	global $gdl_form, $gdl_db;
	
	$dbres = $gdl_db->select("relation","relation_id,name,no,size,note","identifier='$identifier'");
	$numrow = mysqli_num_rows($dbres);
	while ($rows = mysqli_fetch_row($dbres)){
		$file[$rows[2]]['id']=$rows[0];
		$file[$rows[2]]['name']=$rows[1];
		$file[$rows[2]]['size']=$rows[3];
		$file[$rows[2]]['note']=$rows[4];
	}
		
	$gdl_form->set_name("file");
	$gdl_form->action="./gdl.php?mod=upload&amp;op=file";
	$gdl_form->enctype=true;
	$gdl_form->add_field(array(
				"type"=>"hidden",
				"name"=>"count",
				"value"=>$count));
	$gdl_form->add_field(array(
				"type"=>"hidden",
				"name"=>"id",
				"value"=>$identifier));
				
	if ($numrow > $count) $count=$numrow;
	for ($i = 1; $i <= $count; $i++) {
		
		$rel_id="";
		if (isset($file[$i]['name'])){
			$rel_id =$file[$i]['id'];
			$gdl_form->add_field(array(
						"type"=>"title",
						"text"=>_FILE." $i : ".$file[$i]['name']." (".$file[$i]['size']." bytes) [<a href=\"./gdl.php?mod=upload&amp;op=file&amp;del=".$file[$i]['id']."\">"._DELETE."</a>]"));
		}else{
			$gdl_form->add_field(array(
						"type"=>"title",
						"text"=>_FILE." $i. "._UPLOADNEFILE));
		}
		
		$gdl_form->add_field(array(
					"type"=>"hidden",
					"name"=>"relation[$i]",
					"value"=>$rel_id));
					
		$gdl_form->add_field(array(
					"type"=>"file",
					"name"=>"fname[$i]",
					"text"=>_SOURCEPATH,
					"size"=>50));
			
		$gdl_form->add_field(array(
					"type"=>"textarea",
					"name"=>"desc[$i]",
					"value"=>$file[$i]['note'],
					"text"=>_DESCRIPTION,
					"rows"=>5,
					"cols"=>59));
	}
	$gdl_form->add_button(array(
				"type"=>"submit",
				"name"=>"submit",
				"value"=>_SUBMIT));
	$gdl_form->add_button(array(
				"type"=>"reset",
				"name"=>"reset",
				"value"=>_RESET));
	$main = $gdl_form->generate("110");
	return $main;
}

function current_state(){
	global $gdl_metadata, $gdl_folder;
	
	if ($_SESSION["gdl_identifier"]){
		$frm = $gdl_metadata->read($_SESSION["gdl_identifier"]);
		$main .= "<p><b>"._CURRENTMETADATA." :</b> <a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=".$_SESSION["gdl_identifier"]."\">$frm[TITLE]</a> ";
		$main .= "[ <a href=\"./gdl.php?mod=upload&amp;op=step2&amp;id=".$_SESSION["gdl_identifier"]."\">"._EDIT."</a> ]</p>";
	}
	
	$main .= "<p><b>"._CURRENTFOLDER." :</b> ".$gdl_folder->get_path_name($_SESSION['gdl_node'],true)."</p>";
	return $main;
}

?>