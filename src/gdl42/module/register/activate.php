<?php
if (preg_match("/activate.php/i",$_SERVER['PHP_SELF'])) die();


function form_activate(){	
	global $gdl_form, $gdl_content;

$gdl_form->set_name("activate");
$gdl_form->action="./gdl.php?mod=register&amp;op=activate&amp;page=act";

$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_ACTIVATE_TITLE));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"account",
			"text"=>_USER_ACCOUNT,
			"value"=>isset($_GET['account']) ? $_GET['account'] : null,
			"required"=>true,
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"vn",
			"text"=>_USER_CODE,
			"value"=>isset($_GET['vn']) ? $_GET['vn'] : null,
			"required"=>true,
			"size"=>45));
$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>false,
			"value"=>_ACTIVATE));
$gdl_form->add_button(array(
			"type"=>"reset",
			"name"=>"reset",
			"value"=>_RESET));

$content = $gdl_form->generate("30%");
return $content; 
}

$page = isset($_GET['page']) ? $_GET['page'] : null;
if (!isset($page)) {
	$main = "<p>"._ACTIVATENOTE."</p>\n";
	$main .= form_activate();
	$main = gdl_content_box($main,_ACTIVATE);
	$gdl_content->set_main($main); 
	$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=register\">"._ACTIVATE."</a>";
} else {
	$val['account'] = $_POST['account'];
	$val['vn'] = $_POST['vn'];
	
	if ($gdl_form->verification($val)) {

		if ($gdl_account->activate($val['account'],$val['vn'])){
			$main = "<p>"._ACTIVATESUCCESS."</p>\n";
			$main = gdl_content_box($main,_ACTIVATE);
			$gdl_content->set_main($main); 
			$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=register\">"._ACTIVATE."</a>";	
		} else {	
			$main = "<p>"._ACTIVATEFAIL."</p>\n";
			$main .= form_activate();
			$main = gdl_content_box($main,_ACTIVATE);
			$gdl_content->set_main($main); 
			$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=register\">"._ACTIVATE."</a>";
		}
	
	} else{
		$main = "<p>"._ACTIVATENOTE."</p>\n";
		$main .= form_activate();
		$main = gdl_content_box($main,_ACTIVATE);
		$gdl_content->set_main($main); 
		$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=register\">"._ACTIVATE."</a>";
	}

}
?>