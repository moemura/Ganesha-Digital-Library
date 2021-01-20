<?php 
/***************************************************************************
                         /module/organization/function.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function organization_not_exist() {
	global $gdl_folder;
	$content.=_ORGANIZATIONFOLDER." <b>/Organization/</b> "._DOESNOTEXISTDOYOUWANTTOCREATE." <a href='./gdl.php?mod=organization&amp;op=create'>"._YES."</a>";
	return $content;
}

function create_organization() {
	global $gdl_folder;
	$folder["name"]="Organization";
	$folder["parent"]=0;
	if ($gdl_folder->add($folder)) {
		$content.="<b>"._ORGANIZATIONCREATED."</b><br>";
		$content.=list_of_organization();
	}
	else
		$content.="<b>"._ORGANIZATIONCREATEFAILED."</b>";
		
	return $content;	
}

function list_of_organization() {
	global $gdl_folder,$gdl_db,$gdl_repeater;
	
	require_once("./class/repeater.php");
	$grid=new repeater();
	
	$organization_node=$gdl_folder->check_folder("Organization",0);
	$dbres=$gdl_db->select("folder","folder_id,name","parent=".$organization_node,"name","ASC");
	
	$header[1]="No";
	$header[2]=_ORGANIZATIONNAME;
	$header[3]=_ACTION;
	$no=1;
	while ($row=mysqli_fetch_array($dbres)) {
		$field[1]=$no;
		$field[2]=$row["name"];
		$field[3]="<a href='./gdl.php?mod=organization&amp;op=edit&amp;id=".$row["folder_id"]."'>"._EDIT."</a> - <a href='./gdl.php?mod=organization&amp;op=delete&amp;del=confirm&amp;id=".$row["folder_id"]."'>"._DELETE."</a>";
		$item[]=$field;
		$no++;
	}
	
	$colwidth[1] = "10px";
	$colwidth[2] = "75px";
	$colwidth[3] = "75px";
	
	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	$content="<a href='./gdl.php?mod=organization&amp;op=add'>"._ADD."</a>";
	$content.=$grid->generate();
	
	return $content;	
}

function add_organization_form($action="") {
	global $gdl_form,$gdl_folder,$gdl_sys,$frm;
	
	$gdl_form->set_name("add_organization");
	if (!$action){
		$gdl_form->action="./gdl.php?mod=organization&amp;op=add";
		$submitbutton=_ADD;
		$title=_ORGANIZATIONADDNEW;
	}
	else
	{	$gdl_form->action=$action;
		$submitbutton=_EDIT;
		$title=_ORGANIZATIONEDITING;
	}
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>$title));
	
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[orgname]",			
				"value"=>$frm["orgname"],
				"text"=>_ORGANIZATIONNAME,   /***********/
				"required"=>true,
				"size"=>50));

	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>"",
			"value"=>$submitbutton)); /*************/
	$content = $gdl_form->generate();
	return $content;	
}

function insert_organization() {
	global $frm,$gdl_folder;
	
	$content ='';
	$organization_node=$gdl_folder->check_folder("Organization",0);
	if (preg_match("/err/",$organization_node))
		$content.=_ADDORGANIZATIONFAILED;
	else {
		if (!preg_match("/err/",$gdl_folder->check_folder($frm["orgname"],$organization_node)))
			$content.=_ADDORGANIZATIONFAILED;
		else {
			$folder["name"]=$frm["orgname"];
			$folder["parent"]=$organization_node;
			if (!$gdl_folder->add($folder))
				$content.=_ADDORGANIZATIONFAILED;
			else
				$content.=_ADDORGANIZATIONSUCCESS;
		}
	}
	
	return $content;	
}

function edit_organization() {
	global $frm,$gdl_folder,$id;
	
	$content ='';
	$organization_node=$gdl_folder->check_folder("Organization",0);
	if (preg_match("/err/",$organization_node))
		$content.=_EDITORGANIZATIONFAILED;
	else {
		$org_node=$gdl_folder->check_folder($frm["orgname"],$organization_node);
		if (!preg_match("/err/",$org_node) && $id <> $org_node)
			$content.=_EDITORGANIZATIONFAILED;
		else {
			$folder["node"]=$id;
			$folder["name"]=$frm["orgname"];
			$folder["parent"]=$organization_node;
			if (!$gdl_folder->edit_property($folder))
				$content.=_EDITORGANIZATIONFAILED;
			else
				$content.=_EDITORGANIZATIONSUCCESS;
		}
	}
	
	return $content;	
}


