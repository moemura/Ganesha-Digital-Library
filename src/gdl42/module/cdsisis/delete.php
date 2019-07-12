<?
/***************************************************************************
                         /module/cdsisis/delete.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
if (eregi("delete.php",$_SERVER['PHP_SELF'])) die();

$db_name = $_GET['db_name'];
$del=$_GET["del"];
require_once("./module/cdsisis/function.php");

if (isset($db_name)){
	if (isset($db_name) and $del=="confirm"){
		// confirmation

		$main = "<p class=\"box\"><b>"._CONFIRMATION."</b></p>\n";
		$main .= _DBNAME ." : ". $db_name;
		$main .= "<p>"._DELETECDSISISCONFIRMATION."  <a href=\"./gdl.php?mod=cdsisis&amp;op=delete&amp;db_name=".$db_name."\">"._DELETEYES."</a></p>\n";
		$main = gdl_content_box($main,_DELETECDSISIS);
	}else{
		$main .= delete_cdsisis($db_name);
		$main .= "<p>".list_cdsisis()."</p>";
		$main = gdl_content_box($main,_CDSISIS);
	}
}

$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis\">"._CDSISIS."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis&amp;op=delete&amp;del=confirm&amp;db_name=".$db_name."\">"._DELETE."</a>";

?>