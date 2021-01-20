<?php
/***************************************************************************
                         /module/bookmark/function.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, Lastiko Wibisono, KMRG ITB
    email                : hayun@kmrg.itb.ac.id, leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/
 
if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$_SESSION['DINAMIC_TITLE'] = _MYBOOKMARK;
$id = isset($_GET["id"]) ? $_GET["id"] : null;
$date = date("Y-m-d H:i:s");
$user = $gdl_session->user_id;
$act = isset($_POST["act"]) ? $_POST["act"] : null;
$arr_id = isset($_POST['id']) ? $_POST['id'] : null;

// delete bookmark
if ((!empty($act)) and (!empty($arr_id))) {
	$submit=isset($_POST["submit"]) ? $_POST["submit"] : null;
	if (preg_match('/'._DELETEBOOKMARK.'/i',$submit) || preg_match('/'._DELETEREQUEST.'/i',$submit))
	{	
		foreach ($arr_id as $key => $val) {
			$gdl_db->delete("bookmark","bookmark_id=$key");
		}
	} elseif (preg_match('/'._USERREQUESTMOVE.'/i',$submit)) {
		foreach ($arr_id as $key => $val) {
				$gdl_db->update("bookmark","time_stamp='".$date."',request=1","bookmark_id=$key");
		}
	} elseif (preg_match('/'._BOOKMARKMOVE.'/i',$submit)) {
		foreach ($arr_id as $key => $val) {
				$gdl_db->update("bookmark","time_stamp='".$date."',request=null","bookmark_id=$key");
		}		
	}
}
var_dump($gdl_session->refresh);
if ($user=="public") $user = "public";
if ($gdl_session->refresh==false){
	// insert $id into bookmark
	if ((isset($id))and ($id<>"")){
		$gdl_db->insert("bookmark","user_id,time_stamp,identifier","'$user','".$date."','$id'");
	}
}
include "./module/bookmark/function.php";

$main=display_bookmark();
$gdl_content->main = gdl_content_box($main,_MYBOOKMARK);
$gdl_content->path="<a href=\"./index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=bookmark\">"._MYBOOKMARK."</a>";
?>