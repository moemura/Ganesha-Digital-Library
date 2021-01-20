<?php

if (preg_match("/folder.php/i",$_SERVER['PHP_SELF'])) die();

function generate_form ($property=array()){
	global $gdl_folder,$gdl_session,$gdl_form,$gdl_sys;
	
	$node = isset($_SESSION['gdl_node']) ? $_SESSION['gdl_node'] : null;
	if (count($property)==0) {
		$property['mode']=isset($gdl_sys['default_mode']) ? $gdl_sys['default_mode'] : null;
		$property['owner']=$gdl_session->user_id;
	}
	
	// generate form
	$folder = $gdl_folder->list_all($node);

	$gdl_form->set_name("folder");
	$gdl_form->action="./gdl.php?mod=explorer&amp;op=folder";
	$gdl_form->add_field(array(
				"type"=>"hidden",
				"name"=>"action",
				"value"=>"upload"));
	$gdl_form->add_field(array(
				"type"=>"title",
				"text"=>_ADDNEWFOLDER));	
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"name",
				"value"=>isset($property['name']) ? "$property[name]" : '',
				"required"=>true,
				"text"=>_NAME,
				"size"=>30));
	$gdl_form->add_field(array(
				"type"=>"select",
				"name"=>"parent",
				"required"=>true,
				"option"=>$folder,
				"text"=>_PARENT));
	$gdl_form->add_button(array(
				"type"=>"submit",
				"name"=>"submit",
				"value"=>_SUBMIT));
	$gdl_form->add_button(array(
				"type"=>"reset",
				"name"=>"reset",
				"value"=>_RESET));
	$main = $gdl_form->generate("100px","400px");
	return $main;
}

$action = isset($_POST['action']) ? $_POST['action'] : null;

$main = '';
if (isset($action) and $action=="upload"){
	// create new folder
	require_once("./module/explorer/function.php");
	
	if ($gdl_form->upload=="folder"){
		$property['node'] = $node;
		$property['name'] = isset($_POST['name']) ? $_POST['name'] : null;
		$property['parent'] = isset($_POST['parent']) ? $_POST['parent'] : null;
		if ($gdl_form->verification($property)){
			$gdl_folder->add($property);
			// display explorer
			display_explorer($_SESSION['gdl_node']);
		}else{
		
		}
	}else{
		// display explorer
		display_explorer($_SESSION['gdl_node']);
	}
}else{

	// generate form
	$main = generate_form();
	$main = gdl_content_box($main,_ADDNEWFOLDER);
}

$gdl_content->set_main($main);
$gdl_folder->set_path($_SESSION['gdl_node']);
?>