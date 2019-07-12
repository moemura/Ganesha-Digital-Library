<?php
if (eregi("export.php",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/synchronization/function.php");
$frm=$_POST["frm"];
if ($frm && $gdl_form->verification($frm) )
	$main = $gdl_import->export_process();
else	
	$main = export_form();

$main = gdl_content_box($main,_EXPORT." Metadata");
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=publisher\">"._PUBLISHER."</a>";

?>