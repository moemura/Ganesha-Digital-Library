<?php
/***************************************************************************
                         /module/bookmark/delete.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/

if (preg_match("/delete.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$act = isset($_POST["act"]) ? $_POST["act"] : null;
$arr_id = isset($_POST['id']) ? $_POST['id'] : null;

// delete bookmark
if ((!empty($act)) and (!empty($arr_id))) {
	foreach ($arr_id as $key => $val) {
		$gdl_db->delete("bookmark","bookmark_id=$key");
	}
}
?>