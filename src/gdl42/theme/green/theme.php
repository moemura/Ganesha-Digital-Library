<?

if (eregi("theme.php",$_SERVER['PHP_SELF'])) {
    die();
}

echo "<div class=\"fluid fluidtop\">\n"				// fluidtop start
     ."<div class=\"wrap\">\n"						// wrap start
	 ."<div id=\"top\" class=\"clearboth\">\n"		// top start
     ."<div class=\"floatleft\">\n"					// floatleft start
     ."<div class=\"block\">\n"						// block start
//	 ."<a href=\"./index.php\"><strong>GDL</strong></a> / \n"
	 ."<a href=\"./index.php\">Home</a>\n"
	 ."</div>\n"									// block stop
     ."</div>\n"									// floatleft stop
     ."<hr />\n"
     ."<div class=\"centerright floatright\">\n"	// floatright start
	 ."<div class=\"floatleft\">\n"					// floatleft start
     ."<div class=\"block\">\n"					// block start
	 ."<div class=\"state_offline_close\">\n";
if ($gdl_session->user_id=="public") {
	echo "<a href=\"./gdl.php?mod=browse&amp;op=login\">"._LOGIN."</a> / \n";
	echo "<a href=\"./gdl.php?mod=register\">"._REGISTRATION."</a> \n";
	if ($gdl_sys["activate_account"]){	
		echo "/ <a href=\"./gdl.php?mod=register&amp;op=activate\">"._ACTIVATE."</a>\n";
	}	
} else {
	echo _SIGNINAS."<strong>".$gdl_session->user_name."</strong> / <a href=\"./gdl.php?mod=browse&amp;op=login&amp;page=out\">"._LOGOUT."</a>\n";
}

echo  "</div>"										// offline stop
	 ."</div>\n"								    // block stop
     ."</div>\n"									// floatleft stop
     ."<hr />\n"
	 ."<div class=\"floatright\">\n"				// floatright start
     ."<div class=\"block\">\n"						// block start
     ."<a href=\"./gdl.php?mod=browse&amp;op=faq\">F.A.Q.</a> / \n"
     ."<a href=\"./gdl.php?mod=browse&amp;op=credit\">"._CREDIT."</a> / \n"
     ."<a href=\"./gdl.php?mod=browse&amp;op=contact\">"._CONTACTUS."</a>\n"
	 ."</div>\n"									// block stop
	 ."</div>\n"									// floatright stop
     ."<div class=\"clearboth\"></div>\n"
     ."</div>\n"									// floatright stop
     ."<div class=\"clearboth\"></div>\n"
     ."</div>\n"									// top stop
     ."</div>\n"									// wrap stop
     ."</div>\n\n";									// fluidtop stop


echo "<hr />\n"
    ."<div class=\"fluid fluidheader\">\n"
    ."<div class=\"wrap\">\n"
	."<div id=\"header\" class=\"header\" title=\"$gdl_publisher[publisher] | A member of the IndonesiaDLN $gdl_publisher[network] Network\">\n"
	."<div id=\"headerimg\" class=\"center floatleft\"><h1><a href=\"./index.php\">GDL</a></h1></div>\n"
	."<div id=\"welcome\" class=\"center floatright\">\n"
	."<h2><a href=\"./index.php\">$gdl_publisher[publisher]</a></h2>\n"
	."<div class=\"hideprint\">A member of the $gdl_publisher[network] Network</div>\n"
	."</div>\n"
	."<div class=\"clearboth\"></div>\n"
	."</div>\n"
	."<div class=\"clearboth\"></div>\n"
	."</div>\n"
	."</div>\n"
	."<hr />\n\n";


echo "<div class=\"fluid fluidmain\">\n"
    ."<div class=\"wrap\">\n"
  	."<div id=\"main\" class=\"main\">\n";


echo "<div id=\"nav\" class=\"left floatleft\">\n";

echo "<!-- is used to live CD this block should be opened -->\n"
	."<div class=\"state_offline_open\">\n"
		."<div class=\"splash\"></div>\n"
		."<h2 class=\"welcome\">Info</h2>\n"
		."<div class=\"block\">\n"
		."<p>"._LIVECDVERSIONCOMEFROM." <br/>\n"
		."<strong>".$gdl_publisher['publisher']."</strong><br/>\n"
		._ADDRESS.$gdl_publisher['address']." <br/>\n"
		."<a href=\"./contact.html\">"._MOREINFO."</a></p>\n"
		."</div>\n"
	."</div>\n";

