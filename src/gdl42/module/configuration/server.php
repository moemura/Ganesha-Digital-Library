<?php

/***************************************************************************
                         /module/configuration/server.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/server.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/configuration/function.php");
$frm=$_POST["frm"];
if ($gdl_form->verification($frm) && $frm) {
	$main .= write_file_publisher();
}
$main .= edit_server_form();
$main = gdl_content_box($main,_SERVERCONF);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=configuration\">"._CONFIGURATION."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=configuration&amp;op=server\">Server</a>";

?>
