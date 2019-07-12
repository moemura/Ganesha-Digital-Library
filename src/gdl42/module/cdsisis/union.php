<?php

/***************************************************************************
                         /module/cdsisis/union.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
if (eregi("union.php",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/cdsisis/function.php");
$union=$_GET["union"];
if (!$union){
	$main.=list_isis_index();
} else {
	$main.=union_isis_index();
}
$main = gdl_content_box($main,_BUILDUNIONINDEX);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis\">"._CDSISIS."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis&amp;op=union\">"._BUILDUNIONINDEX."</a>";

?>