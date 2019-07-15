<?php
if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) {
    die();
}
$_SESSION['DINAMIC_TITLE'] = _SYNCHRONIZATION;
require_once("./module/synchronization/function.php");

$main .= synchronization_main();
$main .= _SYNCHRINDEX;
$main = gdl_content_box($main,_SYNCHRONIZATION);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=synchronization\">"._SYNCHRONIZATION."</a>";

?>