<?php
/***************************************************************************
                         /module/explorer/multiview.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/


if (preg_match("/multiview.php/i",$_SERVER['PHP_SELF'])) die();

$_SESSION['DINAMIC_TITLE'] = "EXPLORER";
// get node to display
$node = isset($_GET['node']) ? $_GET['node'] : null;
if (!isset($node)){
	$node = isset($_SESSION['gdl_node']) ? $_SESSION['gdl_node'] : null;
	if (!isset($node)) $node=0;
}

// display explorer
$_SESSION["node1"]=$node;
$_SESSION["node2"]=$node;

require_once("./module/explorer/function.php");

display_explorer($node);
// simpan node pada session
$_SESSION['gdl_node']=$node;
?>