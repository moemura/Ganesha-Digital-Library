<?php 

/***************************************************************************
                         /module/cdsisis/function.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
 
if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function list_cdsisis(){
	
	require_once "./class/repeater.php";
	
	$grid = new repeater();
	$dir="./files/isisdb";
	if ($dirhandle=opendir($dir)) {
	
		$header[1]="No";
		$header[2]=_DBNAME;
		$header[3]=_ACTION;
		
		$no=1;
		while (false !== ($file = readdir($dirhandle))) { 
			if ($file != "." && $file != "..") {
					$field[1]=$no;
					$field[2]=$file;
					$field[3]="<a href='./gdl.php?mod=cdsisis&amp;op=edit&amp;db_name=".$file."'>"._EDIT."</a> - <a href='./gdl.php?mod=cdsisis&amp;op=delete&amp;del=confirm&amp;db_name=".$file."'>"._DELETE."</a> - <a href='./gdl.php?mod=cdsisis&amp;op=configure&amp;db_name=".$file."'>"._CONFIGURE."</a> - <a href='./gdl.php?mod=cdsisis&amp;op=test&amp;db_name=".$file."'>"._TEST."</a> - <a href='./gdl.php?mod=cdsisis&amp;op=build&amp;db_name=".$file."'>"._BUILDINDEX."</a>";
					$item[]=$field;
					$no++;
			}
		}
		
		$colwidth[1] = "10px";
		$colwidth[2] = "20px";
		$colwidth[3] = "100px";
		
		$grid->header=$header;
		$grid->item=$item;
		$grid->colwidth=$colwidth;
			
		$content.= @$grid->generate();			

	} else
		$content.=_CANTFINDFOLDER."<b>".$dir."</b>";
		
	return $content;
}

function list_cdsisis_files($db_name) {
	
	require_once "./class/repeater.php";
	
	$grid = new repeater();
	$dir="./files/isisdb/".$db_name;
	
	if ($dirhandle=opendir($dir)) {
	
		$header[1]="No";
		$header[2]=_FILENAME;
		$header[3]=_SIZE." (Bytes)";
		$header[4]=_DATEMODIFIED;
		
		$no=1;
		while (false !== ($file = readdir($dirhandle))) { 
			if ($file != "." && $file != ".." && !preg_match("/owner/i",$file)) {
					$field[1]=$no;
					$field[2]=$file;
					$field[3]=filesize($dir."/".$file);
					$field[4]=date("Y-m-d H:i:s",filemtime($dir."/".$file));
					$item[]=$field;
					$no++;
			}
		}
		
		$colwidth[1] = "10px";
		$colwidth[2] = "75px";
		$colwidth[3] = "75px";
		$colwidth[4] = "75px";
		
		$grid->header=$header;
		$grid->item=$item;
		$grid->colwidth=$colwidth;
			
		$content.= @$grid->generate();			

	} else
		$content.=_CANTFINDFOLDER."<b>".$dir."</b>";
		
	return $content;
	
}

function delete_cdsisis($db_name) {
	global $gdl_isisdb;
	
	if ($gdl_isisdb->delete_db($db_name)) {
		$content.=_SUCCESSDELETE. " <b>".$gdl_isisdb->isisdbdir."/".$db_name."</b>";
	} else
		$content.=_FAILEDDELETE. " <b>".$gdl_isisdb->isisdbdir."/".$db_name."</b>";
	
	return $content;	
}

function new_cdsisis($action) {
	global $gdl_form,$frm;
	
	$gdl_form->set_name("add_cdsisisdatabase");
	$gdl_form->enctype=true;
	
	if (!$action){
		$gdl_form->action="./gdl.php?mod=cdsisis&amp;op=new";
		$submitbutton=_ADD;
	}
	else
	{	$gdl_form->action=$action;
		$submitbutton=_EDIT;		
	}
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_DATABASEOWNER));
		
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[orgname]",			
				"value"=>$frm["orgname"],
				"text"=>_ORGANIZATIONNAME,   /***********/
				"required"=>true,
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[dbname]",			
				"value"=>$frm["dbname"],
				"text"=>_DATABASENAME,   /***********/
				"required"=>true,
				"size"=>50));				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[email]",			
				"value"=>$frm["email"],
				"text"=>_LIBRARIANEMAIL,   /***********/
				"required"=>true,
				"size"=>50));
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_FILES));				
	$gdl_form->add_field(array(
				"type"=>"file",
				"name"=>"cnt_file",
				"text"=>"CNT"   
				));				
	$gdl_form->add_field(array(
				"type"=>"file",
				"name"=>"ifp_file",
				"text"=>"IFP"   
				));
	$gdl_form->add_field(array(
				"type"=>"file",
				"name"=>"l01_file",
				"text"=>"L01"   
				));
	$gdl_form->add_field(array(
				"type"=>"file",
				"name"=>"l02_file",
				"text"=>"L02"   
				));
	$gdl_form->add_field(array(
				"type"=>"file",
				"name"=>"mst_file",
				"text"=>"MST"   
				));
	$gdl_form->add_field(array(
				"type"=>"file",
				"name"=>"n01_file",
				"text"=>"N01"   
				));
	$gdl_form->add_field(array(
				"type"=>"file",
				"name"=>"n02_file",
				"text"=>"N02"   
				));
	$gdl_form->add_field(array(
				"type"=>"file",
				"name"=>"xrf_file",
				"text"=>"XRF"   
				));
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"frm[submit]",
			"value"=>_SUBMIT));
	$content = $gdl_form->generate();
	return $content;
}

