<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_PARTNERSHIP') or define("_PARTNERSHIP","Partner");
defined('_PARTNERNO') or define("_PARTNERNO","No");
defined('_PARTNERID') or define("_PARTNERID","Partner ID");
defined('_PARTNERNAME') or define("_PARTNERNAME","Nama Partner");
defined('_HOSTNAME') or define("_HOSTNAME","Hostname");
defined('_REMOTEUSER') or define("_REMOTEUSER","Remote");
defined('_PAGE') or define("_PAGE","Halaman");
defined('_PARTNERDISPLAYING') or define("_PARTNERDISPLAYING","Daftar Partner");
defined('_OF') or define("_OF","dari");
defined('_SEARCHPARTNER') or define("_SEARCHPARTNER","Pencarian");
defined('_PARTNERSEARCH') or define("_PARTNERSEARCH","Cari");
?>