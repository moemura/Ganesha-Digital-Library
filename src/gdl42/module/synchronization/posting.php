<?php
if (preg_match("/posting.php/i",$_SERVER['PHP_SELF'])) {
    die();
}
include_once "buffer_file.php";
include_once "function.php";
global $HTTP_SESSION_VARS,$gdl_harvest,$gdl_synchronization;

$verb		= isset($_GET['verb']) ? $_GET['verb'] : null;
$sub		= isset($_GET['sub']) ? $_GET['sub'] : null;
$action		= isset($_GET['action']) ? $_GET['action'] : null;
$record		= isset($_GET['record']) ? $_GET['record'] : null;

if(isset($action)){
	if(($action == "delete") && !empty($record)){
		$gdl_synchronization->delete_queue($record);
	}else if(($action == "queue") && !empty($record)){
		$gdl_synchronization->re_queue($record);
	}
}

$main_url 	=	"./gdl.php?mod=synchronization&amp;op=posting";
$gdl_harvest->main_url = $main_url;
$main 		= 	$gdl_harvest->operation_navigator_posting($main_url);

if(isset($sub)){
	if($sub == 0){
		//$main	.=	$gdl_harvest->execute_verb($verb);
	}else if($sub == 1){
		$frm	= isset($_POST['frm']) ? $_POST['frm'] : null;
		if(isset($frm)){

			if(empty($frm['posting'])){
				$gdl_synchronization->update_queue_job($frm);
			}else if($frm['posting'] == _POSTINGFILES)
				$main	.=	$gdl_harvest->execute_verb("PutFileFragment");
		}else if(!empty($verb))
				$main	.=	$gdl_harvest->execute_verb($verb);
		
		$path	= isset($_GET['path']) ? $_GET['path'] : null;
		$main	.= box_files("",$path);
		$main	.= box_queue();
		$main	.= box_status_posting($main_url,1);
	}else if($sub == 2){
		$action 	= isset($_GET['action']) ? $_GET['action'] : null;
		
		$sub2_msg = '';
		if(isset($action)){
			if($action == "delete"){
				$status	= isset($_GET['status']) ? $_GET['status'] : null;
				if(isset($status)){
						$sub2_msg	= "<br/><br/>".$gdl_synchronization->clean_outbox($status);
				}
			}else if($action == "extract"){
				$sub2_msg	= "<br/><br/>".$gdl_synchronization->extract_identifier_from_metadata($main_url."&amp;sub=2&amp;action=extract");
			}
		}
		$main	.=  box_status_outbox($main_url."&amp;sub=2").$sub2_msg;
	}
}

$main 				= gdl_content_box($main,_POSTING);
$gdl_content->set_main($main); 
$gdl_content->path	= "<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"$main_url\">"._POSTING."</a>";
?>