<?php

/***************************************************************************
                         /module/install/data.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (eregi("table.php",$_SERVER['PHP_SELF'])) {
    die();
}

include "./module/install/function.php";
$frm=$_POST["frm"];

if (!file_exists("./files/misc/install.lck")) {	
	if ($gdl_form->verification($frm) && $frm) {
		if ($gdl_account->cek_password($frm["PASSWORD"],$frm["PASSWORDCONFIRM"]) && ($gdl_account->cek_mail($frm["EMAIL"])) && ($gdl_account->cek_existing_mail($frm["EMAIL"])))
			$main.="<p>".fill_data()."</p>";
		else{
			if (! ($gdl_account->cek_mail($frm['EMAIL']))) 
				$regerror = _REGISTRATION_ERROR_EMAIL;
			if (! ($gdl_account->cek_password ($frm['PASSWORD'], $frm['PASSWORDCONFIRM'])))
				$regerror .= _REGISTRATION_ERROR_PASSWORD;	
			if (! ($gdl_account->cek_existing_mail($frm['EMAIL']))) 
				$regerror .= _REGISTRATION_ERROR_EMAIL_EXIST;						
			
			$main = "<p>".$regerror."</p>\n";
			$main.="<p>"._FILLDATAMAIN."</p>";
			$main.="<p>".data_form()."</p>";
		}		
	} else {
		$main.="<p>"._FILLDATAMAIN."</p>";
		$main.="<p>".data_form()."</p>";
	}
	
}
else
	$main.="<p><b>"._ALREADYINSTALLED."</b></p>";
$gdl_content->main = gdl_content_box($main,_INSTALLATION." ("._FILLDATA.")");
$gdl_content->path="<a href=\"./index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install\">"._INSTALLATION."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install&amp;op=fileperms\">"._CHECKFILEPERMS."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install&amp;op=database\">"._DATABASECONF."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install&amp;op=table\">"._TABLECONF."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=install&amp;op=data\">"._FILLDATA."</a>";
?>