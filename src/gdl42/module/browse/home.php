<?php

/***************************************************************************
                         /module/browse/home.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, Arif Suprabowo, KMRG ITB
    email                : hayun@kmrg.itb.ac.id, mymails_supra@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/
if (preg_match("/home.php/i",$_SERVER['PHP_SELF'])) die();

require_once ("./module/browse/function.php");
require_once("./config/type.php");

// welcome message
$welcome = "<p class=\"welcome\">"._WELCOMETOTHE." $gdl_publisher[publisher]</p>\n";
if ($gdl_session->user_id=="public"){
	$welcome = "<h2 class=\"welcome\">"._WELCOMETOTHE." $gdl_publisher[publisher]</h2>\n";
}else{
	$welcome = "<h2 class=\"welcome\">"._WELCOME.", ".$gdl_session->user_name." [ ".$gdl_session->group_name." ] </h2>\n";
}
$gdl_content->set_main($welcome);



// new articles
$state			= $_GET['state'];
$node_offline	= $_GET['node'];
$ext			= $_GET['ext'];
$parent			= $_GET['parent'];
$folks_offline	= $_GET['folks_offline'];

if($state == "offline"){
	$arr_node	= explode(",",$node_offline);
	$stop 		= false;
	if(is_array($arr_node)){
		for($i=0;($i<count($arr_node)) && !$stop;$i++){
			$stop = ereg("^[0-9]+$",$arr_node[$i])?false:true;
		}
		
		$node_offline = $stop?"":$arr_node;
	}else
		$node_offline = ereg("^[0-9]+$",$node_offline)?$node_offline:"";
}else
	$node_offline = "";
	
$metadata = $gdl_metadata->get_list($node_offline,"","0,5",false);
if (is_array($metadata)){
	$main .= "<ul class=\"filelist\">\n";
	while (list($key,$val) = each($metadata)){
		$type = $val['TYPE'];
		$file = "";
		if ($val['RELATION_COUNT'] > 0) $file = ", $val[RELATION_COUNT] "._FILES;
		$main .= "<li><b><a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=$key\">$val[TITLE]</a></b><br/>\n";
		$main .= "<span class=\"note\">".substr($val['DATE_MODIFIED'],0,10).", $gdl_type[$type] "._BY." $val[CREATOR]$file</span><br/>\n";
		$main .= "</li>\n";
	}
	$main .= "</ul>\n";
	$main = gdl_content_box($main,_NEWARTICLES);
	$gdl_content->set_main($main);
}


// display sub folder

$def_node = 0;

$is_offline	= false;

if(($state == "offline")){
	$is_offline	= true;
	
	if($ext == "single")
		$under_node = ereg("^[0-9]+$",$parent)?$parent:0;
	else if($ext == "multiply"){
		$arr_parent	= explode(",",$parent);
		$stop 		= false;
		if(is_array($arr_parent)){
			$arr_parent	= array_unique($arr_parent);
			for($i=0;($i<count($arr_parent)) && !$stop;$i++){
				$stop = ereg("^[0-9]+$",$arr_parent[$i])?false:true;
			}
			$under_node 	= $stop?0:$arr_parent;
		}else
			$under_node = ereg("^[0-9]+$",$parent)?$parent:0;
	}
	
}
	
$_SESSION['gdl_node']=$def_node;
$gdl_folder->set_path($def_node);
$gdl_content->set_main("<p class=\"dirpath\"><strong>Path</strong>: ".$gdl_content->path."</p>\n");
$gdl_folder->set_list($def_node,$is_offline,$under_node);

$close_tmp_folksonomy	= (($folks_offline == "off") && ($state == "offline"))?true:false;

if ($gdl_folks["folks_active_option"]) {
	if(!$close_tmp_folksonomy){
		$main = $gdl_folksonomy->show_box_folksonomy();
		$main = gdl_content_box($main,"");
		$gdl_content->set_main($main);
	}
}

// get last news

$metadata = $gdl_metadata->get_list($node_offline,"","5,7");
if (is_array($metadata)){
	while (list($key, $val) = each($metadata)) {
		$news[]= "<a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=$key\">$val[TITLE]</a>";
	}
	$last_news = gdl_relation_box($news,_LASTNEWS);
}
$gdl_content->set_relation ($last_news);

// links
$links = "<p class=\"title\">Links...</p>\n"
	."<ul>\n"
	."<li><a href=\"http://kmrg.itb.ac.id\">KMRG ITB</a></li>\n"
	."<li><a href=\"http://digilib.itb.ac.id\">ITB Library Home</a></li>\n"
	."<li><a href=\"http://www.itb.ac.id\">ITB Home</a></li>\n"
	."<li><a href=\"http://www.indonesiadln.org\">IndonesiaDLN</a></li>\n"
	."</ul>\n\n";
	
//$gdl_content->set_relation ($links);
$gdl_content->path="";

?>