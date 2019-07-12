<?

if (eregi("folder.php",$_SERVER['PHP_SELF'])) die();

function generate_form ($property=""){
	global $gdl_folder,$gdl_session,$gdl_form,$gdl_sys;
	
	$node = $_SESSION['gdl_node'];
	if ($property=="") {
		$property['mode']=$gdl_sys['default_mode'];
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
				"value"=>"$property[name]",
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

$action = $_POST['action'];

if (isset($action) and $action=="upload"){
	// create new folder
	require_once("./module/explorer/function.php");
	
	if ($gdl_form->upload=="folder"){
		$property['node'] = $node;
		$property['name'] = $_POST['name'];
		$property['parent'] = $_POST['parent'];
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