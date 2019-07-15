<?php

if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

define("_AREYOUSURETOUPDATEDATABASEINDEX","Are you sure to update database index");
define("_HASBEENDUMP","has been dump");
define("_METADATAWILLBEINDEX","metadata will be index");
define("_YESUPDATE","Yes, Update database index");

?>