<?php 

/***************************************************************************
                         /module/discussion/function.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function display_discussion($searchkey) {
	global $gdl_content,$gdl_sys, $gdl_db,$gdl_session;

	
	require_once("./class/repeater.php");
	
	$page=$_GET['page'];
	if (!isset($page)){
	 	$page = 0 ;
	}else{
		$page = $page-1;
	}

	if (!empty($searchkey)) {
			$urlsearch="&amp;searchkey=$searchkey";
			$where=" AND (m.xml_data LIKE '%<title>%$searchkey%</title>%' or c.user_id LIKE '%$searchkey%' or c.subject LIKE '%$searchkey%')";
		}

	
	//if (empty ($searchkey)) {
		$limit = $page * $gdl_sys['perpage_discussion'];
		$limitfinal = $limit.",$gdl_sys[perpage_discussion]";
	//} else {
	//	$limitfinal="";
	//}
	$dbres=$gdl_db->select("metadata m,comment c","c.*","m.identifier=c.identifier$where","c.date","asc");
	$dbres2=$gdl_db->select("metadata m,comment c","c.*","m.identifier=c.identifier$where","c.date","asc","$limitfinal");
	if ($dbres && $dbres2) {
		$total=@mysql_num_rows($dbres);		
		$count=@mysql_num_rows($dbres2);		
		
		$grid=new repeater();
				
		$header[1]="No";
		$header[2]=_DATE;
		$header[3]="Identifier";
		$header[4]=_USERID;
		$header[5]=_SUBJECT;		
		if ($gdl_session->authority=="*" || $gdl_session->group_id=="CKO")
			$header[6]=_OPTION;
		
				
		$page = $page + 1;
		$pages = ceil($total/$gdl_sys['perpage_discussion']);
		$start = 1 + (($page-1) * $gdl_sys['perpage_discussion']);
		$url = "./gdl.php?mod=discussion&amp;";
		$j=$limit+1;
				
		while ($row=mysql_fetch_array($dbres2)) {
			$field[1]=$j;
			$field[2]=$row["date"];
			$field[3]="<a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=".$row["identifier"]."\">".$row["identifier"]."</a>";
			$field[4]=$row["user_id"];
			$field[5]="<a href=\"./gdl.php?mod=browse&amp;op=comment&amp;page=read&amp;id=".$row["identifier"]."\">".$row["subject"]."</a>";
			if ($gdl_session->authority=="*" || $gdl_session->group_id=="CKO")
				$field[6]="<a href=\"./gdl.php?mod=discussion&amp;op=delete&amp;del=confirm&amp;id=".$row["comment_id"]."\">"._DELETE."</a>";
			$item[]=$field;
			$j++;
		}
		
		$colwidth[1] = "10px";
		$colwidth[2] = "75px";
		$colwidth[3] = "75px";
		$colwidth[4] = "15px";
		$colwidth[5] = "75px";	
		if ($gdl_session->authority=="*" || $gdl_session->group_id=="CKO")
			$colwidth[6] = "15px";
										
		$grid->header=$header;
		$grid->item=$item;
		$grid->colwidth=$colwidth;
				
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
		$form = "<p class=\"contentlisttop\">"._COMMENTDISPLAYING." $start - $end "._OF." total $total "._COMMENTS."<br/>";
				//if (empty ($searchkey))
		$form .= "<span><strong>$pref_nav</strong> | <strong>$next_nav</strong></span></p>";
				
				
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

function search_discussion_form ($action="")
{
	global $gdl_form;

	$gdl_form->set_name("search");
	
	if (!$action)
		$gdl_form->action="./gdl.php?mod=discussion";
	else
		$gdl_form->action=$action;
		
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"searchkey",			
				"value"=>"$_POST[searchkey]",
				"text"=>_SEARCHDISCUSSION,
				"size"=>30));
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>"",
			"value"=>_SEARCH));			

	$content = $gdl_form->single_line("30%");	
	return $content;
}