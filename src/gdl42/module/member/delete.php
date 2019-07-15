<?php
/***************************************************************************
                         /module/member/delete.php
                             -------------------
    copyright            : (C) 2007 Beni Rio Hermanto, KMRG ITB
    email                : benirio@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
if (preg_match("/delete.php/i",$_SERVER['PHP_SELF'])) die();

$a = $_GET['a'];
$del = $_GET['del'];

if (isset($del) and $del=="confirm"){
	$style = "span.title {\n"
		."width: 110px;\n"
		."float: left;\n"
		."}\n";
	$gdl_content->set_style( $style);
}

// delete folder

// delete metadata
if (isset($a)){
	if (isset($a) and $del=="confirm"){
		// confirmation
		$property = $gdl_account->get_property($a);
		$main = "<p class=\"box\"><b>"._CONFIRMATION."</b></p>\n";
		$main .= "<p><span class=\"title\">Account</span>: $a<br/>\n";
		$main .= "<span class=\"title\">"._NAME."</span>: $property[FULLNAME]<br/>\n";
		$main .= "<span class=\"title\">"._LEVELGROUP."</span>: $property[GROUP]<br/>\n";
			if ($property[ACTIVE] == 1)
					$active = _ACTIVE;
			else
					$active = _NOACTIVE;
		$main .= "<span class=\"title\">"._STATUS."</span>: $active<br/>\n";
		$main .= "<p>"._DELETEMEMBERCONFIRMATION." ? <a href=\"./gdl.php?mod=member&amp;op=delete&amp;a=$a\">"._YESDELETE."</p>\n";
		$main = gdl_content_box($main,_DELETEMEMBER);
	}else{
		// delete folder
		$gdl_account->delete($a);
		
		header ("Location: ./gdl.php?mod=member");
		// display explorer
		//require_once("./module/explorer/function.php");
		//display_explorer($_SESSION['gdl_node']);
	}
}

$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=member\">"._MEMBER."</a>";

?>