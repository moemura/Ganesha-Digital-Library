<?php
global $gdl_synchronization,$gdl_harvest,$gdl_sync,$gdl_stdout;
include_once "buffer_file.php";
require_once "./module/synchronization/function.php";

$frm=$_POST["frm"];

if ($gdl_form->verification($frm) && $frm){
	global $bufferFormSubmit;
	
	$main 		.= write_file_sync($frm);
	
	if(is_array($bufferFormSubmit))
		$frm = $bufferFormSubmit;
		
	$edit_form	= edit_form($frm);
}

$show_form	= 0;
$action 	= $_GET['action'];
if(!empty($action)){
	if($action == "repo")
		$rUpdate = $gdl_synchronization->update_repository_from_publisher();
	else if($action == "edit"){
		$frm		= $gdl_synchronization->get_info_repository($_GET['record']);
		$edit_form	= edit_form($frm);
	}else if($action == "delete"){
		$failed = $gdl_synchronization->delete_record_repository($_GET['record']);
		if(!$failed){
			$page	= $_GET['page'];
			if(isset($page)){
				$page = (ereg("^[0-9]+$",$page))?$page-1:1;
				$page = ($page > 0)?$page:1;
			}else
				$page = 1;
			
			$main .= $gdl_stdout->header_redirect(1,"./gdl.php?mod=synchronization&op=option&page=$page");
		}
	}else if($action == "add"){
		$edit_form	= edit_form("","new");
	}
	$show_form = 1;
}else{
	$update = $_GET['update'];
	if($update == "Identify"){
		$_SESSION['sess_Identify']	= $_GET['record'];
		$rUpdate = $gdl_harvest->execute_verb("Identify");
	}else if($update == "ListSets"){
		$_SESSION['sess_Identify']	= $_GET['record'];
		$rUpdate  = $gdl_harvest->execute_verb("ListSets");
	}
}

if(empty($edit_form) && $show_form)
	$edit_form	= edit_form();

$url  =  "http://$gdl_sync[sync_hub_server_name]/$gdl_sync[sync_oai_script]";

$main .= "<br/>".search_repository_form()."<br/>";
$main .= "<br/>"._CURRENTREPOSITORY."<br/><strong>..:: $gdl_sync[sync_repository_name]  ($url) ::..</strong><br/><br/>";
$main .= shortcut_repository();
$main .= display_repository($_POST['searchkey']);
$main .= $rUpdate;
$main .= $edit_form;

$main = gdl_content_box($main,_CONFIGURATION);
$gdl_content->set_main($main); 
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=synchronization\">"._SYNCHRONIZATION."</a>";



?>