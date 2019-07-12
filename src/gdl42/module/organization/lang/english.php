<?php
if (eregi("english.php",$_SERVER['PHP_SELF'])) {
    die();
}

define("_ORGANIZATIONFOLDER","Organization's folder ");
define("_DOESNOTEXISTDOYOUWANTTOCREATE","does not exist. Do you want to create it now ?");
define("_ORGANIZATIONCREATED","Organization Created");
define("_ORGANIZATIONEXIST","Organization folder already exist");
define("_ORGANIZATIONCREATEFAILED","Organization Creation Failed");
define("_ORGANIZATIONNAME","Organization's name");
define("_ORGANIZATIONADDNEW","Add new Organization");
define("_ORGANIZATIONEDITING","Edit Organization");
define("_ADDORGANIZATIONFAILED","Failed to Add New Organization");
define("_ADDORGANIZATIONSUCCESS","Add Organization Success");
define("_EDITORGANIZATIONFAILED","Failed to Edit Organization");
define("_EDITORGANIZATIONSUCCESS","Edit Organization Success");
define("_DELETEORGANIZATIONCONFIRMATION","Are you sure you want to delete this Organization ? ");
define("_DELETEORGANIZATIONSUCCESS","Organization deletion success");
define("_DELETEORGANIZATIONFAILED","Organization deletion failed");
define("_YESSURE","Yes, I'm sure");
define("_CONFIRMATION","Deletion Confirmation");
define("_ACTION","Action");
define("_ADD","Add");
define("_EDIT","Edit");

?>