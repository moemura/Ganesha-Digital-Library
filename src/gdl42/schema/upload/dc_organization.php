<?php

if (preg_match("/dc_organization.php/i",$_SERVER['PHP_SELF'])) {
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
			"value"=>"dc_organization"));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_TYPE));
$gdl_form->add_field(array(
			"type"=>"select",
			"name"=>"frm[TYPE]",
			"value"=>isset($frm['TYPE']) ? "$frm[TYPE]" : '',
			"required"=>true,
			"option"=>array("org"=>_ORGDOCUMENT),
			"text"=>_TYPEOFTHEDOCUMENT));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_ORGANIZATION));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[ORGANIZATION_NAME]",
			"required"=>true,
			"value"=>isset($frm['ORGANIZATION_NAME']) ? "$frm[ORGANIZATION_NAME]" : '',
			"text"=>_ORGANIZATIONNAME,
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[ORGANIZATION_EMAIL]",
			"required"=>false,
			"value"=>isset($frm['ORGANIZATION_EMAIL']) ? "$frm[ORGANIZATION_EMAIL]" : '',
			"text"=>"E-mail",
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[ORGANIZATION_URL]",
			"value"=>isset($frm['ORGANIZATION_URL']) ? "$frm[ORGANIZATION_URL]" : '',
			"text"=>"Website",
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"name"=>"frm[ORGANIZATION_ADDRESS]",
			"value"=>isset($frm['ORGANIZATION_ADDRESS']) ? "$frm[ORGANIZATION_ADDRESS]" : '',
			"text"=>_ADDRESS,
			"rows"=>5,
			"cols"=>34));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[ORGANIZATION_PHONE]",
			"value"=>isset($frm['ORGANIZATION_PHONE']) ? "$frm[ORGANIZATION_PHONE]" : '',
			"text"=>_PHONE,
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[ORGANIZATION_FAX]",
			"value"=>isset($frm['ORGANIZATION_FAX']) ? "$frm[ORGANIZATION_FAX]" : '',
			"text"=>_FAX,
			"size"=>45));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_DESCRIPTION));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"name"=>"frm[DESCRIPTION_EXPERTISE]",
			"text"=>_EXPERTISE1,
			"required"=>true,
			"column"=>false,
			"value"=>isset($frm['DESCRIPTION_EXPERTISE']) ? "$frm[DESCRIPTION_EXPERTISE]" : '',
			"rows"=>20,
			"cols"=>59));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"name"=>"frm[DESCRIPTION_EXPERIENCE]",
			"text"=>_EXPERIENCE,
			"column"=>false,
			"value"=>isset($frm['DESCRIPTION_EXPERIENCE']) ? "$frm[DESCRIPTION_EXPERIENCE]" : '',
			"rows"=>20,
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