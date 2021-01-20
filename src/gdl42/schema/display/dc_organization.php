<?php

if (preg_match("/dc_organization.php/i",$_SERVER['PHP_SELF'])) {
    die();
}
global $gdl_metadata;
$type_val 	= $gdl_metadata->get_value($frm,"TYPE");
$orgname 	= $gdl_metadata->get_value($frm,"ORGANIZATION_NAME");
$orgmail	= $gdl_metadata->get_value($frm,"ORGANIZATION_EMAIL");
$orgurl		= strip_tags($gdl_metadata->get_value($frm,"ORGANIZATION_URL"));
$orgaddress	= $gdl_metadata->get_value($frm,"ORGANIZATION_ADDRESS");
$orgphone	= $gdl_metadata->get_value($frm,"ORGANIZATION_PHONE");
$orgfax		= $gdl_metadata->get_value($frm,"ORGANIZATION_FAX");
$expertise	= $gdl_metadata->get_value($frm,"DESCRIPTION_EXPERTISE");
$experience	= $gdl_metadata->get_value($frm,"DESCRIPTION_EXPERIENCE");
$publisher	= $gdl_metadata->get_value($frm,"PUBLISHER");
$datemod	= $gdl_metadata->get_value($frm,"DATE_MODIFIED");
$creator	= $gdl_metadata->get_value($frm,"CREATOR");
$date		= $gdl_metadata->get_value($frm,"DATE");
$relation	= $gdl_metadata->get_value($frm,"RELATION_COUNT");


include ("./config/type.php");
$type = $gdl_type["$type_val"];
$content = '';
if (!empty($orgname) && (substr($orgname,0,1) != '#') && (substr($orgname,-1,1) != '#'))
	$content .= "<p><b>"._ORGANIZATIONNAME."</b> : $orgname<br/>";

if (!empty($orgmail) && (substr($orgmail,0,1) != '#') && (substr($orgmail,-1,1) != '#'))
	$content .= "<b>E-mail</b> : $orgmail<br/>";

if (!empty($orgurl) && (substr($orgurl,0,1) != '#') && (substr($orgurl,-1,1) != '#')) {
		if (!preg_match("/http:\/\//",$orgurl)) 
			$orgurl="http://".$orgurl;
		$content .= "<b>Website</b> : <a href='".$orgurl."'>$orgurl</a><br/>";
	}

if (!empty($orgaddress) && (substr($orgaddress,0,1) != '#') && (substr($orgaddress,-1,1) != '#'))
	$content .= "<b>"._ADDRESS."</b> : $orgaddress<br/>";

if (!empty($orgphone) && (substr($orgphone,0,1) != '#') && (substr($orgphone,-1,1) != '#'))
	$content .= "<b>"._PHONE."</b> : $orgphone<br/>";

if (!empty($orgfax) && (substr($orgfax,0,1) != '#') && (substr($orgfax,-1,1) != '#'))
	$content .= "<b>"._FAX."</b> : $orgfax<br/>";	
$content .="</p>";	
if (!empty($expertise) && (substr($expertise,0,1) != '#') && (substr($expertise,-1,1) != '#'))
	$content .= "<p><b>"._EXPERTISE1."</b> : <br/>$expertise</p>";	
	
if (!empty($experience) && (substr($experience,0,1) != '#') && (substr($experience,-1,1) != '#'))
	$content .= "<p><b>"._EXPERIENCE."</b> : <br/>$experience</p>";	
	

$content .= "<span class=\"note\">$type from $publisher / $datemod</span><br/>\n";
$content .=	ucfirst(_BY)." : $creator<br/>\n"
		.ucfirst(_CREATED)." : $date, "._WITH." $relation "._FILES."<br/><br/>\n";

// relation file located in relation		
related_file();
?>