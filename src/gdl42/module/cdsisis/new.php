<?php
/***************************************************************************
                         /module/cdsisis/new.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
if (eregi("add.php",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/cdsisis/function.php");
$frm=$_POST['frm'];

if ($gdl_form->verification($frm) && $frm) {
	$main = insert_cdsisis($db_name);	
	$main .= "<p>".list_cdsisis()."</p>";
} else
{
	$main .= new_cdsisis($action);	
}

$main = gdl_content_box($main,_NEWCDSISIS);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis\">"._CDSISIS."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis&amp;op=new\">"._NEW."</a>";

?>