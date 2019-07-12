<?php

if (eregi("indonesian.php",$_SERVER['PHP_SELF'])) {
    die();
}

define("_COMMENT","Comment");
define("_DISPLAYINGMETADATA","Displaying Metadata");
define("_DATE","Date");
define("_DOWNLOAD","Downnload");
define("_DOWNLOADNOTE","Download for member only.");
define("_EDITOR","Editor");
define("_EMAIL","E Mail");
define("_ERRORREADMETADATA","Read metadata is error");
define("_FORMOREINFORMATION","For more information :");
define("_FILES","files");
define("_GIVECOMMENT","Give Comment");
define("_LASTNEWS","Last News");
define("_LOGINFAIL","Login Fail");
define("_LOGINFAILNOTE","Your ID or Password is wrong.<br/>Please re-type your ID and Password ...");
define("_LOGINNOTE","Please enter your account and password to log in ...<br/>Forget password ? send mail to $gdl_publisher[admin]");
define("_LOGINACTIVATE", "Sorry, your account is not activated yet. <br/> Please contact administrator( $gdl_publisher[admin]) <br/>or enter your account and Activation Code here:
		<a href=\"./gdl.php?mod=register&amp;op=activate\" >Activate Account</a>.");
define("_METADATAS","Metadata");
define("_METADATAINFOLDER","Metadata in folder ");
define("_NEWARTICLES","New Article");
define("_NAME","Name");
define("_OF","of");
define("_PASSWORD","Password");
define("_PAGE","Page");
define("_READCOMMENT","Read Comment");
define("_READARTICLE","Read Article");
define("_SUBFOLDERON","Sub Folder ");
define("_USERLOGIN","User Login");
define("_USERID","User ID");
define("_SUBJECT","Subject");
define("_BY","by");
define("_PUBLISHERID","Publisher ID");
define("_PUBLISHERNAME","Publisher Name");
define("_PUBLISHERCITY","City");
define("_PUBLISHERNETWORK","Network");
define("_PUBLISHERHUBID","HUB ID");
define("_PUBLISHERSERIALNUMBER","Publisher Serial Number");
define("_PUBLISHERNETWORK","Publisher Network");
define("_PUBLISHERID","Publisher ID");
define("_PUBLISHERAPP","Application Code");
define("_PUBLISHERTYPE","Publisher Type");
define("_PUBLISHERNAME","Publisher Name");
define("_PUBLISHERORGNAME","Organization Name");
define("_PUBLISHERCONTYPE","Connection Type");
define("_PUBLISHERHOSTNAME","Host Name / Server Name");
define("_PUBLISHERIPADDRESS","IP Address of The Server");
define("_PUBLISHERCONTACTNAME","Contact Name");
define("_PUBLISHERADDRESS","Address");
define("_PUBLISHERCITY","City");
define("_PUBLISHERREGION","Region");
define("_PUBLISHERCOUNTRY","Country");
define("_PUBLISHERPHONE","Phone");
define("_PUBLISHERFAX","Fax");
define("_PUBLISHERADMINEMAIL","Administrator E-mail");
define("_PUBLISHERCKOEMAIL","CKO E-mail");
define("_PUBLISHERPROPERTY","Property");
define("_PROPERTYVALUE","Value");
define("_VERIFICATION","Verification");
define ("_REGISTRATION_ERROR_VERIFICATION", "Verification code is invalid<br/>");
define("_CONFIRMDOWNLOAD","Live CD !!! If you failed, file doen not available in this CD.Contact the publisher that issued current content.");
?>