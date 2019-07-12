<?php

if (eregi("dc_person.php",$_SERVER['PHP_SELF'])) {
    die();
}


$type_val 	= $gdl_metadata->get_value($frm,"TYPE");
$fullname	= $gdl_metadata->get_value($frm,"PERSON_FULLNAME");
$persontitle= $gdl_metadata->get_value($frm,"PERSON_TITLE");
$birthday	= $gdl_metadata->get_value($frm,"PERSON_BIRTHDAY");
$place		= $gdl_metadata->get_value($frm,"PERSON_BIRTHDAYPLACE");
$p_orgname	= $gdl_metadata->get_value($frm,"PERSON_ORGNAME");
$p_position	= $gdl_metadata->get_value($frm,"PERSON_POSITION");
$p_email	= $gdl_metadata->get_value($frm,"PERSON_EMAIL");
$p_url		= strip_tags($gdl_metadata->get_value($frm,"PERSON_URL"));
$p_address	= $gdl_metadata->get_value($frm,"PERSON_ADDRESS");
$p_phone	= $gdl_metadata->get_value($frm,"PERSON_PHONE");
$p_fax		= $gdl_metadata->get_value($frm,"PERSON_FAX");
$p_expertise= $gdl_metadata->get_value($frm,"DESCRIPTION_EXPERTISE");
$p_experience= $gdl_metadata->get_value($frm,"DESCRIPTION_EXPERIENCE");
$p_education= $gdl_metadata->get_value($frm,"DESCRIPTION_EDUCATION");
$p_writing	= $gdl_metadata->get_value($frm,"DESCRIPTION_WRITING");
$p_interest	= $gdl_metadata->get_value($frm,"DESCRIPTION_INTEREST");
$publisher	= $gdl_metadata->get_value($frm,"PUBLISHER");
$datemod	= $gdl_metadata->get_value($frm,"DATE_MODIFIED");
$creator	= $gdl_metadata->get_value($frm,"CREATOR");
$date		= $gdl_metadata->get_value($frm,"DATE");
$relation	= $gdl_metadata->get_value($frm,"RELATION_COUNT");

include ("./config/type.php");
$type = $gdl_type[strtolower($type_val)];
if (!empty($fullname) && (substr($fullname,0,1) != '#') && (substr($fullname,-1,1) != '#'))
	$content .= "<p><b>"._FULLNAME."</b> : $fullname<br/>";

if (!empty($persontitle) && (substr($persontitle,0,1) != '#') && (substr($persontitle,-1,1) != '#'))
	$content .= "<b>"._PERSONTITLE."</b> : $persontitle<br/>";
	
if (!empty($birthday) && (substr($birthday,0,1) != '#') && (substr($birthday,-1,1) != '#'))
	$content .= "<b>"._BIRTHDAY."</b> : $birthday<br/>";

if (!empty($place) && (substr($place,0,1) != '#') && (substr($place,-1,1) != '#'))
	$content .= "<b>"._BIRTHPLACE."</b> : $place<br/>";
	
if (!empty($p_orgname) && (substr($p_orgname,0,1) != '#') && (substr($p_orgname,-1,1) != '#'))
	$content .= "<b>"._ORGANIZATIONNAME."</b> : $p_orgname<br/>";
	
if (!empty($p_position) && (substr($p_position,0,1) != '#') && (substr($p_position,-1,1) != '#'))
	$content .= "<b>"._POSITION."</b> : $p_position<br/>";
	
if (!empty($p_email) && (substr($p_email,0,1) != '#') && (substr($p_email,-1,1) != '#'))
	$content .= "<b>E-mail</b> : $p_email<br/>";

if (!empty($p_url) && (substr($p_url,0,1) != '#') && (substr($p_url,-1,1) != '#')) {
	if (!ereg("http://",$p_url)) 
		$p_url="http://".$p_url;
	$content .= "<b>Website</b> : <a href='".$p_url."'>$p_url</a><br/>";
	}

if (!empty($p_address) && (substr($p_address,0,1) != '#') && (substr($p_address,-1,1) != '#'))
	$content .= "<b>"._ADDRESS."</b> : $p_address<br/>";

if (!empty($p_phone) && (substr($p_phone,0,1) != '#') && (substr($p_phone,-1,1) != '#'))
	$content .= "<b>"._PHONE."</b> : $p_phone<br/>";

if (!empty($p_fax) && (substr($p_fax,0,1) != '#') && (substr($p_fax,-1,1) != '#'))
	$content .= "<b>"._FAX."</b> : $p_fax<br/>";	
$content .="</p>";	
if (!empty($p_expertise) && (substr($p_expertise,0,1) != '#') && (substr($p_expertise,-1,1) != '#'))
	$content .= "<p><b>"._EXPERTISE1."</b> : <br/>$p_expertise</p>";	
	
if (!empty($p_experience) && (substr($p_experience,0,1) != '#') && (substr($p_experience,-1,1) != '#'))
	$content .= "<p><b>"._EXPERIENCE."</b> : <br/>$p_experience</p>";	
	
if (!empty($p_education) && (substr($p_education,0,1) != '#') && (substr($p_education,-1,1) != '#'))
	$content .= "<p><b>"._EDUCATION."</b> : <br/>$p_education</p>";	

if (!empty($p_writing) && (substr($p_writing,0,1) != '#') && (substr($p_writing,-1,1) != '#'))
	$content .= "<p><b>"._PAPERNPUBLICATION."</b> : <br/>$p_writing</p>";	
	
if (!empty($p_interest) && (substr($p_interest,0,1) != '#') && (substr($p_interest,-1,1) != '#'))
	$content .= "<p><b>"._INTEREST."</b> : <br/>$p_interest</p>";	

$content .= "<span class=\"note\">$type from $publisher / $datemod</span><br/>\n";
$content .=	ucfirst(_BY)." : $creator<br/>\n"
		.ucfirst(_CREATED)." : $date, "._WITH." $relation "._FILES."<br/><br/>\n";

// relation file located in relation		
related_file();

?>