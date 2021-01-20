<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_MYDOCUMENTSFOLDER') or define("_MYDOCUMENTSFOLDER","Folder My Documents");
defined('_DOESNOTEXISTDOYOUWANTTOCREATE') or define("_DOESNOTEXISTDOYOUWANTTOCREATE","tidak ada. Apa Anda ingin membuat folder tersebut ?");
defined('_MYDOCUMENTSCREATED') or define("_MYDOCUMENTSCREATED","My Documents telah dibuat");
defined('_MYDOCUMENTSCREATEFAILED') or define("_MYDOCUMENTSCREATEFAILED","My Documents gagal dibuat");
?>