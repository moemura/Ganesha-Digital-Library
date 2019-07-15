<?php

/***************************************************************************
                         /module/install/table.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
 
 

if (preg_match("/table.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

include "./module/install/function.php";
$frm=$_POST["frm"];

if (!file_exists("./files/misc/install.lck")) {	
	if ($gdl_form->verification($frm) && $frm) {
		$main.="<p>".create_table()."</p>";
	} else {
		if (preg_match("/err/",$gdl_db->test_connection()))
			$main.="<p>"._DATABASECONNECTIONERROR."</p>";
		else {
			$main.="<p><b>"._DATABASECONNECTIONSUCCESS."</b></p>";
			$main.="<p>".table_configuration()."</p>";
			}
	}
	
}
else
	$main.="<p><b>"._ALREADYINSTALLED."</b></p>";
$gdl_content->main = gdl_content_box($main,_INSTALLATION." ("._TABLECONF.")");
$gdl_content->path="<a href=\"./index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install\">"._INSTALLATION."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install&amp;op=fileperms\">"._CHECKFILEPERMS."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install&amp;op=database\">"._DATABASECONF."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install&amp;op=table\">"._TABLECONF."</a>";
?>