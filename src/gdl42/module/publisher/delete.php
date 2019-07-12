<?

/***************************************************************************
                         /module/publisher/delete.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/


if (eregi("delete.php",$_SERVER['PHP_SELF'])) die();

$PUBLISHER_ID = str_replace('%20',' ',$_GET['id']);
$PUBLISHER_ID = str_replace('\\','',$PUBLISHER_ID);
$PUBLISHER_ID = str_replace('\'','',$PUBLISHER_ID);
$del = $_GET['del'];

if (isset($PUBLISHER_ID)){
	if (isset($PUBLISHER_ID) and $del=="confirm"){
		// confirmation
		require_once("./module/publisher/function.php");
		$main = "<p class=\"box\"><b>"._CONFIRMATION."</b></p>\n";
		$main .= display_property($PUBLISHER_ID);
		$main .= "<p>"._DELETEPUBLISHERCONFIRMATION."  <a href=\"./index.php?mod=publisher&amp;op=delete&amp;id='".$PUBLISHER_ID."'\">"._DELETEYES."</a></p>\n";
		$main = gdl_content_box($main,_DELETEPUBLISHER);
	}else{
		// delete folder
		$gdl_publisher2->delete($PUBLISHER_ID);
		
		header ("Location: ./gdl.php?mod=publisher");

	}
}

$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=member\">"._MEMBER."</a>";

?>