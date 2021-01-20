<?php

/***************************************************************************
                         /module/explorer/function.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, Lastiko Wibisono, KMRG ITB
    email                : hayun@kmrg.itb.ac.id, leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/


if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function display_explorer($node){
	global $gdl_content,$gdl_folder;
	require_once("./module/explorer/function.php");

/*
	//if ($node > 0)
	//	$metadata = get_metadata($node);
	
	if ($folder == ""){
		if ($metadata <> ""){
			//$metadata = gdl_content_box($metadata,_METADATAINFOLDER." $folder_name");
			//$gdl_content->set_main($metadata);
		}
	}else{
		//$folder = gdl_content_box($folder,_SUBFOLDERON." $folder_name");
		//$gdl_content->set_main($folder);
		if ($metadata <> ""){
			//$metadata = gdl_content_box($metadata,_METADATAINFOLDER." $folder_name");
			//$gdl_content->set_main($metadata);
		}
	}
*/	
	if (isset($_SESSION["node2"])) {
	
	$folder_name1  = $gdl_folder->get_name($_SESSION["node1"]);
	$folder1 = get_folder($_SESSION["node1"],1);
	$folder_name2  = $gdl_folder->get_name($_SESSION["node2"]);
	$folder2 = get_folder($_SESSION["node2"],2);	
	$content="<table>
			   	<tr valign=top><td>".gdl_content_box($folder1,"")."</td><td>".gdl_content_box($folder2,"")."</td></tr>
			  </table>";
	} else {
		$folder_name  = $gdl_folder->get_name($node);
		$folder = get_folder($node,3);
		$content=gdl_content_box($folder,_SUBFOLDERON." $folder_name");
	}
	$gdl_folder->set_path($node);
	$gdl_content->set_main($content);
}

