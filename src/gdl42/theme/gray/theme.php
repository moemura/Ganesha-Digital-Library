<?php

if (eregi("theme.php",$_SERVER['PHP_SELF'])) {
    die();
}

echo "<div id=\"header\" title=\"$gdl_publisher[publisher] | A member of the IndonesiaDLN $gdl_publisher[network]\">\n"
	."<h1>$gdl_publisher[publisher]</h1>\n"
	."<p class=\"hideprint\">A member of the $gdl_publisher[network] Network</p>\n"
	."</div>\n\n";

echo "<div id=\"container1\">\n";
echo "<div id=\"container2\">\n";
echo "<div id=\"lbox\">\n";

echo "<div class=\"hideprint\" id=\"searchbox\">\n"
	."<form method=\"post\" action=\"./gdl.php?mod=search\">\n"
	."<p><input type=\"hidden\" name=\"s\" value=\"all\"/></p>\n"
	."<p><strong>"._SEARCH."...</strong>\n"
	."<input type=\"text\" class=\"textsearch\" name=\"keyword\"/></p>\n"
	."<p><input type=\"submit\" class=\"submit\" value=\"Go\"/></p>\n"
	."</form>\n"
	."</div>\n\n";

//ambi dari "blue"
echo "<p class=\"title\">"._MAINMENU."...</p>\n";
ksort($gdl_content->module);
echo "<ul>\n"
	."<li><a href=\"./index.php\">Home</a></li>\n";
while (list($key, $val) = each($gdl_content->module)) {
	echo "<li><a href=\"./gdl.php?mod=$key\">$val</a></li>\n";
	if ($key==$gdl_mod){
		while (list($menukey, $menuval) = each($gdl_content->menu)) {
			echo "<li class=\"menu\">$menuval</li>\n";
		}
	}
}

echo "<li><a href=\"./gdl.php?mod=browse&amp;op=faq\">F.A.Q.</a></li>\n";
echo "<li><a href=\"./gdl.php?mod=browse&amp;op=contact\">"._CONTACTUS."</a></li>\n";

if ($gdl_session->user_id=="public"){
	echo "<li><a href=\"./gdl.php?mod=browse&amp;op=login\">"._LOGIN."</a></li>\n";
	echo "<li><a href=\"./gdl.php?mod=register\">"._REGISTRATION."</a></li> \n";
	if ($gdl_sys["activate_account"]){	
		echo "<li><a href=\"./gdl.php?mod=register&amp;op=activate\">"._ACTIVATE."</a><li>\n";
	}	

}else{
	echo "<li><a href=\"./gdl.php?mod=browse&amp;op=login&amp;page=out\">"._LOGOUT."</a></li>\n";
}
echo "</ul>\n\n";

$url = substr(strrchr($_SERVER['REQUEST_URI'], "?"),1);
if ($url<>""){
	$url=htmlentities($url)."&amp;";
}

echo "<p class=\"title\">"._LANGUAGE."...</p>\n"
	."<ul>\n"
	."<li><a href=\"".$_SERVER['PHP_SELF']."?".$url."newlang=indonesian\">"._INDONESIAN."</a></li>\n"
	."<li><a href=\"".$_SERVER['PHP_SELF']."?".$url."newlang=english\">"._ENGLISH."</a></li>\n"
	."</ul>\n";
	

echo "<p class=\"title\">"._LINKS."...</p>\n"
	."<ul>\n"
	."<li><a href=\"http://www.indonesiadln.org\">IndonesiaDLN</a></li>\n"
	."<li><a href=\"http://www.dikti.org\">DIKTI</a></li>\n"
	."</ul>\n";


echo "<p class=\"title\">Theme</p>\n"
	."<ul>\n"
	."<li><a href=\"".$_SERVER['PHP_SELF']."?".$url."newtheme=gray\">Gray</a></li>\n"
	."<li><a href=\"".$_SERVER['PHP_SELF']."?".$url."newtheme=green\">Green</a></li>\n"
	."</ul>\n";

echo "</div>\n\n";

echo "<div id=\"rbox\" class=\"hideprint\">\n";
if (($_GET['mod']== "browse") && ($_GET['op']=="read") && (! empty ($_GET['id']))) {
		$relation = "<p class=\"print\"><span style=\"cursor: pointer;\" onclick=\"window.print()\">"._PRINTTHISPAGE."					</span></p>\n";

echo $relation.$gdl_content->relation."\n";
} else {
	echo $gdl_content->relation."\n";
}

echo "</div>\n\n";

echo "<div id=\"content\">\n";
if ($gdl_content->path <> "") echo "<p class=\"dirpath\"><strong>Path</strong>: ".$gdl_content->path."</p>\n";
echo $gdl_content->main."\n";
echo "</div>\n";
echo "<div id=\"cleardiv\"></div>\n";
echo "</div>\n";
echo "</div>\n\n";

echo "<div id=\"footer\">\n"
	."<p>This work was carried out with the aid of a grant from INHERENT-DIKTI"
	." | <a href=\"./gdl.php?mod=browse&amp;op=credit\">"._CREDIT."</a></p>"
	."<p>&copy; 2006 ITB. All rights reserved. | Valid <a href=\"http://validator.w3.org/check/referer\"><abbr title=\"eXtensible HyperText Markup Language\">XHTML</abbr></a>"
	." + <a href=\"http://jigsaw.w3.org/css-validator/check/referer\"><abbr title=\"Cascading Style Sheets\">CSS</abbr></a>"
	." | Best Viewed with <a href=\"http://www.spreadfirefox.com/?q=affiliates&amp;id=7247&amp;t=82\">Firefox!</a></p>\n"
	."</div>\n\n";
	
?>
