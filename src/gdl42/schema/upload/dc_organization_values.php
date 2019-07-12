<?php

if (eregi("dc_organization_values.php",$_SERVER['PHP_SELF'])) die();

include ("./config/publisher.php");

if (!isset($frm['LANGUAGE'])) {
	if ($gdl_content->language=="indonesian"){
		$frm['LANGUAGE'] = "Bahasa Indonesia";
	}else{
		$frm['LANGUAGE'] = "English";
	}
}
if (!isset($frm['RELATION_COUNT'])) $frm['RELATION_COUNT']=0;
if (!isset($frm['DATE'])) $frm['DATE']=date("Y-m-d");
$frm['IDENTIFIER_HIERARCHY']=$gdl_folder->get_hierarchy($_SESSION['gdl_node']);
?>