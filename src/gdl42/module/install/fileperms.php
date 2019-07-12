<?php


/***************************************************************************
                         /module/install/fileperms.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
 
if (eregi("fileperms.php",$_SERVER['PHP_SELF'])) {
    die();
}

include "./module/install/function.php";

if (!file_exists("./files/misc/install.lck")) {	
	$main.=checking_dir();
	if (!ereg(_CANTWRITE,$main))
		$main.="<p><a href='./gdl.php?mod=install&amp;op=database'>"._DATABASECONF."</a></p>";
}
else
	$main.="<p><b>"._ALREADYINSTALLED."</b></p>";
$gdl_content->main = gdl_content_box($main,_INSTALLATION." ("._CHECKFILEPERMS.")");
$gdl_content->path="<a href=\"./index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install\">"._INSTALLATION."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install&amp;op=fileperms\">"._CHECKFILEPERMS."</a>";
?>