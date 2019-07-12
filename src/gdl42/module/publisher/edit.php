<?php

/***************************************************************************
                         /module/publisher/edit.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

$PUBLISHER_ID = str_replace('%20',' ',$_GET['id']);
$PUBLISHER_ID = str_replace('\\','',$PUBLISHER_ID);
$PUBLISHER_ID = str_replace('\'','',$PUBLISHER_ID);

require_once "./module/publisher/function.php";
/*Hiks...ud ngerancang supaya gampang, malah jadi repot.....*/
$frm=$_POST['frm'];
if ($gdl_form->verification($frm) && $frm) {
	$gdl_publisher2->update($frm,$PUBLISHER_ID);
	$main=_EDITPUBLISHERSUCCESS;	
	$main.=display_publisher($searchkey);
} else 
{
	if (!$frm) {
		$result = $gdl_publisher2->get_property($PUBLISHER_ID);
		$frm['serialnumber']=$result[_PUBLISHERSERIALNUMBER];
		$frm['network']=$result[_PUBLISHERNETWORK];
		$frm['ID']=$PUBLISHER_ID;
		$frm['type']=$result[_PUBLISHERTYPE];
		$frm['name']=$result[_PUBLISHERNAME];
		$frm['orgname']=$result[_PUBLISHERORGNAME];
		$frm['contype']=$result[_PUBLISHERCONTYPE];
		$frm['hostname']=$result[_PUBLISHERHOSTNAME];
		$frm['ipserver']=$result[_PUBLISHERIPADDRESS];
		$frm['contact']=$result[_PUBLISHERCONTACTNAME];
		$frm['address']=$result[_PUBLISHERADDRESS];
		$frm['city']=$result[_PUBLISHERCITY];
		$frm['region']=$result[_PUBLISHERREGION];
		$frm['country']=$result[_PUBLISHERCOUNTRY];
		$frm['phone']=$result[_PUBLISHERPHONE];
		$frm['fax']=$result[_PUBLISHERFAX];
		$frm['adminemail']=$result[_PUBLISHERADMINEMAIL];
		$frm['ckoemail']=$result[_PUBLISHERCKOEMAIL];
	} else {
		$frm=$_POST['frm'];
	}
	
	$action="./index.php?mod=publisher&amp;op=edit&amp;id='$PUBLISHER_ID'";
	$main=add_publisher_form($action);

}

$main = gdl_content_box($main,_PUBLISHEREDITING);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=publisher\">"._PUBLISHER."</a>";

?>