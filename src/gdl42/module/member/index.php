<?php

/***************************************************************************
                         /module/member/index.php
                             -------------------
    copyright            : (C) 2007 Beni Rio Hermanto, KMRG ITB
    email                : benirio@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$frm = $gdl_account->get_identity ($gdl_session->user_id);
if (isset ($_POST['q'])){
	$q = urlencode ($_POST['q']);
	header ("location: ./gdl.php?mod=member&y=$q");
}

$y=$_GET['y'];
$frm=$_POST["frm"];

require_once("./module/member/function.php");
require_once("./module/register/function.php");
$action = "./gdl.php?mod=member";
if (preg_match("/{member->*}/",$gdl_session->authority) || $gdl_session->authority=="*") {
	$main = "<p>".search_member_form ()."</p>\n";
	$main .= display_member($y);
	$main = gdl_content_box($main,_MEMBERMANAGEMENT);
} else {
	if ($gdl_form->verification($frm) && $frm) {
		if (empty ($frm['PASSWORD']) && empty ($frm['PASSWORDCONFIRM'])){
		$frm['PASSWORD'] = $frm['PASSWORDCONFIRM'] = "'".$gdl_account->passwd."'";
		} else {
		$frm['PASSWORD'] =  "SHA2('$frm[PASSWORD]', 512)";
		$frm['PASSWORDCONFIRM'] = "SHA2('$frm[PASSWORDCONFIRM]', 512)";
		}
	   if (!($gdl_account->cek_password ($frm['PASSWORD'], $frm['PASSWORDCONFIRM']))) {				
			$main = "<p>"._UPDATE_ERROR_PASSWORD."</p>\n";
			$main .= form_register ($action);
			$main = gdl_content_box($main,_USEREDIT." (".$gdl_session->user_id.")");
 		 } else {
		    	$prosesupadate = $gdl_account->update_user($frm,$gdl_session->user_id);					
			if ($prosesupadate) {
				$main = "<p>"._UPDATESUCCESS."</p>\n";
				$main = gdl_content_box($main,_USEREDIT." (".$gdl_session->user_id.")");				
			} else { echo "gagal";}
		}
	} else {

		$frm = $gdl_account->get_identity ($gdl_session->user_id);
		$group_level = $gdl_db->select("group", "group_id","group_id <> 'public'");
		while ($rowlevel = @mysqli_fetch_array ($group_level)) {
			$grouplevel[$rowlevel['group_id']] = $rowlevel['group_id'];
		}
		$userstatus ['1'] = _ACTIVE;
		$userstatus ['0'] = _NOACTIVE;
		$main .=form_register($action);
		$main = gdl_content_box($main,_USEREDIT." (".$gdl_session->user_id.")");
		
	}
}
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=member\">"._MEMBER."</a>";

?>