<?php
if (preg_match("/conf.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$gdl_modul['name']		= "Folksonomy";
$gdl_menu['option']		= _CONFIGURATION;
$gdl_menu['garbage']	= _GARBAGE;
$gdl_menu['update']		= _UPDATE;

?>