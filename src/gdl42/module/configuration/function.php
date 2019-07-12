<?php 

/***************************************************************************
                         /module/configuration/function.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (eregi("function.php",$_SERVER['PHP_SELF'])) die();

function edit_server_form() {
	global $gdl_form,$gdl_publisher,$frm;
	
	if (!isset($frm)) {
		foreach ($gdl_publisher as $IdxGdlPublisher => $ValGdlPublisher) 
			$frm[$IdxGdlPublisher]=$ValGdlPublisher;
	}
	
	$gdl_form->set_name("edit_publisherserver");
	$gdl_form->action="./gdl.php?mod=configuration&amp;op=server";
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_SERVERCONF));
			
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[id]",			
				"value"=>$frm["id"],
				"text"=>_PUBLISHERID,
				"required"=>true,
				"size"=>50));
	
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[serialno]",			
				"value"=>$frm["serialno"],
				"text"=>_PUBLISHERSERIALNUMBER,
				"required"=>true,
				"size"=>50));						
				
	$key=array_keys(array("institution","personal","warnet"),strtolower($frm["type"]));		
	if (!$key)
		$key[0]=$frm["type"];
		
	$gdl_form->add_field(array(
				"type"=>"radio",
				"name"=>"frm[type]",
				"checked"=>array("Institution","Personal","Warnet"),/************/
				"value"=>"".$key[0],
				"required"=>true,
				"text"=>_PUBLISHERTYPE   /***********/
				));	
	
	$key=array_keys(array("dedicated","temporary"),strtolower($frm["connection"]));
	if (!$key)
		$key[0]=$frm["connection"];
		
	$gdl_form->add_field(array(
				"type"=>"radio",
				"name"=>"frm[connection]",
				"checked"=>array("Dedicated","Temporary"),/*************/
				"value"=>"".$key[0],
				"required"=>true,
				"text"=>_PUBLISHERCONTYPE   /***********/
				));	
	
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[apps]",			
				"value"=>$frm[apps],
				"text"=>_PUBLISHERAPP,   /***********/				
				"size"=>50));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[publisher]",			
				"value"=>$frm["publisher"],
				"text"=>_PUBLISHERNAME,   /***********/
				"required"=>true,
				"size"=>50));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[orgname]",			
				"value"=>$frm["orgname"],
				"text"=>_PUBLISHERORGNAME,   /***********/
				"size"=>50));

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[hostname]",			
				"value"=>$frm["hostname"],
				"text"=>_PUBLISHERHOSTNAME,   /***********/				
				"size"=>50));				
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[ipaddress]",			
				"value"=>$frm["ipaddress"],
				"text"=>_PUBLISHERIPADDRESS,   /***********/
				"size"=>50));

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[contact]",			
				"value"=>$frm["contact"],
				"text"=>_PUBLISHERCONTACTNAME,   /***********/
				"required"=>true,
				"size"=>50));			

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[address]",			
				"value"=>$frm["address"],
				"text"=>_PUBLISHERADDRESS,   /***********/
				"required"=>true,
				"size"=>50));

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[city]",			
				"value"=>$frm["city"],
				"text"=>_PUBLISHERCITY,   /***********/
				"required"=>true,
				"size"=>50));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[region]",			
				"value"=>$frm["region"],
				"text"=>_PUBLISHERREGION,   /***********/
				"required"=>true,
				"size"=>50));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[country]",			
				"value"=>$frm["country"],
				"text"=>_PUBLISHERCOUNTRY,   /***********/
				"required"=>true,
				"size"=>50));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[phone]",			
				"value"=>$frm["phone"],
				"text"=>_PUBLISHERPHONE,   /***********/				
				"size"=>50));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[fax]",			
				"value"=>$frm["fax"],
				"text"=>_PUBLISHERFAX,   /***********/
				"size"=>50));
				
$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[admin]",			
				"value"=>$frm["admin"],
				"text"=>_PUBLISHERADMINEMAIL,   /***********/
				"required"=>true,
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[cko]",			
				"value"=>$frm["cko"],
				"text"=>_PUBLISHERCKOEMAIL,   /***********/
				"required"=>true,
				"size"=>50));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[network]",			
				"value"=>$frm["network"],
				"text"=>_PUBLISHERNETWORK,   /***********/
				"required"=>true,
				"size"=>50));				

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[hubserver]",			
				"value"=>$frm["hubserver"],
				"text"=>_PUBLISHERHUBSERVER,   /***********/
				"required"=>true,
				"size"=>50));
				
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>"",
			"value"=>_EDIT)); /*************/
	$content = $gdl_form->generate("25%");
	return $content;
}

