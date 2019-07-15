<?php

if (preg_match("/english.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

define("_ACTION","Action");
define("_ADDFOLDER","Add Folder");
define("_ADDNEWFOLDER","Add New Folder");
define("_CONFIRMATION","Confirmation");
define("_CHILD","Sub Folder");
define("_DISPLAYINGMETADATA","Displaying Metadata");
define("_DATE","Date");
define("_DELETEFOLDER","Delete Folder");
define("_DELETEMETADATA","Delete Metadata");
define("_DELETEFOLDERCONFIRMATION","Are you sure to delete this folder and all metadata inside");
define("_DELETEMETADATACONFIRMATION","Are you sure delete this metadata");
define("_EDIT","Edit");
define("_FOLDER","Folder");
define("_METADATAINFOLDER","Metadata in Folder");
define("_METADATAS","Metadata");
define("_METADATA","Metadata");
define("_MODE","Mode");
define("_NAME","Name");
define("_OF","of");
define("_OWNER","Owner");
define("_PAGE","Page");
define("_PROPERTY","Property");
define("_PARENT","Parent Folder");
define("_PROPERTYFOLDER","Folder Property");
define("_PROPERTYMETADATA","Metadata Property");
define("_SUBFOLDERON","Sub Folder");
define("_SUBMIT","Submit");
define("_TITLE","Identifier / Title");
define("_UPLOADMETADATA","Upload Metadata");
define("_WORKGROUP","Workgroup");
define("_YESDELETE","Yes, delete");
define("_RESET","Reset");
define("_EDITFOLDER","Edit Folder");
define("_BACK","Go Back");
define("_CANNOTDELETEFOLDERCONFIRMATION","This folder has sub folder, You can not delete it");
define("_MULTIVIEW","Multi view");
define("_SINGLEVIEW","Single view");
define("_MOVE","Move");
define("_DATEMODIFIED","Date Modified");
?>