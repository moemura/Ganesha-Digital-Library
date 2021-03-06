<?php
/***************************************************************************
                         /module/migration/configuration.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/configuration.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/migration/function.php");

$main = '';
$frm=isset($_POST['frm']) ? $_POST['frm'] : null;
if ($gdl_form->verification($frm) && $frm) {
	$main.=write_file_system();
}

$main .= "<p>".edit_system_form()."</p>";
$main = gdl_content_box($main,_MIGRATION);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=migration\">"._MIGRATION."</a>";
?>