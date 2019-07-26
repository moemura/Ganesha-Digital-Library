<?php 
if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

global $bufferFormSubmit;


function shortcut_repository(){
	global $gdl_sync;
	
	$id	= $gdl_sync['sync_repository_id'];
	$id	= (preg_match("/^[0-9]+$/",$id))?$id:"";
	
	$page	= $_GET['page'];
	$page	= (preg_match("/^[0-9]+$/",$page))?$page:1;
	
	$content="";
	if(!empty($id)){
		$url = "./gdl.php?mod=synchronization&amp;op=option&amp;";
		$identify 	= "<a href='$url"."page=$page&amp;update=Identify&amp;record=$id#responseAction'>Identify</a>";
		$set 		= "<a href='$url"."page=$page&amp;update=ListSets&amp;record=$id#responseAction'>Set</a>";
		$edit 		= "<a href='$url"."page=$page&amp;action=edit&amp;record=$id#editRepository'>Edit</a>";
		$delete		= "<a href='$url"."page=$page&amp;action=delete&amp;record=$id'>Delete</a>";
		
		$content	= "Shortcut action <b>..:: [$identify][$set][$edit][$delete] ::..</b><br/><br/><br/>";
	}
	
	return $content;
}

function search_repository_form(){
	global $gdl_form;

	$gdl_form	= new form();
	$gdl_form->set_name("filteringRepository");
	
	$gdl_form->action="./gdl.php?mod=synchronization&amp;op=option";
		
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"searchkey",			
				"value"=>"$_POST[searchkey]",
				"text"=>_SEARCHREPOSITORY,
				"size"=>30));
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>"",
			"value"=>_REPOSITORYSEARCH));			

	$content = $gdl_form->single_line("30%");	
	return $content;
}



