<?

/***************************************************************************
                         /module/explorer/delete.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, KMRG ITB
    email                : hayun@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (eregi("delete.php",$_SERVER['PHP_SELF'])) die();

$parent = $_GET['p'];
$node = $_GET['node'];
$id = $_GET['id'];
$del = $_GET['del'];

if (isset($del) and $del=="confirm"){
	$style = "span.title {\n"
		."width: 110px;\n"
		."float: left;\n"
		."}\n";
	$gdl_content->set_style( $style);
}

// delete folder
if (isset($node)){

	if (isset($del) and $del=="confirm"){
		// confirmation
		$property = $gdl_folder->get_property($node);
		$subfolder = $gdl_folder->folder_count($node);
		$main = "<p class=\"box\"><b>"._CONFIRMATION."</b></p>\n";
		$main .= "<p><span class=\"title\">ID</span>: $node<br/>\n";
		$main .= "<span class=\"title\">"._NAME."</span>: $property[name]<br/>\n";
		$main .= "<span class=\"title\">"._PARENT."</span>: ".$gdl_folder->get_path_name($property['parent'])."<br/>\n";
		$main .= "<span class=\"title\">"._CHILD."</span>: $subfolder<br/>\n";
		$main .= "<span class=\"title\">"._METADATA."</span>: ".$gdl_folder->content_count($node)."</p>\n";
		if ($subfolder==0){
			$main .= "<p>"._DELETEFOLDERCONFIRMATION." ? <a href=\"./gdl.php?mod=explorer&amp;op=delete&amp;p=$parent&amp;node=$node\">"._YESDELETE."</p>\n";
		}else{
			$main .= "<p>"._CANNOTDELETEFOLDERCONFIRMATION." ? <a href=\"./gdl.php?mod=explorer&amp;node=$parent\">"._BACK."</p>\n";
		}
		$main = gdl_content_box($main,_DELETEFOLDER);
	}else{
		// delete folder
		$gdl_folder->delete($node);
		// write log
		// display explorer
		require_once("./module/explorer/function.php");
		display_explorer($parent);
	}	
}

// delete metadata
if (isset($id)){
	if (isset($del) and $del=="confirm"){
		// confirmation
		$property = $gdl_metadata->get_property($id);
		$main = "<p class=\"box\"><b>"._CONFIRMATION."</b></p>\n";
		$main .= "<p><span class=\"title\">ID</span>: $id<br/>\n";
		$main .= "<span class=\"title\">"._TITLE."</span>: $property[title]<br/>\n";
		$main .= "<span class=\"title\">"._FOLDER."</span>: ".$gdl_folder->get_path_name($property['folder'])."<br/>\n";
		$main .= "<span class=\"title\">"._OWNER."</span>: $property[owner]<br/>\n";
		$main .= "<p>"._DELETEMETADATACONFIRMATION." ? <a href=\"./gdl.php?mod=explorer&amp;op=delete&amp;id=$id\">"._YESDELETE."</p>\n";
		$main = gdl_content_box($main,_DELETEMETADATA);
	}else{
		// delete folder
		$gdl_metadata->delete($id);
		
		// display explorer
		require_once("./module/explorer/function.php");
		display_explorer($_SESSION['gdl_node']);
	}
}

$gdl_content->set_main($main);
$gdl_folder->set_path($_SESSION['gdl_node']);

?>