function edit_system_form() {
	global $gdl_form,$gdl_sys,$frm;
	
	if (!isset($frm)) {
		foreach ($gdl_sys as $IdxGdlSys => $ValGdlSys) 
			{
				if ($ValGdlSys===true)
					$frm[$IdxGdlSys]="true";
				elseif ($ValGdlSys===false)
					$frm[$IdxGdlSys]="false";
				else
					$frm[$IdxGdlSys]=$ValGdlSys;
				
			}
	}
	$themedir="./theme";
	$dirhandle=@opendir($themedir);
	if ($dirhandle) {
		while (false !== ($dir=readdir($dirhandle))) {
			if (is_dir($themedir."/".$dir) && $dir != "." && $dir != "..")
				$arrdir[$dir]=$dir;
		}
		closedir($dirhandle);
	}
	
	$lang=array("indonesian"=>_INDONESIA,
				"english"=>_ENGLISH);
	
	$gdl_form->set_name("edit_system");
	$gdl_form->action="./gdl.php?mod=configuration&amp;op=system";
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_SYSTEMCONF));

	$gdl_form->add_field(array(
				"type"=>"select",
				"name"=>"frm[theme]",
				"option"=>$arrdir,
				"value"=>$frm["theme"],
				"text"=>_THEME,
				"required"=>true));
			
	$gdl_form->add_field(array(
				"type"=>"select",
				"name"=>"frm[language]",
				"option"=>$lang,
				"value"=>$frm["language"],
				"text"=>_LANGUAGE,
				"required"=>true));
	
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[page_caching]",			
				"value"=>$frm["page_caching"],
				"text"=>_PAGECACHING,
				"required"=>true,
				"size"=>10));						
				
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[perpage_browse]",			
				"value"=>$frm["perpage_browse"],
				"text"=>_PERPAGEBROWSE,
				"size"=>10,
				"required"=>true));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[perpage_member]",			
				"value"=>$frm["perpage_member"],
				"text"=>_PERPAGEMEMBER,
				"size"=>10,
				"required"=>true));

				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[perpage_publisher]",			
				"value"=>$frm["perpage_publisher"],
				"text"=>_PERPAGEPUBLISHER,
				"size"=>10,
				"required"=>true));

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[perpage_request]",			
				"value"=>$frm["perpage_request"],
				"text"=>_PERPAGEREQUEST,
				"size"=>10,
				"required"=>true));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[perpage_discussion]",			
				"value"=>$frm["perpage_discussion"],
				"text"=>_PERPAGEDISCUSSION,
				"size"=>10,
				"required"=>true));

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[collection_folder]",			
				"value"=>$frm["collection_folder"],
				"text"=>_COLLECTIONFOLDER,
				"size"=>30,
				"required"=>true));

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[neon_name]",			
				"value"=>$frm["neon_name"],
				"text"=>_NEONNAME,
				"size"=>30,
				"required"=>true));

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[apps]",			
				"value"=>$frm["apps"],
				"text"=>_APPS,
				"size"=>30,
				"required"=>true));				

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[network]",			
				"value"=>$frm["network"],
				"text"=>_NETWORK,
				"size"=>30,
				"required"=>true));
	$gdl_form->add_field(array(
				"type"=>"select",
				"name"=>"frm[support_oai_dc]",
				"option"=>array(_NO,_YES),
				"value"=>$frm["support_oai_dc"],
				"text"=>_SUPPORTSTANDARD,
				"required"=>true));
	$gdl_form->add_field(array(
				"type"=>"radio",
				"checked"=>array("NODE"=>"Node","HUB"=>"Hub"),
				"name"=>"frm[role]",			
				"value"=>$frm["role"],
				"text"=>_ROLE,
				"required"=>true));				
	
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[application_signature]",			
				"value"=>$frm["application_signature"],
				"id"=>"random",
				"text"=>_APP_SIGNATURE." [<a href=\"javascript:generateRandom();\">Generate</a>]",
				"size"=>30,
				"required"=>false));	
				
	$gdl_form->add_field(array(
				"type"=>"radio",
				"checked"=>array("true"=>_PUBLIC,"false"=>"Member"),
				"name"=>"frm[public_download]",			
				"value"=>$frm["public_download"],
				"text"=>_ACCESSLEVEL,
				"required"=>true));
	
	$gdl_form->add_field(array(
				"type"=>"radio",
				"checked"=>array("win"=>"Windows","freebsd"=>"Freebsd", "debian"=>"Debian","linux"=>"Other Linux"),
				"name"=>"frm[os]",			
				"value"=>$frm["os"],
				"text"=>_OPERATINGSYSTEM,
				"required"=>true));

	$gdl_form->add_field(array(
				"type"=>"radio",
				"name"=>"frm[folder_refresh]",			
				"checked"=>array("true"=>_YES,"false"=>_NO),
				"value"=>$frm["folder_refresh"],
				"text"=>_FOLDERREFRESH,  
				"required"=>true));				
				
	$gdl_form->add_field(array(
				"type"=>"radio",
				"checked"=>array("true"=>_YES,"false"=>_NO),
				"name"=>"frm[activate_account]",			
				"value"=>$frm["activate_account"],
				"text"=>_ACTIVATEACCOUNT,
				"required"=>true));

	$gdl_form->add_field(array(
				"type"=>"radio",
				"checked"=>array("true"=>_YES,"false"=>_NO),
				"name"=>"frm[index_cdsisis]",			
				"value"=>$frm["index_cdsisis"],
				"text"=>_INDEXCDSISIS,
				"required"=>true));

	$gdl_form->add_field(array(
				"type"=>"radio",
				"checked"=>array("true"=>_YES,"false"=>_NO),
				"name"=>"frm[remote_login]",			
				"value"=>$frm["remote_login"],
				"text"=>_REMOTELOGIN,
				"required"=>true));	
				
	$hidden_arr=array("timeout","home","index","modul_list","folder_separator","repository_dir","metadata_per_dir",
					  "sync_maxsize_gzfile","sync_repository_name","sync_repository_id","sync_hub_server_name","sync_hub_server_port",
					  "sync_use_proxy","sync_proxy_server_address","sync_proxy_server_port","sync_oai_script","sync_opt_script",
					  "sync_harvest_node","sync_harvest_from","sync_harvest_until","sync_harvest_set","sync_count_records",
					  "sync_show_response","sync_harvest_node","sync_fragment_size","folks_active_option","folks_min_frekuensi",
					  "folks_token_per_abjad","folks_max_size_font","folks_min_size_font","folks_bg_color","folks_font_color");

	foreach ($hidden_arr as $valHidden) {
		$gdl_form->add_field(array(
				"type"=>"hidden",
				"name"=>"frm[".$valHidden."]",
				"value"=>$frm[$valHidden]
		));
	}				  
					
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>"",
			"value"=>_EDIT)); 
	$content = $gdl_form->generate("30%");
	
	$js = "\n<script language=\"javascript\"> function generateRandom(){
				document.getElementById('random').value = \"".randomGenerator(30)."\";
			}</script>\n";
	return $content.$js;
}


