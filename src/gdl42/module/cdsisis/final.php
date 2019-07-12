<?php

/***************************************************************************
                         /module/cdsisis/final.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
 
if (eregi("final.php",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/cdsisis/function.php");
$final=$_GET["final"];
$main.=final_union_index();
$main = gdl_content_box($main,_BUILDFINALUNIONINDEX);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis\">"._CDSISIS."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis&amp;op=final\">"._BUILDFINALUNIONINDEX."</a>";

?>