<?php 

/***************************************************************************
                         /module/bookmark/function.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/

if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function display_bookmark() {
	global $gdl_db,$gdl_metadata,$gdl_content,$gdl_session;
	
include ("./class/repeater.php");
include ("./config/type.php");
	// display my bookmark
$user=$gdl_session->user_id;
$dbres = $gdl_db->select("bookmark","bookmark_id,identifier","user_id='$user' and (request is null or request = 0)","bookmark_id","desc");

// jika tidak ada bookmark
$main .= "<p class=box>"._MYBOOKMARK."</p>";
if (mysqli_num_rows($dbres)==0) {
	$main .= "<p>"._BOOKMARKISEMPTY."</p>\n";
}else{
	$main .= "<form method=\"post\" action=\"./gdl.php?mod=bookmark\">\n";
	$main .= "<p><input name=\"act\" type=\"hidden\" value=\"mark\"/></p>\n";
	while ($rows = mysqli_fetch_row($dbres)){
		$frm[$rows[0]] = $gdl_metadata->read($rows[1]);
	}
	
	
	$grid = new repeater();
	
	// table header
	$header[1] = "&nbsp;";
	$header[2] = _TITLE;

	

	// generate item
	foreach ($frm as $key => $val) {
		$type = $val['TYPE'];
		//$meta_count = $gdl_folder->content_count($key);

		$title = $gdl_metadata->get_value($val,"TITLE");
		$identifier= $gdl_metadata->get_value($val,"IDENTIFIER");
		$date	= $gdl_metadata->get_value($val,"DATE_MODIFIED","DATE");
		$type	= $gdl_metadata->get_value($val,"TYPE");
		$creator= $gdl_metadata->get_value($val,"CREATOR");
		$field[1] = "<input type=\"checkbox\" name=\"id[$key]\" value=\"$key\"/>";
		$field[2] = "<a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=$identifier\">$title</a><br/><span class=\"note\">".substr($date,0,10).", $gdl_type[$type], $creator</span>";
		$item[] = $field;
	}
	
	// generate style
	$colwidth[1] = "10px";	
	
	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	$main .= $grid->generate();
	$main .= "<p><img src=\"./theme/".$gdl_content->theme."/image/arrow_ltr.gif\" alt=\"Delete\"/><input type=\"submit\" name=\"submit\" value=\""._DELETEBOOKMARK."\" /> <input type=\"submit\" name=\"submit\" value=\""._USERREQUESTMOVE."\" /></p>\n";
	$main .= "</form>\n";
}

$dbres = $gdl_db->select("bookmark","bookmark_id,identifier,time_stamp,response","user_id='$user' and request > 0","bookmark_id","desc");

// jika tidak ada bookmark
$main .= "<p class=box>"._USERREQUEST."</p>";
if (mysqli_num_rows($dbres)==0) {
	$main .= "<p>"._USERREQUESTISEMPTY."</p>\n";
}else{
	
	$main .= "<form method=\"post\" action=\"./gdl.php?mod=bookmark\">\n";
	$main .= "<p><input name=\"act\" type=\"hidden\" value=\"mark\"/></p>\n";
	
	while ($rows = mysqli_fetch_row($dbres)){
		$frm[$rows[0]]["metadata"] = $gdl_metadata->read($rows[1]);
		$frm[$rows[0]]["time_stamp"]=$rows[2];
		$frm[$rows[0]]["response"]=$rows[3];
	}
	
	$grid = new repeater();
	
	// table header
	$header[1] = "&nbsp;";
	$header[2] = _TITLE;
	$header[3] = _SENT;
	$header[4] = _RESPONSE;
	

	require_once("./config/type.php");

	// generate item
	foreach ($frm as $key => $val) {
		$type = $val["metadata"]['TYPE'];
		$title = $gdl_metadata->get_value($val["metadata"],"TITLE");
		$identifier= $gdl_metadata->get_value($val["metadata"],"IDENTIFIER");
		$date	= $gdl_metadata->get_value($val["metadata"],"DATE_MODIFIED","DATE");
		$type	= $gdl_metadata->get_value($val["metadata"],"TYPE");
		$creator= $gdl_metadata->get_value($val["metadata"],"CREATOR");
//		$meta_count = $gdl_folder->content_count($key);
		$field[1] = "<input type=\"checkbox\" name=\"id[$key]\" value=\"$key\"/>";
		$field[2] = "<a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=".$identifier."\">".$title."</a><br/><span class=\"note\">".substr($date,0,10).", $gdl_type[$type], ".$creator."</span>";
		$field[3] = $val["time_stamp"];
		if (!$val['response'])
			$val['response']=_GIVEYOURMESSAGE;
		$field[4] = "<a href='./gdl.php?mod=bookmark&amp;op=comment&amp;id=".$key."'>".$val["response"]."</a>";
		$item1[] = $field;
	}
	
	// generate style
	$colwidth[1] = "10px";	
	
	$grid->header=$header;
	$grid->item=$item1;
	$grid->colwidth=$colwidth;
	$main .= $grid->generate();
	$main .= "<p><img src=\"./theme/".$gdl_content->theme."/image/arrow_ltr.gif\" alt=\"Delete\"/><input type=\"submit\" name=\"submit\" value=\""._DELETEREQUEST."\" /> <input type=\"submit\" name=\"submit\" value=\""._BOOKMARKMOVE."\" /></p>\n";
	$main .= "</form>\n";
}
	return $main;
}

function comment_form($bookmark_id) {
	global $gdl_form,$gdl_db,$gdl_metadata,$gdl_session,$frm;
	
	$dbres=$gdl_db->select("bookmark","*","bookmark_id=".$bookmark_id);
	if (!$dbres)
	{
		$content.="<p>"._CANNOTFOUNDBOOKMARKDATA."</p>";
	} else {
		$row=mysqli_fetch_array($dbres);
		$property=$gdl_metadata->get_property($row["identifier"]);
		$strcontent.=_FROM." : ".$row["user_id"]."<br>";
		$strcontent.=_DATESENT." : ".$row["time_stamp"]."<br>";
		$strcontent.=_TITLE." : <a href='./gdl.php?mod=browse&amp;op=read&amp;id=".$row["identifier"]."'>".$row["identifier"]." / ".$property["title"]."</a><br>";
		$strcontent.=_AUTHOR." : ".$property["creator"]."<br>";
		$strcontent.="Publisher : ".$property["publisher"]."<br>";
		
		$message="[".date("Y-m-d H:i:s")." ".$gdl_session->user_id."]";
		$gdl_form->set_name("add_comment");
		$gdl_form->action="./gdl.php?mod=bookmark&amp;op=comment&amp;id=".$bookmark_id;		
			
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
					"text"=>_RESPONSE,   /***********/
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
	$dbres=$gdl_db->update("bookmark","time_stamp=now(),response='".$frm["comment"]."'","bookmark_id=".$bookmark_id);
	if ($dbres)
		$content.="<b>"._INSERTCOMMENTSUCCESS."</b>";
	else
		$content.="<b>"._INSERTCOMMENTFAILED."</b>";
		
	return $content;
}
