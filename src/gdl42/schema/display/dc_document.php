<?php

if (preg_match("/dc_document.php/i",$_SERVER['PHP_SELF'])) {
    die();
}
global $gdl_metadata;

include ("./config/type.php");
$title2		= $gdl_metadata->get_value($frm,"TITLE_ALTERNATIVE");
$title3		= $gdl_metadata->get_value($frm,"TITLE_SERIES");
$type_val 	= $gdl_metadata->get_value($frm,"TYPE");
$rights		= $gdl_metadata->get_value($frm,"RIGHTS");
$creator	= $gdl_metadata->get_value($frm,"CREATOR");
$creatormail= $gdl_metadata->get_value($frm,"CREATOR_EMAIL");
$creatororg = $gdl_metadata->get_value($frm,"CREATOR_ORGNAME");
$moddate	= $gdl_metadata->get_value($frm,"DATE_MODIFIED","DATE");
$date		= substr($gdl_metadata->get_value($frm,"DATE"),0,10);
$relation	= $gdl_metadata->get_value($frm,"RELATION_COUNT","RELATION");
$keyword	= $gdl_metadata->get_value($frm,"SUBJECT_KEYWORDS");
$subject	= $gdl_metadata->get_value($frm,"SUBJECT");
$heading	= $gdl_metadata->get_value($frm,"SUBJECT_HEADING");
$ddc		= $gdl_metadata->get_value($frm,"SUBJECT_DDC");
$desc		= $gdl_metadata->get_value($frm,"DESCRIPTION");
$desc2		= $gdl_metadata->get_value($frm,"DESCRIPTION_ALTERNATIVE","DESCRIPTION",1);
$pub		= $gdl_metadata->get_value($frm,"PUBLISHER");
$url		= $gdl_metadata->get_value($frm,"SOURCE_URL");
$source	= $gdl_metadata->get_value($frm,"SOURCE");
$coverage    = $gdl_metadata->get_value($frm,"COVERAGE");
$key = key($frm);
if(preg_match("/DC:/",$key) && empty($keyword)){
	$keyword = $subject;
	$subject = "";
}

$type = $gdl_type[strtolower($type_val)];

$content ='';
if (!empty($title2) && (substr($title2,0,1) != '#') && (substr($title2,-1,1) != '#')){
	$content .= "<p><b>$title2</b></p>\n";
}
if (!empty($title3)&& (substr($title3,0,1) != '#') && (substr($title3,-1,1) != '#')){
	$content .= "<b>$title3</b><br/>\n";
}

if(!empty($type))
	$type = "$type from ";
	
if (!empty($creatororg)&& (substr($creatororg,0,1) != '#') && (substr($creatororg,-1,1) != '#')){
	$creatororg = ", $creatororg";
} else
	$creatororg="";	

if (!empty($creatormail)&& (substr($creatormail,0,1) != '#') && (substr($creatormail,-1,1) != '#')){
	$creatormail = " ($creatormail)";
} else
	$creatormail="";

$content .= "<span class=\"note\">$type$pub / $moddate</span><br/>\n";
$content .=	ucfirst(_BY)." : $creator$creatororg$creatormail<br/>\n"
		.ucfirst(_CREATED)." : $date, "._WITH." $relation "._FILES."<br/><br/>\n";
if (!empty($keyword)){
	$content .= "<b>".ucfirst(_KEYWORDS)." :</b> $keyword<br/>\n";
}
if (!empty($subject)&& (substr($subject,0,1) != '#') && (substr($subject,-1,1) != '#')){
	$content .=	"<b>".ucfirst(_SUBJECT)." :</b> $subject<br/>\n";
}
if (!empty($heading)&& (substr($heading,0,1) != '#') && (substr($heading,-1,1) != '#')){
	$content .=	"<b>".ucfirst(_SUBJECT_HEADING)." :</b> $heading<br/>\n";
}
if (!empty($ddc)&& (substr($ddc,0,1) != '#') && (substr($ddc,-1,1) != '#')){
	$content .=	"<b>".ucfirst(_SUBJECT_DDC)." :</b> $ddc<br/>\n";
}
if (!empty($url)&& (substr($url,0,1) != '#') && (substr($url,-1,1) != '#')){
	if (!preg_match("/http:\/\//",$url)) 
		$url="http://".$url;
	$content .=	"<b>Url :</b> <a href='".$url."'>$url</a><br/>\n";
}
if (!empty($source)&& (substr($source,0,1) != '#') && (substr($source,-1,1) != '#')){
	$content .=	"<b>"._SOURCEFROM." :</b> $source<br/>\n";
}
if (!empty($coverage)&& (substr($coverage,0,1) != '#') && (substr($coverage,-1,1) != '#')){
	$content .=	"<b>"._COVERAGE." : </b> $coverage<br/>\n";
}

$content .= "<p>$desc</p>\n";

if (!empty($desc2)&& (substr($desc2,0,1) != '#') && (substr($desc2,-1,1) != '#')){
	$content .= "<b>"._DESCRIPTION_ALTERNATIVE." :</b><br/><br/><i> $desc2</i><br/>\n";
}
//print_r($frm);
if (!empty($rights)&& (substr($rights,0,1) != '#') && (substr($rights,-1,1) != '#')){
	$content .=	"<p><b>".ucfirst(_RIGHTS)." :</b> $rights</p>\n";
}

// relation file located in relation		
related_file();
?>