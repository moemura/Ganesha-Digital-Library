<?php
if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) die();

$_SESSION['DINAMIC_TITLE'] = _REGISTRATION;
require_once "./module/register/function.php";
$page = isset($_GET['page']) ? $_GET['page'] : null;
$action = "./gdl.php?mod=register&amp;page=reg";
if (!isset($page) || empty ($page) || ($page!== "reg")){

	$main = "<p>"._REGISTRATIONNOTE."</p>\n";
	$main .= form_register($action);
	$main = gdl_content_box($main,_REGISTRATION);
	$gdl_content->set_main($main); 
	$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=register\">"._REGISTRATION."</a>";
} else {	
	$frm = isset($_POST['frm']) ? $_POST['frm'] : null;
	
	if ($gdl_form->verification($frm)) {
		if (! ($gdl_account->cek_mail($frm['EMAIL'])) || ! ($gdl_account->cek_password ($frm['PASSWORD'], $frm['PASSWORDCONFIRM'])) || ! ($gdl_account->cek_existing_mail($frm['EMAIL'])) || !($gdl_captcha->check_captcha($frm["CAPTCHA_PKEY"],$frm["CAPTCHA_TEXT"]))) {
			if (! ($gdl_account->cek_mail($frm['EMAIL']))) 
				$regerror = _REGISTRATION_ERROR_EMAIL;
			if (! ($gdl_account->cek_password ($frm['PASSWORD'], $frm['PASSWORDCONFIRM'])))
				$regerror .= _REGISTRATION_ERROR_PASSWORD;	
			if (! ($gdl_account->cek_existing_mail($frm['EMAIL']))) 
				$regerror .= _REGISTRATION_ERROR_EMAIL_EXIST;			
			if (!($gdl_captcha->check_captcha($frm["CAPTCHA_PKEY"],$frm["CAPTCHA_TEXT"])))
				$regerror .= _REGISTRATION_ERROR_VERIFICATION;
			
			$main = "<p>".$regerror."</p>\n";
			$main .= form_register($action);
			$main = gdl_content_box($main,_REGISTRATION);
			$gdl_content->set_main($main); 
			$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=register\">"._REGISTRATION."</a>";
		} else if ($gdl_account->cek_mail($frm['EMAIL']) && $gdl_account->cek_password ($frm['PASSWORD'], $frm['PASSWORDCONFIRM'])) {
 		    $gdl_account->register($frm['EMAIL'], $frm['PASSWORD'], $frm['FULLNAME'], $frm['ADDRESS'], $frm['CITY'], $frm['COUNTRY'], $frm['INSTITUTION'],$frm['JOB']);

			if ($gdl_account->sendmail_registration($frm))
				$regresult = _REGISTRATION_SUCCESS;
			else
				$regresult = _REGISTRATION_ADMIN;
					
				$main = "<p>".$regresult."</p>\n";
				$main = gdl_content_box($main,_REGISTRATION);
				$gdl_content->set_main($main); 
				$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=register\">"._REGISTRATION."</a>";
		} else {
			$main = "<p>"._REGISTRATION_FAIL."</p>\n";
			$main = gdl_content_box($main,_REGISTRATION);
			$gdl_content->set_main($main); 
			$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=register\">"._REGISTRATION."</a>";
		}	
	} else {
		$main = "<p>"._REGISTRATIONNOTE."</p>\n";
		$main .= form_register($action);
		$main = gdl_content_box($main,_REGISTRATION);
		$gdl_content->set_main($main); 
		$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=register\">"._REGISTRATION."</a>";
	}
} 
?>