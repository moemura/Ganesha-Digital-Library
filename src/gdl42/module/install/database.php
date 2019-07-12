<?php

/***************************************************************************
                         /module/install/database.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
 

if (eregi("database.php",$_SERVER['PHP_SELF'])) {
    die();
}

include "./module/install/function.php";
$frm=$_POST["frm"];

if (!file_exists("./files/misc/install.lck")) {	
	if ($gdl_form->verification($frm) && $frm) {
		$main.="<p>".save_configuration()."</p>";
		$main.="<p>".view_configuration()."</p>";
		$main.="<p><a href='./gdl.php?mod=install&amp;op=table'>"._TABLECONF."</a></p>";
	} else {
		if (file_exists("./config/db.php")) {
			include "./config/db.php";
			foreach ($gdl_db_conf as $idx => $val) {
			  if ($val)
				$frm[$idx]=$val;
			}
		}
		$main.="<p>"._DATABASEEXPL."</p>";
		$main.="<p>".database_form()."</p>";
	}
	
}
else
	$main.="<p><b>"._ALREADYINSTALLED."</b></p>";
$gdl_content->main = gdl_content_box($main,_INSTALLATION." ("._DATABASECONF.")");
$gdl_content->path="<a href=\"./index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install\">"._INSTALLATION."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install&amp;op=fileperms\">"._CHECKFILEPERMS."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install&amp;op=database\">"._DATABASECONF."</a>";
?>