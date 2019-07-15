<?php
if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) {
    die();
}
$_SESSION['DINAMIC_TITLE'] = _FOLKSONOMY;
global $gdl_folksonomy;

$main	= $gdl_folksonomy->show_box_folksonomy();
$main 	= gdl_content_box($main,_FOLKSONOMY);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=folksonomy\">"._FOLKSONOMY."</a>";

?>