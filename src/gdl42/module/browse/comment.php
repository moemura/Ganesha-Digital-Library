<?php
/***************************************************************************
                         /module/browse/comment.php
                             -------------------
    copyright            : (C) 2007 Hayun Kusumah, Lastiko Wibisono, KMRG ITB
    email                : hayun@kmrg.itb.ac.id, leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/
if (preg_match("/comment.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$id = $_GET['id'];
$page = $_GET['page'];
$dbres = $gdl_db->select("comment","count(comment_id) as total","identifier='$id'");
$row = @mysqli_fetch_assoc($dbres);
$count = $row["total"];
$title = $gdl_metadata->read($id);

function upload_form(){
	global $id, $count, $frm, $gdl_form, $gdl_session,$gdl_captcha;
	
	if ($gdl_session->user_id <> "public") $frm["name"] = $gdl_session->user_name;	

	$gdl_form->set_name("comment");
	$gdl_form->action="./gdl.php?mod=browse&amp;op=comment&amp;page=upload&amp;id=$id";
	$gdl_form->add_field(array(
				"type"=>"title",
				"text"=>_GIVECOMMENT));	
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[name]",
				"value"=>"$frm[name]",
				"required"=>true,
				"text"=>_NAME,
				"size"=>30));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[email]",
				"value"=>"$frm[email]",
				"text"=>_EMAIL,
				"size"=>30));
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[subject]",
				"value"=>"$frm[subject]",
				"text"=>_SUBJECT,
				"required"=>true,
				"size"=>60));
	$gdl_form->add_field(array(
				"type"=>"textarea",
				"name"=>"frm[desc]",
				"value"=>"$frm[desc]",
				"required"=>true,
				"text"=>_COMMENT,
				"rows"=>10,
				"cols"=>60));
				
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_VERIFICATION));
			
	$gdl_form->add_field(array(
			"type"=>"hidden",
			"name"=>"frm[CAPTCHA_PKEY]",
			"value"=>$gdl_captcha->get_public_key()));

	$gdl_form->add_field(array(
			"type"=>"text",
			"name"=>"frm[CAPTCHA_TEXT]",
			"text"=>$gdl_captcha->display_captcha(true),
			"column"=>false));
				
	$gdl_form->add_button(array(
				"type"=>"submit",
				"name"=>"submit",
				"value"=>_OK));
	$gdl_form->add_button(array(
				"type"=>"reset",
				"name"=>"reset",
				"value"=>_CANCEL));
	$main = $gdl_form->generate("70px");
	$main .= "<p><a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=$id\">"._READARTICLE."</a> | <a href=\"./gdl.php?mod=browse&amp;op=comment&amp;page=read&amp;id=$id\">"._READCOMMENT."? ( $count )</a></p>";
	return $main;
}

function read_comment(){
	global $id, $gdl_db;
	
	include ("./class/repeater.php");
	$grid = new repeater();

	// table header
	$header[1] = "&nbsp;";
	
	// generate item
	$dbres = $gdl_db->select("comment","*","identifier='$id'","comment_id","desc");
	while ($rows = mysqli_fetch_row($dbres)){
		$main = "<b>$rows[6]</b>";
		$main .= "<br/>\n";
		$main .= "<span class=\"note\">$rows[1]";
		$main .= " by $rows[4]";
		if ($rows[5]<>"") $main .= ", ".str_replace("@","{at}",$rows[5]);
		$main .= "</span><br/>\n";
		$main .= strip_tags($rows[7])."\n";
		$field[1] = "$main";
		$item[] = $field;
	}
	
	
	$grid->header=$header;
	$grid->item=$item;
	$form = $grid->generate();
	$form .= "<p><a href=\"./gdl.php?mod=browse&amp;op=read&amp;id=$id\">"._READARTICLE."</a> | ";
	$form .= "<a href=\"./gdl.php?mod=browse&amp;op=comment&amp;id=$id\">"._GIVECOMMENT." ?</a></p>\n";
	return $form;
}

if (!isset($page)) {
	
	// generate form upload comment
	$main = upload_form();
	
}elseif ($page=="upload"){
	
		// upload comment
		$frm=$_POST["frm"];
		
		$user_id = $gdl_session->user_id;
		$date = date("Y-m-d H:i:s");
		
		// check required
		if ($gdl_form->verification($frm) && $gdl_captcha->check_captcha($frm["CAPTCHA_PKEY"],$frm["CAPTCHA_TEXT"])){
			$gdl_db->insert("comment","date,identifier,user_id,name,email,comment,subject","'$date','$id','$user_id','$frm[name]','$frm[email]','$frm[desc]','$frm[subject]'");
			$main = read_comment($id);
		}else{
			if (!($gdl_captcha->check_captcha($frm["CAPTCHA_PKEY"],$frm["CAPTCHA_TEXT"])))
				$regerror .= _REGISTRATION_ERROR_VERIFICATION;
			$main .= "<p><b>".$regerror."</b></p>";
			$main .= upload_form();
		}	
}elseif ($page=="read"){
	if ($count==0){
		$main = upload_form();
	}else{
		$main = read_comment();
	}
}

$main = gdl_content_box($main,$title['TITLE']);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=browse&amp;op=comment\">"._COMMENT."</a>";

?>