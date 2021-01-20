<?php
require_once "./module/folksonomy/function.php";

global $gdl_stdout;

$frm=isset($_POST["frm"]) ? $_POST["frm"] : null;
$main = '';
if ($gdl_form->verification($frm) && $frm) {
	$main .= write_file_sync();
}
$main .= edit_form();
$main = gdl_content_box($main,_FOLKSONOMY);
$gdl_content->set_main($main); 
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=folksonomy\">"._FOLKSONOMY."</a>";

?>