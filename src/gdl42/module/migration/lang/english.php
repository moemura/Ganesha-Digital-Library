<?php

if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

define("_FOLDER","Folder");
define("_GDL40DATABASECONFIG","GDL 4.0 DatabaseConfiguration");
define("_METADATA","Metadata");
define("_MIGRATIONNOTE","Untuk merubah konfigurasi diatas, edit file ./module/migration/conf.php");
define("_FILE","File");
define("_CONFIGURATION","Configuration");
define("_USER","User");
define("_PUBLISHER","Publisher");
define("_MIGRATIONSTEPS","Migration Steps");
define("_MIGRATIONCONF","Migration Configuration");
define("_USERNAME","Username");
define("_HOST","Hostname");
define("_PASSWORD","Password");
define("_DBNAME","Database Name");
define("_EDIT","Edit");
define("_SYSTEMCONFSAVE","Configuration saved");
define("_TRYCONNECT","Try connection to data source");
define("_PLEASEWAIT","Please, wait .... ");
define("_LOCK","Your database has been locked for migration.<br/>
		You can unlock it by deleting ");
define("_NOWLOCK","Your database is now locked for future migration.<br/>
				You can unlock it by deleting ");	
define("_RELATION","Relation");
define("_SELECTFILE","Please copy files that needed by metadata from GDL 4.0 into the folder <b>./files</b> in GDL 4.2, please note that the folder structure must be same with the source");
define("_FILESORFOLDER","Folders / Files");
define("_SIZE","Size");
define("_LOCKFILE","Please note that you MUST lock this files migration feature after you copied all of the relation files that needed by metadata, click <a href='./gdl.php?mod=migration&amp;op=files&amp;lock=yes'>here</a> to lock");
define("_ACCESSLOG2","Access Log");
?>