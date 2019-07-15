<?php 


/***************************************************************************
                         /module/mydocs/function.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
 if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function mydocs_exist() {
	global $gdl_folder,$gdl_session;
	$member_node=$gdl_folder->check_folder("Member",0);
	$node=$gdl_folder->check_folder($gdl_session->user_id,$member_node);
	$content.="Folder <a href='./gdl.php?mod=browse&amp;node=".$node."'>/Member/".$gdl_session->user_id."/</a> [<a href='./gdl.php?mod=browse&amp;node=".$node."'>Browse</a>] | [<a href='./gdl.php?mod=explorer&amp;node=".$node."'>Explorer</a>] ";
	return $content;
}

function mydocs_not_exist() {
	global $gdl_folder,$gdl_session;
	$content.=_MYDOCUMENTSFOLDER." <b>/Member/".$gdl_session->user_id."/</b> "._DOESNOTEXISTDOYOUWANTTOCREATE." <a href='./gdl.php?mod=mydocs&amp;op=create'>"._YES."</a>";
	return $content;
}

function create_mydocs() {
	global $gdl_folder,$gdl_session;
	
	$member_node=$gdl_folder->check_folder("Member",0);
	if (ereg("err",$member_node)){
		$folder["name"]="Member";
		$folder["parent"]=0;
		if (!$gdl_folder->add($folder)) {
			$content.="<b>"._MYDOCUMENTSCREATEFAILED."</b><br>";
			return $content;
		}
			
		$member_node=mysql_insert_id();
	} 
	$folder["name"]=$gdl_session->user_id;
	$folder["parent"]=$member_node;
	if ($gdl_folder->add($folder)) {
			$content.="<b>"._MYDOCUMENTSCREATED."</b><br>";
			$content.=mydocs_exist();
		}
	else
		$content.="<b>"._MYDOCUMENTSCREATEFAILED."</b><br>";
	
	return $content;
}


