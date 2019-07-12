<?php

if (eregi("index.php",$_SERVER['PHP_SELF'])) {
    die();
}
$_SESSION['DINAMIC_TITLE'] = "Searching";
include_once ("./schema/lang/".$gdl_content->language.".php");
include_once ("./module/search/function.php");
include_once ("./class/search.php");
$search = new search();



if(isset($_GET['page'])){ 
	$schema = $_GET['s']; 
} else { 
	$schema = $_POST['s'];
}

if (!isset($schema)) 
	$schema = $_GET['schema'];

if (file_exists("./schema/$schema.xml") || $schema=="catalogs"){
	$gdl_content->set_main($search->generate_form($schema));
} else { 
	$gdl_content->set_main($search->generate_form("dc"));
}

$folks = $_GET['action'];
if (isset($_GET['page']) or isset($_POST['s'])) 
	$main = search_result($schema);
else if(isset($folks)){
	$main = search_result("dc","url");
}

$gdl_content->set_main($main);

// searching for all node
$_SESSION['gdl_node']=0;

?>