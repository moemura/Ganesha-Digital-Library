<?php
if (eregi("posting.php",$_SERVER['PHP_SELF'])) {
    die();
}
include_once "buffer_file.php";
include_once "function.php";
global $HTTP_SESSION_VARS,$gdl_harvest,$gdl_synchronization;

$verb		= $_GET['verb'];
$sub		= $_GET['sub'];
$action		= $_GET['action'];
$record		= $_GET['record'] ;

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
		$frm	= $_POST['frm'];
		if(isset($frm)){

			if(empty($frm['posting'])){
				$gdl_synchronization->update_queue_job($frm);
			}else if($frm['posting'] == _POSTINGFILES)
				$main	.=	$gdl_harvest->execute_verb("PutFileFragment");
		}else if(!empty($verb))
				$main	.=	$gdl_harvest->execute_verb($verb);
		
		$path	= $_GET['path'];
		$main	.= box_files("",$path);
		$main	.= box_queue();
		$main	.= box_status_posting($main_url,1);
	}else if($sub == 2){
		$action 	= $_GET['action'];
		
		if(isset($action)){
			if($action == "delete"){
				$status	= $_GET['status'];
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