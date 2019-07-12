<?php
if (eregi("step3.php",$_SERVER['PHP_SELF'])) die();

require_once ("./module/upload/function.php");
$frm = $_POST['frm'];
if (isset($frm) and is_array($frm)){
	if ($gdl_form->upload=="metadata"){
		if ($gdl_form->verification($frm)){
			
			$gdl_metadata->write($frm,$_SESSION['gdl_property']);
			
			// upload success
			if ($frm['RELATION_COUNT']==0){
				// upload finish
				$id = $gdl_metadata->identifier;
				header("Location:./gdl.php?mod=browse&op=read&id=$id");
			}else{
				// next step, upload file
				$main = relation_form($frm['RELATION_COUNT'],$gdl_metadata->identifier);
				$main = gdl_content_box($main,_STEP3);
			}
		}else{
			// form not complete, verification false
			$schema = $frm['TYPE_SCHEMA'];
			if (!isset($schema)) $schema="dc_document";
			$main = $gdl_metadata->generate_form($schema);
			$main = gdl_content_box($main,_STEP2);
		}
	}else{
		// user refresh this page
		$id = $gdl_metadata->identifier;
		header("Location:./gdl.php?mod=browse&op=read&id=$id");
	}
}else{
	// user click step3 by pass step2
	header("Location:./gdl.php?mod=upload");
}

$gdl_content->set_main($main);
$gdl_folder->set_path($_SESSION['gdl_node']);

?>