function edit_form($frm="",$option="") {
	global $gdl_form,$gdl_sync,$gdl_db;
	
	if (empty($frm) && empty($option)) {
		foreach ($gdl_sync as $IdxGdlSync => $ValGdlSync) 
			$frm[$IdxGdlSync]=$ValGdlSync;
	}
	
	$gdl_form->set_name("edit_sync");
	$page = $_GET['page'];
	
	if(isset($page))$page = "&amp;page=$page";
	
	$gdl_form->action="./gdl.php?mod=synchronization&amp;op=option$page#editRepository";
	
	$array_set		= array("");
	$array_set_name	= array();
	$array_set_desc	= array();
	
	$repo_name 	= $frm["sync_repository_name"];
	$repo_id	= $frm["sync_repository_id"];
	if(empty($repo_name)) $repo_name = "N/A";
	if(!empty($repo_id)){
		$dbres = $gdl_db->select("Set","spec,name,description","nomor = $repo_id");
		if($dbres){
			$idx=0;
			while($row = mysqli_fetch_row($dbres)){
				array_push($array_set,$row[0]);
				$array_set_name[$row[0]] = $row[1];
				$array_set_desc[$row[0]] = $row[2];
			}
		}
	}
	
	$option_prefix 	= $frm["sync_opt_script"];	
	$use_proxy 		= (empty($frm["sync_use_proxy"]))?"0":$frm["sync_use_proxy"];
	$show_xml 		= (empty($frm["sync_show_response"]))?"0":$frm["sync_show_response"];
	
	if(empty($option))
		$additional = " ..::[ $repo_name ]::.. <a name=\"editRepository\"></a>";
	else
		$additional = "[Repository Baru]<a name=\"editRepository\"></a>";
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_CONFIGURATION.$additional));
			
	$gdl_form->add_field(array(
			"type"=>"hidden",
			"name"=>"frm[sync_repository_id]",
			"value"=>$repo_id));
			
	$gdl_form->add_field(array(
			"type"=>"hidden",
			"name"=>"frm[sync_repository_name]",
			"value"=>$repo_name));
			
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[sync_hub_server_name]",			
				"value"=>$frm["sync_hub_server_name"],
				"text"=>_TARGETSERVERNAME,
				"required"=>true,
				"size"=>50));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[sync_hub_server_port]",			
				"value"=>$frm["sync_hub_server_port"],
				"text"=>"Port",
				"required"=>true,
				"size"=>4));				
		
	$gdl_form->add_field(array(
				"type"=>"radio",
				"name"=>"frm[sync_use_proxy]",
				"checked"=>array(_NO,_YES),
				"value"=>$use_proxy,
				"required"=>true,
				"text"=>_USEPROXY   
				));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[sync_proxy_server_address]",			
				"value"=>$frm["sync_proxy_server_address"],
				"text"=>_PROXYADDRESS, 
				"size"=>50));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[sync_proxy_server_port]",			
				"value"=>(!empty($frm["sync_proxy_server_port"]))?$frm["sync_proxy_server_port"]:8080,
				"text"=>"Port",
				"size"=>4));			
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[sync_oai_script]",			
				"value"=>$frm["sync_oai_script"],
				"text"=>_OAISCRIPT,
				"required"=>true,
				"size"=>50));
	
	$gdl_form->add_field(array(
				"type"=>"radio",
				"name"=>"frm[sync_opt_script]",
				"checked"=>array("GENERAL","DUBLIN CORE"),
				"value"=>$option_prefix,
				"required"=>true,
				"text"=>_OPTION_SCRIPT
				));
	
	$count_set = count($array_set);	
	
	// Exclusive for connect to hub.indonesiadln.org
	if($gdl_sync['sync_hub_server_name'] == "hub.indonesiadln.org"){
		$array_set = array("under:node");
		$frm["sync_harvest_set"] = "0";
		$frm["sync_harvest_node"] = "0";
		$count_set = 2;
	}
	
	if($count_set > 1){
		$gdl_form->add_field(array(
				"type"=>"select",
				"option"=>$array_set,
				"name"=>"frm[sync_harvest_set]",			
				"value"=>$frm["sync_harvest_set"],
				"text"=>_SETOPTION));
				
		$gdl_form->add_field(array(
					"type"=>"text",
					"name"=>"frm[sync_harvest_node]",			
					"value"=>$frm["sync_harvest_node"],
					"text"=>_HARVESTALLRECORDSUNDERNODEID,
					"size"=>10));
	}
	
	$gdl_form->add_field(array(
					"type"=>"text",
					"name"=>"frm[sync_harvest_from]",			
					"value"=>$frm["sync_harvest_from"],
					"text"=>_OPTIONFROM,
					"size"=>10));
	$gdl_form->add_field(array(
					"type"=>"text",
					"name"=>"frm[sync_harvest_until]",			
					"value"=>$frm["sync_harvest_until"],
					"text"=>_OPTIONUNTIL,
					"size"=>10));
					
	$key = array_keys(array("3","5","10","15","20","25","30"),$frm["sync_count_records"]);
	
	$gdl_form->add_field(array(
				"type"=>"select",
				"option"=>array("3","5","10","15","20","25","30"),
				"name"=>"frm[sync_count_records]",			
				"value"=>$key[0],
				"text"=>_NUMOFRECORD,
				"required"=>true));

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[sync_fragment_size]",			
				"value"=>$frm["sync_fragment_size"],
				"text"=>_FRAGMENTSIZE, 
				"required"=>true,
				"size"=>10));
	$gdl_form->add_field(array(
				"type"=>"radio",
				"name"=>"frm[sync_show_response]",			
				"checked"=>array(_SHOW,_HIDE),
				"value"=>$show_xml,
				"required"=>true,
				"text"=>_SERVERRESPONSEDETAIL));
	
	$gdl_form->add_field(array(
				"type"=>"select",
				"option"=>array("Save Repository","Default Connection","Default Setting"),
				"name"=>"frm[sync_type_action]",			
				"text"=>_TYPEACTION,
				"required"=>true));
							
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"frm[submit]",
			"value"=>_SAVECHANGES)); 

	$content .= $gdl_form->generate();
	return $content;	
}


