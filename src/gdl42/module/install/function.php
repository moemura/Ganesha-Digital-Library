<?php 

/***************************************************************************
                         /module/install/function.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function checking_dir() {
	$dirfiles="./files";
	$dirconfig="./config";
	$dirhandle=opendir($dirfiles);
	$content.=_DIRECTORYPERMISSION."<br/><br/>";
	if (is_writable($dirfiles))
		$canwrite=_CANWRITE;
	else
		$canwrite=_CANTWRITE;
		
	$content.="folder <b>".$dirfiles."</b> $canwrite<br/>";
	
	if (is_writable("./bin"))
		$canwrite=_CANWRITE;
	else
		$canwrite=_CANTWRITE;
	$content.="folder <b>./bin</b> $canwrite<br/>";
	
	if (is_writable("./files/tmp/indexing"))
		$canwrite=_CANWRITE;
	else
		$canwrite=_CANTWRITE;
	$content.="folder <b>./files/tmp/indexing</b> $canwrite<br/>";
	
	if (is_writable($dirconfig))
		$canwrite=_CANWRITE;
	else
		$canwrite=_CANTWRITE;
	$content.="folder <b>".$dirconfig."</b> $canwrite";
	
	if ($dirhandle) {
		while (false !== ($dirname=readdir($dirhandle))) {
			if ($dirname != "." && $dirname != "..") {
					if (is_writable($dirfiles."/".$dirname))
						$canwrite=_CANWRITE;
					else
						$canwrite=_CANTWRITE;
					$content.="<br/>folder <b>".$dirfiles."/".$dirname."</b> $canwrite";				
			}
			
		}
	}
	
	closedir($dirhandle);
	if (is_writable("./module/migration/conf.php"))
		$canwrite=_CANWRITE;
	else
		$canwrite=_CANTWRITE;
	$content.="<br/>file <b>./module/migration/conf.php</b> $canwrite";
	
	if (is_writable("./module/accesslog/conf.php"))
		$canwrite=_CANWRITE;
	else
		$canwrite=_CANTWRITE;
	$content.="<br/>file <b>./module/accesslog/conf.php</b> $canwrite";

	$dirhandle=opendir($dirconfig);
	if ($dirhandle) {
		while (false !== ($filename=readdir($dirhandle))) {
			if ($filename != "." && $filename != ".." && !preg_match("/type/i",$filename)) {
				if (is_writable($dirconfig."/".$filename))
					$canwrite=_CANWRITE;
				else
					$canwrite=_CANTWRITE;
				$content.="<br/>file <b>".$dirconfig."/".$filename."</b> $canwrite";
			}
			
		}
	}	
	
	closedir($dirhandle);
	return $content;
}

function database_form() {
	global $gdl_form,$gdl_db,$frm;
	
	$gdl_form->set_name("configure_database");
	$gdl_form->action="./gdl.php?mod=install&amp;op=database";
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_DATABASECONF));
			
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[host]",			
				"value"=>$frm["host"],
				"text"=>"Host",
				"required"=>true,
				"size"=>30));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[uname]",			
				"value"=>$frm["uname"],
				"text"=>"Username",
				"required"=>true,
				"size"=>30));				
		
	$gdl_form->add_field(array(
				"type"=>"password",
				"name"=>"frm[password]",			
				"value"=>$frm["password"],
				"text"=>"Password",
				"size"=>30));				
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[name]",			
				"value"=>$frm["name"],
				"text"=>_DBNAME,
				"required"=>true,
				"size"=>30));

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[prefix]",			
				"value"=>$frm["prefix"],
				"text"=>_TABLEPREFIX,
				"size"=>30));
				
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"frm[submit]",
			"value"=>_SAVE)); 
			
	$content .= $gdl_form->generate();
	return $content;	
}

function save_configuration() {
	global $frm;
	$dbphp="./config/db.php";
	$filehandle=@fopen($dbphp,"w");
	if ($filehandle) {
		$strcfg="<?
		";
		
		foreach ($frm as $idx => $val) {
		  if (!preg_match("/submit/",$idx)) {
		  	$strcfg.="\$gdl_db_conf[\"".$idx."\"]=\"".$val."\";
			";
		  }
		}
		
		$strcfg.="?>";
		
		if (fputs($filehandle,$strcfg)) {
			$content.=_SUCCESSWRITE. "<b>".$dbphp."</b>";
		} else
			$content.=_FAILEDWRITE. "<b>".$dbphp."</b>";
		fclose($filehandle);
	} else
		$content.=_CANNOTOPENFILE. "<b>".$dbphp."</b>";
		
	return $content;
}

function view_configuration() {
	require_once("./class/repeater.php");
	include ("./config/db.php");
	$grid=new repeater();
			
	$header[1]="No";
	$header[2]=_CONFIGURATIONNAME;
	$header[3]=_VALUE;
	
	$field[1]=1;
	$field[2]="Host";
	$field[3]=$gdl_db_conf["host"];
	$item[]=$field;
	$field[1]=2;
	$field[2]="Username";
	$field[3]=$gdl_db_conf["uname"];
	$item[]=$field;
	$field[1]=3;
	$field[2]=_DBNAME;
	$field[3]=$gdl_db_conf["name"];
	$item[]=$field;
	$field[1]=4;
	$field[2]=_TABLEPREFIX;
	$field[3]=$gdl_db_conf["prefix"];
	$item[]=$field;
	$colwidth[1] = "10px";
	$colwidth[2] = "75px";
	$colwidth[3] = "75px";
	
	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	
	return $grid->generate();
}

function table_configuration() {
	global $gdl_form;
	$content.=_CREATETABLE;
	
	require_once("./class/repeater.php");
	include ("./config/db.php");
	$grid=new repeater();
			
	$header[1]="No";
	$header[2]=_TABLENAME;
	
	if ($gdl_db_conf["prefix"])
		$gdl_db_conf["prefix"].="_";
	
	$tablename=array("bookmark","comment","folder","folksonomy","garbagetoken","group","inbox","log","metadata","online","outbox","publisher","queue","relation","repository","Set","session","user");
	$i=1;
	foreach ($tablename as $val) {
		$field[1]=$i;
		$field[2]=$gdl_db_conf["prefix"].$val;		
		$item[]=$field;
		$i++;
	}
	
	$colwidth[1] = "10px";
	$colwidth[2] = "75px";
		
	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	
	$content.="<p>".$grid->generate()."</p>";
	
	$gdl_form->set_name("configure_table");
	$gdl_form->action="./gdl.php?mod=install&amp;op=table";
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_CREATEDATABASE));
	
	$gdl_form->add_field(array(
			"type"=>"hidden",
			"name"=>"frm[tablename]",
			"value"=>$tablename
	));
		
	$gdl_form->add_field(array(
				"type"=>"radio",
				"name"=>"frm[database]",			
				"checked"=>array("yes"=>_YES,"no"=>_NO),
				"text"=>_CHOICE,
				"required"=>true,
				"value"=>$frm["database"]
));
					
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"frm[submit]",
			"value"=>_CREATE)); 
			
	$content .= $gdl_form->generate();
	
	return $content;
}

function create_table() {
	global $frm,$gdl_db;
	include ("./config/db.php");
	
	if (preg_match("/yes/",$frm["database"])) {
		$result=$gdl_db->create_db($gdl_db_conf["name"]);
		if ($result)
				$content.="<p>"._CREATEDBSUCCESS." <b>".$gdl_db_conf["name"]."</b></p>";					
		else
			$content.="<p>"._CREATEDBFAILED." <b>".$gdl_db_conf["name"]."</b></p>";

	} 
	
	$result=@mysqli_select_db($gdl_db_conf["name"]);
	if ($result) {
			$content.="<p>"._SELECTDBSUCCESS." <b>".$gdl_db_conf["name"]."</b></p>";			
		}
	else
		$content.="<p>"._SELECTDBFAILED." <b>".$gdl_db_conf["name"]."</b></p>";
	
	if ($gdl_db_conf["prefix"])
		$gdl_db_conf["prefix"].="_";
		
	$tablename=array(
		$gdl_db_conf["prefix"]."bookmark"=>"
									CREATE TABLE `".$gdl_db_conf["prefix"]."bookmark` (
									  `bookmark_id` int(5) NOT NULL auto_increment,
									  `time_stamp` datetime default NULL,
									  `user_id` varchar(50) default NULL,
									  `identifier` varchar(100) default NULL,
									  `request` int(11) default NULL,
									  `response` text NOT NULL,
									  PRIMARY KEY  (`bookmark_id`)
									);",
		$gdl_db_conf["prefix"]."comment"=>"
									CREATE TABLE `".$gdl_db_conf["prefix"]."comment` (
									  `comment_id` int(11) NOT NULL auto_increment,
									  `date` date NOT NULL default '0000-00-00',
									  `identifier` varchar(100) NOT NULL default '',
									  `user_id` varchar(50) NOT NULL default '',
									  `name` varchar(50) NOT NULL default '',
									  `email` varchar(50) NOT NULL default '',
									  `subject` varchar(200) NOT NULL default '',
									  `comment` text NOT NULL,
									  PRIMARY KEY  (`comment_id`),
									  UNIQUE KEY `comment_id` (`comment_id`)
									);",
		$gdl_db_conf["prefix"]."folder"=>"
									CREATE TABLE `".$gdl_db_conf["prefix"]."folder` (
									  `folder_id` int(9) NOT NULL auto_increment,
									  `parent` int(9) default '0',
									  `path` varchar(255) NOT NULL default '',
									  `count` int(9) NOT NULL default '0',
									  `name` TEXT default NULL,
									  `date_modified` datetime default '0000-00-00 00:00:00',
									  PRIMARY KEY  (`folder_id`)
									);",
		$gdl_db_conf["prefix"]."folksonomy"=>"
									CREATE TABLE `".$gdl_db_conf["prefix"]."folksonomy` (
									  `Token` varchar(45) NOT NULL default '',
									  `Frekuensi` int(10) unsigned NOT NULL default '0',
									  PRIMARY KEY  (`Token`)
									);",
		$gdl_db_conf["prefix"]."garbagetoken"=>"
									CREATE TABLE `".$gdl_db_conf["prefix"]."garbagetoken` (
									  `garbage_id` int(10) unsigned NOT NULL auto_increment,
									  `Token` varchar(45) NOT NULL default '',
									  PRIMARY KEY  (`garbage_id`)
									);",
		$gdl_db_conf["prefix"]."group"=>"
									CREATE TABLE `".$gdl_db_conf["prefix"]."group` (
									  `group_id` varchar(10) NOT NULL default '',
									  `name` varchar(50) default NULL,
									  `authority` varchar(255) default NULL,
									  `description` varchar(200) default NULL,
									  PRIMARY KEY  (`group_id`),
									  UNIQUE KEY `name` (`name`)
									);",
		$gdl_db_conf["prefix"]."inbox"=>"
									CREATE TABLE `".$gdl_db_conf["prefix"]."inbox` (
									  `ID` int(9) NOT NULL auto_increment,
									  `TYPE` varchar(8) default NULL,
									  `IDENTIFIER` varchar(100) NOT NULL default 'id',
									  `STATUS` varchar(8) default NULL,
									  `FOLDER` varchar(8) default NULL,
									  `DATEMODIFIED` datetime default NULL,
									  PRIMARY KEY  (`ID`),
									  UNIQUE KEY `IDENTIFIER` (`IDENTIFIER`)
									);",
		$gdl_db_conf["prefix"]."log"=>"
									CREATE TABLE `".$gdl_db_conf["prefix"]."log` (
									  `log_id` bigint(20) NOT NULL auto_increment,
									  `session_id` varchar(50) NOT NULL default '',
									  `datestamp` datetime NOT NULL default '0000-00-00 00:00:00',
									  `user_id` varchar(255) NOT NULL,
									  `ipaddress` varchar(30) NOT NULL,
									  `url` varchar(150) default NULL,
									  PRIMARY KEY  (`log_id`)
									);",
		$gdl_db_conf["prefix"]."metadata"=>"
									CREATE TABLE `".$gdl_db_conf["prefix"]."metadata` (
									  `identifier` varchar(100) NOT NULL default '',
									  `folder` int(9) default NULL,
									  `path` varchar(100) NOT NULL default '',
									  `type` varchar(20) default NULL,
									  `xml_data` text,
									  `date_modified` datetime default '0000-00-00 00:00:00',
									  `owner` varchar(36) default NULL,
									  `status` varchar(8) default NULL,
									  `prefix` varchar(10) default NULL,
									  `repository` text,
									  PRIMARY KEY  (`identifier`)
									);",		
		$gdl_db_conf["prefix"]."online"=>"
									CREATE TABLE `".$gdl_db_conf["prefix"]."online` (
									  `session_id` varchar(50) NOT NULL default '',
									  `time_stamp` int(15) default '0',
									  `url` varchar(150) default NULL,
									  PRIMARY KEY  (`session_id`)
									);",
		$gdl_db_conf["prefix"]."outbox"=>"
									CREATE TABLE `".$gdl_db_conf["prefix"]."outbox` (
									  `ID` int(9) NOT NULL auto_increment,
									  `TYPE` varchar(8) default NULL,
									  `IDENTIFIER` varchar(100) NOT NULL default 'id',
									  `STATUS` varchar(8) default NULL,
									  `FOLDER` varchar(8) default NULL,
									  `DATEMODIFIED` datetime default NULL,
									  PRIMARY KEY  (`ID`),
									  UNIQUE KEY `IDENTIFIER` (`IDENTIFIER`)
									);",
		$gdl_db_conf["prefix"]."publisher"=>"
									CREATE TABLE `".$gdl_db_conf["prefix"]."publisher` (
							  `IDPUBLISHER` int(3) NOT NULL auto_increment,
							  `DC_PUBLISHER_ID` varchar(15) NOT NULL default 'DEFAULT',
							  `DC_PUBLISHER_SERIALNO` varchar(30) default NULL,
							  `DC_PUBLISHER_TYPE` varchar(15) default NULL,
							  `DC_PUBLISHER_APPS` varchar(16) default NULL,
							  `DC_PUBLISHER` varchar(50) default NULL,
							  `DC_PUBLISHER_ORGNAME` varchar(50) default NULL,
							  `DC_PUBLISHER_HOSTNAME` varchar(50) default NULL,
							  `DC_PUBLISHER_IPADDRESS` varchar(15) default NULL,
							  `DC_PUBLISHER_ADMIN` varchar(50) default NULL,
							  `DC_PUBLISHER_CKO` varchar(50) default NULL,
							  `DC_PUBLISHER_CONTACT` varchar(100) default NULL,
							  `DC_PUBLISHER_ADDRESS` varchar(150) default NULL,
							  `DC_PUBLISHER_CITY` varchar(64) default NULL,
							  `DC_PUBLISHER_REGION` varchar(64) default NULL,
							  `DC_PUBLISHER_COUNTRY` varchar(64) default NULL,
							  `DC_PUBLISHER_PHONE` varchar(30) default NULL,
							  `DC_PUBLISHER_FAX` varchar(30) default NULL,
							  `DC_PUBLISHER_CONNECTION` varchar(10) default NULL,
							  `DC_PUBLISHER_NETWORK` varchar(80) default NULL,
							  `DC_PUBLISHER_HUBSERVER` varchar(15) default 'DEFAULT',
							  `DC_PUBLISHER_DATEMODIFIED` datetime default NULL,
							  PRIMARY KEY  (`IDPUBLISHER`),
							  UNIQUE KEY `DC_PUBLISHER_ID` (`DC_PUBLISHER_ID`)
									);",
		$gdl_db_conf["prefix"]."queue"=>"
								CREATE TABLE `".$gdl_db_conf["prefix"]."queue` (
								  `no` int(10) unsigned NOT NULL auto_increment,
								  `path` varchar(200) NOT NULL default '',
								  `datemodified` varchar(45) NOT NULL default '',
								  `status` varchar(45) NOT NULL default 'queue',
								  `dc_publisher_id` varchar(255) NOT NULL default '',
								  `temp_folder` varchar(255) NOT NULL default '',
								  PRIMARY KEY  (`no`),
								  UNIQUE KEY `path` (`path`)
								);",
		$gdl_db_conf["prefix"]."relation"=>"
								CREATE TABLE `".$gdl_db_conf["prefix"]."relation` (
						  `relation_id` int(4) NOT NULL auto_increment,
						  `identifier` varchar(100) default NULL,
						  `date_modified` datetime default NULL,
						  `no` int(2) default NULL,
						  `name` varchar(150) default NULL,
						  `part` varchar(150) default NULL,
						  `path` varchar(150) default NULL,
						  `format` varchar(50) default NULL,
						  `size` varchar(10) default NULL,
						  `uri` varchar(255) default NULL,
						  `note` varchar(255) default NULL,
						  PRIMARY KEY  (`relation_id`),
						  UNIQUE KEY `part` (`part`),
						  KEY `identifier` (`identifier`)
									);",
		$gdl_db_conf["prefix"]."repository"=>"
								CREATE TABLE `".$gdl_db_conf["prefix"]."repository` (
								  `nomor` int(10) unsigned NOT NULL auto_increment,
								  `repository_name` varchar(255) NOT NULL default 'N/A',
								  `host_url` varchar(200) NOT NULL default '',
								  `port_host` int(4) unsigned NOT NULL default '80',
								  `use_proxy` int(1) unsigned NOT NULL default '0',
								  `proxy_address` varchar(200) NOT NULL default '',
								  `port_proxy` int(4) unsigned NOT NULL default '0',
								  `oai_script` varchar(20) NOT NULL default '',
								  `option_prefix` varchar(15) NOT NULL default 'general',
								  `fragmen` int(8) unsigned NOT NULL default '0',
								  `show_xml` int(1) unsigned NOT NULL default '0',
								  `list_set` varchar(20) NOT NULL default '',
								  `id_publisher` varchar(15) NOT NULL default '',
								  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
								  `protocol_version` varchar(45) NOT NULL default '',
								  `admin_email` varchar(45) NOT NULL default '',
								  `from_clause` datetime NOT NULL default '0000-00-00 00:00:00',
								  `until_clause` datetime NOT NULL default '0000-00-00 00:00:00',
								  `count_record` int(3) unsigned NOT NULL default '0',
								  `harvest_node` varchar(50) NOT NULL default '',
								  PRIMARY KEY  (`nomor`)
								);",
		$gdl_db_conf["prefix"]."session"=>"
								CREATE TABLE `".$gdl_db_conf["prefix"]."session` (
						  `session_id` varchar(50) NOT NULL default '',
						  `user_id` varchar(20) default NULL,
						  `remote_ip` varchar(21) default NULL,
						  `begin_visit` varchar(20) default NULL,
						  `last_visit` varchar(20) default NULL,
						  PRIMARY KEY  (`session_id`),
						  KEY `session_id` (`session_id`)
									);",
		$gdl_db_conf["prefix"]."Set"=>"
								CREATE TABLE `".$gdl_db_conf["prefix"]."Set` (
								  `nomor` int(10) unsigned NOT NULL default '0',
								  `spec` varchar(20) NOT NULL default '',
								  `name` varchar(45) NOT NULL default '',
								  `description` varchar(200) NOT NULL default '',
								  `modified` datetime NOT NULL default '0000-00-00 00:00:00'
								);",
		$gdl_db_conf["prefix"]."user"=>"
								CREATE TABLE `".$gdl_db_conf["prefix"]."user` (
						  `user_id` varchar(60) NOT NULL default '',
						  `password` varchar(20) NOT NULL default '',
						  `active` tinyint(1) NOT NULL default '0',
						  `group_id` varchar(100) default NULL,
						  `name` varchar(50) default NULL,
						  `date_modified` datetime default '0000-00-00 00:00:00',
						  `validation` varchar(11) default NULL,
						  `address` varchar(100) default NULL,
						  `city` varchar(20) default NULL,
						  `country` varchar(20) default NULL,
						  `institution` varchar(50) default NULL,
						  `job` varchar(40) default NULL,
						  PRIMARY KEY  (`user_id`),
						  KEY `user_id` (`user_id`)
						);");
	
	$content.="<p>";
	$success=true;
	foreach ($tablename as $idxtbl => $valtbl) {
		$result=@mysqli_query($gdl_db->con, $valtbl);
		if ($result)
			$content.=_CREATETABLESUCCESS." <b>".$idxtbl."</b><br/>";
		else
			{
				$content.=_CREATETABLEFAILED." <b>".$idxtbl."</b><br/>";
				$content.="<b>".mysqli_error($gdl_db->con)."</b><br/>";
				$success=false;
			}
	}	
	
	$content.="</p>";
	
	if ($success)
		$content.="<p><a href='./gdl.php?mod=install&amp;op=data'>"._FILLDATA."</a>";
	return $content;	
}

function data_form() {
	global $gdl_form,$frm,$gdl_sys;
	include "./config/usertype.php";
	
	$gdl_form->set_name("data_fill");
	$gdl_form->action="./gdl.php?mod=install&amp;op=data";
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_ADMINISTRATORINFORMATION));
	
	$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[EMAIL]",			
			"value"=>"$frm[EMAIL]",
			"text"=>_USER_MAIL,
			"required"=>true,
			"size"=>45));

	$gdl_form->add_field(array(
			"type"=>"password",
			"name"=>"frm[PASSWORD]",
			"text"=>_USER_PASSWD,
			"required"=>true,
			"size"=>45));

	$gdl_form->add_field(array(
			"type"=>"password",
			"name"=>"frm[PASSWORDCONFIRM]",
			"text"=>_USER_PASSWD_CONFIRM,
			"required"=>true,
			"size"=>45));
			
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_USER_GENERAL));
	$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[FULLNAME]",
			"value"=>"$frm[FULLNAME]",
			"text"=>_USER_FULLNAME,
			"required"=>true,
			"size"=>45));
	$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[ADDRESS]",
			"value"=>"$frm[ADDRESS]",
			"text"=>_USER_ADDRESS,
			"required"=>true,
			"size"=>45));
	$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[CITY]",
			"value"=>"$frm[CITY]",
			"text"=>_USER_CITY,
			"required"=>true,
			"size"=>45));
	$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[COUNTRY]",
			"value"=>"$frm[COUNTRY]",
			"text"=>_USER_COUNTRY,
			"required"=>true,
			"size"=>45));

	$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[INSTITUTION]",
			"value"=>"$frm[INSTITUTION]",
			"text"=>_USER_INSTITUTION,
			"required"=>true,
			"size"=>45));
	$gdl_form->add_field(array(
			"type"=>"select",
			"name"=>"frm[JOB]",
			"value"=>"$frm[JOB]",
			"option"=>$user_type,
			"text"=>_TYPEOFUSER));
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"frm[submit]",
			"value"=>_SUBMIT)); 
			
	$content .= $gdl_form->generate();
	return $content;
}

function fill_data() {
	global $frm, $gdl_account, $gdl_publisher2, $gdl_db;;
	
	$result1=$gdl_db->insert("user","user_id,group_id,name","'public','public','Public'");
	$result2=$gdl_db->insert("user","","'".$frm["EMAIL"]."',old_password('".$frm["PASSWORD"]."'),1,'admin','".$frm["FULLNAME"]."',now(),NULL,'".$frm["ADDRESS"]."','".$frm["CITY"]."','".$frm["COUNTRY"]."','".$frm["INSTITUTION"]."','".$frm["JOB"]."'");
	if ($result1 && $result2)
		$content.="<b>"._SUCCESSADDLOGIN."</b><br/>";
	else
		$content.="<b>"._FAILEDADDLOGIN."</b><br/>";		
	$result3=$gdl_db->insert("group","","'admin','Superuser','*','Default Superuser Group'");
	$result4=$gdl_db->insert("group","","'public','Public','{browse->*}{bookmark->*}{search->*}{register->*}{discussion->*}','Default Guest Group'");
	$result5=$gdl_db->insert("group","","'CKO','CKO','{browse->*}{bookmark->*}{search->*}{upload->*}{request->*}{synchronization->*}{cdsisis->*}{explorer->*}{indexing->*}{migration->*}{mydocs->*}{organization->*}{member->index}{discussion->*}{partnership->*}','Default CKO Group'");
	$result6=$gdl_db->insert("group","","'User','User','{browse->*}{bookmark->*}{search->*}{register->*}{member->index}{discussion->*}{partnership->*}','Default User Group'");
	$result7=$gdl_db->insert("group","","'Editor','Editor','{browse->*}{bookmark->*}{search->*}{upload->*}{explorer->*}{mydocs->*}{member->index}{discussion->*}{partnership->*}','Default Editor Group'");
	$result7=$gdl_db->insert("group","","'Remote','Remote User','{browse->*}{bookmark->*}{search->*}{register->*}{member->index}{discussion->*}{partnership->*}','Remote User Group'");	
	
	if ($result3 && $result4 && $result5 && $result6 && $result7)
		$content.="<b>"._SUCCESSINSERTGROUPUSER."</b><br/>";
	else
		$content.="<b>"._FAILEDINSERTGROUPUSER."</b><br/>";
		
	if ($result1 && $result2 && $result3 && $result4 && $result5 && $result6 && $result7)
		$result=write_install_lck();
		
	if ($result) {
		$content.="<b>"._SUCCESSWRITEINSTALLLCK."</b><br/>";
		$content.="<p>"._FINISHED."</p>";
	} else {
		$content.="<b>"._FAILEDWRITEINSTALLLCK."</b><br/>";
	}
		
	return $content;
}

function write_install_lck() {
	$filehandle=@fopen("./files/misc/install.lck","w");
	$result=@fputs($filehandle,"Installed on : ".date("r"));
	return $result;
}
?>