<?php
/***************************************************************************
                         /module/cdsisis/build.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (eregi("build.php",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/cdsisis/function.php");
$db_name=$_GET["db_name"];
$step=$_GET["step"];
$id=$_GET["id"];
switch ($step) {
	case "1" : $main.=export_database($db_name);
			   break;
	case "2" : $main.=indexing_process($db_name);
			   break;
	default :
				$main.=build_index($db_name);
}

$main = gdl_content_box($main,_BUILDINDEX);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis\">"._CDSISIS."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis&amp;op=build&amp;db_name=".$db_name."\">"._BUILDINDEX."</a>";

?>