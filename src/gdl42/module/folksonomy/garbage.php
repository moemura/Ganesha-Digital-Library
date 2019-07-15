<?php
if (preg_match("/garbage.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

global $gdl_folksonomy;

require_once("./module/folksonomy/function.php");
$del	= $_GET['del'];
if(isset($del) && ($del == "confirm")){
	$gdl_folksonomy->delete_tokenStopword();
}

if(isset($_POST['stopword'])){
	$ret = $gdl_folksonomy->addNewStopword($_POST['stopword']);
	if($ret == 0){
		$ret_ins = _FAILED_INSERT_STOPWORD;
	}else if($ret == -1){
		$ret_ins = _DUPLICATE_STOPWORD;
	}else $ret_ins = _SUCCESS_INSERT_STOPWORD;
}

$main	= "<p>".add_stopword_form ()."</p>";
$main	.= $ret_ins;
$main 	.= display_stopword();

$main 	= gdl_content_box($main,_STOPWORDMANAGEMENT);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=publisher\">"._PUBLISHER."</a>";

?>