<?php

if (preg_match("/dc_emall.php/i",$_SERVER['PHP_SELF'])) {
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
			"name"=>"frm[CREATOR]",
			"value"=>isset($frm['CREATOR']) ? "$frm[CREATOR]" : ''));
$gdl_form->add_field(array(
			"type"=>"hidden",
			"name"=>"frm[IDENTIFIER_HIERARCHY]",
			"value"=>isset($frm['IDENTIFIER_HIERARCHY']) ? "$frm[IDENTIFIER_HIERARCHY]" : ''));
$gdl_form->add_field(array(
			"type"=>"hidden",
			"name"=>"frm[TYPE_SCHEMA]",
			"value"=>"dc_emall"));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_TYPE));
$gdl_form->add_field(array(
			"type"=>"select",
			"name"=>"frm[TYPE]",
			"value"=>isset($frm['TYPE']) ? "$frm[TYPE]" : '',
			"required"=>true,
			"option"=>array("emall"=>_EMALLCOMODITY),
			"text"=>_TYPEOFTHEDOCUMENT));
$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_TITLE));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[TITLE]",
			"required"=>true,
			"value"=>isset($frm['TITLE']) ? "$frm[TITLE]" : '',
			"text"=>_COMODITYNAME,
			"size"=>35));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[DESCRIPTION_MODEL]",
			"required"=>false,
			"value"=>isset($frm['DESCRIPTION_MODEL']) ? "$frm[DESCRIPTION_MODEL]" : '',
			"text"=>"Model",
			"size"=>35));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[DESCRIPTION_DIMENSION]",
			"value"=>isset($frm['DESCRIPTION_DIMENSION']) ? "$frm[DESCRIPTION_DIMENSION]" : '',
			"text"=>_SIZE,
			"size"=>35));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[DESCRIPTION_UNIT]",
			"value"=>isset($frm['DESCRIPTION_UNIT']) ? "$frm[DESCRIPTION_UNIT]" : '',
			"text"=>"Unit",
			"size"=>35));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[DESCRIPTION_PRICE]",
			"value"=>isset($frm['DESCRIPTION_PRICE']) ? "$frm[DESCRIPTION_PRICE]" : '',
			"text"=>_PRICEPERUNIT,
			"size"=>35));
$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[DESCRIPTION_STOCK]",
			"value"=>isset($frm['DESCRIPTION_STOCK']) ? "$frm[DESCRIPTION_STOCK]" : '',
			"text"=>_STOCK,
			"size"=>35));
$gdl_form->add_field(array(
			"type"=>"textarea",
			"name"=>"frm[DESCRIPTION]",
			"value"=>isset($frm['DESCRIPTION']) ? "$frm[DESCRIPTION]" : '',
			"text"=>_DESCRIPTION,
			"cols"=>33,
			"rows"=>4));
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