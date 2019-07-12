<?
/***************************************************************************
                         /module/bookmark/function.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, Lastiko Wibisono, KMRG ITB
    email                : hayun@kmrg.itb.ac.id, leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/
 
if (eregi("index.php",$_SERVER['PHP_SELF'])) {
    die();
}

$_SESSION['DINAMIC_TITLE'] = _MYBOOKMARK;
$id = $_GET["id"];
$date = date("Y-m-d H:i:s");
$user = $gdl_session->user_id;
$act = $_POST["act"];
$arr_id = $_POST['id'];


// delete bookmark
if ((!empty($act)) and (!empty($arr_id))) {
	$submit=$_POST["submit"];
	if (eregi(_DELETEBOOKMARK,$submit) || eregi(_DELETEREQUEST,$submit))
	{	
		while (list($key,$val) = each($arr_id)){
			$gdl_db->delete("bookmark","bookmark_id=$key");
		}
	} elseif (eregi(_USERREQUESTMOVE,$submit)) {
		while (list($key,$val) = each($arr_id)) {
				$gdl_db->update("bookmark","time_stamp='".$date."',request=1","bookmark_id=$key");
		}
	} elseif (eregi(_BOOKMARKMOVE,$submit)) {
		while (list($key,$val) = each($arr_id)) {
				$gdl_db->update("bookmark","time_stamp='".$date."',request=null","bookmark_id=$key");
		}		
	}
}

if ($user=="public") $user = "public";
if ($gdl_session->refresh==false){
	// insert $id into bookmark
	if ((isset($id))and ($id<>"")){
		$gdl_db->insert("bookmark","user_id,time_stamp,identifier","'$user','".$date."','$id'");
	}
}
include "./module/bookmark/function.php";

$main.=display_bookmark();
$gdl_content->main = gdl_content_box($main,_MYBOOKMARK);
$gdl_content->path="<a href=\"./index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=bookmark\">"._MYBOOKMARK."</a>";
?>