<?php
/***************************************************************************
                         /module/cdsisis/edit.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
require_once "./module/cdsisis/function.php";

$db_name=$_GET["db_name"];
$frm=$_POST['frm'];
if ($gdl_form->verification($frm) && $frm) {
	$main = insert_cdsisis($db_name);
	$main .= "<p>".list_cdsisis()."</p>";
} else 
{	
	if (!$frm) {
		require_once($gdl_isisdb->isisdbdir."/".$db_name."/owner.inc");
		foreach ($owner as $idxOwner => $valOwner) {
			$frm[$idxOwner]=$valOwner;			
		}	
	} 
	
	$action="./gdl.php?mod=cdsisis&amp;op=edit&amp;db_name=".$db_name;
	$main.="Folder : <b>".$gdl_isisdb->isisdbdir."/".$db_name."</b><br><br>";
	$main.=new_cdsisis($action);
	$main.="<br>"._FILES."<p>".list_cdsisis_files($db_name)."</p>";

}

$main = gdl_content_box($main,_EDITCDSISIS);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis\">"._CDSISIS."</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=cdsisis&amp;op=edit&amp;db_name=".$db_name."\">"._EDIT."</a>";

?>