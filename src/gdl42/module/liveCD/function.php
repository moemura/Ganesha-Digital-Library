<?php
/***************************************************************************
    copyright            : (C) 2007 Arif Suprabowo, KMRG ITB
    email                : mymails_supra@yahoo.co.uk
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/
 if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

//  ================================= Making manipulation to fetch folder =========================================
$print_A = "";
$print_B = "";

class nodeTreeJob{
	var $info;
	var $listNode = array();
}

function extended_processing_fetch_folder($dist_node){
	global $print_A,$print_B,$gdl_liveCD;
	
	//echo "DIST : $dist_node \n";
	$_SESSION['tree_job'] = null;
	$tree				  = null;
	$list_path = array();
	if(is_array($dist_node)){
		for($i=0; $i<count($dist_node);$i++){
		   // echo "DIST-SUB : $dist_node[$i] \n";
			$list_path = getPath_from_givenNode($dist_node[$i],$list_path);
		}
	}else{
		$list_path = getPath_from_givenNode($dist_node,$list_path);
	}
	
	$c_list_path = count($list_path);
	
	for($i=0;$i<$c_list_path;$i++){
		$arr_node = explode("/",$list_path[$i]);
		$tree = make_tree($arr_node,$tree);
	}
	
	print_info_tree($tree);
	$limit = empty($gdl_liveCD->lv_limit)?20:$gdl_liveCD->lv_limit;
	
	$script 	= $print_A.$print_B;
	$arr_path	= explode("\n",$script);
	$num_record	= count($arr_path);
	$max_token	= ceil($num_record/$limit);
	
	$rs['count']= $max_token;
	$rs['tree']	= $arr_path;
	$rs['limit']= $limit;
	
	$_SESSION['tree_job'] = $rs;
	
	//echo "SCRIPT-TREE : \n $script \n\n";
}

function getPath_from_givenNode($node,$arr_path){
	global $gdl_db;
	
	//$gdl_db->print_script = true;
	if($node > 0)
		$dbres 		= $gdl_db->select("folder","folder_id,path,count","path like '%/$node/%'");
	else 
		$dbres 		= $gdl_db->select("folder","folder_id,path,count","path like '$node/%'");
	//$gdl_db->print_script = false;
	
	$num_rows = @mysqli_num_rows($dbres);
	if($num_rows == 0){
		$dbres 		= $gdl_db->select("folder","folder_id,path,count","((folder_id = $node) or (parent = $node))");
	}
		
	//echo "GIVEN-NODE : $node \n";
	if(!is_array($arr_path))
		$arr_path = array();
		
	while($row = @mysqli_fetch_row($dbres)){
		//echo "PATH-ROW : $row[0] $row[1] \n";
		if($row[2] > 0){
			if(!in_array("$row[1]/$row[0]",$arr_path))
				array_push($arr_path,"$row[1]/$row[0]");
		}
	}
	
	return $arr_path;
}

function make_tree($arr_node,$tree){

	if(is_array($arr_node)){
		if($tree == null){
			//echo "NULL TREE \n";
			$tree = new nodeTreeJob();
			$tree->info = $arr_node[0];
		}
		
		if($arr_node[0] == "0"){
			$curr_node = &$tree;
			for($i=1;$i<count($arr_node);$i++){
				//echo "INSERT-LEAF : $arr_node[$i] \n";
				
				$arr_node[$i] = trim($arr_node[$i]);
				
				if(strlen($arr_node[$i]) > 0){
					$object = &$curr_node->listNode[$arr_node[$i]];
					if($object == null){
						$object = new nodeTreeJob();
						$object->info = $arr_node[$i];
						$curr_node->listNode[$arr_node[$i]] = &$object;
						//echo "NEW-LEAF : ".$object->info."\n";
					}
					//echo "C-PARENT-LEAF : ".$curr_node->info." (".count($curr_node->listNode).") \n";
					$curr_node = &$object;
				}
			}
		}
	}
	//echo "\n\n";
	return $tree;
}

function print_info_tree($node_tree){
	global $print_A,$print_B;
	//echo "NT : $node_tree \n";
	if($node_tree != null){
		$print_A .= $node_tree->info." ";
		//echo "COUNT-LEAF : ".count($node_tree->listNode)."\n";
		if(count($node_tree->listNode) > 0){
			foreach($node_tree->listNode as $info => $node)
				$print_A .= "$info/";
			$print_A .= "\n";
			//echo "PA : $print_A \n";
			foreach($node_tree->listNode as $info => $node){
				if(count($node->listNode) > 0)
					print_info_tree($node);
				else
					$print_B .= $node->info."\n";
			}
			//echo "PB : $print_B \n";
		}
	}
}
//  ============================================================================

function start_buildLiveCD($node,$url){
	global $gdl_form;

	$gdl_form	= new form();
	$gdl_form->set_name("buildLiveCD");
	
	$gdl_form->action=$url;
	if(is_array($node))
		$node	= implode("-",$node);
		
	$gdl_form->add_field(array("type"=>"title","text"=>_TITLESTARTLIVECD));
	$gdl_form->add_field(array("type"=>"hidden","name"=>"frm[node]","value"=>$node));
	$gdl_form->add_field(array("type"=>"select"
							  ,"option"=>array(_NO,_YES)
							  ,"name"=>"frm[file]"
							  ,"required"=>true
							  ,"text"=>_LIVECDINCLUDEFILE));
							  
	$gdl_form->add_field(array("type"=>"select"
							  ,"option"=>array(_NO,_YES)
							  ,"name"=>"frm[folks]"
							  ,"required"=>true
							  ,"text"=>_LIVECDINCLUDEFOLKSONOMY));
							  
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"frm[start]",
			"column"=>"",
			"value"=>_STARTBUILDLIVECD));			

	$content = $gdl_form->generate("100");	
	return $content;
}


function box_info_connection($node,$url){
	global $gdl_liveCD;
	
	$_SESSION['livecd_node']	= null;
	$gdl_liveCD->lv_parentNode 	= $node;
	$gdl_liveCD->setPreConnection();
	
	require_once("./class/repeater.php");
	
	$data_connection			= $gdl_liveCD->getInfoConnectionHandle();

	if(is_array($data_connection) && ($node == -1)){
		$buffer['HOST URL']			= $data_connection[0]['host_url'];
		$buffer['PORT HOST']		= $data_connection[0]['port_host'];
		
		$len	= count($data_connection);
		$node	= array();
		for ($i=0;$i<$len;$i++){

			array_push($node,$data_connection[$i]['under_node']);
			$buffer['UNDER NODE']		= empty($buffer['UNDER NODE'])?
												$data_connection[$i]['under_node']:
												$buffer['UNDER NODE']."<br/>".$data_connection[$i]['under_node'];

		}
		
		$data_connection	= $buffer;
	}
	
	$grid				=	new repeater();
	
	$header[1]	=	_CONNINFO;
	$header[2]	=	_CONNVALUE;
	
	if(is_array($data_connection))
	foreach ($data_connection as $index => $value){
		$field[1]	= $index;
		
		if($index == "use_proxy")
			$value = $value?"YES":"NO";
			
		$field[2]	= $value;
		$item[]=$field;
	}
	
	$colwidth[1] = "15px";
	$colwidth[2] = "45px";

			
	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	
	$form	= $grid->generate();
	$form	= ($data_connection == null)?$form:$form.start_buildLiveCD($node,$url);
	return "<p align=\"right\"><strong>"._CONFIRMATIONJOB."</strong></p>".$form;
}

function get_listNode($filter,$start,$limit){
	global $gdl_db;

	if(!empty($filter)){
		$filter = "name like '%$filter%'";
	}
	
	$dbres = $gdl_db->select("folder","folder_id,name",$filter,"","","$start,$limit");
	
	while ($row = @mysqli_fetch_row($dbres)) {
		$result[$row[0]]['NODE']	= $row[0];
		$result[$row[0]]['NAME']	= $row[1];
	}
	
	return $result;
}

function getTotalNode($filter){
	global $gdl_db;

	if(!empty($filter)){
		$filter = "name like '%$filter%'";
	}
	
	$dbres = $gdl_db->select("folder","count(folder_id) as total",$filter);
	$row = @mysqli_fetch_assoc($dbres);
	return (int)$row["total"];
}

function handle_build_liveCD($url,$url2){
	global $gdl_liveCD,$gdl_stdout,$gdl_sys;
	
	$frm 	= $_POST['frm'];
	$type	= $_GET['type'];
	
	$gdl_liveCD->lv_limit	= intval($gdl_sys["perpage_browse"]);
	if(is_array($frm)){
		$_SESSION['livecd_relation']	= (((int)$frm['file']) == 0)?"false":"true";
		$_SESSION['livecd_folksonomy']	= (((int)$frm['folks']) == 0)?"false":"true";
		
		$type						= "folder";
		$arr_node	= explode("-",$frm['node']);

		if(count($arr_node) > 1)
			$frm['node']	= $arr_node;
		
		extended_processing_fetch_folder($frm['node']);
		
		$node	= is_array($frm['node'])?array_shift($frm['node']):$frm['node'];
		
		$_SESSION['livecd_arr_node']= null;
		$_SESSION['livecd_arr_node']= $frm['node'];
				
		$_SESSION['livecd_paging']	= null;
		$_SESSION['livecd_node']	= $node;
		$_SESSION['livecd_token']	= 1;
		$token						= 0;

		$gdl_liveCD->lv_parentNode 	= $node;
		$gdl_liveCD->setPreConnection();
		
		$status		= $gdl_liveCD->fetchPage($token,$type);
		$message	= handleMessage_Fetch($token,$type);
		
		compress_liveCD();
	}else{
		if(($type == "folder") || ($type == "metadata") || ($type == "paging") || ($type == "folksonomy")){
			$gdl_liveCD->lv_parentNode 	= $_SESSION['livecd_node'];
			$token						= $_SESSION['livecd_token'];
			$gdl_liveCD->setPreConnection();
			
			$status		= $gdl_liveCD->fetchPage($token,$type);
			$message	= handleMessage_Fetch($token,$type);
			
			if($type != "folksonomy")
				$_SESSION['livecd_token']	= $token+1;
			else{
				if(!is_array($_SESSION['livecd_metadataFolks']))
					$_SESSION['livecd_token']	= $token+1;
			}
		}
	}
	
	if($type == "folder"){
		if($status){
			$message .= $gdl_stdout->header_redirect(1,$url."&amp;type=folder");
		}else{
			$message .= $gdl_stdout->header_redirect(1,$url."&amp;type=metadata");
			$_SESSION['livecd_token']	= 0;
		}
	}else if($type == "metadata"){
		if($status){
			$message .= $gdl_stdout->header_redirect(1,$url."&amp;type=metadata");
		}else{
			$message .= $gdl_stdout->header_redirect(1,$url."&amp;type=folksonomy");
			$_SESSION['livecd_token']	= 0;
		}
	}else if($type == "folksonomy"){
		if($status){
			$message .= $gdl_stdout->header_redirect(1,$url."&amp;type=folksonomy");
		}else{
			$message .= $gdl_stdout->header_redirect(1,$url."&amp;type=paging");
			$_SESSION['livecd_token']	= 0;
		}
	}else if($type == "paging"){
		if($status){

			$message .= $gdl_stdout->header_redirect(1,$url."&amp;type=paging");
		}else{
			
			if(is_array($_SESSION['livecd_arr_node'])){
				$_SESSION['livecd_node']	= trim(array_shift($_SESSION['livecd_arr_node']));
				$_SESSION['livecd_token']	= 0;
				$_SESSION['livecd_paging']	= null;
				
				if(strlen($_SESSION['livecd_node']) > 0)
					$message .= $gdl_stdout->header_redirect(1,$url."&amp;type=folder");
				else{
					$message .= $gdl_stdout->header_redirect(1,$url."&amp;type=paging");
					$_SESSION['livecd_arr_node'] = null;
				}
			}else{
				
				$_SESSION['livecd_node']	= null;
				$_SESSION['livecd_token']	= null;
				$_SESSION['livecd_paging']	= null;
				$_SESSION['livecd_arr_node']= null;
				$_SESSION['livecd_relation']= null;
				$_SESSION['tree_job'] 		= null;
				
				
				compress_liveCD("closing");
				$message .= $gdl_stdout->header_redirect(1,$url2);
			}
		}
	}

	return $message;
}

function handleMessage_Fetch($token,$type){
	global $gdl_liveCD;
	
	if($type == "folder"){
		$cursor = $token + 1;
		if(is_array($_SESSION['tree_job'])){
			$rs 		= $_SESSION['tree_job'];
			$max_token	= $rs['count'];
			if(count($rs['tree']) == 0){
				$cursor = $max_token;
			}
		}else{
			$rs			= $gdl_liveCD->TotalFetch_Folder();
			$max_token	= $rs['MAX_TOKEN']+1;
		}
		
		$message	.= "<br/><b>Step-1 :</b> Processing fetch folder ($cursor of $max_token)";
		
		if($cursor == $max_token){
			$message = "<br/><b>Step-1 :</b> Accomplished fetch folder.\n";
			$message .= "<br/><b>&nbsp;&nbsp;&nbsp;Next :</b> Processing fetch metadata.\n";
		}
		
	}else if($type == "metadata"){
		$cursor = $token + 1;
		
		$rs			= $gdl_liveCD->TotalFetch_Metadata();
		$max_token	= $rs['MAX_TOKEN']+1;
		$message 	= "<br/><b>Step-1 :</b> Accomplished fetch folder.\n";
		$message	.= "<br/><b>Step-2 :</b> Processing fetch metadata ($cursor of $max_token)";
		
		if($cursor == $max_token){
			$message = "<br/><b>Step-1 :</b> Accomplished fetch folder.\n";
			$message .= "<br/><b>Step-2 :</b> Accomplished fetch metadata.\n";
			$message .= "<br/><b>&nbsp;&nbsp;&nbsp;Next :</b> Processing fetch folksonomy.\n";
		}
		
	}else if($type == "folksonomy"){
		$cursor = $token + 1;
		
		$max_token	= 26;
		$message 	= "<br/><b>Step-1 :</b> Accomplished fetch folder.\n";
		$message 	.= "<br/><b>Step-2 :</b> Accomplished fetch metadata.\n";
			if($_SESSION['livecd_folksonomy'] == "true"){
				$message	.= "<br/><b>Step-3 :</b> Processing fetch folksonomy ($cursor of $max_token)";
			}else
				$message	.= "<br/><b>Step-3 :</b> Processing fetch folksonomy ($cursor of $max_token) ..... (skipped)";
				
		if($cursor == $max_token){
			$message = "<br/><b>Step-1 :</b> Accomplished fetch folder.\n";
			$message .= "<br/><b>Step-2 :</b> Accomplished fetch metadata.\n";
			$message .= "<br/><b>Step-3 :</b> Accomplished fetch folksonomy.\n";
			$message .= "<br/><b>&nbsp;&nbsp;&nbsp;Next :</b> Processing fetch paging.\n";
		}
		
	}else if($type == "paging"){
		
		if(is_array($_SESSION['livecd_paging'])){
			$num_cell	 = count($_SESSION['livecd_paging']);
			
			$cell 		= $_SESSION['livecd_paging'][0];
			$paging		= ceil($cell['COUNT']/$gdl_liveCD->lv_limit);
			
			if(($num_cell == 0) && ($paging == 0)){
				$message =  "<br/><b>Step-1 :</b> Accomplished fetch folder.\n";
				$message .= "<br/><b>Step-2 :</b> Accomplished fetch metadata.\n";
				$message .= "<br/><b>Step-3 :</b> Accomplished fetch folksonomy.\n";
				$message .= "<br/><b>Step-4 :</b> Accomplished fetch cell paging.\n";
				if(!is_array($_SESSION['livecd_arr_node'])){
					$message .= "<br/>Accomplished job to make live CD.\n";
					
					if(($_SESSION['livecd_folksonomy'] == "false") || $_SESSION['livecd_relation'] == "false"){
						$message .= "<br/>&nbsp;&nbsp;<b>Note : </b>";
						
						if($_SESSION['livecd_folksonomy'] == "false")
							$message .= "<br/>&nbsp;&nbsp;&nbsp;+>Folksonomy is skipped.";
							
						if($_SESSION['livecd_relation'] == "false")
							$message .= "<br/>&nbsp;&nbsp;&nbsp;+>File relation does not be included.";
					}
					
				}else{
					$count	= count($_SESSION['livecd_arr_node']);
					if($count > 0)
						$message .= "<br/>Waiting execute next node .......($count queue job again)\n";
					else{
						$message .= "<br/>Accomplished job to make live CD.\n";
						$message .= "<br/><b>&nbsp;&nbsp;&nbsp;Next :</b> Compress Live CD File with format <b>liveCD-<epoch Time>.tar.gz</b> .\n";
					}
				}
			}else{
				$message 	= "<br/><b>Step-1 :</b> Accomplished fetch folder.\n";
				$message 	.= "<br/><b>Step-2 :</b> Accomplished fetch metadata.\n";
				$message 	.= "<br/><b>Step-3 :</b> Accomplished fetch folksonomy.\n";
				$message	.= "<br/><b>Step-4 :</b> Processing fetch cell paging.\n";
				$message	.= "<br/>&nbsp;&nbsp;&nbsp; Fetch $num_cell cell paging again.\n";
				$message	.= "<br/>&nbsp;&nbsp;&nbsp; Paging $paging again.\n";
			}
		}else{
			$message 	=  "<br/><b>Step-1 :</b> Accomplished fetch folder.\n";
			$message 	.= "<br/><b>Step-2 :</b> Accomplished fetch metadata.\n";
			$message 	.= "<br/><b>Step-3 :</b> Accomplished fetch folksonomy.\n";
			$message 	.= "<br/><b>Step-4 :</b> Initializing cell paging.";
		}
	}
	
	return "<b>Please Wait .................................</b> \n".$message;
}


function box_folder($url){
	global $gdl_liveCD,$gdl_content,$gdl_sys;
	
	$node	= $_GET['node'];
	$node	= preg_match("/^[0-9]+$/",$node)?$node:0;

	$rs_folder	= $gdl_liveCD->getListFolder($node);
	$arr_name	= $gdl_liveCD->getTaksonomyFolder($node);
	if(is_array($arr_name)){
		foreach ($arr_name as $index => $value){
			$path	=(empty($path))?"<a href=\"$url&amp;node=$index\">$value</a> ":
								    "$path".$gdl_sys[folder_separator]." <a href=\"$url&amp;node=$index\">$value</a>&nbsp;&nbsp;";
		}
	}
	
	require_once("./class/repeater.php");
			
	$grid				=	new repeater();
	
	$header[1]	=	_NOMOR;
	$header[2]	=	_FOLDERNAME;
	$header[3]	=	_FOLDERCOUNT;
	$header[4]	=	_FOLDERACTION;
		
	if(is_array($rs_folder)){
				
		foreach ($rs_folder as $index => $value){
			
			if($value['STATE'] == "node")
				$folder		= "<a href=\"$url&amp;node=$index\">$value[NAME]</a>";
			else 
				$folder		= $value['NAME'];
			
			$field[1]	= ++$j;
			$field[2]	= "<img src=\"./theme/".$gdl_content->theme."/image/icon_dir_list.png\" alt=\"\"/>&nbsp;&nbsp;$folder";
			$field[3]	= $value['COUNT'];
			$field[4]	= "<input type=checkbox name=folder[$j] value=$value[ID] />";
			$item[]=$field;
		}
		
	}
	
	$colwidth[1] = "15px";
	$colwidth[2] = "45px";
	$colwidth[3] = "100px";
	$colwidth[4] = "50px";			
			
	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	
	$form	= $grid->generate();
	$form  .= "<div align=\"right\"><br/><input type=submit name=submit value="._JOBFOLDER."></div>";
	$form	= "<form  method=\"post\" action=\"$url&amp;node=$node\">".$form."</form>";
	
	return "<p align=\"right\"><strong>"._LISTFOLDER."</strong></p>".$path.$form;
}

function box_job($url){
	global	$gdl_sys,$gdl_liveCD,$gdl_content;
	
	$folder	= $_POST['folder']	;

	$arr_repo	= explode("/",$gdl_sys["repository_dir"]);
	$dir_tmp	= array_shift($arr_repo)."/tmp/liveCD";
	$arr_tmp	= explode("/",$dir_tmp);
	
	for($i=0;$i<count($arr_tmp);$i++){
		$dir_tmp	= ($i==0)?$arr_tmp[0]:"$dir_tmp/$arr_tmp[$i]";
		if(!file_exists($dir_tmp)){
			mkdir($dir_tmp,0777);
			chmod($dir_tmp,0777);
		}
	}
	
	if($_POST[submit]== _JOBRESET)
		remove_job($dir_tmp,"job.txt");
	else if(($_POST[submit] == _JOBREMOVE) && (is_array($_POST[remove])))
		remove_job($dir_tmp,"job.txt",$_POST[remove]);
	
	if(is_array($folder)){
		$arr_job	= @file("$dir_tmp/job.txt");
		if(!is_array($arr_job)){
			$arr_job	= array();
		}
		foreach ($folder as $index => $node){
			if(!in_array($node,$arr_job)){
				array_push($arr_job,$node);
			}
		}
	}
	
	if(!is_array($arr_job)){
		$arr_job	= @file("$dir_tmp/job.txt");
	}else{
		foreach ($arr_job as $idx => $nd)
			$arr_job[$idx]	= trim($nd);
				
		$arr_job= array_unique($arr_job);
		sort($arr_job);
		
		$first_node	= trim($arr_job[0]);
		if(strlen($first_node) == 0)
			array_shift($arr_job);
		
		$data	= implode("\n",$arr_job);
		$handle	= fopen("$dir_tmp/job.txt","w");
		fwrite($handle,$data);
		fclose($handle);
		
	}
	
	require_once("./class/repeater.php");
			
	$grid				=	new repeater();
	
	$header[1]	=	_NOMOR;
	$header[2]	=	_FOLDERNODE;
	$header[3]	=	_FOLDERNAME;
	$rs_folder	= array();
	
	if(is_array($arr_job)){
		
		for($i=0;$i<count($arr_job);$i++){
			$node	= trim($arr_job[$i]);
			array_push($rs_folder,$gdl_liveCD->getTaksonomyFolder($node,"node"));
		}
	}
	if(is_array($rs_folder)){

		$i=0;
		foreach ($rs_folder as $index => $value){
			
			$folder="";
			foreach ($value as $idx => $val){
				$folder	= empty($folder)?"$val":"$folder ".$gdl_sys[folder_separator]." $val";
			}
			
			$field[1]	= ++$j."<input type=checkbox name=remove[$j] value=$arr_job[$i] />";
			$field[2]	= $arr_job[$i];
			$field[3]	= "<img src=\"./theme/".$gdl_content->theme."/image/icon_dir_list.png\" alt=\"\"/>&nbsp;&nbsp;$folder";

			$item[]=$field;
			$i++;
		}
		
	}
	
	$colwidth[1] = "10px";
	$colwidth[2] = "10px";
	$colwidth[3] = "100px";		
			
	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	
	$form	= $grid->generate();
	$form  .= "<div align=\"right\"><br/>
				<input type=submit name=submit value="._JOBACTION."> &nbsp;&nbsp;
				<input type=submit name=submit value="._JOBRESET."> &nbsp;&nbsp; 
				<input type=submit name=submit value="._JOBREMOVE.">
			  </div>";
	

	$form	= "<form  method=\"post\" action=\"$url\">".$form."</form>";
	
	return  "<p align=\"right\"><strong>"._LISTJOBFOLDER."</strong></p>".$form;
}

function remove_job($dir_tmp,$file,$arr_folder=""){
	if(!is_array($arr_folder))
		@unlink("$dir_tmp/$file");
	else{
		$arr_job	= file("$dir_tmp/$file");
		
		if(is_array($arr_job)){
			foreach ($arr_job as $idx => $nd){
				$arr_job[$idx]	= trim($nd);
				
				//echo "$idx ========> ".$arr_job[$idx]."(".strlen($arr_job[$idx]).")<br/>";
			}
						
			$arr_Rfolder	= array();
			
			foreach ($arr_job as $index => $node){
				$node	= trim($node);
				//echo " N:$node (".strlen($node).") ";
				if(!in_array($node,$arr_folder)){
					array_push($arr_Rfolder,$node);
				}
			}
			
			array_unique($arr_Rfolder);
			$data	= implode("\n",$arr_Rfolder);
			$handle	= fopen("$dir_tmp/$file","w");
			fwrite($handle,$data);
			fclose($handle);
		}
	}
}

function list_of_uploaded_file($url) {
	
	$action	= trim($_GET['action']);
	$file	= trim($_GET['file']);
	
	if((strlen($action) > 0) && (strlen($file) > 0)){
		@unlink("./files/export/$file");
	}
			
	require_once("./class/repeater.php");

	$dir_file	= array();
	$pool_file	= array();
	$dirhandle=opendir("./files/export/");
	while (($file = readdir($dirhandle)) !== false) {
		if (preg_match("/.gz/i",$file) && preg_match("/liveCD-/i",$file)){
			$dir_file[$file] = "./files/export/$file";
		}
	}
	closedir($dirhandle);
	krsort($dir_file);
	
	$num_file	= count($dir_file);	
	if ($num_file > 0) {
			
			$grid=new repeater();
			
			$header[1]="No";
			$header[2]=_FILENAME;
			$header[3]=_DATE;
			$header[4]=_FILESIZE." (bytes)";
			$header[5]=_ACTION;
			
			$no=1;
			foreach($dir_file as $filename => $filepath){
				$field[1]=$no;
				$field[2]=$filename;
				$field[3]=date("Y-m-d H:i:s",filemtime($filepath));
				$field[4]=convertSizeInfo(filesize($filepath));
				$field[5]="[<a href=\"$filepath\" >Download</a>] <br/> [<a href=\"$url&amp;action=delete&amp;file=".$filename."\">"._DELETE."</a>]";
				$item[]=$field;
				$no++;
			}
						
			$colwidth[1] = "10px";
			$colwidth[2] = "50px";
			$colwidth[3] = "75px";
			$colwidth[4] = "15px";
			$colwidth[5] = "75px";
			
					
			$grid->header=$header;
			$grid->item=$item;
			$grid->colwidth=$colwidth;
			
			$content.= @$grid->generate();			
		}
	
	return "<p align=\"right\"><strong>"._LISTLIVECDFILE."</strong></p>".$content;
}

function convertSizeInfo($size){
	if(!preg_match("/^[0-9]+$/",$size)) return null;
	
	$arr_info  = array("Byte","KBs","MBs","GBs","TBs");
	$idx 		= 1;
	$multiply	= 1000;


	$c_size		= floor($size/$multiply);
	while($c_size > 0){
		$idx++;
		$prev_size	= $c_size;
		$c_size 	= floor($c_size/($idx * $multiply));
	}

	if($idx == 1){
		return "$size $arr_info[0] .";
	}else{
		$idx--;
		$prev_size++;
		return "$prev_size $arr_info[$idx] .";
	}
}

function compress_liveCD($state=""){

	require_once("./extension/pcltar-1-3/conf.php");
	$epoch		= date("U");
	$dist_file	= "./files/export/liveCD-$epoch.tar.gz";
	$tmp_folder	= "./files/tmp/liveCD";
	
	if($state == "closing"){
		@unlink("$tmp_folder/job.txt");
		PclTarCreate($dist_file,$tmp_folder,"tgz");
	}
	
	if(@file_exists($dist_file)){
		unlink_folder($tmp_folder);
	}
}

function unlink_folder($path){
//	echo "UNLINK : $path <br/>\n";
	$dh = @opendir($path);

	while(($file = readdir($dh)) > -1){

		if(($file != ".") && ($file != "..")){
			if(@is_dir("$path/$file")){
				unlink_folder("$path/$file");
				rmdir("$path/$file");
			}else{
//				echo "UNLINK-FILE : $path/$file <br/>\n";
				unlink("$path/$file");
			}
		}

	}
	@closedir($dh);
}

function intro_liveCD(){
	global $gdl_content,$gdl_liveCD;
	
	$curr_theme = $gdl_content->theme;
	$list_theme	= $gdl_liveCD->getThemeSupported();
	
	if(is_array($list_theme)){
		$msg_theme_support	= in_array($curr_theme,$list_theme)?_THEMESUPPORTLIVECD:_THEMENOTSUPPORTLIVECD;
		$theme_support		= _THEMECOLLECTION."<br/>";
		for($i=0;$i<count($list_theme);$i++){
			$theme_support	.= "<br/>&nbsp;&nbsp;&nbsp;".($i+1).". <b>$list_theme[$i]</b>. [<a href=\"$url&amp;newtheme=$list_theme[$i]\">"._CHANGETHEME."</a>]";
		}
	}else
		$msg_theme_support	= _THEMENOTSUPPORTLIVECD;
		
	$intro	= _WELCOMELIVECD;
	$intro	.= "<br/><br/>"._MODULEINFOLIVECD
	."<br/><br/>"._THEMESTATUS." <b>".$curr_theme."</b> "._THEMESTATUSCON." <b>".$msg_theme_support."</b>."
	."<br/>$theme_support"
	."<br/><br/>"._THEMENOTE
	."<br/><br/>"._LIVECDSTEP." :"
	."<br/>1.  "._LIVECDSTEP1	// tambahkan list theme yang mendukung live CD
	."<br/>2.  "._LIVECDSTEP2
	."<br/>3.  "._LIVECDSTEP3
	."<br/>4.  "._LIVECDSTEP4 	// tambahkan title pada tabel informasi 
	."<br/>5.  "._LIVECDSTEP5
	."<br/>6.  "._LIVECDSTEP6
	."<br/>7.  "._LIVECDSTEP7
	."<br/>8.  "._LIVECDSTEP8
	."<br/>9.  "._LIVECDSTEP9
	."<br/>10. "._LIVECDSTEP10; 	// tambahkan autorun
	
	return $intro;
}

function checkSupportedTheme($url){
	global $gdl_content,$gdl_liveCD;
	
	$curr_theme = $gdl_content->theme;
	$list_theme	= $gdl_liveCD->getThemeSupported();
	
	if(is_array($list_theme)){
		$msg_theme_support	= in_array($curr_theme,$list_theme)?_THEMESUPPORTLIVECD:_THEMENOTSUPPORTLIVECD;
		$theme_support		= _THEMECOLLECTION."<br/>";
		for($i=0;$i<count($list_theme);$i++){
			$theme_support	.= "<br/>&nbsp;&nbsp;&nbsp;".($i+1).". <b>$list_theme[$i]</b>. [<a href=\"$url&amp;newtheme=$list_theme[$i]\">"._CHANGETHEME."</a>]";
		}
	}else
		$msg_theme_support	= _THEMENOTSUPPORTLIVECD;
		
	$intro	= _WELCOMELIVECD;
	$intro	.= "<br/><br/>"._MODULEINFOLIVECD
	."<br/><br/>"._THEMESTATUS." <b>".$curr_theme."</b> "._THEMESTATUSCON." <b>".$msg_theme_support."</b>."
	."<br/>$theme_support"
	."<br/><br/>"._THEMENOTE;
	
	
	$checkSupport	= ($msg_theme_support == _THEMESUPPORTLIVECD)?true:false;

	$result['status'] = $checkSupport;
	$result['message']= $intro;
	
	return $result;
}
?>