<?php

/***************************************************************************
                         /module/explorer/singleview.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/singleview.php/i",$_SERVER['PHP_SELF'])) die();

$_SESSION['DINAMIC_TITLE'] = "EXPLORER";
if (isset($_SESSION["node1"]))
	$node=$_SESSION["node1"];
else
	$node=$_SESSION["gdl_node"];
	
unset($_SESSION["node1"]);
unset($_SESSION["node2"]);

require_once("./module/explorer/function.php");

display_explorer($node);
// simpan node pada session
$_SESSION['gdl_node']=$node;

?>