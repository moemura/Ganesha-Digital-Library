<?php

if (eregi("english.php",$_SERVER['PHP_SELF'])) {
    die();
}

//penambahan oleh benirio
define("_USER_ID","USER ID");
define ("_USER_EMAIL", "Email");
define ("_USER_PASSWD", "Password");
define ("_USER_PASSWD_CONFIRM", "Confirm Password");
define ("_USER_GENERAL", "General");
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
define ("_REGISTRATIONNOTE", "Please complete the following form. <br/> Note: * required. 
			<font color=#FF0000>red</font> = invalid / incomplete");
define ("_REGISTRATION_SUCCESS", "Please <a href=\"./gdl.php?mod=register&amp;op=activate\">acitvate</a> your Account. <br/> Activation code will be sent using e-mail <br/> If fail, please contact administrator ($gdl_publisher[admin])."); 
define ("_REGISTRATION_ADMIN", "Contact the administrator ($gdl_publisher[admin]) to acitvate your account");
define ("_REGISTRATION_FAIL", "Registration Fail. <br/> Please contact administrator ($gdl_publisher[admin])");								
define ("_REGISTRATION_ERROR_EMAIL", "Your e&ndash;mail is incorrect<br/>");
define ("_REGISTRATION_ERROR_PASSWORD", " Password and Password Confirmation are different<br/>");
define ("_REGISTRATION_ERROR_EMAIL_EXIST", "Account is already exist<br/>");
define ("_REGISTRATION_ERROR_VERIFICATION", "Verification code is wrong<br/>");
define ("_ACTIVATE", "Activate");
define ("_ACTIVATENOTE", "Please enter your Account and Activation Code to activate your account.<br/>
			Note: * required. 
			<font color=#FF0000>red</font> = invalid / incomplete");
define ("_ACTIVATE_TITLE", "Activate Account ");
define("_ACTIVATEFAIL","Your ID or Activation Code is wrong.<br/>Please re-type your ID and Activation Code...");
define("_ACTIVATESUCCESS", "Your ID is already active");
define ("_USER_SECURITY","User Level");
define ("_USER_JOB","Job");
define ("_VERIFICATION","Verification");
?>