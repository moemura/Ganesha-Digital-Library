<?php

if (eregi("indonesian.php",$_SERVER['PHP_SELF'])) {
    die();
}

define("_AREYOUSURETOUPDATEDATABASEINDEX","Anda yakin akan meng-update index database");
define("_HASBEENDUMP","telah di-dump");
define("_METADATAWILLBEINDEX","metadata akan di-index");
define("_YESUPDATE","Ya, Update index database");

?>