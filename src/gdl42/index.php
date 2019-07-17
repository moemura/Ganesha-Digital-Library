<?php

require_once ("./config/system.php");
$gdl_mod = isset($_GET['mod']) ? $_GET['mod'] : null;
$gdl_op = isset($_GET['op']) ? $_GET['op'] : null;
if (!isset($gdl_mod)) $gdl_mod = $gdl_sys['home'];
if (!isset($gdl_op)) $gdl_op = $gdl_sys['index'];
include ("./main.php");
?>