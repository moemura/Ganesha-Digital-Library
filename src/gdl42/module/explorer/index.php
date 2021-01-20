<?php

/***************************************************************************
                         /module/explorer/index.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, Lastiko Wibisono, KMRG ITB
    email                : hayun@kmrg.itb.ac.id, leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/


if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) die();

$_SESSION['DINAMIC_TITLE'] = "EXPLORER";
// get node to display
$node = isset($_GET['node']) ? $_GET['node'] : null;
$n1   = isset($_GET['n1']) ? $_GET['n1'] : null;
$n2   = isset($_GET['n2']) ? $_GET['n2'] : null;
$submit = isset($_POST["submit"]) ? $_POST["submit"] : null;

if (!isset($node)){
	$node = isset($_SESSION['gdl_node']) ? $_SESSION['gdl_node'] : null;
	if (!isset($node)) $node=0;
} else {
	unset($_SESSION["node1"]);
	unset($_SESSION["node2"]);
	}

if (isset($n1)) {
	$_SESSION["node1"]=$n1;
	$node=$n1;
	$destination=isset($_SESSION["node2"]) ? $_SESSION["node2"] : null;
}

if (isset($n2)) {
	$_SESSION["node2"]=$n2;
	$node=$n2;
	$destination=$_SESSION["node1"];	
}

if (preg_match("/err/", isset($_SESSION["node1"]) ? $gdl_folder->check_folder_id($_SESSION["node1"]) : '') && isset($_SESSION["node1"]))
	$_SESSION["node1"]=0;
	
if (preg_match("/err/", isset($_SESSION["node2"]) ? $gdl_folder->check_folder_id($_SESSION["node2"]) : '')&& isset($_SESSION["node2"]))
	$_SESSION["node2"]=0;
	
// display explorer
require_once("./module/explorer/function.php");

if (isset($submit)) {
	$folder=isset($_POST["folder"]) ? $_POST["folder"] : null;
	$metadata=isset($_POST["metadata"]) ? $_POST["metadata"] : null;
	if ($destination > 0)
			$destproperty=$gdl_folder->get_property($destination);
	if (is_array($folder)) {
		foreach ($folder as $idxFolder => $valFolder) {
			$oldproperty=$gdl_folder->get_property($valFolder);
			$newproperty["name"]=$oldproperty["name"];
			$newproperty["node"]=$valFolder;
			if (($destination == 0) || (!preg_match('/'.$valFolder.'/',$destproperty["path"]) && ($valFolder != $destproperty["parent"]) && $valFolder != $destination)) {
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