function synchronization_main() {
	global $gdl_synchronization,$gdl_sync;
	if ($gdl_synchronization->is_connected())
		$connect=_CONNECTED;
	else
		$connect=_NOT." "._CONNECTED;
		
	if($gdl_sync['sync_opt_script'] == "1")
		$req_format = " DUBLIN CORE (OAI standard)";
	else
		$req_format = " GENERAL";
		
	$content ="<p>Status : <b>".$connect."</b> "._TOTARGETSERVER." <b>".$gdl_sync['sync_repository_name']." (http://".$gdl_sync['sync_hub_server_name']."/".$gdl_sync['sync_oai_script'].")</b></p>";
	$content .="<p>"._REQUESTFORMAT." : <strong>$req_format</strong></p>";
	return $content;
}

function write_file_sync($frm) {
	global $gdl_sys,$gdl_synchronization,$bufferFormSubmit,$gdl_stdout;
	
	$action = $frm["sync_type_action"];
	
	if ($action == "2") {
		foreach ($frm as $IdxFrm => $ValFrm) {
			if ($IdxFrm <> "submit")
				$frm[$IdxFrm]=$gdl_sys[$IdxFrm];
		}
	} else {
		$sync_count_records=array("3","5","10","15","20","25","30");
		$frm["sync_count_records"]=$sync_count_records[$frm["sync_count_records"]];
	}

	
	/**
	* 0 : Save form to repository
	* 1 : Make default Connection 
	* 2 : Load default setting
	*/

	if($action=="2"){
		$bufferFormSubmit = $frm;
	}else{
		$option = ($action=="1")?1:0;
		if ($gdl_synchronization->save_configuration($frm,$option)){
			$gdl_synchronization->sync_disconnection();
			$message=_OPTIONSAVE;
			$message .= $gdl_stdout->header_redirect(1,"./gdl.php?mod=synchronization&op=option");
		}else{
			$message=_OPTIONSAVEFAILED;
		}
		$bufferFormSubmit="";
	}
	
	return $message;
}

function disconnection_form(){
	global $gdl_synchronization;
	
	if($gdl_synchronization->is_connected()){
		$gdl_synchronization->client_disconnection();
		
		$title = "Connecting to HUB Server";
		$msg = "<b>DISCONNECTED...</b><br>You have been disconnected from the Hub Server.";
	}else{
		$title = "Attention";
		$msg = "<b>YOU DID NOT HAVE CONNECTION TO SERVER</b>";
	}
	
	$html = "<table border=\"0\" collspacing=\"0\" collpadding=\"0\" width=\"50%\">";
		$html .= "<tr><td>$title</td></tr>";
		$html .= "<tr><td>$msg</td></tr>";
	$html .= "<table border=\"0\" collspacing=\"0\" collpadding=\"0\" width=\"50%\">";
	
	return $html;
}


function export_form() {
	global $gdl_form,$frm;
	
	$gdl_form->set_name("export");
	$gdl_form->action="./gdl.php?mod=synchronization&amp;op=export";
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_SETSTARTINGLASTMODIFIED));			

	$gdl_form->add_field(array(
				"type"=>"radio",
				"name"=>"frm[server]",
				"checked"=>array(_ALLSERVER,_MYGDLSERVER,_SERVERWITHPUBLISHERID),
				"value"=>$frm["server"],
				"required"=>true,
				"text"=>_EXPORTFROMSERVER   
				));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[publisher_id]",			
				"value"=>$frm["publisher_id"],
				"text"=>_PUBLISHERID, 
				"size"=>20));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[starting_date]",
				"column"=>false,
				"value"=>"",
				"text"=>_STARTINGDATE."<br>"._IFEMPTY,
				"size"=>13));			
				
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"frm[submit]",
			"value"=>_EXPORT)); 
			
	$content .= $gdl_form->generate();
	return $content;	
}

