<?php

/***************************************************************************
                         /module/member/edit.php
                             -------------------
    copyright            : (C) 2007 Beni Rio Hermanto, KMRG ITB
    email                : benirio@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

$frm = $gdl_account->get_identity ($_GET['a']);
$group_level = $gdl_db->select("group", "group_id,name","group_id <> 'public'");
while ($rowlevel = @mysqli_fetch_array ($group_level)) {
	$grouplevel[$rowlevel['group_id']] = $rowlevel['name'];
}
$userstatus ['1'] = _ACTIVE;
$userstatus ['0'] = _NOACTIVE;

require_once "./module/register/function.php";
require_once "./module/member/function.php";
$action = "./gdl.php?mod=member&amp;op=edit&amp;a=$_GET[a]&amp;page=updt";

$page = $_GET['page'];
if (!isset($page) || empty ($page) || ($page!== "updt")){
	$main .= form_register ($action);
	$main = gdl_content_box($main,_USEREDIT." (".$_GET[a].")");
	$gdl_content->set_main($main); 
	$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=member\">"._MEMBER."</a>";
} else {
	$frm=$_POST['frm'];
	if (empty ($frm['PASSWORD']) && empty ($frm['PASSWORDCONFIRM'])){
		$frm['PASSWORD'] = $frm['PASSWORDCONFIRM'] = "'".$gdl_account->passwd."'";
	} else {
		$frm['PASSWORD'] =  "old_password('$frm[PASSWORD]')";
		$frm['PASSWORDCONFIRM'] = "old_password('$frm[PASSWORDCONFIRM]')";
	}
	
	if ($gdl_form->verification($frm)) {
		if (! ($gdl_account->cek_password ($frm['PASSWORD'], $frm['PASSWORDCONFIRM']))) {				
			$main = "<p>"._UPDATE_ERROR_PASSWORD."</p>\n";
			$main .= form_register ($action);
			$main = gdl_content_box($main,_USEREDIT." (".$_GET[a].")");
			$gdl_content->set_main($main); 
			$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=member\">"._MEMBER."</a>";
 		 } else {

		    	$prosesupadate = $gdl_account->update($frm,$_GET['a']);					
				if ($prosesupadate) {
				$main = "<p>"._UPDATESUCCESS."</p>\n";
				$main .= "<p>".search_member_form ()."</p>\n";
				$main .= display_member($y);
				$main = gdl_content_box($main,_USEREDIT." (".$_GET[a].")");
				$gdl_content->set_main($main); 
				$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=member\">"._MEMBER."</a>";
				} else { echo "gagal";}
		} 
	} else {
		$main .= form_register ($action);
		$main = gdl_content_box($main,_USEREDIT." (".$_GET[a].")");
		$gdl_content->set_main($main); 
		$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=member\">"._MEMBER."</a>";
	}
} 





?>