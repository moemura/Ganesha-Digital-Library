<?php
include_once "buffer_file.php";
global $gdl_synchronization,$gdl_stdout,$gdl_sync;

$gdl_synchronization->sync_disconnection();

$title	= "Connecting to HUB Server";
$msg	= "<b>DISCONNECTED...</b><br>
			You have been disconnected from the Hub Server.<br>
			Your Hub Server : <b>$gdl_sync[sync_hub_server_name]</b>";

$main = $gdl_stdout->print_message($title,$msg);		

$main = gdl_content_box($main,_CONNECTION);
$gdl_content->set_main($main); 
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=synchronization\">"._SYNCHRONIZATION."</a>";

?>