function export_process() {
	global $frm,$gdl_metadata,$gdl_publisher;
	if (strlen($frm["starting_date"]) <> 10)
		$frm["starting_date"]="0000-00-00";
	else {
			$temp=$frm["starting_date"];
			$frm["starting_date"]=substr($temp,6,4)."-".substr($temp,3,2)."-".substr($temp,0,2);
			
		}
	
	$strdump=$gdl_metadata->metadata_dump($frm["server"],$frm["publisher_id"],$frm["starting_date"]);
	if ($strdump)	{
	
		$end_date = date("Y-m-d");
		$filename = "files/export/metadata-".$gdl_publisher["id"];
		
		$gzfilename = "$filename.gz";
		$zp = gzopen($gzfilename, "w9");

		gzwrite($zp, $strdump);

		gzclose($zp);
		
		if (file_exists($gzfilename)){
			$content=_EXPORTSUCCESS;
			$content.="<br>"._FILENAME." : <b>$gzfilename</b>.
				<br>"._FILESIZE." : ".filesize($gzfilename)." bytes";
		} else {
			$content=_EXPORTFAILED;		
		}
		
		if (preg_match('/'._EXPORTSUCCESS.'/i',$content)){
			$str_info = $frm["starting_date"]."--$end_date";
			$fp = fopen("$filename.txt","w");
			fputs($fp,$str_info);
			fclose($fp);
		}
	} else {
		$content=_EXPORTFAILED;		
	}
	
	return $content;
	
}

function download_metadata_archive() {
	global $gdl_publisher;

	$fname_metadata = "files/export/metadata-".$gdl_publisher["id"].".gz";
	$fname_metadata_info = "files/export/metadata-".$gdl_publisher["id"].".txt";
	$content=_TODOWNLOAD;

	if (file_exists($fname_metadata)){
		// metadata
		$content.="<p><a href='".$fname_metadata."'><b>"._DOWNLOADMETADATA."</b></a> for
			".join('',file($fname_metadata_info))." (".filesize($fname_metadata)." bytes).";	
	} else {
		$content=_METADATANOTARCHIVED;
	}

	return $content;
}

function import_form() {
	global $gdl_form,$frm;
	
	$gdl_form->set_name("export");
	$gdl_form->action="./gdl.php?mod=synchronization&amp;op=import";
	$gdl_form->enctype=true;
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>"Upload File (metadata-PUBLISHERID.gz)"));			

	$gdl_form->add_field(array(
				"type"=>"file",
				"name"=>"archived_file",
				"text"=>"File"   
				));	
				
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"frm[submit]",
			"value"=>"Upload")); 
			
	$content = "<p>"._UPLOADARCHIVEDMETADATA."</p>";
	$content .= $gdl_form->generate();
	return $content;	
}

function list_of_uploaded_file() {
	$content="<p>"._IMPORTUPLOADEDFILE."</p>";
		
	require_once("./class/repeater.php");

	$dirhandle=opendir("./files/import/");
	
	
	if ($dirhandle) {
			
			$grid=new repeater();
			
			$header[1]="No";
			$header[2]=_FILENAME;
			$header[3]=_DATE;
			$header[4]=_FILESIZE."(bytes)";
			$header[5]=_FROM;
			$header[6]="Status";
			$header[7]=_ACTION;
			
			$no=1;
			
			while (($file = readdir($dirhandle)) !== false) {
				if (preg_match("/.gz/i",$file) && preg_match("/metadata-/i",$file) && !preg_match("/.log/i",$file)) {
					$pubid = explode("-",$file);
					$pubid2 = explode(".",$pubid[1]);
					$from = $pubid2[0];
					if (empty($from)) continue;
					
					$log = "files/import/$file.log";
					if (file_exists($log)){
						$status = join('',file($log));
					} else {
						$status = "New";
					}
				
					$field[1]=$no;
					$field[2]="<a href=files/import/$file>$file</a>";
					$field[3]=date("Y-m-d h:i:s",filemtime("files/import/$file"));
					$field[4]=filesize("files/import/$file");
					$field[5]=$from;
					$field[6]=$status;
					$field[7]="<a href=\"./gdl.php?mod=synchronization&amp;op=import&amp;action=import&amp;filename=".$file."\">"._IMPORT."</a> - <a href=\"./gdl.php?mod=synchronization&amp;op=import&amp;action=delete&amp;file=".$file."\">"._DELETE."</a>";
					$no++;
					$item[]=$field;
				}
			}
			
			$colwidth[1] = "10px";
			$colwidth[2] = "75px";
			$colwidth[3] = "75px";
			$colwidth[4] = "15px";
			$colwidth[5] = "75px";
			$colwidth[6] = "75px";
			$colwidth[7] = "75px";
			
					
			$grid->header=$header;
			$grid->item=$item;
			$grid->colwidth=$colwidth;
			
			$content.= @$grid->generate();			
		}
	closedir($dirhandle);
	return $content;
}

