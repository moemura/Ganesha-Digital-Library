<?php
global $gdl_synchronization,$gdl_harvest,$gdl_sync,$gdl_stdout;
include_once "buffer_file.php";
require_once "./module/synchronization/function.php";

$frm = isset($_POST["frm"]) ? $_POST["frm"] : null;
$main = '';
$edit_form = '';
if ($gdl_form->verification($frm) && $frm){
	global $bufferFormSubmit;
	
	$main 		.= write_file_sync($frm);
	
	if(is_array($bufferFormSubmit))
		$frm = $bufferFormSubmit;
		
	$edit_form	= edit_form($frm);
}

$rUpdate = '';
$show_form	= 0;
$action 	= isset($_GET['action']) ? $_GET['action'] : null;
if(!empty($action)){
	if($action == "repo")
		$rUpdate = $gdl_synchronization->update_repository_from_publisher();
	else if($action == "edit"){
		$frm		= $gdl_synchronization->get_info_repository(isset($_GET['record']) ? $_GET['record'] : null);
		$edit_form	= edit_form($frm);
	}else if($action == "delete"){
		$failed = $gdl_synchronization->delete_record_repository(isset($_GET['record']) ? $_GET['record'] : null);
		if(!$failed){
			$page	= isset($_GET['page']) ? $_GET['page'] : null;
			if(isset($page)){
				$page = (preg_match("/^[0-9]+$/",$page))?$page-1:1;
				$page = ($page > 0)?$page:1;
			}else
				$page = 1;
			
			$main .= $gdl_stdout->header_redirect(1,"./gdl.php?mod=synchronization&op=option&page=$page");
		}
	}else if($action == "add"){
		$edit_form	= edit_form(null,"new");
	}
	$show_form = 1;
}else{
	$update = isset($_GET['update']) ? $_GET['update'] : null;
	if($update == "Identify"){
		$_SESSION['sess_Identify']	= isset($_GET['record']) ? $_GET['record'] : null;
		$rUpdate = $gdl_harvest->execute_verb("Identify");
	}else if($update == "ListSets"){
		$_SESSION['sess_Identify']	= isset($_GET['record']) ? $_GET['record'] : null;
		$rUpdate  = $gdl_harvest->execute_verb("ListSets");
	}
}

if(empty($edit_form) && $show_form)
	$edit_form	= edit_form();

$url  =  "http://$gdl_sync[sync_hub_server_name]/$gdl_sync[sync_oai_script]";

$main .= "<br/>".search_repository_form()."<br/>";
$main .= "<br/>"._CURRENTREPOSITORY."<br/><strong>..:: $gdl_sync[sync_repository_name]  ($url) ::..</strong><br/><br/>";
$main .= shortcut_repository();
$main .= display_repository(isset($_POST['searchkey']) ? $_POST['searchkey'] : null);
$main .= $rUpdate;
$main .= $edit_form;

$main = gdl_content_box($main,_CONFIGURATION);
$gdl_content->set_main($main); 
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=synchronization\">"._SYNCHRONIZATION."</a>";
?>