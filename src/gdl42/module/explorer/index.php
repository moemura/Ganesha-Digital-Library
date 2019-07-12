<?php

/***************************************************************************
                         /module/explorer/index.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, Lastiko Wibisono, KMRG ITB
    email                : hayun@kmrg.itb.ac.id, leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/


if (eregi("index.php",$_SERVER['PHP_SELF'])) die();

$_SESSION['DINAMIC_TITLE'] = "EXPLORER";
// get node to display
$node = $_GET['node'];
$n1   = $_GET['n1'];
$n2   = $_GET['n2'];
$submit = $_POST["submit"];

if (!isset($node)){
	$node = $_SESSION['gdl_node'];
	if (!isset($node)) $node=0;
} else {
	unset($_SESSION["node1"]);
	unset($_SESSION["node2"]);
	}

if (isset($n1)) {
	$_SESSION["node1"]=$n1;
	$node=$n1;
	$destination=$_SESSION["node2"];
}

if (isset($n2)) {
	$_SESSION["node2"]=$n2;
	$node=$n2;
	$destination=$_SESSION["node1"];	
}

if (ereg("err",$gdl_folder->check_folder_id($_SESSION["node1"])) && isset($_SESSION["node1"]))
	$_SESSION["node1"]=0;
	
if (ereg("err",$gdl_folder->check_folder_id($_SESSION["node2"]))&& isset($_SESSION["node2"]))
	$_SESSION["node2"]=0;
	
// display explorer
require_once("./module/explorer/function.php");

if (isset($submit)) {
	$folder=$_POST["folder"];
	$metadata=$_POST["metadata"];
	if ($destination > 0)
			$destproperty=$gdl_folder->get_property($destination);
	if (is_array($folder)) {
		foreach ($folder as $idxFolder => $valFolder) {
			$oldproperty=$gdl_folder->get_property($valFolder);
			$newproperty["name"]=$oldproperty["name"];
			$newproperty["node"]=$valFolder;
			if (($destination == 0) || (!ereg($valFolder,$destproperty["path"]) && ($valFolder != $destproperty["parent"]) && $valFolder != $destination)) {
				$newproperty["parent"]=$destination;
				$gdl_folder->edit_property($newproperty);
			} 
		}
	}
	
	if (is_array($metadata)) {
		if ($destination > 0) {
			foreach ($metadata as $idxMetadata => $valMetadata) {
				$oldproperty=$gdl_metadata->get_property($valMetadata);
				$newproperty["owner"]=$oldproperty["owner"];
				$newproperty["id"]=$valMetadata;
				$newproperty["folder"]=$destination;			
				$gdl_metadata->edit_property($newproperty);			
			}
		}
	}
}

display_explorer($node);
// simpan node pada session
$_SESSION['gdl_node']=$node;

?>