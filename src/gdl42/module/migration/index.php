<?php
if (preg_match("/index.php/i",$_SERVER['PHP_SELF'])) die();
$_SESSION['DINAMIC_TITLE'] = _MIGRATION;

$style = "span {\n"
		."width: 100px;\n"
		."float: left;\n"
		."}\n";
$gdl_content->set_style( $style);

include("./module/migration/conf.php");
$main = "<p class=\"box\"><b>"._GDL40DATABASECONFIG."</b></p>\n";
$main .= "<p><span>Host</span>: ".$db_source['host']."<br/>\n";
$main .= "<span>User Name</span>: ".$db_source['uname']."<br/>\n";
$main .= "<span>Database</span>: ".$db_source['name']."</p>\n";
$main .= "<p class=\"box\"><b>"._MIGRATIONSTEPS."</b></p>\n";
$main .= "<ul class=\"filelist\">\n"
	."<li><a href=\"./gdl.php?mod=migration&amp;op=folder\">"._MIGRATION." "._FOLDER."</a></li>\n"
	."<li><a href=\"./gdl.php?mod=migration&amp;op=metadata\">"._MIGRATION." "._METADATA."</a></li>\n"
	."<li><a href=\"./gdl.php?mod=migration&amp;op=relation\">"._MIGRATION." "._RELATION."</a></li>\n"
	."<li><a href=\"./gdl.php?mod=migration&amp;op=files\">"._MIGRATION." "._FILE."</a></li>\n"
	."<li><a href=\"./gdl.php?mod=migration&amp;op=user\">"._MIGRATION." "._USER."</a></li>\n"
	."<li><a href=\"./gdl.php?mod=migration&amp;op=bookmark\">"._MIGRATION." "._BOOKMARK."</a></li>\n"
	."<li><a href=\"./gdl.php?mod=migration&amp;op=publisher\">"._MIGRATION." "._PUBLISHER."</a></li>\n"
	."<li><a href=\"./gdl.php?mod=migration&amp;op=accesslog\">"._MIGRATION." "._ACCESSLOG2."</a></li>\n"
	."<ul>\n";
$main = gdl_content_box($main,_MIGRATION4042);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=migration\">"._MIGRATION."</a>";

?>