function insert_cdsisis($db_name) {
	global $_FILES,$frm,$gdl_isisdb;
	
	$return=$gdl_isisdb->insert_new_db($db_name,$frm);
	foreach ($return as $idxReturn => $valReturn) {
		if (preg_match("/file/",$idxReturn)) {
			if (is_array($valReturn)) {
				foreach ($valReturn as $idxValReturn => $valValReturn)  {
					if ($valValReturn)
						$content.=_UPLOADFILESUCCESS." <b>".$idxValReturn."</b><br>";
					else
						$content.=_UPLOADFILEFAILED." <b>".$idxValReturn."</b><br>";
				}
			}
		} elseif (preg_match("/add/",$idxReturn)) {
			if ($valReturn)
				$content.="<b>"._ADDCDSISISSUCCESS."</b><br>";
			else
				$content.="<b>"._ADDCDSISISFAILED."</b><br>";
		} elseif (preg_match("/edit/",$idxReturn)) {
			if ($valReturn)
				$content.="<b>"._EDITCDSISISSUCCESS."</b><br>";
			else
				$content.="<b>"._EDITCDSISISFAILED."</b><br>";		
		}
	}
	
	return $content;
}

function configure_cdsisis($db_name) {
	global $gdl_isisdb, $gdl_metadata,$id,$gdl_form;
	
	if (!isset($id))
		$id=1;
		
	$nextid=$id+1;
	$record=$gdl_isisdb->get_record($db_name,$id);
	if (preg_match("/<row>/",$record)) {
		$xml=$gdl_metadata->readXML($record);
		$main.=_READSUCCESS;
		$main.="<p><a href='./gdl.php?mod=cdsisis&amp;db_name=".$db_name."&amp;op=configure&amp;id=".$nextid.">"._NEXTRECORD." (".$nextid.")</a></p>";
		
		if (file_exists($gdl_isisdb->isisdbdir."/".$db_name."/elements.cfg"))
			require_once ($gdl_isisdb->isisdbdir."/".$db_name."/elements.cfg");
				
			
		
		$gdl_form->set_name("configure_cdsisis");
		$gdl_form->action="./gdl.php?mod=cdsisis&amp;&db_name=".$db_name."&amp;op=configure";		
		$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>""));
	
		foreach ($xml as $idxXml => $valXml) {
			$gdl_form->add_field(array(
				"type"=>"select",
				"text"=>"<b>".$idxXml."</b><br><br>(".$valXml[0].")",
				"name"=>"frm[".$idxXml."]",
				"option"=>array(
					""=>_SELECTLABEL,
					"period"=>_PERIOD,
					"date"=>_DATE,
					"isbn"=>"ISBN",
					"issn"=>"ISSN",
					"location"=>_LOCATION,
					"language"=>_LANGUAGE,
					"classification"=>_CLASSIFICATION,
					"call_number"=>_CALLNUMBER,
					"ddc_edition"=>_DDCEDITION,
					"local_classification"=>_LOCALCLASSIFICATION,
					"author"=>_AUTHOR,
					"author_corporate"=>_AUTHORCORPORATE,
					"conference"=>_CONFERENCE,
					"title_of_journal"=>_TITLEOFJOURNAL,
					"title"=>_TITLE,
					"alternative_title"=>_ALTERNATIVETITLE,
					"description"=>_DESCRIPTION,
					"edition"=>_EDITION,
					"place_of_publisher"=>_PLACEOFPUBLISHER,
					"publisher"=>_PUBLISHER,
					"dimention"=>_DIMENTION,
					"illustration"=>_ILLUSTRATION,
					"height"=>_HEIGHT,
					"series"=>_SERIES,
					"note"=>_NOTE,
					"bibliography"=>_BIBLIOGRAPHY,
					"summary"=>_SUMMARY,
					"subject"=>_SUBJECT,
					"coauthor"=>_COAUTHOR,
					"coauthor_corporate"=>_COAUTHORCORPORATE,
					"volume"=>"Volume",
					"pagina"=>"Pagina",
					"identification"=>_IDENTIFICATION),
				"value"=>$frm[$idxXml],
				"size"=>15
			));
		}
		$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"frm[submit]",
			"value"=>_SAVE));
		
		$main.=$gdl_form->generate();
		
	} else {
		$main.="<b>"._FAILEDCDSISIS."</b>";
		$main.="<p>".list_cdsisis()."</p>";		
	}
	return $main;
}

