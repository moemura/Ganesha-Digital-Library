<?php

if (preg_match("/english.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

define("_MAININSTALL","Welcome to Ganesha Digital Library (GDL) 4.2 installation page, now
					   we will configure GDL setting in order to make GDL 4.2 running in your server.
					   You must follow the instructions in every step of installation.");
define("_CHECKFILEPERMS","Files and folders permission");
define("_DIRECTORYPERMISSION","You must change the permission for the files and directories below so we can access and write the directory that needed by GDL 4.2");
define("_DATABASECONF","Database Configuration");
define("_DATABASEEXPL","Now, we want to configure database connection, make sure you can access the MySql server and you have a priviledge to creating database and the tables");
define("_DBNAME","Database Name");
define("_TABLEPREFIX","Table Prefix");
define("_SAVE","Save");
define("_SUCCESSWRITE","Success to write database configuration file ");
define("_SUCCESSWRITE","Fail to write database configuration file ");
define("_CONFIGURATIONNAME","Configuration Name");
define("_VALUE","Value");
define("_TABLECONF","Tables Configuration");
define("_DATABASECONNECTIONERROR","There's an error while trying to connect to MySql server. Make sure you fill the right connection configuration");
define("_DATABASECONNECTIONSUCCESS","Connected to MySql server");
define("_CREATETABLE","Now we will create table (and) database in MySql server, below is the name of table required by GDL 4.2");
define("_TABLENAME","Table Name");
define("_CREATEDATABASE","Do you want to create new database ?");
define("_CREATE","Create");
define("_CHOICE","Choice");
define("_CREATEDBSUCCESS","Success creating database");
define("_CREATEDBFAILED","Failed creating database");
define("_SELECTDBSUCCESS","Success selecting database");
define("_SELECTDBFAILED","Failed selecting database");
define("_CREATETABLESUCCESS","Success creating table");
define("_CREATETABLEFAILED","Failed creating table");
define("_FILLDATA","Fill data");
define("_FILLDATAMAIN","Now we will insert initial data into your GDL server, some data will be based on your input in the form below");
define("_ADMINISTRATORINFORMATION","Administrator Login Information");
define("_USER_ID","USER ID");
define ("_USER_EMAIL", "Email");
define ("_USER_PASSWD", "Password");
define ("_USER_PASSWD_CONFIRM", "Confirm Password");
define ("_USER_GENERAL", "General Information");
define ("_USER_MAIL", "E&ndash;mail");
define ("_USER_FULLNAME", "Full Name");
define ("_USER_ADDRESS", "Address");
define ("_USER_CITY", "City");
define ("_USER_COUNTRY","Country");
define ("_USER_INSTITUTION","Institution");
define ("_USER_ACCOUNT","Account");
define ("_USER_CODE","Code");
define ("_TYPEOFUSER", "Job");
define ("_REGISTRATION", "Registration");
define ("_SUBMIT", "Submit");
define ("_RESET", "Reset");
define ("_CANNOTOPENFILE","Cannot open file ");
define("_PUBLISHERID","Publisher ID");
define("_PUBLISHERNAME","Publisher Name");
define("_PUBLISHERCITY","City");
define("_PUBLISHERNETWORK","Network");
define("_PUBLISHERHUBID","HUB ID");
define("_PUBLISHERACTION","Action");
define("_PUBLISHEREDIT","Edit");
define("_PUBLISHERDELETE","Delete");
define("_PUBLISHERADD","Add");
define("_PUBLISHERDISPLAYING","List of Publisher");
define("_PUBLISHERSEARCH","Search");
define("_PUBLISHERMANAGEMENT","Publisher Management");
define("_PUBLISHERADDNEW","Add New Publisher");
define("_PUBLISHEREDITING","Edit Publisher");
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
define("_PUBLISHERHUBSERVER","HUB Server");
define("_SUCCESSADDLOGIN","Administrator login inserted successfully ");
define("_FAILEDADDLOGIN","Administrator login insertion failed");
define("_SUCCESSSAVECONFIGURATION","Server configuration (Publisher) successfully saved on ");
define("_SUCCESSINSERTPUBLISHER","Publisher information inserted successfully ");
define("_SUCCESSINSERTGROUPUSER","Group user information inserted successfully ");
define("_FAILEDINSERTGROUPUSER","Group user information insertion failed ");
define("_SUCCESSWRITEINSTALLLCK","Lock file (<b>./files/misc/install.lck</b>) written successfully");
define("_FAILEDWRITEINSTALLLCK","Lock file (<b>./files/misc/install.lck</b>) written failed, installation failed...please reinstall the GDL system");
define("_ALREADYINSTALLED","Lock file (<b>./files/misc/install.lck</b>) found, cannot access this module");
define("_FINISHED","Congratulation, the Ganesha Digital Libary 4.2 (GDL 4.2) installation process has been done, please using menu to access your GDL Engine");
define("_CANWRITE","is writeable");
define("_CANTWRITE","cannot writeable, please change the permission");
define ("_REGISTRATION_ERROR_EMAIL", "Your e&ndash;mail is incorrect<br/>");
define ("_REGISTRATION_ERROR_PASSWORD", " Password and Password Confirmation are different<br/>");
define ("_REGISTRATION_ERROR_EMAIL_EXIST", "Account is already exist<br/>");
?>