function get_folder($folder,$window){
	global $gdl_folder, $gdl_content, $gdl_metadata, $gdl_sys;
	if ($window==1)
		$url="./gdl.php?mod=explorer&amp;n1=";
	elseif ($window==2)
		$url="./gdl.php?mod=explorer&amp;n2=";
	else
		$url="./gdl.php?mod=explorer&amp;node=";
		
	$form = "<p><img src=\"./theme/".$gdl_content->theme."/image/dir_new.gif\" alt=\"New Folder\"/> <a href=\"./gdl.php?mod=explorer&amp;op=folder\">"._ADDFOLDER."</a> - <a href='./gdl.php?mod=explorer&amp;op=multiview'>"._MULTIVIEW."</a> - <a href='./gdl.php?mod=explorer&amp;op=singleview'>"._SINGLEVIEW."</a> </p>\n";
	if (isset($_SESSION["node2"]))
		$form.="<form method=post action='".$url.$_SESSION["node".$window]."'>";
	$form .= $gdl_folder->get_list_path($folder,$window);
	$data = $gdl_folder->get_list($folder);
		

	require_once ("./class/repeater.php");
	
	$grid = new repeater();
	
		
		// table header
	$header[1] = "&nbsp;";
	$header[2] = _TITLE;
	$header[3] = _OWNER;
	$header[4] = _DATEMODIFIED;
	$header[5] = _ACTION;

	$colwidth[1] = "15px";
	$colwidth[2] = "";
	$colwidth[3] = "100px";
	$colwidth[4] = "100px";
	$colwidth[5] = "120px";	
	
	if ($folder > 0) {
		$property = $gdl_folder->get_property($folder);
		$field[1] = "<img src=\"./theme/".$gdl_content->theme."/image/icon_dir_list.png\" alt=\"\"/>";
		$field[2] = "<a href=\"$url$property[parent]\">..</a>";
		$field[3] = "&nbsp;";
		$field[4] = "&nbsp;";
		$field[5] = "&nbsp;";
		$item[] = $field;
	}
	if (is_array($data)){
		$input = '';
		$i = 0;
		foreach ($data as $key => $val) {
			if (isset($_SESSION["node2"]))
				$input="<input type=checkbox name=folder[$i] value=$key />";
			$field[1] = "<img src=\"./theme/".$gdl_content->theme."/image/icon_dir_list.png\" alt=\"\"/>$input";
			$field[2] = "<a href=\"$url$key\">$val[name]</a> ($val[count])";
			$field[3] = "&nbsp;";
			$field[4] = "&nbsp;";
			$field[5] = "<a href=\"./gdl.php?mod=explorer&amp;op=property&amp;p=$folder&amp;node=$key\">"._PROPERTY."</a> - <a href=\"./gdl.php?mod=explorer&amp;op=delete&amp;del=confirm&amp;p=$folder&amp;node=$key\">"._DELETE."</a>";
			$item[] = $field;
			$i++;
		}
	}	
		

	if ($folder > 0) {
		$metadata = $gdl_metadata->get_list($folder,"","",true);
		$i=0;
		if (is_array($metadata)){
			$meta_arr = array();
			foreach ($metadata as $key => $val) {
				$title = $val['TITLE'];
				if (strlen($title) > 50 ) $title = substr($title,0,47)."...";
				$meta_arr[$key] = "<a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=$key\">$title</a>";
			}
			
			foreach ($meta_arr as $key => $val) {
				if (isset($_SESSION["node2"]))
					$input="<input type=checkbox name=metadata[$i] value=$key />";
				$property = $gdl_metadata->get_property($key);
				$field[1] = "<img src=\"./theme/".$gdl_content->theme."/image/icon_file_list.png\" alt=\"\"/>$input";
				$field[2] = "$val";
				if (substr($property['owner'],0,1) != '#' && substr($property['owner'],-1,1) != '#')
					$field[3] = "$property[owner]";
				else
					$field[3] = "&nbsp;";
				$field[4] = "$property[date_modified]";
				$field[5] = "<a href=\"./gdl.php?mod=explorer&amp;op=property&amp;id=$key\">"._PROPERTY."</a> - <a href=\"./gdl.php?mod=explorer&amp;op=delete&amp;del=confirm&amp;id=$key\">"._DELETE."</a></a>";
				$item[] = $field;
				$i++;
			}
		} 
	}

	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	$form .= $grid->generate();
	if (isset($_SESSION["node2"])) {
		$form .= "<img src=\"./theme/".$gdl_content->theme."/image/arrow_ltr.gif\" alt=\"Delete\"/>";
		$form .= "<input type=submit name=submit value="._MOVE."></form>";
	}

	return $form;
}
/*
function get_metadata($node){
	global $gdl_content,$gdl_metadata,$gdl_sys;
	
	require_once("./config/type.php");
	require_once("./class/repeater.php");
	
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
			$title = $val['TITLE'];
			if (strlen($title) > 150 ) $title = substr($title,0,147)."...";
			$meta_arr[$key] = "<a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=$key\">$title</a>";
		}
		
		$page = $page + 1;
		$total = $gdl_metadata->total;
		$pages = ceil($total/$gdl_sys['perpage_browse']);
		$start = 1 + (($page-1) * $gdl_sys['perpage_browse']);
		$count = $gdl_metadata->count;
		$url = "./gdl.php?mod=explorer&amp;node=$node&amp;";

		// generate grid format
		$grid = new repeater();
		
		// table header
		$header[1] = "&nbsp;";
		$header[2] = _TITLE;
		$header[3] = _OWNER;
		$header[4] = _ACTION;
		
		// generate item
		foreach ($meta_arr as $key => $val) {
			$property = $gdl_metadata->get_property($key);
			$field[1] = "<img src=\"./theme/".$gdl_content->theme."/image/icon_file_list.png\" alt=\"\"/>";
			$field[2] = "$val";
			$field[3] = "$property[owner]";
			$field[4] = "<a href=\"./gdl.php?mod=explorer&amp;op=property&amp;id=$key\">"._PROPERTY."</a> - <a href=\"./gdl.php?mod=explorer&amp;op=delete&amp;del=confirm&amp;id=$key\">"._DELETE."</a></a>";
			$item[] = $field;
		}
		
		// generate style
		$colwidth[1] = "15px";
		$colwidth[2] = "";
		$colwidth[3] = "100px";
		$colwidth[4] = "120px";
		
		
		$grid->header=$header;
		$grid->item=$item;
		$grid->colwidth=$colwidth;
		
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
		
		$end = $start + $count - 1;
		$form = "<p class=\"contentlisttop\">"._DISPLAYINGMETADATA." $start - $end "._OF." total $total "._METADATAS."<br/>";
        $form .= "<span><strong>$pref_nav</strong> | <strong>$next_nav</strong></span></p>";

		$form .= $grid->generate();

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
		return $form;
	}
}
*/
?>