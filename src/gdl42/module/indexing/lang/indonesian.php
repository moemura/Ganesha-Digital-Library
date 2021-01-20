<?php

if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_AREYOUSURETOUPDATEDATABASEINDEX') or define("_AREYOUSURETOUPDATEDATABASEINDEX","Anda yakin akan meng-update index database");
defined('_HASBEENDUMP') or define("_HASBEENDUMP","telah di-dump");
defined('_METADATAWILLBEINDEX') or define("_METADATAWILLBEINDEX","metadata akan di-index");
defined('_YESUPDATE') or define("_YESUPDATE","Ya, Update index database");
?>