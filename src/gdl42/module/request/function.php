<?php 

/***************************************************************************
                         /module/request/function.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/


if (eregi("function.php",$_SERVER['PHP_SELF'])) die();

function display_request($searchkey="") {
	global $gdl_content,$gdl_sys,$gdl_db,$gdl_metadata;

	
	require_once("./class/repeater.php");
	
	$page=$_GET['page'];
	$page = $_GET['page'];
	if (!isset($page)){
	 	$page = 0 ;
	}else{
		$page = $page-1;
	}

	if (!empty($searchkey))
		$urlsearch="&amp;searchkey=$searchkey";

	
//	if (empty ($searchkey)) {
		$limit = $page * $gdl_sys['perpage_request'];
		$limitfinal = $limit.",$gdl_sys[perpage_request]";
//	} else {
//		$limitfinal="";
//	}
	
	$dbres=$gdl_db->select("bookmark","bookmark_id,time_stamp,user_id,identifier,response","request is not null and user_id like '%$searchkey%' and response != ''","time_stamp","desc");
	$total=mysql_num_rows($dbres);	
	
	$dbres=$gdl_db->select("bookmark","bookmark_id,time_stamp,user_id,identifier,response","request is not null and user_id like '%$searchkey%' and response != ''","time_stamp","desc","$limitfinal");
	$count=mysql_num_rows($dbres);
	
			$grid=new repeater();
			
			$header[1]="No";
			$header[2]=_TITLE;
			$header[3]=_USER;
			$header[4]=_SENT;
			$header[5]=_COMMENT;
			$header[6]=_ACTION;
						
			$page = $page + 1;
			$pages = ceil($total/$gdl_sys['perpage_request']);
			$start = 1 + (($page-1) * $gdl_sys['perpage_request']);
			$url = "./gdl.php?mod=request&amp;";
			$j=$limit+1;
			
			while ($row=mysql_fetch_array($dbres)) {
				$property=$gdl_metadata->get_property($row["identifier"]);
				$field[1]=$j;
				$field[2]="<a href='./gdl.php?mod=browse&amp;op=read&amp;id=".$row["identifier"]."'>".$row["identifier"]." / ".$property["title"]."</a>";
				$field[3]=$row["user_id"];
				$field[4]=$row["time_stamp"];
				$field[5]="<a href='./gdl.php?mod=request&amp;op=comment&amp;id=".$row["bookmark_id"]."'>".$row["response"]."</a>";
				$field[6]="<a href='./gdl.php?mod=request&amp;op=delete&amp;id=".$row["bookmark_id"]."'>"._DELETE."</a>";
				$j++;
				$item[]=$field;
			}
						
			$colwidth[1] = "10px";
			$colwidth[2] = "75px";
			$colwidth[3] = "75px";
			$colwidth[4] = "15px";
			$colwidth[5] = "75px";
			$colwidth[6] = "75px";
								
			$grid->header=$header;
			$grid->item=$item;
			$grid->colwidth=$colwidth;
			
			if ($page==1){
			$pref_nav = "<a href=\"./gdl.php?mod=request&amp;page=1$urlsearch\">&laquo; Prev</a>";
			} else{
				$prev_page = $page-1;
				$pref_nav = "<a href=\"./gdl.php?mod=request&amp;page=$prev_page$urlsearch\">&laquo; Prev</a>";
			}

			// next navigator
			if ($page==$pages){
				$next_nav = "<a href=\"./gdl.php?mod=request&amp;page=$page$urlsearch\">Next &raquo;</a>";
			}else{
				$next_page = $page+1;
				$next_nav = "<a href=\"./gdl.php?mod=request&amp;page=$next_page$urlsearch\">Next &raquo;</a>";
			}
			
			$end = $start + $count - 1;
			$form = "<p class=\"contentlisttop\">"._REQUESTDISPLAYING." $start - $end "._OF." total $total "._USERREQUEST."<br/>";
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
						$page_nav .= "<a href=\"./gdl.php?mod=request&amp;page=$i$urlsearch\">$i</a> ";
					}
					$i++; 
				}
			}
			//if (empty ($searchkey))
				$form .= "<p class=\"contentlistbottom\">$page_nav</p>\n";
		
	return $form;	
}

function delete_request($bookmark_id) {
	global $gdl_db;
	$dbres=$gdl_db->delete("bookmark","bookmark_id=".$bookmark_id);
	if ($dbres)
		$content.="<b>"._REQUESTDELETESUCCESS."</b>";
	else
		$content.="<b>"._REQUESTDELETEFAILED."</b>";
		
	return $content;
}

function search_request_form ($action="")
{
	global $gdl_form;

	$gdl_form->set_name("search");
	
	if (!$action)
		$gdl_form->action="./gdl.php?mod=request";
	else
		$gdl_form->action=$action;
		
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"searchkey",			
				"value"=>"$_POST[searchkey]",
				"text"=>_SEARCHREQUEST,
				"size"=>30));
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>"",
			"value"=>_SEARCH));			

	$content = $gdl_form->single_line("30%");	
	return $content;
}

function comment_form($bookmark_id) {
	global $gdl_form,$gdl_db,$gdl_metadata,$gdl_session,$frm;
	
	$dbres=$gdl_db->select("bookmark","*","bookmark_id=".$bookmark_id);
	if (!$dbres)
	{
		$content.="<p>"._CANNOTFOUNDREQUESTDATA."</p>";
	} else {
		$row=mysql_fetch_array($dbres);
		$property=$gdl_metadata->get_property($row["identifier"]);
		$strcontent.=_FROM." : ".$row["user_id"]."<br>";
		$strcontent.=_SENT." : ".$row["time_stamp"]."<br>";
		$strcontent.=_TITLE." : <a href='./gdl.php?mod=browse&amp;op=read&amp;id=".$row["identifier"]."'>".$row["identifier"]." / ".$property["title"]."</a><br>";
		$strcontent.=_AUTHOR." : ".$property["creator"]."<br>";
		$strcontent.="Publisher : ".$property["publisher"]."<br>";
		
		$message="[".date("Y-m-d H:i:s")." ".$gdl_session->user_id."]";
		$gdl_form->set_name("add_comment");
		$gdl_form->action="./gdl.php?mod=request&amp;op=comment&amp;id=".$bookmark_id;		
			
		$gdl_form->add_field(array(
				"type"=>"title",
				"text"=>_GIVEYOURMESSAGE));
		$gdl_form->add_field(array(
				"type"=>"title",
				"text"=>$strcontent));		
		$gdl_form->add_field(array(
					"type"=>"textarea",
					"name"=>"frm[comment]",			
					"value"=>$message,
					"text"=>_COMMENT,   /***********/
					"cols"=>34,
					"rows"=>5));
		$gdl_form->add_button(array(
					"type"=>"submit",
					"name"=>"submit",
					"value"=>_SEND));			
		$content = $gdl_form->generate();
		return $content;	
	}
}

function insert_comment($bookmark_id) {
	global $frm, $gdl_db;
	$dbres=$gdl_db->update("bookmark","response='".$frm["comment"]."'","bookmark_id=".$bookmark_id);
	if ($dbres)
		$content.="<b>"._INSERTCOMMENTSUCCESS."</b>";
	else
		$content.="<b>"._INSERTCOMMENTFAILED."</b>";
		
	return $content;
}