function upload_file() {
	global $frm,$_FILES,$gdl_sys;
	
	$content="<p>";
	if (preg_match("/gzip/i",$_FILES["archived_file"]["type"])) {
		if (preg_match("/metadata-/i",$_FILES["archived_file"]["name"])) {
			if ($_FILES["archived_file"]["size"] < $gdl_sys['sync_maxsize_gzfile']) {
			    if (@is_uploaded_file($_FILES["archived_file"]["tmp_name"])) {
				  if (copy($_FILES["archived_file"]["tmp_name"],"./files/import/".$_FILES["archived_file"]["name"])) {
					@unlink("./files/import/".$_FILES["arhived_file"]["name"].".log");
					$content.=_UPLOADFILESUCCESS;					
				   } else
					$content.=_UPLOADFILEERROR;
			     } else
				       $content.=_UPLOADFILEERROR;
			} else
				$content.=_UPLOADFILESIZEERROR." ".$gdl_sys['sync_maxsize_gzfile']." bytes";
		} else
			$content.=_METADATAUPLOADERROR;
	} else
		$content.=_GZIPUPLOADERROR.$_FILES["archived_file"]["type"];
		
	$content.="</p>";

	return $content;
}

function delete_file($filename) {
	if (@unlink("./files/import/".$filename)) 
		$content="file <b>/files/import/".$filename."</b>"._DELETESUCCESS;
	else
		$content=_DELETEFAILED."<b>/files/import/".$filename."</b>";
	
	return $content;
}

function import_metadata($filename) {
	
	$maxsize=1000000;
	$gzfile="./files/import/".$filename;
	if (file_exists($gzfile)) {
		$gzhandle=gzopen($gzfile,"r");
		while (!gzeof($gzhandle))	{
				$xmldata=gzread($gzhandle,$maxsize);				
			}
		gzclose($gzhandle);	
		$content.=$xmldata;
	} else
		$content.="file <b>".$gzfile."</b> "._NOTFOUND;
	return $content;
}


