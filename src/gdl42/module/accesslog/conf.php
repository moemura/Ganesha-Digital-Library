<?php
if (preg_match("/conf.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$gdl_modul['name'] = _ACCESSLOG;
$accesslog["accesslog_index.php"] = true;
$accesslog["bookmark_comment.php"] = true;
$accesslog["bookmark_delete.php"] = true;
$accesslog["browse_comment.php"] = true;
$accesslog["browse_contact.php"] = true;
$accesslog["browse_credit.php"] = true;
$accesslog["browse_faq.php"] = true;
$accesslog["browse_home.php"] = true;
$accesslog["browse_login.php"] = true;
$accesslog["cdsisis_delete.php"] = true;
$accesslog["cdsisis_union.php"] = true;
$accesslog["configuration_server.php"] = true;
?>