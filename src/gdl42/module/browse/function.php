<?php
/***************************************************************************
                         /module/browse/function.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, Arif Suprabowo, Lastiko Wibisono, KMRG ITB
    email                : hayun@kmrg.itb.ac.id, mymails_supra@yahoo.com, leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/
if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();


function get_metadata($node){
	global $gdl_content,$gdl_metadata,$gdl_sys,$gdl_folder,$gdl_db,$gdl_op;
	
	require_once("./config/type.php");
	$page = $_GET['page'];
	if (!isset($page)){
	 	$page = 0 ;
	}else{
		$page = $page-1;
	}

	$limit = $page * $gdl_sys['perpage_browse'];
	$metadata = $gdl_metadata->get_list($node,"","$limit,$gdl_sys[perpage_browse]",true);
	if (is_array($metadata)){
		foreach ($metadata as $key => $val) {
			$type = $val['TYPE'];
			$file = "";
			if ($val['RELATION_COUNT'] > 0) $file = ", $val[RELATION_COUNT] "._FILES;
			$result = "<b><a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=$key\">$val[TITLE]</a></b><br/>\n";
			$result .= "<span class=\"note\">".substr($val['DATE_MODIFIED'],0,10).", $gdl_type[$type] "._BY." $val[CREATOR]$file</span><br/>\n";
			$meta_arr[] = $result;
		}
		
		$page = $page + 1;
		$total = $gdl_metadata->total;
		$pages = ceil($total/$gdl_sys['perpage_browse']);
		$start = 1 + (($page-1) * $gdl_sys['perpage_browse']);
		$count = $gdl_metadata->count;
		$url = "./gdl.php?mod=browse&amp;node=$node&amp;";

		$metadata_list = gdl_metadata_list($meta_arr,$start,$count,$total,$page,$pages,$url);		
		return $metadata_list;
	}
}

function gdl_metadata_list($data,$start,$count,$total,$page,$pages,$url=""){
	global $gdl_op, $gdl_session, $gdl_theme;
	
	if (is_array($data)){
		$node = $_SESSION['gdl_node'];
		$end = $start + $count - 1;
				
		// previous navigator
		if ($page==1){
			$pref_nav = "<a href=\"$url"."page=1\">&laquo; Prev</a>";
		}else{
			$prev_page = $page-1;
			$pref_nav = "<a href=\"$url"."page=$prev_page\">&laquo; Prev</a>";
		}

		// next navigator
		if ($page==$pages){
			$next_nav = "<a href=\"$url"."page=$page\">Next &raquo;</a>";
		}else{
			$next_page = $page+1;
			$next_nav = "<a href=\"$url"."page=$next_page\">Next &raquo;</a>";
		}

		$form = "<p class=\"contentlisttop\">"._DISPLAYINGMETADATA." $start - $end "._OF." total $total "._METADATAS.".<br/>";
        $form .= "<span><strong>$pref_nav</strong> | <strong>$next_nav</strong></span></p>";
		$form .= "<ul class=\"filelist\">\n";
		
		foreach ($data as $key => $val) {
    		$form .= "<li>$val</li>\n";
		}
		
		$form .= "</ul>\n";
		
		// generate page
		if($pages<>""){
			$page_nav = _PAGE." : ";
			$i = 1;
			while ($i <= $pages) {
				if ($i==$page){
					$page_nav .= "<b>[$i]</b> ";
				}else{
					$page_nav .= "<a href=\"$url"."page=$i\">$i</a> ";
				}
				$i++; 
			}
		}

		$form .= "<p class=\"contentlistbottom\">$page_nav</p>\n";

	}
	return $form;
}

function format_key($prefix,$key){
	if($prefix == "general")
		return $key;
	else
		return "DC:$key";
}

function display_metadata($frm){
	global $gdl_content, $gdl_db, $gdl_metadata, $gdl_file, $gdl_sys,$gdl_err;	
	
	if (is_array($frm)){
		$prefix = "general";
		$fKey	= key($frm);
		
		if(preg_match("/DC:/",$fKey))
			$prefix = "oai_dc";
		
		
		$title 		= $gdl_metadata->get_value($frm,format_key($prefix,"TITLE"));
		$identifier = $gdl_metadata->get_value($frm,format_key($prefix,"IDENTIFIER"));
		// contributor
		
		include ("schema/lang/".$gdl_content->language.".php");
		$contributor = _EDITOR.": ".$gdl_metadata->get_editor($identifier);
		if (isset($frm[format_key($prefix,"CONTRIBUTOR")])){
			$name_contributor = $gdl_metadata->get_value($frm,format_key($prefix,"CONTRIBUTOR"));
			if (!(substr($name_contributor,0,-1) != '#' && substr($name_contributor,-1,1) != '#'))
					$name_contributor="";
				
			$contributor = "$name_contributor, $contributor";
		}

		$gdl_content->set_relation (gdl_relation_box($contributor,_CONTRIBUTOR));			
		
		$type_schema = $gdl_metadata->get_value($frm,format_key($prefix,"TYPE_SCHEMA"));
		if (file_exists("./schema/display/$type_schema.php")){
			include ("./schema/display/$type_schema.php");
		}else{
			include ("./schema/display/dc_document.php");
		}
		
		// comment, bookmark adn print
		$dbres = $gdl_db->select("comment","count(comment_id) as total","identifier='$identifier'");
		$row = @mysqli_fetch_assoc($dbres);
		$content .= "<p class=\"hideprint\"><a href=\"./gdl.php?mod=browse&amp;op=comment&amp;id=$identifier\"> "._GIVECOMMENT." ?</a>#<a href=\"./gdl.php?mod=browse&amp;op=comment&amp;page=read&amp;id=$identifier\">(".$row["total"].")</a>";
		$content .= " | <a href=\"./gdl.php?mod=bookmark&amp;id=$identifier\"> "._BOOKMARK."</a></p>";

		return $content;
	}else{
		return "";
	}
}

function related_file(){
	global $gdl_content;
	// related file
	$arr_file =  $gdl_content->files;
	if (!empty($arr_file)){
		if ($gdl_sys['public_download']==false) $file = "<p>"._DOWNLOADNOTE."</p>";
		
		$state= $_GET['state'];
		
		$type_file	= "";
		foreach ($arr_file as $key => $val) {
			
			if($state == "offline"){
				$type_file	= "&amp;file=$val[name]";
				$additional_js = "function openFile(filename){\n"
									."if (confirm(\""._CONFIRMDOWNLOAD."\")) {\n"
									  ."self.location.href	= filename;\n"
									."}\n"
								."}\n";
			}
			
			$file .= "<p>";
			$file .= "<img src=\"$val[icon]\" alt=\"Download Image\"/><a href=\"javascript:openDocumentWindow('./download.php?id=$val[id]$type_file')\"><br/>\n";
			$file .= "File : $val[name]</a><br/>($val[size] bytes)<br/>\n";
			$file .= "$val[note]";
			$file .= "</p>\n";
		}
		$gdl_content->set_relation (gdl_relation_box($file,_DOWNLOAD));
		
		$javascript = "function openDocumentWindow(url) {\n"
				."var newWinObj = window.open('','oWin',\n"
				."'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,copyhistory=0,width=480,height=380')\n"
				."newWinObj = window.open(url,'oWin')\n"
				."if (navigator.appVersion.charAt(0)>=3){\n"
				."newWinObj.focus()\n"
				."}\n"
				."}\n";
				
		$gdl_content->set_javascript($javascript.$additional_js);		
	}
}

function display_contact_general($frm){
	global $gdl_publisher2, $gdl_publisher;
	
	$pub	= $frm["PUBLISHER"];
	if(is_array($pub)) $pub = $pub[0];
	
	if ($pub == "#PUBLISHER#")
		$pub = $gdl_publisher['id'];
		
	$orgname = $frm["CREATOR_ORGNAME"];
	if(empty($orgname)) $orgname = $frm["CREATOR.ORGNAME"][0];
	
	if (substr($orgname,0,1) == '#' && substr($orgname,-1,1) == '#')
		$orgname="";
	
	require_once("./class/repeater.php");	
	$grid=new repeater();
	
	$property=$gdl_publisher2->get_property($pub);
	$header[1]=_PUBLISHERPROPERTY;
	$header[2]=_PROPERTYVALUE;

		$field[1]=_PUBLISHERID;
		$field[2]=$pub;
		$item[]=$field;
		$field[1]=_ORGANIZATION;
		$field[2]=$orgname;
		$item[]=$field;	
	if (! empty($property[_PUBLISHERCONTACTNAME])) {	
		$field[1]=_PUBLISHERCONTACTNAME;
		$field[2]=$property[_PUBLISHERCONTACTNAME];
		$item[]=$field;
		$field[1]=_PUBLISHERADDRESS;
		$field[2]=$property[_PUBLISHERADDRESS];
		$item[]=$field;
		$field[1]=_PUBLISHERCITY;
		$field[2]=$property[_PUBLISHERCITY];
		$item[]=$field;
		$field[1]=_PUBLISHERREGION;
		$field[2]=$property[_PUBLISHERREGION];
		$item[]=$field;
		$field[1]=_PUBLISHERCOUNTRY;
		$field[2]=$property[_PUBLISHERCOUNTRY];
		$item[]=$field;
		$field[1]=_PUBLISHERPHONE;
		$field[2]=$property[_PUBLISHERPHONE];
		$item[]=$field;
		$field[1]=_PUBLISHERFAX;
		$field[2]=$property[_PUBLISHERFAX];
		$item[]=$field;
		$field[1]=_PUBLISHERADMINEMAIL;
		$field[2]=$property[_PUBLISHERADMINEMAIL];
		$item[]=$field;
		$field[1]=_PUBLISHERCKOEMAIL;
		$field[2]=$property[_PUBLISHERCKOEMAIL];
		$item[]=$field;
	} else {
		$field[1]=_PUBLISHERCONTACTNAME;
		$field[2]=$gdl_publisher['contact'];
		$item[]=$field;
		$field[1]=_PUBLISHERADDRESS;
		$field[2]=$gdl_publisher['address'];
		$item[]=$field;
		$field[1]=_PUBLISHERCITY;
		$field[2]=$gdl_publisher['city'];
		$item[]=$field;
		$field[1]=_PUBLISHERREGION;
		$field[2]=$gdl_publisher['region'];
		$item[]=$field;
		$field[1]=_PUBLISHERCOUNTRY;
		$field[2]=$gdl_publisher['country'];
		$item[]=$field;
		$field[1]=_PUBLISHERPHONE;
		$field[2]=$gdl_publisher['phone'];
		$item[]=$field;
		$field[1]=_PUBLISHERFAX;
		$field[2]=$gdl_publisher['fax'];
		$item[]=$field;
		$field[1]=_PUBLISHERADMINEMAIL;
		$field[2]=$gdl_publisher['admin'];
		$item[]=$field;
		$field[1]=_PUBLISHERCKOEMAIL;
		$field[2]=$gdl_publisher['cko'];
		$item[]=$field;	
	}
	$colwidth[1] = "150px";
	$colwidth[2] = "350px";
	
	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	
	return $grid->generate("500px");
}
function display_contact_repository($frm){
	global $gdl_metadata,$gdl_db;
	
	$repo_name	= trim($gdl_metadata->get_value($frm,"PUBLISHER"));
	if(!empty($repo_name) && ($repo_name != "N/A")){
		$dbres = $gdl_db ->select("repository","host_url,admin_email","repository_name like '$repo_name'");
		if(mysqli_num_rows($dbres) > 0){
			$row 	= mysqli_fetch_row($dbres);
			$host	= "http://".$row[0];
			$admin	= $row[1];
			
			require_once("./class/repeater.php");	
			$grid=new repeater();
				
			$header[1]	=	_PUBLISHERPROPERTY;
			$header[2]	=	_PROPERTYVALUE;
			
			$field[1]	=	_REPOSITORYNAME;
			$field[2]	=	$repo_name;
			$item[]		=	$field;
			$field[1]	=	_SOURCEHOST;
			$field[2]	=	$host;
			$item[]		=	$field;	
			$field[1]	=	_REPOSITORYCONTACTNAME;
			$field[2]	=	$admin;
			$item[]		=	$field;

			$colwidth[1] = "150px";
			$colwidth[2] = "350px";
			
			$grid->header=$header;
			$grid->item=$item;
			$grid->colwidth=$colwidth;
			
			return $grid->generate("500px");
	
		}
	}
	return "";
}

function display_contact($frm){
	global $gdl_metadata;
	$metadataPrefix = $gdl_metadata->get_value($frm,"PREFIX");
	switch($metadataPrefix){
		case "oai_dc" : $result = display_contact_repository($frm);
						break;
		case "general": $result = display_contact_general($frm);
						break;
		default		  :
						$result = "";
	}
	
	return $result;
}

?>