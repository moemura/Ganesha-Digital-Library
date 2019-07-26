<?php
/***************************************************************************
    copyright            : (C) 2007 Arif Suprabowo, KMRG ITB
    email                : mymails_supra@yahoo.co.uk
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
		
 ***************************************************************************/
 if (preg_match("/liveCD.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class liveCD{
	var $lv_db;
	var $lv_publisherId;
	var $lv_host;
	var $lv_port;
	var $lv_synchronization;
	var $lv_parentNode;
	var $lv_limit;
	var $lv_numRecords;
	var $lv_sys;
	var $lv_folks;
	var $lv_search;
	
	function __construct(){
		$this->init();
	}
	
	function init(){
		include_once ("./class/search.php");
		global $gdl_db,$gdl_publisher,$gdl_synchronization,$gdl_sys,$gdl_folks;
		
		$this->lv_db 				= $gdl_db;
		$this->lv_publisherId		= $gdl_publisher['id'];
		$this->lv_synchronization	= $gdl_synchronization;
		$this->lv_sys				= $gdl_sys;
		$this->lv_folks				= $gdl_folks;
		$this->lv_search			= new search();
		
		$this->lv_search->treshold	= -1;

	}
	
	function setPreConnection(){
		$this->lv_host		= $_SERVER['HTTP_HOST'];
		$this->lv_port		= 80;
		$this->lv_proxy		= 0;
		$this->lv_portProxy	= 0;
		$this->lv_useProxy	= false;

	}
	
	function getInfoConnectionHandle(){
		if($this->lv_parentNode == -1){
			$dir_repo	= $this->lv_sys["repository_dir"];
			$arr_repo	= explode("/",$dir_repo);
			$file_job	= array_shift($arr_repo)."/tmp/liveCD/job.txt";
			
			if(!file_exists($file_job))
				return null;
			
			$arr_job	= file($file_job);
			$arr_result	= array();
			$c_job		= count($arr_job); 
			for ($i=0; $i<$c_job; $i++){
				$node	= trim($arr_job[$i]);
				if(strlen($node) > 0){
					$buf	= $this->getInfoConnection($node);
					if(is_array($buf))
						array_push($arr_result,$buf);
				}
			}
			return $arr_result;
		}else if($this->lv_parentNode >= 0)
			return getInfoConnection($this->lv_parentNode);
		else
			return null;
	}
	
	function getInfoConnection($node=""){
		
		$node	= trim($node);
		$node	= (strlen($node) > 0)?$node:$this->lv_parentNode;
		
		if($node > 0)
			$dbres	= $this->lv_db->select("folder f,metadata m","f.folder_id,m.identifier","f.folder_id = $node","","","0,1");
		else 
			$dbres	= $this->lv_db->select("folder f,metadata m","f.folder_id,m.identifier","f.parent = $node","","","0,1");
		
		$num	= @mysqli_num_rows($dbres);
		$result	= null;
		
		if($num > 0){
			$result['host_url']			= $this->lv_host;
			$result['port_host']		= $this->lv_port;
			$result['under_node']		= $node;
		}
		return $result;
	}
	
	
	function getResponseElement($URI){
		//echo "URI : $URI\n";
		$fpr = file($URI);
		if($fpr)
			foreach ($fpr as $lines)
				$result .= $lines;

		return $result;
	}
	
	function getResponseFromHub($request){

		$result	= trim($this->getResponseElement($request));

		$result	= (strlen($result) == 0)?"TIMEOUT":$result;

		return $result;
	}

	function setParentNode($node){
		$this->lv_parentNode = $node;
	}
	
	function fetchPage($token,$type){
		
		$limit	= empty($this->lv_limit)?20:$this->lv_limit;
		$this->lv_limit = $limit;
		$cursor = $token*$limit;
		
		$status = false;
		if($type == "folder"){
			$status	= $this->fetchPage_folder($cursor,$limit);
		}else if($type == "metadata"){
			$status = $this->fetchPage_metadata($cursor,$limit);
		}else if($type == "paging"){
			$status	= $this->fetchPage_Paging();
		}else if($type == "folksonomy"){
			if($this->lv_folks['folks_active_option'] != "1")
				return false;
			if($_SESSION['livecd_folksonomy'] == "false")
				return false;
				
			$status	= $this->fetchPage_Folksonomy($token,$limit);
		}
		
		return $status;
	}
	
	
	function fetchPage_folder($cursor,$limit){
		$arr_page 	= array("index.php?state=offline&node=","gdl.php?state=offline&mod=browse&node=");
		$buf_tmp	= explode("/",$this->lv_sys["repository_dir"]);
		$dir_tmp	= array_shift($buf_tmp)."/tmp/liveCD/";
		$failed		= false;
		
		$page_save	= "";
		if($cursor == 0){
/*	
			if($this->lv_parentNode > 0){
				$dbres 		= $this->lv_db->select("folder","path","folder_id = ".$this->lv_parentNode);
				$row = @mysqli_fetch_assoc($dbres);
				$arr_path	= explode("/",$row["path"]);
				array_push($arr_path,$this->lv_parentNode);
			}else
				$arr_path = array("0");
*/

			$theme = $_COOKIE['gdl_theme'];

			//icon
			$dir_icon 	= "theme/$theme/icon";
			$p_icon		= "$dir_tmp$dir_icon";
			$arr_path	= explode("/",$p_icon);
			
			for($i=0;$i<count($arr_path);$i++){
				$p_icon = ($i == 0)?$arr_path[0]:"$p_icon/$arr_path[$i]";
				if(!file_exists($p_icon)){
					mkdir($p_icon,0777);
				}

			}
			
			//$failed	= !$this->createDataDirectory($arr_Folder,$arr_page,$dir_tmp,array("faq","credit","contact"));
			
			$dh = opendir($dir_icon);
			while($file = readdir($dh)){
				if(($file != ".") && ($file != ".."))
					@copy("$dir_icon/$file","$p_icon/$file");
			}
			closedir($dh);
			
			//image
			$dir_image 	= "theme/$theme/offlinegdl/img";
			$p_image	= "$dir_tmp"."img";
			$arr_path	= explode("/",$p_image);
			
			for($i=0;$i<count($arr_path);$i++){
				$p_image = ($i == 0)?$arr_path[0]:"$p_image/$arr_path[$i]";
				if(!file_exists($p_image)){
					mkdir($p_image,0777);
				}
			}
			
			$dh = opendir($dir_image);
			while($file = readdir($dh)){
				if(($file != ".") && ($file != ".."))
					@copy("$dir_image/$file","$p_image/$file");
			}
			closedir($dh);
			
			// Main img, image folder under root
			$dir_imgMain = "img";
			$p_image	 = "$dir_tmp/img";
			
			$dh = opendir($dir_imgMain);
			while($file = readdir($dh)){
				if(($file != ".") && ($file != ".."))
					@copy("$dir_imgMain/$file","$p_image/$file");
			}
			closedir($dh);
			
			
			//CSS
			$dir_css = "theme/$theme/offlinegdl/css";
			$p_css	 = "$dir_tmp/css";
			if(!file_exists($p_css)){
				mkdir($p_css,0777);
			}

			$dh = opendir($dir_css);
			while($file = readdir($dh)){
				if(preg_match("/\.css/",$file))
					@copy("$dir_css/$file","$p_css/$file");
			}
			closedir($dh);
			
			// auto run script
			$autorun_script = "[autorun]\n"
							 ."\nopen=browsercall.exe CDREPLACEindex.html";
							 
			$fp 	= fopen("$dir_tmp"."autorun.inf","w");
			fwrite($fp,$autorun_script);
			fclose($fp);
			
			// browser call
			@copy("extension/browsercall/browsercall.exe","$dir_tmp"."browsercall.exe");
			
		}
		
		if(!$failed){
			if(is_array($_SESSION['tree_job'])){
				$rs		   = $_SESSION['tree_job'];
				$arr_path  = $rs['tree'];
				$limit	   = $rs['limit'];
				$c_arr_path= count($arr_path);
				
				$counter   = 0;
				$pool_path = array();
				if($c_arr_path > 0){
					do{
						
						$path 	= array_shift($arr_path);
						$path 	= trim($path);
						
						if(strlen($path) > 0){
							//echo "PATH-POOL : $path \n";
							
							$arr_Folder	= explode(" ",$path);
							
							//if($arr_Folder[0] != "0") // not top
								array_push($pool_path,$path);
						}
						$counter++;
						$c_arr_path--;
					}while(($c_arr_path > 0) && ($counter < $limit));
					
					$_SESSION['tree_job']['tree'] = $arr_path;
					$failed		= !$this->createDataDirectory($pool_path,$arr_page,$dir_tmp,"",true);
				}else
					$failed = true;
					
			}else{
			/*
				//$this->lv_db->print_script=true;
				if($this->lv_parentNode > 0)
					$dbres 		= $this->lv_db->select("folder","folder_id,parent,path","path like '%/".$this->lv_parentNode."/%'","","","$cursor,$limit");
				else 
					$dbres 		= $this->lv_db->select("folder","folder_id,parent,path","path like '".$this->lv_parentNode."/%' or path = 0","","","$cursor,$limit");
				//$this->lv_db->print_script=false;
					
				$num_row	= @mysqli_num_rows($dbres);
				$failed		= ($num_row > 0)?false:true;
	
						
				if(!$failed){
					while(($row = mysqli_fetch_row($dbres)) && !$failed){
						$arr_Folder	= explode("/","$row[2]/$row[0]");
						$failed		= !$this->createDataDirectory($arr_Folder,$arr_page,$dir_tmp);
					}
				}
				*/
				
				$failed = true;
			}
		}
		
		
		return ($failed)?false:true;
	}
	
	function fetchPage_metadata($cursor,$limit){
				
		$arr_page 	= array("index.php","gdl.php?mod=browse&node=");
		$dir_tmp	= "files/tmp/liveCD/";
		$failed		= false;
		
		$page_save	= "";
		
		if($this->lv_parentNode > 0)
			$dbres 		= $this->lv_db->select("metadata","identifier","path like '%/".$this->lv_parentNode."/%'","","","$cursor,$limit");
		else 
			$dbres 		= $this->lv_db->select("metadata","identifier","path like '".$this->lv_parentNode."/%'","","","$cursor,$limit");
		
		$num_record = @mysqli_num_rows($dbres);
		if($num_record == 0){
			$dbres 		= $this->lv_db->select("metadata","identifier","path like '%/".$this->lv_parentNode."'","","","$cursor,$limit");
			$num_record = @mysqli_num_rows($dbres);
		}
		
		if($num_record > 0){
			while (($row = @mysqli_fetch_row($dbres)) && !$failed){
				$identifier = trim($row[0]);
				
				if(strlen($identifier) > 0){
					$failed = !$this->createDataMetadata($row[0],$dir_tmp);
					
					if(!$failed){
						if($_SESSION['livecd_relation'] == "true")
							$this->createFileRelation($row[0],$dir_tmp);
					}
				}
			}
		}else $failed = true;
		

		return $failed?false:true;
	}
	
	function fetchPage_Paging(){
		$dir_tmp	= "files/tmp/liveCD/";
		$failed 	= false;
		
		$this->lv_limit = empty($this->lv_limit)?20:$this->lv_limit;
		
		$arr_Paging = $_SESSION['livecd_paging'];
		
		if(!is_array($arr_Paging)){

			if($this->lv_parentNode == 0)
				$dbres 	= $this->lv_db->select("metadata","folder, count(path) as total","path like '0/%'","folder","asc","","folder");
			else 
				$dbres	= $this->lv_db->select("metadata","folder, count(path) as total","path like '%/".$this->lv_parentNode."/%'","folder","asc","","folder");
			
				$num_rows = (int)@mysqli_num_rows($dbres);
				
				if($num_rows == 0){
					$dbres		= $this->lv_db->select("metadata","folder, count(path) as total","path like '%/".$this->lv_parentNode."'","folder","asc","","folder");
					$num_rows	= (int)@mysqli_num_rows($dbres);
				}
			
				if($num_rows == 0)
					$failed = true;
				else{
					$arr_Paging = array();
					while($row = @mysqli_fetch_row($dbres)){
						$cell = array("FOLDER"=>$row[0],"COUNT"=>$row[1]);
						array_push($arr_Paging,$cell);
					}
				}
		}
		
		if(!$failed){
			$num_paging = count($arr_Paging);
			if($num_paging > 0){
				$iterator = 0;
				while(($iterator < $this->lv_limit) && !$failed){
					
					$cell = $arr_Paging[0];
					if($cell['COUNT'] <= 0){
						
						array_shift($arr_Paging);
						if(count($arr_Paging) > 0){
							$cell = $arr_Paging[0];
						}else 
							$failed = true;
					}
					
					if(!$failed){
						if($cell['COUNT'] > 0)
							$failed = !$this->createDataPaging($cell['FOLDER'],$cell['COUNT'],$dir_tmp);
						
						if(!$failed){
							$arr_Paging[0]['COUNT']--;
						}
					}
					
					$iterator++;
				}
			}
		}
		
		if(!$failed){
			$_SESSION['livecd_paging'] = $arr_Paging;
		}else{
			$_SESSION['livecd_paging'] = array();
		}
		return $failed?false:true;
	}
	
	function TotalFetch_Folder(){
		
		$limit = empty($this->lv_limit)?20:$this->lv_limit;
		if($this->lv_parentNode > 0)
			$dbres 		= $this->lv_db->select("folder","count(folder_id) as total","path like '%/".$this->lv_parentNode."/%'");
		else 
			$dbres 		= $this->lv_db->select("folder","count(folder_id) as total","path like '".$this->lv_parentNode."/%'");
		
		$row = @mysqli_fetch_assoc($dbres);
		$num_record	= (int)$row["total"];
		$max_token	= ceil($num_record/$limit);
		
		$result['NUM_RECORD']	= $num_record;
		$result['LIMIT']		= $limit;
		$result['MAX_TOKEN']	= $max_token;
		
		return $result;
	}
	
	function TotalFetch_Metadata(){
		
		$limit = empty($this->lv_limit)?20:$this->lv_limit;
		
		if($this->lv_parentNode > 0)
			$dbres 		= $this->lv_db->select("metadata","count(identifier) as total","path like '%/".$this->lv_parentNode."/%'");
		else 
			$dbres 		= $this->lv_db->select("metadata","count(identifier) as total","path like '".$this->lv_parentNode."/%'");
		
		$row = @mysqli_fetch_assoc($dbres);
		$num_record	= (int)$row["total"];
		
		if($num_record == 0){
			$dbres 		= $this->lv_db->select("metadata","count(identifier) as total","path like '%/".$this->lv_parentNode."'");
			$row = @mysqli_fetch_assoc($dbres);
			$num_record	= (int)$row["total"];
		}
		$max_token	= ceil($num_record/$limit);
		
		$result['NUM_RECORD']	= $num_record;
		$result['LIMIT']		= $limit;
		$result['MAX_TOKEN']	= $max_token;
		
		return $result;
	}
	
	function convert_linkDirectory($data){
		
		$pattern 		= "/gdl\.php\?mod=browse&amp;node=(\d+)&amp;page=(\d+)/";
		$replacement 	= "$1-$2".".html";
		$data			= preg_replace($pattern,$replacement,$data);
		
		$pattern 		= "/gdl.php\?mod=browse&amp;node=(\d+)/";
		$replacement 	= "$1".".html";
		$data			= preg_replace($pattern,$replacement,$data);
		
		$pattern 		= "/\.\/gdl\.php\?mod=search&amp;s=dc&amp;dc=(\w+)&amp;type=all&amp;page=(\d+)/";
		$replacement 	= "folks-$1"."-"."$2".".html";
		$data			= preg_replace($pattern,$replacement,$data);

		return $data;
	}
	
	function convert_linkMetadata($data){
		
		$pattern = "/gdl\.php\?mod=browse&amp;op=read&amp;id=([\w|:|@|\.|\s|-]+)&amp;([\w|:|@|\.|\s|-|&amp;|=]+)/";
		$data	 = preg_replace($pattern,"$1"."_search".".html",$data);
		
		$pattern = "/gdl\.php\?mod=browse&amp;op=read&amp;id=([\w|:|@|\.|\s|-]+)/";
		$data	 = preg_replace($pattern,"$1".".html",$data);
		
		return $data;
	}
	
	function convert_linkFCC($data){// FAQ,Contact,Credit
		$pattern 		= "/gdl\.php\?mod=browse&amp;op=(\w+)/";
		$replacement 	= "$1".".html";
		$data			= preg_replace($pattern,$replacement,$data);
		
		return $data;
	}
	
	function convert_linkDocument($data){
		$pattern = "/javascript:openDocumentWindow\(\'\.\/download\.php\?id=(\d+)&amp;file=([\w|:|@|\.|\s|-]+)\'\)/";
		$replacement	= "javascript:openFile('files/$1-$2');";
		$data			= preg_replace($pattern,$replacement,$data);
		
		return $data;
	}

	function convert_CB($data){ // comment, bookmark

		$pattern 		= "/\.\/gdl\.php\?mod=browse&amp;op=comment&amp;id=([\w|:|@|\.|\s|-]+)/";
		$replacement	= "javascript:alert('"._LIVECDWARNINGCOMMENT."');";
		$data			= preg_replace($pattern,$replacement,$data);
		
		$pattern 		= "/\.\/gdl\.php\?mod=bookmark&amp;id=([\w|:|@|\.|\s|-]+)/";
		$replacement	= "javascript:alert('"._LIVECDWARNINGBOOKMARK."');";
		$data			= preg_replace($pattern,$replacement,$data);
		
		$pattern 		= "/\.\/gdl\.php\?mod=browse&amp;op=comment&amp;page=read&amp;id=([\w|:|@|\.|\s|-]+)/";
		$replacement	= "javascript:alert('"._LIVECDWARNINGREADCOMMENT."');";
		$data			= preg_replace($pattern,$replacement,$data);
		
		return $data;
	}
	
	function convert_linkFolksonomy($data){
		$pattern 		= "/\.\/gdl\.php\?mod=search&amp;action=folks&amp;keyword=([\w|-|\d]+)/";
		$replacement 	= "folks-$1".".html";
		$data			= preg_replace($pattern,$replacement,$data);
		
		return $data;
	}

	function clear_unexpectedLink($data){
		$array = array(	"/&amp;newlang=(\w+)/"
						,"/newlang=(\w+)/"
						,"/&amp;newtheme=(\w+)/"
						,"/newtheme=(\w+)/");
		
		$c_array = count($array);
		for ($i=0;$i<$c_array;$i++){
			$data	= preg_replace($array[$i],"",$data);
		}
		return $data;
	}
	
	
	function convertData($data,$dir_tmp){
			
		// convert link to directory
		$data	= $this->convert_linkDirectory($data);
		
		$data	= $this->clear_unexpectedLink($data);
		
		$data	= $this->convert_linkMetadata($data);
		
		$data	= $this->convert_CB($data);
		
		$data	= $this->convert_linkFCC($data);
				
		$data	= $this->convert_linkDocument($data);
		
		$data	= $this->convert_linkFolksonomy($data);
		
		$data	= str_replace(".php",".html",$data);
		$data	= str_replace("/0.html","/index.html",$data);
		
		return $data;
	}
	
	function createDataDirectory($arr_path,$arr_page,$dir_tmp){
		$failed	= false;
		$state	= null;
		
		while((count($arr_path) > 0) && !$failed){
			$node 		 = array_shift($arr_path);
			$arr_node_ex = explode(" ",$node);

			if($arr_node_ex[0] == "0"){
				$state = array("faq","credit","contact");
				
				//$dbres = $this->lv_db->select("folder","parent","folder_id=".$this->lv_parentNode);
				//$row = @mysqli_fetch_assoc($dbres);
				//$parent= $row["parent"];
						
				$page_request 	= $arr_page[0].$this->lv_parentNode."&state=offline";
				$page_save		= "index.html";
				if(is_array($_SESSION['livecd_arr_node'])){
					$arr_parent_node = explode("/",$arr_node_ex[1]);
					array_unique($arr_parent_node);
					$c_list_parent	 = count($arr_parent_node);
					if($c_list_parent > 0){
						if(strlen($arr_parent_node[$c_list_parent - 1]) == 0)
							array_pop($arr_parent_node);
					}
					
					$pool_node	= $_SESSION['livecd_arr_node'];
					$list_parent= implode(",",$arr_parent_node);;
					
					array_unshift($pool_node,$this->lv_parentNode);
					
					$list_node	= implode(",",$pool_node);
					/*
					$c_node		= count($pool_node);
					for($x=0;$x<$c_node;$x++){
						$curr_node	= $pool_node[$x];
						$dbres = $this->lv_db->select("folder","path","folder_id=$curr_node");
						$row = mysqli_fetch_assoc($dbres);
						$curr_path 		= $row["path"];
						$arr_curr_path	= explode("/",$curr_path);
						
						//echo "CURR_NODE : $curr_node \n";
						//echo "CURR_PATH : $curr_path - $arr_curr_path[0] - $arr_curr_path[1]\n";
						if($curr_path == "0"){
							$list_parent = (strlen($list_parent) == 0)?"$curr_node":"$list_parent,$curr_node";
						}else if(preg_match("/^[0-9]+$/",$arr_curr_path[1])){ // cek available folder under node
							$list_parent = (strlen($list_parent) == 0)?"$arr_curr_path[1]":"$list_parent,$arr_curr_path[1]";
						}
					}
					*/
					$page_request 	= $arr_page[0].$list_node;
					$page_request .= "&ext=multiply&parent=$list_parent";
					
				}else{
					$arr_node_ex[1] = trim($arr_node_ex[1]);
					$len = strlen($arr_node_ex[1]);
					if($len > 0){
						$parent = substr($arr_node_ex[1],0,$len-1); // remove char '/' at the end of string
						$page_request .= "&ext=single&parent=$parent";
					}
				}
						
				if($_SESSION['livecd_folksonomy'] === "false"){
					$page_request .= "&folks_offline=off";
				}

			}else{ 
				if(strlen($arr_node_ex[1]) > 0){
					$arr_child  = explode("/",$arr_node_ex[1]);
					$c_arr_child= count($arr_child);
					if($c_arr_child > 0){
						if(strlen($arr_child[$c_arr_child-1]) == 0)
							array_pop($arr_child);
							
						$q_child = "&child=".implode(",",$arr_child);
					}
				}
	
				$page_request 	= $arr_page[1].$arr_node_ex[0].$q_child;
				$page_save		= "$arr_node_ex[0]".".html";
				$q_child		= "";
				
				//echo "REQUEST-TREE : $page_request \n";
			}

			
			$page_save = $dir_tmp.$page_save;
			//echo "Page-0 : $page_request <br/>\n";
			//echo "Page-1 : $page_save <br/>\n";
			if(!file_exists($page_save)){
				$URI 	= "http://".$this->lv_host."/$page_request";										
				
				$data 	= $this->getResponseElement($URI);
				
				$data	= $this->convertData($data,$dir_tmp);
				
				if(!empty($data) && ($data != "TIMEOUT")){
					$fp = fopen($page_save,"w");
					@fwrite($fp,$data);
					@fclose($fp);
				}else 
					$failed = true;
			}
			
		}
		
		if(is_array($state)){
			for($i=0;$i<count($state);$i++){
				$URI		= "http://".$this->lv_host."/gdl.php?mod=browse&op=$state[$i]";
				$page_save	= $state[$i].".html";
				$page_save  = $dir_tmp.$page_save;
				if(!file_exists($page_save)){							
					
					$data 	= $this->getResponseElement($URI);
					
					$data	= $this->convertData($data,$dir_tmp);
					
					if(!empty($data) && ($data != "TIMEOUT")){
						$fp = fopen($page_save,"w");
						@fwrite($fp,$data);
						@fclose($fp);
					}else 
						$failed = true;
				}
			
			}
		}
		
		return $failed?false:true;
	}
	
	function createDataMetadata($identifier,$dir_tmp,$optional=""){
		
		if(empty($optional))
			$page_save = $dir_tmp."$identifier.html";
		else 
			$page_save = $dir_tmp."$identifier"."_search".".html";
		
		$page_save	= str_replace("\n","",$page_save);
		$page_save	= str_replace("\r","",$page_save);
		
		$page_request = "gdl.php?mod=browse&state=offline&op=read&id=$identifier".$optional;
		
		if(!file_exists($page_save)){
			$URI 	= "http://".$this->lv_host."/$page_request";										
			
			$data 	= $this->getResponseElement($URI);
			
			$data	= $this->convertData($data,$dir_tmp);
			
			if(!empty($data) && ($data != "TIMEOUT")){
				$fp = @fopen($page_save,"w");
				@fwrite($fp,$data);
				@fclose($fp);
			}else 
				$failed = true;
		}
		
		return $failed?false:true;
	}
	
	function createFileRelation($identifier,$dir_tmp){
		
		
		$dbres = $this->lv_db->select("relation","relation_id,name,path","identifier like '$identifier'");
		
		if($dbres){
			$dir_tmp	.= "files";
			
			if(!file_exists($dir_tmp))
				mkdir($dir_tmp,0777);
			
			while($row = @mysqli_fetch_row($dbres)){
				
				$rel_id	= $row[0];
				$name	= $row[1];
				$path	= trim($row[2]);
				
				if(file_exists($path)){
				
					$page_save 	= $dir_tmp."/$rel_id"."-$name";
					$page_save	= str_replace("\n","",$page_save);
					$page_save	= str_replace("\r","",$page_save);

					@copy($path,$page_save);
				}
			}
		}
	}
	
	function createDataPaging($folder,$page,$dir_tmp,$keyword=""){
		
		if(empty($keyword)){
			$page_save 		= "$dir_tmp/$folder-$page.html";
			$page_request	= "gdl.php?mod=browse&node=$folder&page=$page";
		}else{
			$page_save 		= "$dir_tmp/folks"."-"."$keyword"."-"."$page.html";
			$page_request	= "gdl.php?mod=search&s=dc&dc=$keyword&type=all&page=$page";
		}

		if(!file_exists($page_save)){
			$URI 	= "http://".$this->lv_host."/$page_request";										
			
			$data 	= $this->getResponseElement($URI);
			
			$data	= $this->convertData($data,$dir_tmp);
			
			if(!empty($data) && ($data != "TIMEOUT")){
				$fp = fopen($page_save,"w");
				@fwrite($fp,$data);
				@fclose($fp);
			}else{
				$failed = true;
				//echo "SALAH [$page_save]";
			}
		}
		
		return $failed?false:true;
	}
	
	function getListFolder($parent){
		$dbres	= $this->lv_db->select("folder","folder_id,name,count","parent = $parent","name","asc");
		if($dbres){
			while($row = mysqli_fetch_row($dbres)){
				$result[$row[0]]['ID']		= $row[0];
				$result[$row[0]]['NAME']	= $row[1];
				$result[$row[0]]['COUNT']	= $row[2];
				
				$rs_db	= $this->lv_db->select("folder","folder_id","parent=$row[0]");
				$num	= @mysqli_num_rows($rs_db);
				$result[$row[0]]['STATE']	= ($num == 0)?"last":"node";
			}
		}
		
		return $result;
	}
	
	function getTaksonomyFolder($parent,$state=""){
		
		if(empty($state))
			$dbres	= $this->lv_db->select("folder","path","parent = $parent","","","0,1");
		else if($state =="node")
			$dbres	= $this->lv_db->select("folder","path","folder_id = $parent");
			
		if($dbres){
			$arr_name	= array();
			$arr_name[0]="Top";
			$row = mysqli_fetch_assoc($dbres);
			$arr_path	= explode("/",$row["path"]."/$parent");
			array_unique($arr_path);
			$len		= count($arr_path);

			for($i=1;$i<$len;$i++){
				$dbres 	= $this->lv_db->select("folder","name","folder_id = $arr_path[$i]");
				$row = @mysqli_fetch_assoc($dbres);
				$name	= $row["name"];
				
				if(!empty($name))
					$arr_name[$arr_path[$i]]	= $name;
			}
		}
		
		return $arr_name;
	}
	
	function getThemeSupported(){
		
		$arr_theme	= array();
		$dh = opendir("./theme");
			while($file = readdir($dh)){
				if(($file != ".") && ($file != ".."))
					if(is_dir("./theme/$file")){
						if(file_exists("./theme/$file/offlinegdl"))
							array_push($arr_theme,$file);
					}
			}
		closedir($dh);
		
		return $arr_theme;
	}
	
	function fetchPage_Folksonomy($token,$limit){
		
		$dir_tmp	= "files/tmp/liveCD/";
		
		if(is_array($_SESSION['livecd_metadataFolks'])){
			$num_metadata	= count($_SESSION['livecd_metadataFolks']);
			
			for($i=0;$i<$num_metadata;$i++){
				$identifier	= array_shift($_SESSION['livecd_metadataFolks']);
				$identifier = explode(" ",$identifier);
				$this->createDataMetadata($identifier[0],$dir_tmp,"&q=".$identifier[1]);
				//echo "KEYWORD :: $identifier[1] ====> $identifier[0] <br/>\n";
			}
			
			if($num_metadata <= $limit)
				$_SESSION['livecd_metadataFolks'] = null;
				
		}else{
			$char	= strtolower(chr($token+65));
			
			$limit	= "0,".$this->lv_folks['folks_token_per_abjad'];
			//$this->lv_db->print_script = true;
			$dbres = $this->lv_db->select("folksonomy","TOKEN, FREKUENSI","TOKEN LIKE '$char%'","FREKUENSI,Token","desc,asc",$limit);
			//$this->lv_db->print_script = false;
			//echo "TOKEN :: $token <br/>";
			
			while (($row = @mysqli_fetch_row($dbres)) && !$failed) {
				$keyword	= ucfirst(strtolower($row[0]));
				//echo "KEYWORD : $keyword <br/>\n";
				$failed 	= !$this->createDataFolksonomy($keyword,$dir_tmp);
				$this->getResultSearch($keyword);
				
				$num_page	= ceil($this->lv_search->hit/$this->lv_sys["perpage_browse"]);
				for($i=1;$i<=$num_page;$i++){
					$this->createDataPaging("",$i,$dir_tmp,$keyword);
				}
			}
			
			if($char == "z")
				$failed = true;
		}
		
		return $failed?false:true;
	}
	
	function getResultSearch($keyword){
		$cmd_rs	= $this->lv_search->cmd(1,"dc = ($keyword)");
		
		if(!is_array($_SESSION['livecd_metadataFolks']))
			$_SESSION['livecd_metadataFolks'] = array();
			
		if($cmd_rs){
			if(is_array($this->lv_search->result))
				foreach ($this->lv_search->result as $index => $value){
					if(!in_array($value,$_SESSION['livecd_metadataFolks']))
						$value	= $value." ".$keyword;
						array_push($_SESSION['livecd_metadataFolks'],$value);
				}
		}
	}
	
	function createDataFolksonomy($keyword,$dir_tmp){
		
		$page_save = $dir_tmp."folks-"."$keyword.html";

		$page_request = "gdl.php?mod=search&action=folks&keyword=$keyword";
		if(!file_exists($page_save)){
			$URI 	= "http://".$this->lv_host."/$page_request";										
			
			$data 	= $this->getResponseElement($URI);
			
			$data	= $this->convertData($data,$dir_tmp);
			
			if(!empty($data) && ($data != "TIMEOUT")){
				$fp = @fopen($page_save,"w");
				@fwrite($fp,$data);
				@fclose($fp);
			}else 
				$failed = true;
		}
		
		return $failed?false:true;
	}

}

?>