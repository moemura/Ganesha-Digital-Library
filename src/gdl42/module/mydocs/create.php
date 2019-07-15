<?php
/***************************************************************************
                         /module/myodcs/create.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/add.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/mydocs/function.php");
$member_node=$gdl_folder->check_folder("Member",0);
if (!preg_match("/err/",$gdl_folder->check_folder($gdl_session->user_id,$member_node)) && !preg_match("/err/",$member_node))
	$main.=mydocs_exist();
else
	$main.=create_mydocs();

$main = gdl_content_box($main,"My Documents");
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=mydocs\">My Documents</a>";

?>