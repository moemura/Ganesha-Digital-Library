<?php
if (preg_match("/english.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

define("_DISPLAYINGMEMBER", "Record");
define("_OF", "of");
define("_MENBER", "Member");
define("_NO", "No.");
define("_NAME", "Full Name");
define("_ACCOUNT", "Account");
define("_LEVELGROUP", "Group Level");
define("_STATUS", "Status");
define("_ACTION", "Action");
define("_EDIT", "Edit");
define("_DELETE", "Delete");
define("_ACTIVE", "Active");
define("_NOACTIVE", "Disable");
define("_PAGE", "Page");
define("_DELETEMEMBER", "Delete Member");
define("_CONFIRMATION", "Confirmation");
define("_DELETEMEMBERCONFIRMATION", "Are you sure delete this member?");
define("_YESDELETE", "Yes, delete");
define("_USEREDIT", "Edit User's General Info");

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
define ("_TYPEOFUSER", "User Type");
define ("_REGISTRATION", "Registration");
define ("_SUBMIT", "Update");
define ("_RESET", "Back");
define ("_VALIDATION", "Activation Code");
define ("_UPDATESUCCESS", "Member has already updated");
define ("_UPDATE_ERROR_PASSWORD", "Password and Confirm Password are different");
define ("_ADDUSERSUCCESS","Add user success");
define ("_USER_SECURITY","User Level");
define ("_MEMBERMANAGEMENT", "User Administration");
define ("_SEARCHMEMBER", "Search");
define ("_SEARCH_USER_MAIL", "Search Username or E&ndash;mail");
define ("_ADDMEMBER","Add Member");
define ("_EDITMYPROFILE","Edit Profile");
define ("_USER_JOB","Job");
define ("_VERIFICATION","Verification");
define ("_REGISTRATION_ERROR_EMAIL", "Your e&ndash;mail is incorrect<br/>");
define ("_REGISTRATION_ERROR_PASSWORD", " Password and Password Confirmation are different<br/>");
define ("_REGISTRATION_ERROR_EMAIL_EXIST", "Account is already exist<br/>");
define ("_REGISTRATION_ERROR_VERIFICATION", "Verification code is wrong<br/>");
?>