<?php

/***************************************************************************
                         /module/request/comment.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (eregi("comment.php",$_SERVER['PHP_SELF'])) {
    die();
}

$id=$_GET["id"];
$frm=$_POST["frm"];
include ("./module/request/function.php");
if ($gdl_form->verification($frm) && $frm)
{	$main.="<p>".insert_comment($id)."</p>";
	$main.="<p>".display_request()."</p>";
}else {	
	$main.="<p>".comment_form($id)."</p>";
}
$gdl_content->main = gdl_content_box($main,_USERREQUEST);
$gdl_content->path="<a href=\"./index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=request\">"._USERREQUEST."</a>";


?>