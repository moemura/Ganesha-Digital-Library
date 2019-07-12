<?php

if (eregi("selection.php",$_SERVER['PHP_SELF'])) {
    die();
}
$content = "<p class=\"box\"><b>"._DOCUMENTMANAGEMENT."</b></p>\n";
$content .= "<ul class=\"filelist\">\n";
$content .= "<li><a href=./gdl.php?mod=upload&amp;op=step2&amp;s=dc_document>"._GENERALDOCUMENT."</a></li>\n";
$content .= "<li><a href=./gdl.php?mod=upload&amp;op=step2&amp;s=dc_simple>"._SIMPLEDOCUEMENT."</a></li>\n";
$content .= "<li><a href=./gdl.php?mod=upload&amp;op=step2&amp;s=dc_image>"._IMAGEDOCUMENT."</a></li>\n";
$content .= "<li><a href=./gdl.php?mod=upload&amp;op=step2&amp;s=dc_person>"._PEOPLEDOCUMENT."</a></li>\n";
$content .= "<li><a href=./gdl.php?mod=upload&amp;op=step2&amp;s=dc_organization>"._ORGDOCUMENT."</a></li>\n";
$content .= "</ul>\n";
$content .= "<p class=\"box\"><b>"._DATAMANAGEMENT."</b></p>\n";
$content .= "<ul class=\"filelist\">\n";
$content .= "<li><a href=./gdl.php?mod=upload&amp;op=step2&amp;s=dc_emall>"._EMALLCOMODITY."</a></li>\n";
$content .= "</ul>\n";

?>