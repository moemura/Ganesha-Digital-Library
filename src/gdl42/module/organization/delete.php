<?php
/***************************************************************************
                         /module/organization/delete.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/delete.php/i",$_SERVER['PHP_SELF'])) die();

$del = isset($_GET['del']) ? $_GET['del'] : null;
$id  = isset($_GET["id"]) ? $_GET["id"] : null;
require_once("./module/organization/function.php");
$main = '';
if (isset($id)){
	if (isset($id) and $del=="confirm"){		
		$main = "<p class=\"box\"><b>"._CONFIRMATION."</b></p>\n";
		$org_name = $gdl_folder->get_property($id);
		$org_name = $org_name["name"];
		$main .= _ORGANIZATIONNAME." : ".$org_name;
		$main .= "<p>"._DELETEORGANIZATIONCONFIRMATION."  <a href='./gdl.php?mod=organization&amp;op=delete&amp;id=".$id."'>"._YESSURE."</a></p>\n";

	}else{
		// delete folder
		if ($gdl_folder->delete($id))
			$main .= _DELETEORGANIZATIONSUCCESS;
		else
			$main .= _DELETEORGANIZATIONFAILED;
			
		$main .= "<p>".list_of_organization()."</p>";
	}
}
$main = gdl_content_box($main,_ORGANIZATION);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=organization\">"._ORGANIZATION."</a>";
?>