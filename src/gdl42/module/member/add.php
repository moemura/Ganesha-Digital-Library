<?php

/***************************************************************************
                         /module/member/add.php
                             -------------------
    copyright            : (C) 2007 Beni Rio Hermanto, KMRG ITB
    email                : benirio@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/add.php/i",$_SERVER['PHP_SELF'])) die();
require_once "./module/register/function.php";
require_once("./module/member/function.php");
$page = $_GET['page'];
$url = substr(strrchr($_SERVER['REQUEST_URI'], "?"),1);
$frm=$_POST["frm"];

if ($url<>""){
	$url=htmlentities($url)."&amp;";
}
$action = $_SERVER['PHP_SELF']."?".$url."&amp;page=add";
//$action = "./gdl.php?mod=register&amp;page=reg";

if (!isset($page) || empty ($page) || ($page!== "add")){
	$main .= form_register($action);
	$main = gdl_content_box($main,_REGISTRATION);
	$gdl_content->set_main($main); 
	$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=register\">"._ADDMEMBER."</a>";
} else {
	if ($gdl_form->verification($frm) && $frm) {
		if ($gdl_account->cek_password($frm['PASSWORD'],$frm['PASSWORDCONFIRM']) && $gdl_account->cek_existing_mail($frm['EMAIL']) && $gdl_account->cek_mail($frm['EMAIL']) && ($gdl_captcha->check_captcha($frm["CAPTCHA_PKEY"],$frm["CAPTCHA_TEXT"]))) {
				$gdl_account->register($frm['EMAIL'],$frm['PASSWORD'],$frm['FULLNAME'],$frm['ADDRESS'],$frm['CITY'],$frm['COUNTRY'],$frm['INSTITUTION'],$frm['JOB']);
				$main .="<b>"._ADDUSERSUCCESS."</b>";
				$main .= "<p>".search_member_form ()."</p>\n";
				$main .=display_member($y);
			}
		else {
			if (! ($gdl_account->cek_mail($frm['EMAIL']))) 
				$regerror = _REGISTRATION_ERROR_EMAIL;
			if (! ($gdl_account->cek_password ($frm['PASSWORD'], $frm['PASSWORDCONFIRM'])))
				$regerror .= _REGISTRATION_ERROR_PASSWORD;	
			if (! ($gdl_account->cek_existing_mail($frm['EMAIL']))) 
				$regerror .= _REGISTRATION_ERROR_EMAIL_EXIST;			
			if (!($gdl_captcha->check_captcha($frm["CAPTCHA_PKEY"],$frm["CAPTCHA_TEXT"])))
				$regerror .= _REGISTRATION_ERROR_VERIFICATION;
			$main .= $regerror;
			$main .= form_register($action);
			$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=register\">"._ADDMEMBER."</a>";
		}
	}
	
	$main = gdl_content_box($main,_REGISTRATION);
	$gdl_content->set_main($main); 
	$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=register\">"._ADDMEMBER."</a>";
}

?>