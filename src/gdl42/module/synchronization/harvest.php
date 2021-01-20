<?php
include_once "buffer_file.php";

/**
Semua data yang ada disini akan direkoding ulang. Saat ini masih dalam tahap pemindahan source untuk menganalisa 
kelakuan dari operasi harvest pada versi sebelumnya.
*/
global $gdl_harvest,$gdl_harvest,$gdl_sync,$gdl_oaipmh;

$verb	= isset($_GET['verb']) ? $_GET['verb'] : null;

$main_url = "./gdl.php?mod=synchronization&amp;op=harvest&amp;";
$gdl_harvest->main_url = $main_url;
$main = $gdl_harvest->operation_navigator_harvest($main_url);
if(isset($verb)){
	if ($verb=="ListRecords") {
	     if ($gdl_sync["sync_opt_script"] == "0"){
			$sub=$_GET["sub"];
			if (isset($sub) && ($sub == "0"))
				$verb = "PutListRecords";
		}
		$main .=	$gdl_harvest->execute_verb($verb);
	} else
		$main .= $gdl_harvest->execute_verb($verb);
	
}else{

	$action = isset($_GET['action']) ? $_GET['action'] : null;
	if(isset($action)){
		if($action == "cleanInbox"){
			$main .= $gdl_synchronization->clean_inbox();
		}
	}
}
$main = gdl_content_box($main,_HARVESTING);
$gdl_content->set_main($main); 
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=synchronization\">"._SYNCHRONIZATION."</a>";
?>