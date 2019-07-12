<?php

/***************************************************************************
                         /module/cdsisis/configure.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
if (eregi("configure.php",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/cdsisis/function.php");
$frm=$_POST['frm'];
$db_name=$_GET["db_name"];
$id=$_GET["id"];

if ($gdl_form->verification($frm) && $frm) {
	$main.=save_configuration($db_name);
	$main.="<p>".list_cdsisis()."</p>";
} else
	$main.=configure_cdsisis($db_name);	


$main = gdl_content_box($main,_CONFIGURECDSISIS);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis\">"._CDSISIS."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis&amp;op=configure&amp;db_name=".$db_name."\">"._CONFIGURE."</a>";

?>