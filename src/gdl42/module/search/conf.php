<?php

if (preg_match("/conf.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$gdl_modul['name'] = _SEARCH;

// search for document
$search_tab['dc_document']['schema'] 				= _DOCUMENT;
$search_tab['dc_document']['title']					= _TITLE;
$search_tab['dc_document']['creator']				= _CREATOR;
$search_tab['dc_document']['type']					= _TYPE;
$search_tab['dc_document']['subject_keywords']		= _SUBJECT;
$search_tab['dc_document']['description']			= _DESCRIPTION;

$search_tab['dc_person']['schema'] = _PEOPLE;
$search_tab['dc_person']['fullname'] = _FULLNAME;
$search_tab['dc_person']['address'] = _ADDRESS;
$search_tab['dc_person']['orgname'] = _ORGANIZATION;
$search_tab['dc_person']['interest'] = _INTEREST;
$search_tab['dc_person']['expertise'] = _EXPERTISE;
$search_tab['dc_person']['experience'] = _EXPERIENCE;

$search_tab['dc_organization']['schema'] = _ORGANIZATION;
$search_tab['dc_organization']['name'] = _NAME;
$search_tab['dc_organization']['address'] = _ADDRESS;
$search_tab['dc_organization']['expertise'] = _EXPERTISE;
$search_tab['dc_organization']['experience'] = _EXPERIENCE;

include "./config/system.php";
if ($gdl_sys['index_cdsisis']){
	$search_tab['catalogs']['schema'] = _CATALOGS;
}
/*
$search_tab['catalogs']['period'] = _PERIOD;
$search_tab['catalogs']["isbn"]   ="ISBN";
$search_tab['catalogs']["issn"]   ="ISSN";
$search_tab['catalogs']["location"]=_LOCATION;
$search_tab['catalogs']["language"]=_LANGUAGE;
$search_tab['catalogs']["classification"]=_CLASSIFICATION;
$search_tab['catalogs']["call_number"]=_CALLNUMBER;
$search_tab['catalogs']["ddc_edition"]=_DDCEDITION;
$search_tab['catalogs']["local_classification"]=_LOCALCLASSIFICATION;
$search_tab['catalogs']["author"]=_AUTHOR;
$search_tab['catalogs']["author_corporate"]=_AUTHORCORPORATE;
$search_tab['catalogs']["conference"]=_CONFERENCE;
$search_tab['catalogs']["title_of_journal"]=_TITLEOFJOURNAL;
$search_tab['catalogs']["title"]=_TITLE;
$search_tab['catalogs']["alternative_title"]=_ALTERNATIVETITLE;
$search_tab['catalogs']["description"]=_DESCRIPTION;
$search_tab['catalogs']["edition"]=_EDITION;
$search_tab['catalogs']["place_of_publisher"]=_PLACEOFPUBLISHER;
$search_tab['catalogs']["publisher"]=_PUBLISHER;
$search_tab['catalogs']["dimention"]=_DIMENTION;
$search_tab['catalogs']["illustration"]=_ILLUSTRATION;
$search_tab['catalogs']["height"]=_HEIGHT;
$search_tab['catalogs']["series"]=_SERIES;
$search_tab['catalogs']["note"]=_NOTE;
$search_tab['catalogs']["bibliography"]=_BIBLIOGRAPHY;
$search_tab['catalogs']["summary"]=_SUMMARY;
$search_tab['catalogs']["subject"]=_SUBJECT;
$search_tab['catalogs']["coauthor"]=_COAUTHOR;
$search_tab['catalogs']["coauthor_corporate"]=_COAUTHORCORPORATE;
$search_tab['catalogs']["volume"]="Volume";
$search_tab['catalogs']["pagina"]="Pagina";
$search_tab['catalogs']["identification"]=_IDENTIFICATION;
*/
$search_tab['catalogs']["title"]=_TITLE;
$search_tab['catalogs']["author"]=_AUTHOR;
$search_tab['catalogs']['period'] = _PERIOD;
$search_tab['catalogs']["publisher"]=_PUBLISHER;
$search_tab['catalogs']["isbn"]   ="ISBN";
$search_tab['catalogs']["subject"]=_SUBJECT;
$search_tab['catalogs']["classification"]=_CLASSIFICATION;
$search_tab['catalogs']["call_number"]=_CALLNUMBER;
$search_tab['catalogs']["description"]=_DESCRIPTION;

?>
