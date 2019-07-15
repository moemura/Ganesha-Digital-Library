<?php

if (preg_match("/dc_document.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

include ("./config/type.php");

$gdl_form->set_name("metadata");
$gdl_form->action="./gdl.php?mod=upload&amp;op=step3";
$gdl_form->add_field(array(
			"type"=>"hidden",
			"name"=>"frm[IDENTIFIER]",
			"value"=>"$frm[IDENTIFIER]"));
$gdl_form->add_field(array(
			"type"=>"hidden",
			"name"=>"frm[IDENTIFIER_HIERARCHY]",
			"value"=>"$frm[IDENTIFIER_HIERARCHY]"));
$gdl_form->add_field(array(
			"type"=>"hidden",
			"name"=>"frm[TYPE_SCHEMA]",
			"value"=>"dc_article"));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_TYPE));
$gdl_form->add_field(array(
			"type"=>"select",
			"name"=>"frm[TYPE]",
			"value"=>"$frm[TYPE]",
			"required"=>true,
			"option"=>$gdl_type,
			"text"=>_TYPEOFTHEDOCUMENT));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_TITLE));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[TITLE]",
			"required"=>true,
			"column"=>false,
			"value"=>"$frm[TITLE]",
			"text"=>_INORIGINALLANGUAGE,
			"size"=>75));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[TITLE_SERIES]",
			"value"=>"$frm[TITLE_SERIES]",
			"text"=>_SERIES,
			"column"=>false,
			"size"=>75));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_CREATOR));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[CREATOR]",
			"text"=>_CREATORNAME,
			"required"=>true,
			"value"=>"$frm[CREATOR]",
			"size"=>45));
$org_node=$gdl_folder->check_folder("Organization",0);
if (!ereg("err",$org_node)) {
	$dbres=$gdl_db->select("folder","name","parent=".$org_node);
	while ($row=mysql_fetch_array($dbres)) {
		$orgname[$row["name"]]=$row["name"];
	}
}
			
$gdl_form->add_field(array(
			"type"=>"select",
			"name"=>"frm[CREATOR_ORGNAME]",
			"text"=>_CREATORORGNAME,
			"value"=>"$frm[CREATOR_ORGNAME]",
			"option"=>$orgname,
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[CREATOR_EMAIL]",
			"text"=>_CREATOREMAIL,
			"value"=>"$frm[CREATOR_EMAIL]",
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_CLASIFICATION));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[SUBJECT_KEYWORDS]",
			"required"=>true,
			"text"=>_KEYWORDS,
			"value"=>"$frm[SUBJECT_KEYWORDS]",
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_DESCRIPTION));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"name"=>"frm[DESCRIPTION]",
			"required"=>true,
			"column"=>false,
			"text"=>_INORIGINALLANGUAGE,
			"value"=>"$frm[DESCRIPTION]",
			"rows"=>20,
			"cols"=>59));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_CONTRIBUTOR));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"column"=>false,
			"name"=>"frm[CONTRIBUTOR]",
			"value"=>"$frm[CONTRIBUTOR]",
			"text"=>_CONTRIBUTORDESC,
			"rows"=>5,
			"cols"=>59));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_DATE));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[DATE]",
			"value"=>"$frm[DATE]",
			"column"=>false,
			"text"=>_CREATEDDATE,
			"size"=>10));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_SOURCE));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[SOURCE_URL]",
			"value"=>"$frm[SOURCE_URL]",
			"column"=>false,
			"text"=>_URL,
			"size"=>75));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"name"=>"frm[SOURCE]",
			"value"=>"$frm[SOURCE]",
			"column"=>false,
			"text"=>_SOURCEFROM,
			"rows"=>5,
			"cols"=>59));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_LANGUAGE));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[LANGUAGE]",
			"value"=>"$frm[LANGUAGE]",
			"text"=>_LANGUAGE,
			"required"=>true,
			"size"=>30));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_RELATEDFILES));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[RELATION_COUNT]",
			"value"=>"$frm[RELATION_COUNT]",
			"text"=>_NUMBEROFFILE,
			"required"=>true,
			"size"=>10));
$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>false,
			"value"=>_SUBMIT));
$gdl_form->add_button(array(
			"type"=>"reset",
			"name"=>"reset",
			"value"=>_RESET));
$content = $gdl_form->generate("30%","");
?>