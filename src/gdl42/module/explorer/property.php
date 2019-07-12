<?

/***************************************************************************
                         /module/explorer/property.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, KMRG ITB
    email                : hayun@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (eregi("property.php",$_SERVER['PHP_SELF'])) die();

$parent = $_GET['p'];
$node = $_GET['node'];
$id = $_GET['id'];
$action = $_POST['action'];

function folder_form($property=""){
	global $node, $gdl_session,$gdl_folder,$gdl_form,$parent;
	
	if ($property=="") $property = $gdl_folder->get_property($node);
	$folder = $gdl_folder->list_all($property['parent']);

	$gdl_form->set_name("folder");
	$gdl_form->action="./gdl.php?mod=explorer&amp;op=property&amp;node=$node&amp;p=$parent";
	$gdl_form->add_field(array(
				"type"=>"hidden",
				"name"=>"action",
				"value"=>"upload"));
	$gdl_form->add_field(array(
				"type"=>"title",
				"text"=>_EDITFOLDER));	
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

	$main = $gdl_form->generate("100");
	return $main;
}

function metadata_form($property=""){
	global $id,$node,$gdl_form,$gdl_metadata,$gdl_folder,$gdl_session,$gdl_sys;
	
	if ($property=="") $property = $gdl_metadata->get_property($id);
	$folder = $gdl_folder->list_all($property['folder']);
	
	$gdl_form->set_name("metadata");
	$gdl_form->action="./gdl.php?mod=explorer&amp;op=property&amp;id=$id";
	$gdl_form->add_field(array(
				"type"=>"hidden",
				"name"=>"action",
				"value"=>"upload"));
	$gdl_form->add_field(array(
				"type"=>"title",
				"text"=>$property['title']));
	$gdl_form->add_field(array(
				"type"=>"select",
				"name"=>"folder",
				"required"=>true,
				"option"=>$folder,
				"text"=>_FOLDER));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"owner",
				"value"=>$property['owner'],
				"required"=>true,
				"text"=>_OWNER,
				"size"=>20));
	$gdl_form->add_button(array(
				"type"=>"submit",
				"name"=>"submit",
				"value"=>_SUBMIT));
	$main = $gdl_form->generate("100");
	return $main;
}

// folder
if (isset($node)){

	if (isset($action) and $action=="upload"){
		if ($gdl_form->upload=="folder"){
			$property['node'] = $node;
			$property['name'] = $_POST['name'];
			$property['parent'] = $_POST['parent'];
			if ($gdl_form->verification($property)){
				// edit property folder
				$gdl_folder->edit_property($property);
				
				// display explorer
				require_once("./module/explorer/function.php");
				display_explorer($property['parent']);
				// simpan di session
				$_SESSION['gdl_node'] = $property['parent'];
			}else{
				// entry form not complete
				$main = folder_form($property);
				$main = gdl_content_box($main,_PROPERTYFOLDER);
			}
			
		}else{
			// display explorer
			require_once("./module/explorer/function.php");
			display_explorer($parent);
		}
		
	}else{
		
		// generate form
		$main = folder_form();
		$main = gdl_content_box($main,_PROPERTYFOLDER);
	}
}

// metadata
if (isset($id)){
	if (isset($action) and $action=="upload"){
		if ($gdl_form->upload=="metadata"){
			$property['id'] = $id;
			$property['folder'] = $_POST['folder'];
			$property['owner'] = $_POST['owner'];
			if ($gdl_form->verification($property)){
				// edit property folder
				$gdl_metadata->edit_property($property);
				
				// display explorer
				require_once("./module/explorer/function.php");
				display_explorer($property['folder']);
				$_SESSION['gdl_node'] = $property['folder'];
			}else{
				// entry form not complete
				$main = metadata_form($property);
				$main = gdl_content_box($main,_PROPERTYMETADATA);
			}
		}else{
			// display explorer
			require_once("./module/explorer/function.php");
			display_explorer($_SESSION['gdl_node']);
		}	
	}else{
		// generate form
		$main = metadata_form();
		$main = gdl_content_box($main,_PROPERTYMETADATA);
	}
}

$gdl_content->set_main($main);

?>