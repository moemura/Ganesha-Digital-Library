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
			"value"=>isset($frm['IDENTIFIER']) ? "$frm[IDENTIFIER]" : ''));
$gdl_form->add_field(array(
			"type"=>"hidden",
			"name"=>"frm[IDENTIFIER_HIERARCHY]",
			"value"=>isset($frm['IDENTIFIER_HIERARCHY']) ? "$frm[IDENTIFIER_HIERARCHY]" : ''));
$gdl_form->add_field(array(
			"type"=>"hidden",
			"name"=>"frm[TYPE_SCHEMA]",
			"value"=>"dc_document"));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_TYPE));
$gdl_form->add_field(array(
			"type"=>"select",
			"name"=>"frm[TYPE]",
			"value"=>isset($frm['TYPE']) ? "$frm[TYPE]" : '',
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
			"value"=>isset($frm['TITLE']) ? "$frm[TITLE]" : '',
			"text"=>_INORIGINALLANGUAGE,
			"size"=>75));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[TITLE_ALTERNATIVE]",
			"required"=>false,
			"column"=>false,
			"value"=>isset($frm['TITLE_ALTERNATIVE']) ? "$frm[TITLE_ALTERNATIVE]" : '',
			"text"=>_INENGLISH,
			"size"=>75));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[TITLE_SERIES]",
			"value"=>isset($frm['TITLE_SERIES']) ? "$frm[TITLE_SERIES]" : '',
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
			"value"=>isset($frm['CREATOR']) ? "$frm[CREATOR]" : '',
			"size"=>45));

$org_node=$gdl_folder->check_folder("Organization",0);
if (!preg_match("/err/",$org_node)) {
	$dbres=$gdl_db->select("folder","name","parent=".$org_node);
	while ($row=mysqli_fetch_array($dbres)) {
		$orgname[$row["name"]]=$row["name"];
	}
}
			
$gdl_form->add_field(array(
			"type"=>"select",
			"name"=>"frm[CREATOR_ORGNAME]",
			"text"=>_CREATORORGNAME,
			"value"=>isset($frm['CREATOR_ORGNAME']) ? "$frm[CREATOR_ORGNAME]" : '',
			"option"=>$orgname,
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[CREATOR_EMAIL]",
			"text"=>_CREATOREMAIL,
			"value"=>isset($frm['CREATOR_EMAIL']) ? "$frm[CREATOR_EMAIL]" : '',
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_CLASIFICATION));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[SUBJECT_KEYWORDS]",
			"required"=>true,
			"text"=>_KEYWORDS,
			"value"=>isset($frm['SUBJECT_KEYWORDS']) ? "$frm[SUBJECT_KEYWORDS]" : '',
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[SUBJECT]",
			"required"=>false,
			"text"=>_SUBJECT,
			"value"=>isset($frm['SUBJECT']) ? "$frm[SUBJECT]" : '',
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[SUBJECT_HEADING]",
			"required"=>false,
			"text"=>_SUBJECT_HEADING,
			"value"=>isset($frm['SUBJECT_HEADING']) ? "$frm[SUBJECT_HEADING]" : '',
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[SUBJECT_DDC]",
			"required"=>false,
			"text"=>_SUBJECT_DDC,
			"value"=>isset($frm['SUBJECT_DDC']) ? "$frm[SUBJECT_DDC]" : '',
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
			"value"=>isset($frm['DESCRIPTION']) ? "$frm[DESCRIPTION]" : '',
			"rows"=>20,
			"cols"=>59));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"name"=>"frm[DESCRIPTION_ALTERNATIVE]",
			"required"=>false,
			"column"=>false,
			"text"=>_INENGLISH,
			"value"=>isset($frm['DESCRIPTION_ALTERNATIVE']) ? "$frm[DESCRIPTION_ALTERNATIVE]" : '',
			"rows"=>20,
			"cols"=>59));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"name"=>"frm[DESCRIPTION_NOTE]",
			"required"=>false,
			"column"=>false,
			"text"=>_DESCRIPTION_NOTE,
			"value"=>isset($frm['DESCRIPTION_NOTE']) ? "$frm[DESCRIPTION_NOTE]" : '',
			"rows"=>5,
			"cols"=>59));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_RELATION));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"column"=>false,
			"name"=>"frm[RELATION]",
			"value"=>isset($frm['RELATION']) ? "$frm[RELATION]" : '',
			"text"=>_RELATIONTEXT,
			"rows"=>5,
			"cols"=>59));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_COVERAGE));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"column"=>false,
			"name"=>"frm[COVERAGE]",
			"value"=>isset($frm['COVERAGE']) ? "$frm[COVERAGE]" : '',
			"text"=>_COVERAGETEXT,
			"rows"=>5,
			"cols"=>59));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_CONTRIBUTOR));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"column"=>false,
			"name"=>"frm[CONTRIBUTOR]",
			"value"=>isset($frm['CONTRIBUTOR']) ? "$frm[CONTRIBUTOR]" : '',
			"text"=>_CONTRIBUTORDESC,
			"rows"=>5,
			"cols"=>59));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_DATE));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[DATE]",
			"value"=>isset($frm['DATE']) ? "$frm[DATE]" : '',
			"column"=>false,
			"text"=>_CREATEDDATE,
			"size"=>10));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_SOURCE));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[SOURCE_URL]",
			"value"=>isset($frm['SOURCE_URL']) ? "$frm[SOURCE_URL]" : '',
			"column"=>false,
			"text"=>_URL,
			"size"=>75));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"name"=>"frm[SOURCE]",
			"value"=>isset($frm['SOURCE']) ? "$frm[SOURCE]" : '',
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
			"value"=>isset($frm['LANGUAGE']) ? "$frm[LANGUAGE]" : '',
			"column"=>false,
			"text"=>_LANGUAGE,
			"required"=>true,
			"size"=>30));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_RIGHTS));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"column"=>false,
			"name"=>"frm[RIGHTS]",
			"value"=>isset($frm['RIGHTS']) ? "$frm[RIGHTS]" : '',
			"text"=>_RIGHTSTEXT,
			"rows"=>5,
			"cols"=>59));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_RELATEDFILES));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[RELATION_COUNT]",
			"value"=>isset($frm['RELATION_COUNT']) ? "$frm[RELATION_COUNT]" : '',
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
$content = $gdl_form->generate("30%");
?>