function box_files($main_url="",$folder=""){
	global $gdl_sys,$gdl_form,$frm,$gdl_synchronization;

	$parent	= $gdl_sys['repository_dir'];

	$handle 	= @opendir($parent);
	$arr_dir 	= array();
	$arr_file	= array();

		while(($entry = @readdir($handle)) > -1){
			if((strcasecmp($entry,".") == 0) || (strcasecmp($entry,"..") == 0)){continue;}
			
			$object = $parent."/".$entry;
			if(is_dir($object)){
				$arr_dir[$entry]=$object;
			}else {
				$arr_file[$entry]=$object;
			}
		}
	closedir($handle);
	
	$uri = "./gdl.php?mod=synchronization&op=posting&sub=1";
	if(empty($folder)) $folder = $parent."/0";
	foreach ($arr_dir as $index => $value){
		if($folder == $value){
			$dir_nav 	.= "&nbsp;[<strong>".$index."</strong>]&nbsp;";
			$directory	= $value;
		}else
			$dir_nav .= "&nbsp;<a href=\"$uri&amp;path=$value\">".$index."</a>&nbsp;";
	}
	
	if(!empty($directory))
		$path = "&amp;path=$directory";
	
	$gdl_form->set_name("sendfile");
	$table = "<form method=\"post\" action=\"gdl.php?mod=synchronization&op=posting&sub=1"."$path\">\n";
		$table .= "<table border=0>\n";
			$table .= "<tr bgcolor=\"#6666CC\" style=\"color:#ffffff;\"  height=\"20px\" align=\"center\">\n";
				$table .= "<td><b>"._NOFILE."</b></td>\n";
				$table .= "<td><b>"._POSTFILE."</b></td>\n";
				$table .= "<td><b>"._FILENAME."</b></td>\n";
				$table .= "<td><b>"._LASTDATE."</b></td>\n";
				$table .= "<td><b>"._SIZE."(Bytes)</b></td>\n";
			$table .= "</tr>\n";
			
	$i=1;
	if(empty($directory))
		$directory = $arr_dir[0];
	
	$list_job	= array();
	$array_job	= $gdl_synchronization->get_list_queue($folder);
	foreach ($array_job as $key => $val) {array_push($list_job,$val['PATH']);}
	$handle 	= @opendir($directory);
	$bgcolor="bgcolor=\"#CCCCFF\" height=\"25px\"";
	while(($entry = @readdir($handle)) > -1){
		if((strcasecmp($entry,".") == 0) || (strcasecmp($entry,"..") == 0)){continue;}
		$object = $directory."/".$entry;
		if(is_file($object)){
			$b_color="";
			if($i % 2 == 0) $b_color = $bgcolor;
			$table .= "<tr $b_color>\n";
				$table .= "<td>$i</td>\n";
				$check	= (in_array($object,$list_job))?"checked":"";
				$table .= "<td><input type =\"checkbox\" name=\"frm[$object]\" value=\"$object\" $check/></td>\n";
				$table .= "<td>$entry</td>\n";
				$waktu_akses = fileatime($object);
				$table .= "<td>".date("j F Y, H:m:i",$waktu_akses)."</td>\n";
				$table .= "<td>".filesize($object)."</td>\n";
			$table .= "<tr>\n";
			$i++;
		}
	}
	@closedir($handle);
				
	$table .= "</table>\n";
	$table .= "<br><input type=\"submit\" name=\"frm[submit]\" value=\"QUEUE\">\n";
	$table .= "</form>\n";
	return $dir_nav.$table;
}

