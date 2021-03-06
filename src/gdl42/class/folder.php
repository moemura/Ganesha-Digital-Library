<?php

if (preg_match("/folder.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class folder{
		
	function set_list($node,$is_offline="",$under_node=""){
		global $gdl_session,$gdl_db,$gdl_mod,$gdl_content,$gdl_sys;
		// refresh to calculate count of metadata
		if ($gdl_sys['folder_refresh']){
			$this->refresh($node);
		}else{
			if ($gdl_session->refresh==true) $this->refresh($node);
		}
		
		// cari folder dalam node tersebut		
		if($is_offline == true){
			//$gdl_db->print_script = true;
			if(is_array($under_node)){
				$c_unode	= count($under_node);
				for($i=0;$i<$c_unode;$i++){
					$where = ($i==0)?"(folder_id=$under_node[$i])":"$where or (folder_id=$under_node[$i])";
				}

				$dbres = $gdl_db->select("folder","folder_id,name,count","parent=$node and $where","name","asc");

			}else{
				if(strlen($under_node) > 0)
					$dbres = $gdl_db->select("folder","folder_id,name,count","(parent=$node and (folder_id = $under_node))","name","asc");
				else
					$dbres = $gdl_db->select("folder","folder_id,name,count","(parent=$node","name","asc");
			}
			//$gdl_db->print_script = false;
		}else
			$dbres = $gdl_db->select("folder","folder_id,name,count","parent=$node","name","asc");
		
		
		$host = "";
		$form = "";
		while ($rows = @mysqli_fetch_row($dbres)){
			$dbres2 = $gdl_db->select("publisher","DC_PUBLISHER,DC_PUBLISHER_HOSTNAME","DC_PUBLISHER_ID='".$rows[1]."'");
			if (@mysqli_num_rows($dbres2) > 0) {
				$rows2=@mysqli_fetch_array($dbres2);
				$host="/ <a href='http://".$rows2["DC_PUBLISHER_HOSTNAME"]."'>".$rows2["DC_PUBLISHER"]."</a>";	
			}
			
			$num_archive = ($is_offline == true)?"":"($rows[2])";
			$form .= "<li><a href=\"./gdl.php?mod=browse&amp;"."node=$rows[0]\">$rows[1]</a> $host $num_archive</li>\n";
		}
		
		if (!empty($form)) {
			$form = "<ul class=\"dirlist\">\n"
					.$form
					."</ul>";
			$name = $this->get_name($node);	
			$form = gdl_content_box($form,_SUBFOLDERON." $name");
			$gdl_content->set_main($form);
		}
		
		$this->set_path($node);
	}

	function get_list($node){
		global $gdl_session,$gdl_db,$gdl_mod,$gdl_content,$gdl_sys;
		// refresh to calculate count of metadata
		if ($gdl_sys['folder_refresh']){
			$this->refresh($node);
		}else{
			if ($gdl_session->refresh==true) $this->refresh($node);
		}
		// cari folder dalam node tersebut
		$result = array();
		$dbres = $gdl_db->select("folder","folder_id,name,count","parent=$node","name","asc");
		while ($rows = @mysqli_fetch_row($dbres)){
			$result[$rows[0]]['name']=$rows[1];
			$result[$rows[0]]['count']=$rows[2];
		}
		
		$this->set_path($node);
		return $result;
	}
	
	function refresh($node){
		// calculate count of metadata
		require_once ("./class/db.php");
		$db = new database();
		$dbres = $db->select("folder","folder_id","parent=$node");
		while ($rows = @mysqli_fetch_row($dbres)){
			$count = $this->content_count($rows[0]);
			$db->update("folder","count=$count","folder_id=$rows[0]");
		}
	}
	
	function get_name($node){
		global $gdl_db;
		if ($node==0){ $name = "Top";
		}else{
			$dbres = $gdl_db->select("folder","name","folder_id=$node");
			if (@mysqli_num_rows($dbres) > 0) {
				$row = @mysqli_fetch_assoc($dbres);
				$name = $row["name"];
			}
		}
		return ":. $name .:";
	}

	function get_property($node){
		global $gdl_db;
		
		//echo "Node : $node";
		
		$dbres = $gdl_db->select("folder","parent,name,path","folder_id=$node");
		$row = mysqli_fetch_assoc($dbres);
		$frm['parent'] = $row["parent"];
		$frm['name'] = $row["name"];
		$frm['path'] = $row["path"];
		$this->set_path($frm['parent']);
		return $frm;
	}
	
	function edit_property($values){
		global $gdl_db,$gdl_metadata;
		
		$dbres = $gdl_db->select("folder","path,parent","folder_id=$values[node]");
		$row = @mysqli_fetch_assoc($dbres);
		$old_path = $row["path"];
		$old_parent = $row["parent"];
		$new_path = $this->get_path($values['parent']);
		$date = date("Y-m-d H:i:s");
		// update database
		$gdl_db->update("folder","parent=$values[parent], path='$new_path', name='$values[name]', date_modified='$date'","folder_id=$values[node]");
		// update path folder child 
		$old_path = "$old_path/$values[node]";
		$len = strlen($old_path) + 1;
		$filter = "(left(path, $len) = '$old_path/') OR (path LIKE '$old_path')";
		$dbres = $gdl_db->select("folder","folder_id,parent",$filter);
		while ($rows = @mysqli_fetch_row($dbres)){
			$new_path = $this->get_path($rows[1]);
			$gdl_db->update("folder","path='$new_path'","folder_id=$rows[0]");
			
		}
		// update metadata path
		$dbres = $gdl_db->select("metadata","identifier,folder,prefix",$filter);
		while ($rows = @mysqli_fetch_row($dbres)){
			$new_path = $this->get_path($rows[1]);
			if (preg_match("/general/",$rows[2])) {
				$frm=$gdl_metadata->read($rows[0]);
				$frm['IDENTIFIER_HIERARCHY']=$this->get_hierarchy($rows[1]);
				$property=$gdl_metadata->get_property ($rows[0]);
				$gdl_metadata->write($frm,$property);	
			}
			$gdl_db->update("metadata","path='$new_path'","identifier='$rows[0]'");
		}
		// refresh folder content count
		$folder = $old_parent;
		while ($folder <> 0){
			$dbres = $gdl_db->select("folder","parent","folder_id=$folder");
			$row = @mysqli_fetch_assoc($dbres);
			$folder =  $row["parent"];
			$this->refresh($folder);
		}
		$folder =$values['parent'];
		while ($folder <> 0){
			$dbres = $gdl_db->select("folder","parent","folder_id=$folder");
			$row = @mysqli_fetch_assoc($dbres);
			$folder =  $row["parent"];
			$this->refresh($folder);
		}
		return true;
	}
 
	function add($values){
		require_once ("./class/db.php");
		$db = new database();
		$parent_path = $this->get_path($values['parent']);
		$date = date("Y-m-d H:i:s");
		$db->insert("folder","parent,path,name,date_modified","$values[parent],'$parent_path','$values[name]','$date'");
		return true;
	}
	
 	function get_path_name($node,$hyperlink=""){
		global $gdl_db,$gdl_sys;
		$path='';
		if ($hyperlink==""){
			while ($node <> 0){
				$dbres = $gdl_db->select("folder","parent,name","folder_id=$node");
				$row = @mysqli_fetch_assoc($dbres);
				$path = " $gdl_sys[folder_separator] ".$row["name"].$path;
				$node =  $row["parent"];
			}
			$path = "Top".$path;
		}else{
			while ($node <> 0){
				$dbres = $gdl_db->select("folder","parent,name","folder_id=$node");
				if (@mysqli_num_rows($dbres) > 0){
					$row = @mysqli_fetch_assoc($dbres);
					$path = " $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=browse&amp;"."node=$node\">".$row["name"]."</a>".$path;	
					$node =  $row["parent"];
				}
			}
			$path = "<a href=\"./gdl.php?mod=browse&amp;"."node=$node\">Top</a>$path";
		}
		return $path;
	}

 	function get_path($node){
		global $gdl_db;
		
		$path = "";
		if ($node==0){$path = "0";
		}else{
			while ($node <> 0){
				$dbres = $gdl_db->select("folder","parent","folder_id=$node");
				$path = "/$node".$path;
				$row = @mysqli_fetch_assoc($dbres);
				$node =  $row["parent"];
			}
			$path = "0".$path;
		}
		return $path;
	}

	function set_path($node){
		global $gdl_mod,$gdl_sys,$gdl_content;
		require_once ("./class/db.php");
		$db = new database();
		if ($gdl_mod=="explorer"){$url ="./gdl.php?mod=explorer&amp;";
		}else{ $url ="./gdl.php?mod=browse&amp;";}
		
		$path = "";
		if(!is_array($node))
			while ($node <> 0){
				$dbres = $db->select("folder","parent,name","folder_id=$node");
				if (@mysqli_num_rows($dbres) > 0){
					$row = @mysqli_fetch_assoc($dbres);
					$path = " $gdl_sys[folder_separator] <a href=\"$url"."node=$node\">".$row["name"]."</a>".$path;	
					$node =  $row["parent"];
				}
			}
		$path = "<a href=\"$url"."node=$node\">Top</a>$path";
		$gdl_content->path=$path;
	}
	
	function get_list_path($node,$window) {
		global $gdl_sys,$gdl_content;
		require_once ("./class/db.php");
		$db = new database();
		
		$url = '';
		if ($window==1)
			$url ="./gdl.php?mod=explorer&amp;n1=";
		elseif ($window==2)
			$url ="./gdl.php?mod=explorer&amp;n2=";
		else
			$url ="./gdl.php?mod=explorer&amp;node=";
			
		$path = '';
		while ($node <> 0){
			$dbres = $db->select("folder","parent,name","folder_id=$node");
			if (@mysqli_num_rows($dbres) > 0){
				$row = @mysqli_fetch_assoc($dbres);				
				$path = " $gdl_sys[folder_separator] <a href=\"$url"."$node\">".$row["name"]."</a>".$path;
				$node =  $row["parent"];
			}
		}
		$path = "<a href=\"$url"."$node\">Top</a>$path";

		return $path;
	}

	function list_all($top_node=""){
		global $gdl_sys,$gdl_db;
		
		if ($top_node <> ""){
			$frm[$top_node] = $this->get_path_name($top_node);
		}
		
		$frm[0] = "Top";
		$dbres = $gdl_db->select("folder","folder_id","","parent","asc");
		while ($rows = mysqli_fetch_row($dbres)){
			$frm[$rows[0]]=$this->get_path_name($rows[0]);
		}
		$frm = array_unique($frm);
		return $frm;
	}
	
	function delete($node){
		
		global $gdl_db,$gdl_metadata;
		
		$path = $this->get_path($node);
		$len = strlen($path) + 1;
		
		// jika punya child maka tidak bisa dihapus
		$filter = "(left(path, $len) = '$path/') OR (path LIKE '$path')";
		$dbres = $gdl_db->select("folder","folder_id", $filter);
		if (@mysqli_num_rows($dbres)>0) return false;
		
		// delete metadata hrs dilakukan menggunakan objek metadata
		$dbmeta = $gdl_db->select("metadata","identifier","folder=$node");
		while ($rows = @mysqli_fetch_row($dbmeta)){
			$gdl_metadata->delete($rows[0]);
		}
		// refresh content count
		$folder = $node;
		while ($folder <> 0){
			$dbres = $gdl_db->select("folder","parent","folder_id=$folder");
			$row = @mysqli_fetch_assoc($dbres);
			$folder =  $row["parent"];
			$this->refresh($folder);
		}
		
		// delete folder tersebut
		$gdl_db->delete("folder","folder_id=$node");
		return true;
	}
	
	function folder_count($node){
		global $gdl_db;
		$path = $this->get_path($node);
		$len = strlen($path) + 1;
		$filter = "(left(path, $len) = '$path/') OR (path LIKE '$path')";
		$dbres = $gdl_db->select("folder","count(folder_id) as total",$filter);
		$row = @mysqli_fetch_assoc($dbres);
		$count = $row["total"];
		return $count;
	}
	
	function content_count($node){
		global $gdl_db;
		$path = $this->get_path($node);
		$len = strlen($path) + 1;
/*
		$dbres = $gdl_db->select("metadata","count(identifier) as total","folder=$node and xml_data != 'deleted'");
		$row = @mysqli_fetch_assoc($dbres);
		$count = $row["total"];
		if ($count == 0) {
			$len=$len+1;
			$path.="/";
		}
*/
		$filter = "((left(path, $len) = '$path/') OR (path LIKE '$path'))";
//		$dbres = $gdl_db->select("metadata","count(identifier) as total","left(path,$len)='$path' AND xml_data !='deleted'");
		$dbres = $gdl_db->select("metadata","count(identifier) as total",$filter." AND xml_data !='deleted'");
		$row = @mysqli_fetch_assoc($dbres);
		$count = $row["total"];
		return $count;
	}

	function check_folder($folder_name,$parent_id){
		global $gdl_db;
		$dbres = $gdl_db->select("folder","folder_id","name='".$folder_name."' AND parent='".$parent_id."'");
		
		if ($dbres){
			if (mysqli_num_rows($dbres)>0) {
				$row = mysqli_fetch_assoc($dbres);
				$folder_node = $row["folder_id"];
				return $folder_node;
			} else {
				return "err";
			}
		} else
			return "err";
	}
	
	function check_folder_id($folder_id) {
		global $gdl_db;
		$dbres = $gdl_db->select("folder","folder_id","folder_id='".$folder_id."'");
		if ($dbres) {
			if (mysqli_num_rows($dbres)>0) {
				return $folder_id;
			} else
				return "err";
		} else
			return "err";
	}

	function get_hierarchy($node) {
		$path=$this->get_path($node);
		$node1=explode("/",$path);
		$name = '';
		if (is_array($node1)) {
			foreach($node1 as $valNode) {
				if ($valNode>0) {
					$property=$this->get_property($valNode);
					$name.="/".$property['name'];
				}
			}
			$name.="/";
		}

		return $name;
	}
}
?>