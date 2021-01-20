<?php
if (preg_match("/import.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/synchronization/function.php");

$frm = isset($_POST["frm"]) ? $_POST["frm"] : null;
$action = isset($_GET["action"]) ? $_GET["action"] : null;

$main = '';
if ($frm) {
	$main.=upload_file();
}

if ($action == "import") {
	$filename = isset($_GET["filename"]) ? $_GET["filename"] : null;
	$main .= $gdl_import->metadata_from_file($filename);
} elseif ($action == "delete") {
	$filename = isset($_GET["file"]) ? $_GET["file"] : null;
	$main .= delete_file($filename);
}

$main .= import_form();
$main .= list_of_uploaded_file();
$main = gdl_content_box($main,_IMPORT." Metadata");
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=synchronization\">"._SYNCHRONIZATION."</a>";
?>