function display_repository($searchkey){
	global $gdl_content,$gdl_synchronization,$gdl_sys;

	
	require_once("./class/repeater.php");
	
	$page	= $_GET['page'];
	if (!isset($page)){
	 	$page = 0 ;
	}else{
		$page = $page-1;
	}

	$limit	= $gdl_sys['perpage_publisher'];
	$start 	= $page * $limit;
	$total	= $gdl_synchronization->get_total_repository($searchkey);
	
	if(!empty($searchkey)){
		$start 	= 0;
		$limit	= $total;
	}
	
	$repositorydata	=	$gdl_synchronization->get_list($searchkey,$start,$limit);
	$count	= count($repositorydata);

			$grid=new repeater();
			
			$header[1]=_REPOSITORYNUMBER;
			$header[2]=_PUBLISHERID;
			$header[3]=_REPOSITORYNAME;
			$header[4]=_BASEURL;
			$header[5]=_PREFIX;
			$header[6]=_REPOSITORYUPDATE;
			$header[7]=_REPOSITORYACTION;
			
			$page = $page + 1;
			$pages = ceil($total/$gdl_sys['perpage_publisher']);
			$start = 1 + (($page-1) * $gdl_sys['perpage_publisher']);
			$url = "./gdl.php?mod=synchronization&amp;op=option&amp;";
			$j=$start;
			
			if(is_array($repositorydata))
				foreach ($repositorydata as $key => $val) {
					$identify 	= "<a href='$url"."page=$page&amp;update=Identify&amp;record=$val[REC]#responseAction'>Identify</a>";
					$set 		= "<a href='$url"."page=$page&amp;update=ListSets&amp;record=$val[REC]#responseAction'>Set</a>";
					$edit 		= "<a href='$url"."page=$page&amp;action=edit&amp;record=$val[REC]#editRepository'>Edit</a>";
					$delete		= "<a href='$url"."page=$page&amp;action=delete&amp;record=$val[REC]'>Delete</a>";
					$field[1]= $j;
					$field[2]= $val['ID'];
					$field[3]= $val['NAME'];
					$field[4]= $val['URL'];
					$field[5]= $val['PREFIX'];
					$field[6]= "[$identify][$set]";
					$field[7]= "[$edit][$delete]";
					$j++;
					$item[]=$field;
				}
			
			
			$colwidth[1] = "10px";
			$colwidth[2] = "45px";
			$colwidth[3] = "150px";
			$colwidth[4] = "15px";
			$colwidth[5] = "35px";
			$colwidth[6] = "75px";
			$colwidth[7] = "75px";
			
					
			$grid->header=$header;
			$grid->item=$item;
			$grid->colwidth=$colwidth;
			
			if ($page==1){
			$pref_nav = "<a href=\"$url"."page=1\">&laquo; Prev</a>";
			} else{
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
			
			$end = $start + $count - 1;
			$form = "<p class=\"contentlisttop\">"._REPOSITORYDISPLAYING." $start - $end "._OF." total $total Repository<br/>";
			if (empty ($searchkey))
				$form .= "<span><strong>$pref_nav</strong> | <strong>$next_nav</strong></span></p>";
			
			$form.= "[<a href='index.php?mod=synchronization&amp;op=option&amp;action=add#editRepository'>"._REPOSITORYADD."</a>]";
			$form.= "[<a href='index.php?mod=synchronization&amp;op=option&amp;action=repo'>"._REPOSITORYFROMPUBLISHER."</a>]";
			$form.= "[<a href='index.php?mod=synchronization&amp;op=option&amp;action=show#editRepository'>"._CURRENTREPOSITORYCONNECTION."</a>]";
			$form.= $grid->generate();
			
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
			if (empty ($searchkey))
				$form .= "<p class=\"contentlistbottom\">$page_nav</p>\n";

	return $form;	
}


function box_queue(){
	global $gdl_content,$gdl_synchronization,$gdl_sys,$gdl_form;

	
	require_once("./class/repeater.php");
	
	$page=$_GET['page'];
	$page = $_GET['page'];
	if (!isset($page)){
	 	$page = 0 ;
	}else{
		$page = $page-1;
	}

	$limit	= $gdl_sys['perpage_publisher'];
	$start 	= $page * $limit;
	$total	= $gdl_synchronization->get_total_queue();
	$queuedata	=	$gdl_synchronization->get_list_queue($folder,$start,$limit);
	$count	= count($queuedata);

			$grid=new repeater();
			
			$header[1]=_QUEUENUMBER;
			$header[2]=_QUEUEPATH;
			$header[3]=_QUEUESTATUS;
			$header[4]=_QUEUEACTION;
			
			$page = $page + 1;
			$pages = ceil($total/$gdl_sys['perpage_publisher']);
			$start = 1 + (($page-1) * $gdl_sys['perpage_publisher']);
			$url = "./gdl.php?mod=synchronization&op=posting&sub=1&amp;";
			$j=$start;
			
			if(is_array($queuedata))
				foreach ($queuedata as $key => $val) {
					$delete		= "<a href='$url"."page=$page&amp;action=delete&amp;record=$val[NO]'>Delete</a>";
					$field[1]= $j;
					$field[2]= $val['PATH'];
					$field[3]= $val['STATUS'];
					$field[4]= "[$delete]";
					$j++;
					$item[]=$field;
				}
			
			
			$colwidth[1] = "10px";
			$colwidth[2] = "300px";
			$colwidth[3] = "50px";
			$colwidth[4] = "150px";
			
					
			$grid->header=$header;
			$grid->item=$item;
			$grid->colwidth=$colwidth;
			
			if ($page==1){
			$pref_nav = "<a href=\"$url"."page=1\">&laquo; Prev</a>";
			} else{
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
			
			$end = $start + $count - 1;
			$form = "<p class=\"contentlisttop\">"._POSTINGDISPLAYING." $start - $end "._OF." total $total posting file<br/>";
			if (empty ($searchkey))
				$form .= "<span><strong>$pref_nav</strong> | <strong>$next_nav</strong></span></p>";
			
			$form.= $grid->generate();
			
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
			if (empty ($searchkey))
				$form .= "<p class=\"contentlistbottom\">$page_nav</p>\n";
				
		$gdl_form->set_name("posting_file");
		$gdl_form->action="./gdl.php?mod=synchronization&op=posting&sub=1";
		$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_STARTPOSTING));
		$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"frm[posting]",
			"value"=>_POSTINGFILES)); 
		$content .= $gdl_form->generate();
	
	return $form.$content;
}

