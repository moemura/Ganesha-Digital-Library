<?php
if (eregi("english.php",$_SERVER['PHP_SELF'])) {
    die();
}

define("_MYDOCUMENTSFOLDER","My Documents folder ");
define("_DOESNOTEXISTDOYOUWANTTOCREATE","does not exist. Do you want to create it now ?");
define("_MYDOCUMENTSCREATED","My Documents Created");
define("_MYDOCUMENTSCREATEFAILED","Failed to create My Documents");
?>