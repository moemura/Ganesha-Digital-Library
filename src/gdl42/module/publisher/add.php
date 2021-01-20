<?php
/***************************************************************************
                         /module/publisher/add.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/add.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

require_once("./module/publisher/function.php");
$frm = isset($_POST['frm']) ? $_POST['frm'] : null;
$main = '';
if ($gdl_form->verification($frm) && $frm) {
	$gdl_publisher2->add_new($frm['serialnumber'],$frm['network'],$frm['ID'],$frm['type'],$frm['name'],$frm['orgname'],$frm['contype'],$frm['hostname'],$frm['ipserver'],$frm['contact'],$frm['address'],$frm['city'],$frm['region'],$frm['country'],$frm['phone'],$frm['fax'],$frm['adminemail'],$frm['ckoemail']);
	$main = _ADDPUBLISHERSUCCESS;
	$main .= display_publisher(null);
	
} else
{
	$main .= add_publisher_form();
}

$main = gdl_content_box($main,_PUBLISHERADDNEW);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=publisher\">"._PUBLISHER."</a>";
?>