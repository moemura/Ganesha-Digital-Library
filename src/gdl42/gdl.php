<?php
$gdl_mod = isset($_GET['mod']) ? $_GET['mod'] : null;
$gdl_op = isset($_GET['op']) ? $_GET['op'] : null;
if (!isset($gdl_op)) $gdl_op = "index";
include ("./main.php");
?>