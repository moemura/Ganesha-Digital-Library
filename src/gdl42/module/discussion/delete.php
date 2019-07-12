<?php

/***************************************************************************
                         /module/discussion/delete.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (eregi("delete.php",$_SERVER['PHP_SELF'])) die();

$id=$_GET["id"];
$del=$_GET["del"];
require_once("./module/discussion/function.php");
if (!empty($id)){
	if (!empty($id) and $del=="confirm"){
		// confirmation
		$dbres=$gdl_db->select("comment","*","comment_id='".$id."'");
		if ($dbres) {
			$row=mysql_fetch_array($dbres);	
			$main = "<p class=\"box\"><b>"._CONFIRMATION."</b></p>\n";
			$main .= "<b>Identifier</b> : <a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=".$row["identifier"]."\">".$row["identifier"]."</a><br/>";
			$main .= "<b>"._DATE."</b>: ".$row["date"]."<br/>";
			$main .= "<b>"._USERID."</b>: ".$row["user_id"]."<br/>";
			$main .= "<b>"._SUBJECT."</b>: ".$row["subject"]."<br/>";
			$main .= "<b>"._COMMENTS."</b>: ".$row["comment"]."<br/>";
			$main .= "<p>"._DELETECOMMENTCONFIRMATION."  <a href=\"./gdl.php?mod=discussion&amp;op=delete&amp;id=".$id."\">"._DELETEYES."</a></p>\n";
			$main = gdl_content_box($main,_DELETECOMMENT);			
		} else {
			$main .= "<b>"._NOTFOUND." $id</b>";
			$main .= "<p>".search_discussion_form ()."</p>\n";
			$main .= display_discussion($searchkey);
			$main = gdl_content_box($main,_DISCUSSION);
		}
		
	}else{
		$dbres=$gdl_db->delete("comment","comment_id=".$id);
		if ($dbres) {
			$main .= "<b>"._SUCCESS."</b>";
			$main .= "<p>".search_discussion_form ()."</p>\n";
			$main .= display_discussion($searchkey);
			$main = gdl_content_box($main,_DISCUSSION);
		}
	}
}

$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=discussion\">"._DISCUSSION."</a>";

?>