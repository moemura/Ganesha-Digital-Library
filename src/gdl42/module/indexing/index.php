<?php

if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) {
    die();
}
$_SESSION['DINAMIC_TITLE'] = "Indexing";
$dbres = $gdl_db->select("metadata","count(identifier) as total","xml_data is not null AND xml_data<>'deleted'");
$row = @mysqli_fetch_assoc($dbres);
$main = $row["total"]." "._METADATAWILLBEINDEX."<br/><br/>\n";
$main .= _AREYOUSURETOUPDATEDATABASEINDEX. " ? <a href=\"./gdl.php?mod=indexing&amp;op=indexing\">"._YESUPDATE."</a>";
$main = gdl_content_box($main,"Update Database Index");
$gdl_content->set_main($main);

?>