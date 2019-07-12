<?php

require_once ("./config/system.php");
$gdl_mod = $_GET['mod'];
$gdl_op = $_GET['op'];
if (!isset($gdl_mod)) $gdl_mod = $gdl_sys['home'];
if (!isset($gdl_op)) $gdl_op = $gdl_sys['index'];
include ("./main.php");
?>