<?php
/***************************************************************************
                         /module/bookmark/comment.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/

if (preg_match("/comment.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$id = isset($_GET["id"]) ? $_GET["id"] : null;
$submit = isset($_POST["submit"]) ? $_POST["submit"] : null;
$frm = isset($_POST["frm"]) ? $_POST["frm"] : null;
include ("./module/bookmark/function.php");
$main = '';
if ($gdl_form->verification($frm) && $frm)
{	$main.="<p>".insert_comment($id)."</p>";
	$main.="<p>".display_bookmark()."</p>";
}else {	
	$main.="<p>".comment_form($id)."</p>";
}
$gdl_content->main = gdl_content_box($main,_MYBOOKMARK);
$gdl_content->path="<a href=\"./index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=bookmark\">"._MYBOOKMARK."</a>";
?>