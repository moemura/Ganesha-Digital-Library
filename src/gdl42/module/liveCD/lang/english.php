<?php

if (preg_match("/english.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

define("_FOLDERCHOICE","Folder Choice");
define("_JOBVIEW","Job View");
define("_EXPORTFILE","Live CD File");
define("_NOMOR","Nomor");
define("_FOLDERNAME","Folder Name");
define("_FOLDERCOUNT","Record Count");
define("_FOLDERACTION","Add List Job");
define("_JOBFOLDER","Add");
define("_FOLDERNODE","Folder Node");
define("_JOBACTION","Execute");
define("_JOBRESET","Reset");
define("_JOBREMOVE","RemoveNode");
define("_CONNINFO","Entity");
define("_CONNVALUE","Value");
define("_FILENAME","File Name");
define("_FILESIZE","Size");
define("_ACTION","Action");
define("_DELETE","Delete");
define("_TITLESTARTLIVECD","Confirmation building Live CD");
define("_STARTBUILDLIVECD","ExecuteBuildLiveCD");
define("_LIVECDINCLUDEFILE","Do you want include relation file ?");
define("_LIVECDINCLUDEFOLKSONOMY","Do you want include folksonomy box?");

define("_LIVECDWARNINGCOMMENT","Live CD !!! Link for comment is not activated.");
define("_LIVECDWARNINGREADCOMMENT","Live CD !!! Link for read comment is not activated.");
define("_LIVECDWARNINGBOOKMARK","Live CD !!! Link for bookmark is not activated.");


define("_WELCOMELIVECD","Welcome.");
define("_MODULEINFOLIVECD","This fiture will create your digital library collection become Live CD collection which can be access without server or database engine.");

define("_THEMECOLLECTION","Theme which support Live CD : ");
define("_CHANGETHEME","Change Theme");

define("_THEMESTATUS","Theme for Live CD is");
define("_THEMESTATUSCON","which has status");
define("_THEMESUPPORTLIVECD","support Live CD");
define("_THEMENOTSUPPORTLIVECD","does not suuport Live CD");
define("_THEMENOTE","If current theme does not support Live CD, Live CD creation will be canceled.");
define("_LIVECDSTEP","Step by step create Live CD");
define("_LIVECDSTEP1","Choose theme which support Live CD.");
define("_LIVECDSTEP2","Choose folder which you prefer.");
define("_LIVECDSTEP3","Push execution button to view information of Live CD configuration");
define("_LIVECDSTEP4","Make sure that configuration has been valid.");
define("_LIVECDSTEP5","Choose option whether relation file which coresponden with metadata will be included in the Live CD or yet.");
define("_LIVECDSTEP6","Push execution button if you have been sure with the configuration.");
define("_LIVECDSTEP7","List of liveCD file (liceCD-xxx.tar.gz) will be shown if Live CD operation have been accomplished.");
define("_LIVECDSTEP8","Click download lastest liveCD file or delete if you want to delete liveCD file.");
define("_LIVECDSTEP9","Default location for liveCD extracted file is <b>files/tmp/liveCD</b>.");
define("_LIVECDSTEP10","Move all file or folder in the <b>files/tmp/liveCD (extraction result)</b> to CD(Compact Disc), flashdisc, ext.");

define("_LISTFOLDER","List Folder of Digital Library");
define("_LISTJOBFOLDER","List Folder for Live CD Collection");
define("_LISTLIVECDFILE","List of Live CD File");
define("_CONFIRMATIONJOB","Pre-Execution Information Before Creating Live CD File.");
?>
