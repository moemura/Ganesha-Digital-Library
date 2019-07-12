<?
if (eregi("conf.php",$_SERVER['PHP_SELF'])) {
    die();
}

$gdl_modul['name'] = _ACCESSLOG;
$accesslog["browse_index.php"] = true;
$accesslog["browse_read.php"] = true;
$accesslog["browse_home.php"] = true;
$accesslog["bookmark_comment.php"] = true;
?>