// Source code : http://www.phpfreaks.com/quickcode/Megapunk_-_Random_Password_Generator/71.php
function randomGenerator($totalChar){
	// *************************
	// Random Password Generator
	// *************************
	$totalChar = ($totalChar < 7)?7:$totalChar;
	
	// salt to select chars from
	$salt = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
	
	// start the random generator
	srand((double)microtime()*1000000);
	
	// set the inital variable
	$password="";
	
	// loop and create password
	for ($i=0;$i<$totalChar;$i++)
		$password = $password . substr ($salt, rand() % strlen($salt), 1);
	
	return $password;
}

function write_file_publisher() {
	global $frm,$gdl_publisher2;
	
	if ($gdl_publisher2->save_configuration($frm))
		$message=_SERVERCONFSAVE;
	else
		$message=_SERVERCONFSAVEFAILED;
		
	return $message;
}

function write_file_system() {
	global $frm;
	
	$file="config/system.php";
	$filehandle=fopen($file,"w");
	if ($filehandle) {
		$str_system="<?
";
		foreach ($frm as $idxFrm => $valFrm) {
			if ($valFrm=="true") {				
				$str_system.="\$gdl_sys[\"".$idxFrm."\"]=true;
";
} elseif ($valFrm=="false") {
				$str_system.="\$gdl_sys[\"".$idxFrm."\"]=false;
";	
} else {
				$str_system.="\$gdl_sys[\"".$idxFrm."\"]=\"".$valFrm."\";
";
}		}

		$str_system.="?>";

		if (fputs($filehandle,$str_system)) {
			$content.="<b>"._SYSTEMCONFSAVE."</b>";
		}
		fclose($filehandle);
	} else
		$content.="<b>"._CANNOTOPENFILE."</b>";
	
	return $content;
}