function save_configuration($db_name) {
	global $frm,$gdl_isisdb;
		
	if ($gdl_isisdb->save_configuration($db_name,$frm))
		$main.="<b>"._CONFIGURATIONSAVEDSUCCESS."</b>";
	else
		$main.="<b>"._CONFIGURATIONSAVEDFAILED."</b>";
		
	return $main;
}

function configuration_test($db_name) {
	global $gdl_isisdb,$gdl_metadata;
	$record=$gdl_isisdb->get_record($db_name,1);
	if (!preg_match("/row/",$record))
		$main.=_FAILEDCDSISIS;
	else {
		$xmlrecord=$gdl_isisdb->get_xml_record($db_name,1,$record);
		if ($xmlrecord) {
			$main.="<p>"._TESTRECORD."</p>";
			$main.="<p><b>"._FILENAME." : catalog=".$db_name."=1</b></p>";
			$main.=nl2br(htmlspecialchars($xmlrecord));
		}

	}
	
	return $main;
}

function build_index($db_name) {
	$main.="<p>"._FOLLOW."</p>";
	$main.="<ol>
				<li><a href='./gdl.php?mod=cdsisis&amp;op=build&amp;step=1&amp;db_name=".$db_name."'>"._EXPORTDATABASE."</a></li>
				<li><a href='./gdl.php?mod=cdsisis&amp;op=build&amp;step=2&amp;db_name=".$db_name."'>"._BUILDINDEX."</a></li>
			</ol>";
	return $main;
}

function export_database($db_name) {
	global $gdl_isisdb,$id;
	
	$tmpdir="./files/tmp";
	if (!file_exists($gdl_isisdb->isisdbdir."/".$db_name."/elements.cfg")) {
		$main.=_CDSISISFAILED;
	} else {
				
		$end_of_record = FALSE;
		while (!$end_of_record){
			$id++;
			$record = $gdl_isisdb->get_record($db_name,$id);
			if (preg_match("/<row>/",$record)){
				$skip = 0;
				$xml = $gdl_isisdb->get_xml_record($db_name,$id,$record);
				$filename = "catalog=".$db_name."=".$id;
				$fout = $tmpdir."/".$filename;
				$fp = fopen($fout,"w");
				if ($fp){
					fputs($fp,$xml);
					fclose($fp);
				}
			} else {
				$skip++;
				if ($skip == 50) $end_of_record = TRUE;
			}

			if ($id % 55 == 0)	{
					$end_of_record=TRUE;
					$main.="<META HTTP-EQUIV=Refresh CONTENT=\"5; URL=./gdl.php?mod=cdsisis&amp;op=build&amp;db_name=".$db_name."&amp;step=1&amp;id=".$id."\">";
				}
		}
		
		$main.="<p><b>"._EXPORTDATABASE."</b></p>";
		if (preg_match("/META/",$main))	{
			$main.="<p><b>"._EXPORTINGINPROGRESS."</b>, ".$id." "._RECORDEXPORTED."</p>";
		} else
			$main.="<p><b>"._EXPORTINGFINISHED."</b></p>";
			
		if (!preg_match("/META/",$main)){
				$main.="<b>".($id-50)."</b> "._RECORDEXPORTED;
				$main.="<p><a href='./gdl.php?mod=cdsisis&amp;op=build&amp;step=2&amp;db_name=".$db_name."'>"._BUILDINDEX."</a></p>";
			}
			
	}
	
	return $main;
}