echo "<!-- is used to live CD this block should be closed -->\n"
	."<div class=\"state_offline_close\">\n";
	
	echo "<div class=\"block\" id=\"searchbox\">\n"
		."<h2>"._SEARCH."...</h2>\n"
		."<form method=\"post\" action=\"./gdl.php?mod=search\"><div>\n"
		."<input type=\"hidden\" name=\"s\" value=\"dc\"/>\n"
		."<input type=\"hidden\" name=\"type\" value=\"all\"/>\n"
		."<input type=\"text\" class=\"textsearch\" name=\"keyword\"/>\n"
		."<input type=\"submit\" class=\"submit\" value=\"Go\"/>\n"
		."</div>\n"
		."</form>\n"
		."</div>\n\n";
	
	
	echo "<div class=\"block\"><h2 class=\"title\">"._MAINMENU."...</h2>\n";
	ksort($gdl_content->module);
	echo "<ul>\n"
		."<li><a href=\"./index.php\">Home</a></li>\n";
	while (list($key, $val) = each($gdl_content->module)) {
		echo "<li><a href=\"./gdl.php?mod=$key\">$val</a>";
		if ($key==$gdl_mod){
			if (count ($gdl_content->menu) != "") {
				echo "<ul>";	
				while (list($menukey, $menuval) = each($gdl_content->menu)) {
					echo "<li class=\"menu\">$menuval</li>\n";
				}		
				echo "</ul>";
			}
		}
		echo "</li>\n";
	}
	
	echo "</ul></div>\n\n";
	
	$url = substr(strrchr($_SERVER['REQUEST_URI'], "?"),1);
	if ($url<>""){
		$url=htmlentities($url)."&amp;";
	}
	echo "<div class=\"block\"><h2 class=\"title\">"._LANGUAGE."...</h2>\n"
		."<ul>\n"
		."<li><a href=\"".$_SERVER['PHP_SELF']."?".$url."newlang=indonesian\">"._INDONESIAN."</a></li>\n"
		."<li><a href=\"".$_SERVER['PHP_SELF']."?".$url."newlang=english\">"._ENGLISH."</a></li>\n"
		."</ul></div>\n";
echo "</div>\n";

echo "<div class=\"block\"><h2 class=\"title\">"._LINKS."...</h2>\n"
	."<ul>\n"
	."<li><a href=\"http://www.indonesiadln.org\">IndonesiaDLN</a></li>\n"
	."<li><a href=\"http://www.dikti.org\">DIKTI</a></li>\n"
	."</ul></div>\n";

//untuk percobaan themes

echo "<!-- is used to live CD this block should be closed -->\n"
	."<div class=\"state_offline_close\">\n";
	echo "<div class=\"block\"><h2 class=\"title\">Theme</h2>\n"
		."<ul>\n"
		."<li><a href=\"".$_SERVER['PHP_SELF']."?".$url."newtheme=gray\">Gray</a></li>\n"
		."<li><a href=\"".$_SERVER['PHP_SELF']."?".$url."newtheme=green\">Green</a></li>\n"
		."</ul></div>\n";
echo "</div>\n";

echo "</div>\n\n"
    ."<hr />\n\n";


echo "<div id=\"content\" class=\"centerright floatright\">\n";
//if ($_SESSION['gdl_user'] == "public"){ 
if ($gdl_session->user_id=="public") {
    echo "<div class=\"splash\"></div>\n";
}

if ($gdl_content->path <> "") $path_show = "<p class=\"dirpath\"><strong>Path</strong>: ".$gdl_content->path."</p>\n";

$state	= $_GET['state'];
$q		= $_GET['q'];

if(($state == "offline") && !empty($q))
	$path_show = "<div class=\"state_offline_close\">$path_show</div>";
	
echo $path_show;
echo $gdl_content->main."\n";

if (($_GET['mod']== "browse") && ($_GET['op']=="read") && (! empty ($_GET['id']))) {
	echo "<div id=\"rel\" class=\"hideprint\">\n";
	$relation = "<p class=\"print\"><span style=\"cursor: pointer;\" onclick=\"window.print()\">"._PRINTTHISPAGE."</span></p>\n";
	echo $relation.$gdl_content->relation."\n";
	echo "<div class=\"clearboth\"></div>\n\n";
	echo "</div>\n\n";
} else if (! empty($gdl_content->relation)){
	echo "<div id=\"rel\" class=\"hideprint\">\n";
	echo $gdl_content->relation."\n";
	echo "<div class=\"clearboth\"></div>\n\n";
	echo "</div>\n\n";
}


echo "<div class=\"clearboth\"></div>\n\n";

echo "</div>\n";
echo "<div class=\"clearboth\"></div>\n";

echo "</div>\n";
echo "</div>\n";
echo "</div>\n\n";


echo "<hr />\n"
    ."<div class=\"fluid fluidfooter\">\n"
    ."<div class=\"wrap\">\n";
	
echo "<div id=\"footer\">\n";

echo "<div class=\"centerleft floatleft\">\n"
    ."<div class=\"block\">\n"
	."This work was carried out with the aid of a grant from INHERENT-DIKTI "
	."| Best Viewed with <a href=\"http://www.spreadfirefox.com/?q=affiliates&amp;id=7247&amp;t=82\">Firefox!</a>\n"
	."<br /><br />"
	."&copy; 2006 ITB. All rights reserved."
	."  | Valid <a href=\"http://validator.w3.org/check/referer\"><abbr title=\"eXtensible HyperText Markup Language\">XHTML</abbr></a>"
	." + <a href=\"http://jigsaw.w3.org/css-validator/check/referer\"><abbr title=\"Cascading Style Sheets\">CSS</abbr></a>"
	."</div></div>\n";
	
echo "<hr /><div class=\"floatright\">\n"
    ."<div class=\"block\"><a href=\"#top\">Top ^</a></div>\n"
    ."</div>\n\n";
	
echo "<div class=\"clearboth\"></div>\n\n";
	
echo "</div>\n\n";

echo "<div class=\"clearboth\"></div>\n\n";

echo "</div>\n"
    ."</div>\n\n";
	
?>
