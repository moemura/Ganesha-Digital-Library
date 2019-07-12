<?php

if (eregi("indonesian.php",$_SERVER['PHP_SELF'])) {
    die();
}

define("_BOOKMARK","Bookmark");
define("_CONTACTUS","Contact Us");
define("_EXIM","Export/Import");
define("_EDITPROFILE","Edit Profile");
define("_EXPLORER","Explorer");
define("_FAQ","Frequently Asked Question");
define("_INDEXING","Update Index");
define("_MIGRATION","Migration");
define("_MIGRATION40TO42", "Migration 4.0 to 4.2");
define("_UPLOAD","Upload / Edit");
define("_SYNCHRONIZATION","Synchronization");
define("_SEARCH","Searching");
define("_USERMANAGEMENT","User");
define("_UPLOAD","Upload Metadata");
define("_WORKGROUP","Workgroup");
define("_USER","User");
define("_ACCESSLOG","Access Log Configuration");
if ($gdl_session->authority == "*")
	define("_MEMBER","Member");
else
	define("_MEMBER","Edit Profile");

define("_PUBLISHER","Publisher");
define("_REGISTRATION", "Registration");
define("_ACTIVATE", "Activate");
define("_MIGRATION4042","Migration GDL 4.0 to 4.2");
define("_PUBLISHER","Publisher");
define("_CONFIGURATION","Configuration");
define("_ORGANIZATION","Organization");
define("_CDSISIS","CDS/ISIS Databases");
define("_USERREQUEST","User Request");
define("_INSTALLATION","Installation");
define("_INSTALLATIONPAGE","If you see this page, that means GDL 4.2 was not installed yet. Please click <a href='./gdl.php?mod=install'>here</a> to execute the installation process");
define("_DISCUSSION","Discussions / Comments");
// general language
define("_CANCEL","Cancel");
define("_DELETE","Delete");
define("_ERROR","Error");
define("_ENGLISH","English");
define("_EXCLAMATION","Attention !");
define("_GUEST","Guest");
define("_INDONESIAN","Indonesian");
define("_INFORMATION","Informasi");
define("_LANGUAGE","Language");
define("_LOGIN","Login");
define("_LOGOUT","Logout");
define("_MAINMENU","Menu");
define("_OK","OK");
define("_OPERATIONNOTEXIST","This operation is not available ...");
define("_PRINTTHISPAGE","Print ...");
define("_SCHEMANOTAVAILABLE","Metadata schema not available");
define("_YOUHAVENOTAUTHORITY","You haven't authority");
define("_YOUCANACCESSDIRECLY","You can not access direcly");
define("_YOUARE","You are");
define("_WELCOMETOTHE","Welcome to the");
define("_WELCOME","Welcome");
define("_YES","Yes");
define("_NO","No");
define("_NOT","Not");
define("_DATE","Date");

define ("_SIGNINAS","Sign in as ");
define ("_LINKS", "Links");
define("_PARTNERSHIP","Partner");
define("_CREDIT","Credit");

define("_LIVECD","LIVE CD");
define("_LIVECDVERSIONCOMEFROM", "liveCD Version from the collections of ");
define("_ADDRESS", "Address: ");
define("_MOREINFO", "More Info");



// *****************************************************8

?>