function indexing_process($db_name) {
	include("./class/indexing.php");
	$indexing = new indexing();
	
	$swishe=$indexing->indexing_var();
	
	if (!file_exists($swishe["bin"])) {
		$main.="<p>"._SWISHENOTEXIST."</p>";
	} else {
		$dbidx_name="isis_".$db_name.".idx";
		$str_cmd=$swishe["bin"]." -f bin/".$dbidx_name." -c ".$swishe["cfg2"];
		
		$result=`$str_cmd`;
		$result=explode("\n",$result);
		
		foreach ($result as $idxResult => $valResult) {
			if (preg_match("/!!!Adding/",$result[$idxResult])){
				$meta=explode("'",$result[$idxResult]);
				$main.="Meta : ".$meta[1]."<br>";
			} else
				$main.=$result[$idxResult]."<br>";
		}
		
		$indexing->indexing_init();
		
	}

	return $main;
}

function list_isis_index() {
	$dir="./bin";
	$dirhandle=opendir($dir);
	
	if ($dirhandle) {
		$main.="<ol>";
		while (false !== ($file=readdir($dirhandle))) {
			if ($file != "." && $file != ".." && is_file($dir."/".$file) && preg_match("/isis_/",substr($file,0,5)) && preg_match("/idx/",substr($file,-3,3))) {
				$idxfile.=$dir."/".$file." ";
				$main.="<li><b>".$idxfile."</b>";				
			}
		}
		$main.="</ol>";
		if (!isset($idxfile))
			$main.=_NOIDXFILEFOUND;
		else {
			$main="<p>"._FOLLOWINGIDX."</p>".$main;
			$main.="<p><a href='./gdl.php?mod=cdsisis&amp;op=union&amp;union=yes'>"._STARTMERGING."</a></p>";
		}

		closedir($dirhandle);
	}
	
	return $main;
}

function union_isis_index() {
	include("./class/indexing.php");
	$indexing = new indexing();
	
	$dir="./bin";
	$dirhandle=opendir($dir);
	
	if($dirhandle) {
		while (false !== ($file=readdir($dirhandle))) {
			if ($file != "." && $file != ".." && is_file($dir."/".$file) && preg_match("/isis_/",substr($file,0,5)) && preg_match("/idx/",substr($file,-3,3))) {
				$idxfile.=$dir."/".$file." ";			
			}
		}
		
		if (!isset($idxfile))
			$main.=_NOIDXFILEFOUND;
		else {
			$tmp_idx=$dir."/tmp_catalog.idx";
			$merge_idx=$dir."/all_isis.idx";
			$main.=$indexing->indexing_merge($tmp_idx,$idxfile,$merge_idx);
		}

		closedir($dirhandle);
	}
	
	return $main;
}

function final_union_index() {
	global $final;
	
	if ($final) {
		include ("./class/indexing.php");
		$indexing = new indexing();
		$main.=$indexing->indexing_union();
	} else {
		$main.="<p>"._FINALUNIONDESCRIPTION."</p>";
		$main.="<p><a href='./gdl.php?mod=cdsisis&amp;op=final&amp;final=yes'>"._BUILDFINALUNIONINDEX."</a></p>";
	}
	
	return $main;
}
