<?php

if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

define("_CURRENTMETADATA","Current Metadata");
define("_DESCRIPTION","Description");
define("_EDIT","Edit");
define("_EXPERTISE","Profesional, Expertise, Researcher, etc");
define("_FOLDER","Folder");
define("_FILE","File");
define("_IMAGE","Image, Photo, Picture, dll");
define("_MODE","Access Mode");
define("_NEXT","Next");
define("_NEWMETADATAPROPERTY","Property / Profile New Metadata");
define("_MULTIMEDIA","Multimedia");
define("_OWNER","Owner");
define("_OTHERS","Others");
define("_ORGANIZATION","Organization, Institution, etc");
define("_RESET","Reset");
define("_STEP1","Step 1. Select Metadata Schema");
define("_STEP2","Step 2. Create / Update Metadata");
define("_STEP3","Step 3. Upload / Update File");
define("_SOURCEPATH","Source Path");
define("_SUBMIT","Submit");
define("_THISMETADATAHASBEENUPLOAD","This metadata has been upload");
define("_THISMETADATAHASBEENUPDATE","This metadata has been update");
define("_TITLE","Title");
define("_UPLOADOREDIT","Upload / Edit Metadata");
define("_UPLOADFAIL","Upload fail");
define("_UPLOADINFO","Your metadata have property as follow.");
define("_UPLOADNEFILE","Upload new file ...");
define("_CURRENTFOLDER","Current Folder");
define("_WHATSCHEMA","What do you want to upload ?");
define("_DIRECTORYERROR","You cannot upload metadata in current directory, you may upload to <a href='./gdl.php?mod=mydocs'>My Documents</a> directory");

?>