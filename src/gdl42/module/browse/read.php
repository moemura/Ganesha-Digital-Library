<?php
/***************************************************************************
                         /module/browse/read.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, Arif Suprabowo, Lastiko Wibisono, KMRG ITB
    email                : hayun@kmrg.itb.ac.id, mymails_supra@yahoo.com, leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/
 
if (preg_match("/read.php/i",$_SERVER['PHP_SELF'])) die();

$id = $_GET['id'];
include ("./module/browse/function.php");

$frm = $gdl_metadata->read($id,1);

// display red for searching term
require_once ("./class/search.php");
$search = new search();
$query = isset($_GET['q']) ? $_GET['q'] : null;
if(isset($query)) $query = explode("+",$query);
if (is_array($query)){
	foreach ($query as $qkey => $qval) {
		foreach ($frm as $key => $val) {
			if(preg_match("/DC:/",$key))
			   {
				if ($key<>"DC:IDENTIFIER" && $key <> "DC:PREFIX" && $key <> "DC:PUBLISHER") $frm[$key] = $search->mark_term($val[0],$qval);
			}	
			else {
				if ($key<>"IDENTIFIER" && $key <> "PREFIX" && $key <> "PUBLISHER" && !preg_match("/URL/",$key)) {
					$frm[$key] = $search->mark_term($val[0],$qval);
				}
			}
		}
	}				
}else{

	if(is_array($frm))
		foreach ($frm as $key => $val) {
			if (!empty($val)) $frm[$key] = $search->mark_term($val,$query);
		}				
}

$title	= $gdl_metadata->get_value($frm,"TITLE");
$main = display_metadata($frm);
$main .= display_contact($frm);
$main = gdl_content_box($main,$title);
$gdl_content->set_main($main);
?>