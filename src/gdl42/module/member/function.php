<?php 

/***************************************************************************
                         /module/member/function.php
                             -------------------
    copyright            : (C) 2007 Beni Rio Hermanto, KMRG ITB
    email                : benirio@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/
if (eregi("function.php",$_SERVER['PHP_SELF'])) die();

function display_member($q){
	global $gdl_content,$gdl_account,$gdl_sys;

	require_once("./class/repeater.php");

	$page = $_GET['page'];
	if (!isset($page) || ereg("add",$page) || ereg("updt",$page) ){
	 	$page = 0 ;
	}else{
		$page = $page-1;
	}

	/*
	if (empty ($q)) {
		$limit = $page * $gdl_sys['perpage_member'];
		$limitfinal = $limit.",$gdl_sys[perpage_member]";
	} else {
		$limitfinal="";
	}	
	
		$account = $gdl_account->get_list($q, $limitfinal,true);

	*/

    $limit = $page * $gdl_sys['perpage_member'];
	$account = $gdl_account->get_list($q, "$limit,$gdl_sys[perpage_member]",true);
	//$account = $gdl_account->get_list($q, $limitfinal,true);
	
	if (is_array($account)){
		while (list($key,$val) = each($account)){
			$title = $val['FULLNAME'];
			$meta_arr[$key] = "<a href=\"./gdl.php?mod=member&amp;op=edit&amp;a=$key\">$title</a>";
	}
		
		$page = $page + 1;
		$total = $gdl_account->total;
		$pages = ceil($total/$gdl_sys['perpage_member']);
		$start = 1 + (($page-1) * $gdl_sys['perpage_member']);
		$count = $gdl_account->count;
		if (empty ($q)) {
			$url = "./gdl.php?mod=member&amp;";
		} else {
			$url = "./gdl.php?mod=member&amp;y=$q&amp;";
		}	

		// generate grid format
		$grid = new repeater();
		
		// table header
		$header[1] = "No";
		$header[2] = _NAME;
		$header[3] = _LEVELGROUP;
		$header[4] = _STATUS;
		$header[5] = _ACTION;		
		
		$j = $limit+1;
		// generate item
		while (list($key, $val) = each($meta_arr)) {
			$property = $gdl_account->get_property($key);
			$field[1] = "$j.";			
			$field[2] = "$val";
			$field[3] = "$property[GROUP]";
				if ($property[ACTIVE] == 1)
					$field[4] = _ACTIVE;
				else
					$field[4] = _NOACTIVE;	
			$field[5] =	"<a href=\"./gdl.php?mod=member&amp;op=edit&amp;a=$key\">"._EDIT."</a> - <a href=\"./gdl.php?mod=member&amp;op=delete&amp;del=confirm&amp;a=$key\">"._DELETE."</a></a>";		
			$j++;
			$item[] = $field;
		}
		
		// generate style
		$colwidth[1] = "15px";
		$colwidth[2] = "75px";
		$colwidth[3] = "75px";
		$colwidth[4] = "15px";
		$colwidth[5] = "75px";
		
		
		$grid->header=$header;
		$grid->item=$item;
		$grid->colwidth=$colwidth;
		
		// previous navigator
		
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
		$form = "<p class=\"contentlisttop\">"._DISPLAYINGMEMBER." $start - $end "._OF." total $total "._MENBER."<br/>";
		//if (empty ($q))
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
		//if (empty ($q))
			$form .= "<p class=\"contentlistbottom\">$page_nav</p>\n";
		
		return $form;
	}
}

function search_member_form ()
{
	global $gdl_form;

	$gdl_form->set_name("search");
	$gdl_form->action="./gdl.php?mod=member";
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"q",			
				"value"=>"$_GET[y]",
				"text"=>_SEARCH_USER_MAIL,
				"size"=>30));
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>"",
			"value"=>_SEARCHMEMBER));			

	$content = $gdl_form->single_line("30%");
	return $content;
}

?>