<?php
if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function form_register($action){	
	global $gdl_form, $gdl_content, $frm,$gdl_mod,$gdl_op,$gdl_captcha;
	include "./config/usertype.php";

if ((preg_match("/member/",$gdl_mod) && preg_match("/edit/",$gdl_op)) || (preg_match("/member/",$gdl_mod) && preg_match("/index/",$gdl_op)))
	$required=false;
else
	$required=true;
$gdl_form->set_name("register");
$gdl_form->action="$action";

$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_USER_ID));

if ($required) {
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[EMAIL]",			
				"value"=>"$frm[EMAIL]",
				"text"=>_USER_MAIL,
				"required"=>true,
				"size"=>45));
}

$gdl_form->add_field(array(
			"type"=>"password",
			"name"=>"frm[PASSWORD]",
			"text"=>_USER_PASSWD,
			"required"=>$required,
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"password",
			"name"=>"frm[PASSWORDCONFIRM]",
			"text"=>_USER_PASSWD_CONFIRM,
			"required"=>$required,
			"size"=>45));
			
			if (isset($_GET['a']) && $_GET['a']) {
				global $grouplevel, $userstatus;
				$gdl_form->add_field(array(
							"type"=>"text",
							"name"=>"frm[VALIDATION]",
							"value"=>"$frm[VALIDATION]",
							"text"=>_VALIDATION,
							"required"=>true,
							"size"=>45));
				$gdl_form->add_field(array(
							"type"=>"title",
							"text"=>_USER_SECURITY));
				$gdl_form->add_field(array(
							"type"=>"select",
							"name"=>"frm[GROUPLEVEL]",
							"value"=>"$frm[GROUPLEVEL]",
							"required"=>true,
							"option"=>$grouplevel,
							"text"=>_TYPEOFUSER));
				$gdl_form->add_field(array(
							"type"=>"radio",
							"name"=>"frm[ACTIVE]",
							"value"=>"$frm[ACTIVE]",
							"checked"=>$userstatus,
							"text"=>_STATUS,
							"required"=>true,
							"size"=>45));		
			}
			
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_USER_GENERAL));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[FULLNAME]",
			"value"=>"$frm[FULLNAME]",
			"text"=>_USER_FULLNAME,
			"required"=>true,
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[ADDRESS]",
			"value"=>"$frm[ADDRESS]",
			"text"=>_USER_ADDRESS,
			"required"=>true,
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[CITY]",
			"value"=>"$frm[CITY]",
			"text"=>_USER_CITY,
			"required"=>true,
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[COUNTRY]",
			"value"=>"$frm[COUNTRY]",
			"text"=>_USER_COUNTRY,
			"required"=>true,
			"size"=>45));

$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[INSTITUTION]",
			"value"=>"$frm[INSTITUTION]",
			"text"=>_USER_INSTITUTION,
			"required"=>true,
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"select",
			"name"=>"frm[JOB]",
			"value"=>"$frm[JOB]",
			"option"=>$user_type,
			"text"=>_USER_JOB));
			
if ($required){
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_VERIFICATION));
			
	$gdl_form->add_field(array(
			"type"=>"hidden",
			"name"=>"frm[CAPTCHA_PKEY]",
			"value"=>$gdl_captcha->get_public_key()));

	$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[CAPTCHA_TEXT]",
			"text"=>$gdl_captcha->display_captcha(true),
			"column"=>false));
}
$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>false,
			"value"=>_SUBMIT));

	if (isset($_GET['a']) && $_GET['a']) {
		$gdl_form->add_button(array(
				"type"=>"button",
				"name"=>"button",
				"onclick"=>"self.history.back();",
				"value"=>_RESET));
	} else {
		$gdl_form->add_button(array(
				"type"=>"reset",
				"name"=>"reset",
				"value"=>_RESET));
	}

$content = $gdl_form->generate("30%");
return $content; 
}

?>
