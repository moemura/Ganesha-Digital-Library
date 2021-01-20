<?php 
/***************************************************************************
                         /module/publisher/function.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function display_publisher($searchkey) {
	global $gdl_content,$gdl_publisher2,$gdl_sys;

	require_once("./class/repeater.php");
	
	$page=isset($_GET['page']) ? $_GET['page'] : null;
	$page = isset($_GET['page']) ? $_GET['page'] : null;
	if (!isset($page)){
	 	$page = 0 ;
	}else{
		$page = $page-1;
	}

	$urlsearch = '';
	if (!empty($searchkey))
		$urlsearch="&amp;searchkey=$searchkey";

	
	//if (empty ($searchkey)) {
		$limit = $page * $gdl_sys['perpage_publisher'];
		$limitfinal = $limit.",$gdl_sys[perpage_publisher]";
	//} else {
	//	$limitfinal="";
	//}
	$publisherdata=$gdl_publisher2->get_list($searchkey,"");
	$total=count($publisherdata);
	
	$publisherdata=$gdl_publisher2->get_list($searchkey,$limitfinal);
	$count=count($publisherdata);
	
	$form = '';
	if (is_array($publisherdata)) {
		$grid=new repeater();
		
		$header[1] = _NO;
		$header[2] = _PUBLISHERID;
		$header[3] = _PUBLISHERNAME;
		$header[4] = _PUBLISHERCITY;
		$header[5] = _PUBLISHERNETWORK;
		$header[6] = _PUBLISHERHUBID;
		$header[7] = _PUBLISHERACTION;
		
		$page = $page + 1;
		$pages = ceil($total/$gdl_sys['perpage_publisher']);
		$start = 1 + (($page-1) * $gdl_sys['perpage_publisher']);
		$url = "./gdl.php?mod=publisher&amp;";
		$j=$limit+1;
		
		$item = array();
		foreach ($publisherdata as $key => $val) {
			$field[1] = $j;
			$field[2] = "<a href=\"./index.php?mod=publisher&amp;op=detail&amp;id='".$publisherdata[$key]["ID"]."'\">".$publisherdata[$key]["ID"]."</a>";
			$field[3] = $publisherdata[$key]["NAME"];
			$field[4] = $publisherdata[$key]["CITY"];
			$field[5] = $publisherdata[$key]["NETWORK"];
			$field[6] = $publisherdata[$key]["HUBSERVER"];
			$field[7] = "<a href=\"./gdl.php?mod=publisher&amp;op=edit&amp;id='".$publisherdata[$key]["ID"]."'\">"._PUBLISHEREDIT."</a> - <a href=\"./gdl.php?mod=publisher&amp;op=delete&amp;id='".$publisherdata[$key]["ID"]."'&amp;del=confirm\">"._PUBLISHERDELETE."</a> - <a href=\"./gdl.php?mod=publisher&amp;op=print&amp;id='".$publisherdata[$key]["ID"]."'\">"._PRINTCONFIGURATION."</a> ";
			$j++;
			$item[] = $field;
		}
		
		$colwidth[1] = "10px";
		$colwidth[2] = "75px";
		$colwidth[3] = "75px";
		$colwidth[4] = "15px";
		$colwidth[5] = "75px";
		$colwidth[6] = "75px";
		$colwidth[7] = "75px";
		
		$grid->header = $header;
		$grid->item = $item;
		$grid->colwidth = $colwidth;
		
		$pref_nav = '';
		if ($page==1){
			$pref_nav = "<a href=\"$url"."page=1$urlsearch\">&laquo; Prev</a>";
		} else{
			$prev_page = $page-1;
			$pref_nav = "<a href=\"$url"."page=$prev_page$urlsearch\">&laquo; Prev</a>";
		}

		// next navigator
		if ($page==$pages){
			$next_nav = "<a href=\"$url"."page=$page$urlsearch\">Next &raquo;</a>";
		}else{
			$next_page = $page+1;
			$next_nav = "<a href=\"$url"."page=$next_page$urlsearch\">Next &raquo;</a>";
		}
		
		$end = $start + $count - 1;
		$form = "<p class=\"contentlisttop\">"._PUBLISHERDISPLAYING." $start - $end "._OF." total $total Publisher<br/>";
		//if (empty ($searchkey))
			$form .= "<span><strong>$pref_nav</strong> | <strong>$next_nav</strong></span></p>";
		
		$form.= "<a href='index.php?mod=publisher&amp;op=add'>"._PUBLISHERADD."</a>";
		$form.= $grid->generate();
		
		if($pages<>""){
			$page_nav = _PAGE." : ";
			$i = 1;
			while ($i <= $pages) {
				if ($i==$page){
					$page_nav .= "<b>[$i]</b> ";
				}else{
					$page_nav .= "<a href=\"$url"."page=$i$urlsearch\">$i</a> ";
				}
				$i++; 
			}
		}
		//if (empty ($searchkey))
			$form .= "<p class=\"contentlistbottom\">$page_nav</p>\n";
	}
	return $form;	
}

function search_publisher_form ($action="")
{
	global $gdl_form;

	$gdl_form->set_name("search");
	
	if (!$action)
		$gdl_form->action="./gdl.php?mod=publisher";
	else
		$gdl_form->action=$action;
		
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"searchkey",			
				"value"=>isset($_POST['searchkey']) ? "$_POST[searchkey]" : '',
				"text"=>_SEARCHPUBLISHER,
				"size"=>30));
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>"",
			"value"=>_PUBLISHERSEARCH));			

	$content = $gdl_form->single_line("30%");	
	return $content;
}

function display_property($PUBLISHER_ID) {
	global $gdl_publisher2;
	
	require_once("./class/repeater.php");
	
	$grid=new repeater();
	
	$property=$gdl_publisher2->get_property($PUBLISHER_ID);
	
	$header[1] = _PUBLISHERPROPERTY;
	$header[2] = _PROPERTYVALUE;
	
	foreach ($property as $key => $val) {
		$field[1] = $key;
		$field[2] = $val;
		$item[] = $field;
	}
	
	$colwidth[1] = "75px";
	$colwidth[2] = "75px";
	
	$grid->header = $header;
	$grid->item = $item;
	$grid->colwidth = $colwidth;
	
	return $grid->generate();	
}

function display_print_configuration($PUBLISHER_ID) {
	global $gdl_publisher2,$gdl_publisher;
	
	$property=$gdl_publisher2->get_property($PUBLISHER_ID);
	
	$content = "<pre>";
	$content .= _CONFIGURATIONFOR." ".$PUBLISHER_ID."\n";
	$content .= _GENERATEDBY." ".$gdl_publisher['id']."\n";
	$content .= _DATE." : ".date("Y-m-d H:i:s")."\n\n";
	$content .= _IFUSINGGDL42."\n";
	$content .= _PUBLISHERID." = \"".$PUBLISHER_ID."\";\n";
	$content .= _PUBLISHERSERIALNUMBER." = \"".$property[_PUBLISHERSERIALNUMBER]."\";\n";	
	$content .= _PUBLISHERTYPE." = \"".$property[_PUBLISHERTYPE]."\";\n";
	$content .= _PUBLISHERCONTYPE." = \"".$property[_PUBLISHERCONTYPE]."\";\n";
	$content .= _PUBLISHERAPP." = \"".$property[_PUBLISHERAPP]."\";\n";
	$content .= _PUBLISHERNAME." = \"".$property[_PUBLISHERNAME]."\";\n";
	$content .= _PUBLISHERORGNAME." = \"".$property[_PUBLISHERORGNAME]."\";\n";
	$content .= _PUBLISHERHOSTNAME." = \"".$property[_PUBLISHERHOSTNAME]."\";\n";
	$content .= _PUBLISHERIPADDRESS." = \"".$property[_PUBLISHERIPADDRESS]."\";\n";
	$content .= _PUBLISHERCONTACTNAME." = \"".$property[_PUBLISHERCONTACTNAME]."\";\n";
	$content .= _PUBLISHERADDRESS." = \"".$property[_PUBLISHERADDRESS]."\";\n";
	$content .= _PUBLISHERCITY." = \"".$property[_PUBLISHERCITY]."\";\n";
	$content .= _PUBLISHERREGION." = \"".$property[_PUBLISHERREGION]."\";\n";
	$content .= _PUBLISHERCOUNTRY." = \"".$property[_PUBLISHERCOUNTRY]."\";\n";
	$content .= _PUBLISHERPHONE." = \"".$property[_PUBLISHERPHONE]."\";\n";
	$content .= _PUBLISHERFAX." = \"".$property[_PUBLISHERFAX]."\";\n";
	$content .= _PUBLISHERADMINEMAIL." = \"".$property[_PUBLISHERADMINEMAIL]."\";\n";
	$content .= _PUBLISHERCKOEMAIL." = \"".$property[_PUBLISHERCKOEMAIL]."\";\n";
	$content .= _PUBLISHERNETWORK." = \"".$property[_PUBLISHERNETWORK]."\";\n";
	$content .= _PUBLISHERHUBSERVER." = \"".$property[_PUBLISHERHUBSERVER]."\";\n\n";
	$content .= _IFUSINGGDL4."\n";
	$content .= _STARTCOPY."\n";
	$content .= "\$DC_PUBLISHER_ID=\"".$PUBLISHER_ID."\";\n";
	$content .= "\$DC_PUBLISHER_SERIALNO=\"".$property[_PUBLISHERSERIALNUMBER]."\";\n";	
	$content .= "\$DC_PUBLISHER_TYPE=\"".$property[_PUBLISHERTYPE]."\";\n";
	$content .= "\$DC_PUBLISHER_CONNECTION=\"".$property[_PUBLISHERCONTYPE]."\";\n";
	$content .= "\$DC_PUBLISHER_APPS=\"".$property[_PUBLISHERAPP]."\";\n";
	$content .= "\$DC_PUBLISHER=\"".$property[_PUBLISHERNAME]."\";\n";
	$content .= "\$DC_PUBLISHER_ORGNAME=\"".$property[_PUBLISHERORGNAME]."\";\n";
	$content .= "\$DC_PUBLISHER_HOSTNAME=\"".$property[_PUBLISHERHOSTNAME]."\";\n";
	$content .= "\$DC_PUBLISHER_IPADDRESS=\"".$property[_PUBLISHERIPADDRESS]."\";\n";
	$content .= "\$DC_PUBLISHER_CONTACT=\"".$property[_PUBLISHERCONTACTNAME]."\";\n";
	$content .= "\$DC_PUBLISHER_ADDRESS=\"".$property[_PUBLISHERADDRESS]."\";\n";
	$content .= "\$DC_PUBLISHER_CITY=\"".$property[_PUBLISHERCITY]."\";\n";
	$content .= "\$DC_PUBLISHER_REGION=\"".$property[_PUBLISHERREGION]."\";\n";
	$content .= "\$DC_PUBLISHER_COUNTRY=\"".$property[_PUBLISHERCOUNTRY]."\";\n";
	$content .= "\$DC_PUBLISHER_PHONE=\"".$property[_PUBLISHERPHONE]."\";\n";
	$content .= "\$DC_PUBLISHER_FAX=\"".$property[_PUBLISHERFAX]."\";\n";
	$content .= "\$DC_PUBLISHER_ADMIN=\"".$property[_PUBLISHERADMINEMAIL]."\";\n";
	$content .= "\$DC_PUBLISHER_CKO=\"".$property[_PUBLISHERCKOEMAIL]."\";\n";
	$content .= "\$DC_PUBLISHER_NETWORK=\"".$property[_PUBLISHERNETWORK]."\";\n";
	$content .= "\$DC_PUBLISHER_HUBSERVER=\"".$property[_PUBLISHERHUBSERVER]."\";\n";
	$content .= _ENDCOPY."\n";
	$content .= "</pre>";
	
	return $content;
}

function add_publisher_form($action="") {
	global $gdl_form,$gdl_publisher2,$gdl_sys,$frm;
	
	$gdl_form->set_name("add_publisher");
	if (!$action){
		$gdl_form->action="./index.php?mod=publisher&amp;op=add";
		$submitbutton=_ADD;
		$title=_PUBLISHERADDNEW;
	}
	else
	{	$gdl_form->action=$action;
		$submitbutton=_EDIT;
		$title=_PUBLISHEREDITING;
	}
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>$title));
	
	if ($frm['serialnumber'])
		$serialnumber=$frm['serialnumber'];
	else
		$serialnumber=$gdl_sys['neon_name']."-".date("Ymd-His");
		
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[serialnumber]",			
				"value"=>$serialnumber,
				"text"=>_PUBLISHERSERIALNUMBER,   /***********/
				"required"=>true,
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[network]",			
				"value"=>isset($frm['network']) ? "$frm[network]" : '',
				"text"=>_PUBLISHERNETWORK,   /***********/
				"required"=>true,
				"size"=>50));				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[ID]",			
				"value"=>isset($frm['ID']) ? "$frm[ID]" : '',
				"text"=>_PUBLISHERID,   /***********/
				"required"=>true,
				"size"=>50));
				
	$key=array_keys(array("INSTITUTION","PERSONAL","WARNET"), isset($frm["type"]) ? strtoupper($frm["type"]) : '');		
	$gdl_form->add_field(array(
				"type"=>"radio",
				"name"=>"frm[type]",
				"checked"=>array("Institution","Personal","Warnet"),/************/
				"value"=>isset($key[0]) ? "".$key[0] : '',
				"required"=>true,
				"text"=>_PUBLISHERTYPE   /***********/
				));				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[name]",			
				"value"=>isset($frm['name']) ? "$frm[name]" : '',
				"text"=>_PUBLISHERNAME,   /***********/
				"required"=>true,
				"size"=>50));				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[orgname]",			
				"value"=>isset($frm['orgname']) ? "$frm[orgname]" : '',
				"text"=>_PUBLISHERORGNAME,   /***********/
				"size"=>50));
				
	$key=array_keys(array("dedicated","temporary"), isset($frm["contype"]) ? strtolower($frm["contype"]) : null);	
	$gdl_form->add_field(array(
				"type"=>"radio",
				"name"=>"frm[contype]",
				"checked"=>array("Dedicated","Temporary"),/*************/
				"value"=>isset($key[0]) ? "".$key[0] : '',
				"required"=>true,
				"text"=>_PUBLISHERCONTYPE   /***********/
				));	
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[hostname]",			
				"value"=>isset($frm['hostname']) ? "$frm[hostname]" : '',
				"text"=>_PUBLISHERHOSTNAME,   /***********/				
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[ipserver]",			
				"value"=>isset($frm['ipserver']) ? "$frm[ipserver]" : '',
				"text"=>_PUBLISHERIPADDRESS,   /***********/
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[contact]",			
				"value"=>isset($frm['contact']) ? "$frm[contact]" : '',
				"text"=>_PUBLISHERCONTACTNAME,   /***********/
				"required"=>true,
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[address]",			
				"value"=>isset($frm['address']) ? "$frm[address]" : '',
				"text"=>_PUBLISHERADDRESS,   /***********/
				"required"=>true,
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[city]",			
				"value"=>isset($frm['city']) ? "$frm[city]" : '',
				"text"=>_PUBLISHERCITY,   /***********/
				"required"=>true,
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[region]",			
				"value"=>isset($frm['region']) ? "$frm[region]" : '',
				"text"=>_PUBLISHERREGION,   /***********/
				"required"=>true,
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[country]",			
				"value"=>isset($frm['country']) ? "$frm[country]" : '',
				"text"=>_PUBLISHERCOUNTRY,   /***********/
				"required"=>true,
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[phone]",			
				"value"=>isset($frm['phone']) ? "$frm[phone]" : '',
				"text"=>_PUBLISHERPHONE,   /***********/				
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[fax]",			
				"value"=>isset($frm['fax']) ? "$frm[fax]" : '',
				"text"=>_PUBLISHERFAX,   /***********/
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[adminemail]",			
				"value"=>isset($frm['adminemail']) ? "$frm[adminemail]" : '',
				"text"=>_PUBLISHERADMINEMAIL,   /***********/
				"required"=>true,
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[ckoemail]",			
				"value"=>isset($frm['ckoemail']) ? "$frm[ckoemail]" : '',
				"text"=>_PUBLISHERCKOEMAIL,   /***********/
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
?>