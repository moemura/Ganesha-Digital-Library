<?

/***************************************************************************
                         /module/browse/login.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, KMRG ITB
    email                : hayun@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/
if (eregi("login.php",$_SERVER['PHP_SELF'])) die();

function form_login(){
	global $gdl_form, $gdl_content;
	
	$gdl_form->set_name("login");
	$gdl_form->action="./gdl.php?mod=browse&amp;op=login&amp;page=in";
	$gdl_form->add_field(array(
				"type"=>"title",
				"text"=>"Login"));	
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"userid",
				"required"=>true,
				"text"=>_USERID,
				"size"=>20));
	$gdl_form->add_field(array(
				"type"=>"password",
				"name"=>"password",
				"required"=>true,
				"text"=>_PASSWORD,
				"size"=>20));
	$gdl_form->add_button(array(
				"type"=>"submit",
				"name"=>"submit",
				"value"=>_LOGIN));
	$gdl_form->add_button(array(
				"type"=>"reset",
				"name"=>"reset",
				"value"=>_CANCEL));
	$main = $gdl_form->generate("75px","300px");
	return $main;
}

$page = $_GET['page'];

if (!isset($page)) {
	$main = "<p>"._LOGINNOTE."</p>\n";
	$main .= form_login();
	$main = gdl_content_box($main,_USERLOGIN);
	$gdl_content->set_main($main); 
	$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=browse&amp;op=login\">"._LOGIN."</a>";
} elseif ($page=="in") {

	$val['userid'] = $_POST['userid'];
	$val['password'] = $_POST['password'];	

	if ($gdl_form->verification($val)) {
		if ($gdl_session->login($val['userid'],$val['password'])){
			header("Location: ./index.php");
		}else{
			if ($gdl_session->activate == 0) {
				$noactive = "<p>"._LOGINACTIVATE."</p>\n";
				$noactive .= form_login();
				$noactive = gdl_content_box($noactive,_LOGINFAIL);
				$gdl_content->set_main($noactive);						
			} else {
				$fail = "<p>"._LOGINFAILNOTE."</p>\n";
				$fail .= form_login();
				$fail = gdl_content_box($fail,_LOGINFAIL);
				$gdl_content->set_main($fail);
			}
		}	
	}else{
		$main = "<p>"._LOGINNOTE."</p>\n";
		$main .= form_login();
		$main = gdl_content_box($main,_USERLOGIN);
		$gdl_content->set_main($main);
	}
	$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=browse&amp;op=login\">"._LOGIN."</a>";
} elseif ($page=="out") {
	$gdl_session->logout();
	header("Location: ./index.php");
}

?>