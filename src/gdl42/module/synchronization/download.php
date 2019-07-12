<?php
if (eregi("download.php",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/synchronization/function.php");

$main = download_metadata_archive();
$main = gdl_content_box($main,_DOWNLOADMETADATA);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=synchronization\">"._SYNCHRONIZATION."</a>";

?>