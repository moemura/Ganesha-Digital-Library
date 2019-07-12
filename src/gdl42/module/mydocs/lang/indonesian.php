<?php
if (eregi("indonesian.php",$_SERVER['PHP_SELF'])) {
    die();
}

define ("_MYDOCUMENTSFOLDER","Folder My Documents");
define ("_DOESNOTEXISTDOYOUWANTTOCREATE","tidak ada. Apa Anda ingin membuat folder tersebut ?");
define("_MYDOCUMENTSCREATED","My Documents telah dibuat");
define("_MYDOCUMENTSCREATEFAILED","My Documents gagal dibuat");
?>