function box_status_outbox($url){
	global $gdl_synchronization;
	
	require_once("./class/repeater.php");
	$grid=new repeater();
			
	$header[1]=_OUTBOX_NUMBER;
	$header[2]=_OUTBOX_FOLDER;
	$header[3]=_OUTBOX_SUM;
	$header[4]=_OUTBOX_ACTION;
	
	$statusdata = $gdl_synchronization->get_list_status_outbox();
	if(is_array($statusdata))
		foreach ($statusdata as $key => $val) {
			$delete		= "<a href='$url". "&amp;action=delete&amp;status=$val[STATUS]'>Delete</a>";
			$field[1]= ++$j;
			$field[2]= $val['STATUS'];
			$field[3]= $val['COUNT'];
			$field[4]= "[$delete]";
			$item[]=$field;
		}
				
	$colwidth[1] = "10px";
	$colwidth[2] = "100px";
	$colwidth[3] = "50px";
	$colwidth[4] = "150px";
	
			
	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	
	$extract		= "[<a href='$url". "&amp;action=extract>Extract Identifier</a>]";
	
	$form.= $grid->generate("195");
	
	return $form.$extract;
}

function box_status_posting($url,$sub){
	global $gdl_synchronization,$gdl_harvest;
	
	$curr_publisher	= $gdl_harvest->get_current_publisher();
	
	if($curr_publisher == null) return "";
	
	require_once("./class/repeater.php");
	$grid=new repeater();
			
	$header[1]=_BOX_NUMBER;
	$header[2]=_BOX_PATH;
	$header[3]=_BOX_STATUS;
	$header[4]=_BOX_ACTION;
	

	$statusdata = $gdl_synchronization->get_list_queue_finish_job();
	if(is_array($statusdata))
		foreach ($statusdata as $key => $val) {
			$delete		= "<a href='$url". "&amp;sub=$sub&amp;action=delete&amp;record=$val[NO]'>Delete</a>";
			$queue		= "<a href='$url". "&amp;sub=$sub&amp;action=queue&amp;record=$val[NO]'>Re-queue</a>";
			
			$action = ($val['STATUS'] == "success")?"[$delete]":"[$delete] [$queue]";
			
			$field[1]= ++$j;
			$field[2]= $val['PATH'];
			$field[3]= $val['STATUS'];
			$field[4]= $action;
			$item[]=$field;

		}
				
	$colwidth[1] = "10px";
	$colwidth[2] = "100px";
	$colwidth[3] = "50px";
	$colwidth[4] = "150px";
	
			
	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	
	$form.= "<br/><br/>"._BOX_STATUS_POSTING." ($curr_publisher)<br/>".$grid->generate("195");
	
	return $form;

}
?>