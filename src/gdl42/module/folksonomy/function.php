<?php 
if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();


function edit_form() {
	global $gdl_form,$gdl_sys,$frm,$gdl_folks,$gdl_folksonomy;
		
	if(!isset($frm)){
		foreach ($gdl_folks as $IdxGdlFolks => $ValGdlFolks) {
			$frm[$IdxGdlFolks]= $ValGdlFolks;			
		}
	}
/*
	else if (!isset($frm)){
		foreach ($gdl_sys as $IdxGdlSync => $ValGdlSync) 
			$frm[$IdxGdlSync]=$ValGdlSync;
	}*/
	
	$range = $gdl_folksonomy->get_range_date();	

	$gdl_form->set_name("edit_folks");
	$gdl_form->action="./gdl.php?mod=folksonomy&amp;op=option";
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_CONFIGURATION));
	
	if(empty($frm["folks_active_option"]))
		$frm["folks_active_option"] = "0";
		
	$gdl_form->add_field(array(
				"type"=>"radio",
				"name"=>"frm[folks_active_option]",
				"checked"=>array(_NO,_YES),			
				"value"=>isset($frm["folks_active_option"]) ? $frm["folks_active_option"] : '',
				"text"=>_AKTIVE_FOLKSONOMY_OPTION,
				"required"=>true));
	
	$gdl_form->add_field(array(
				"type"=>"select",
				"option"=>array("10","15","20","25","30"),
				"name"=>"frm[folks_show_page]",			
				"value"=>isset($frm['folks_show_page']) ? $frm['folks_show_page'] : '',
				"text"=>_SHOW_RECORDS,
				"required"=>true));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[folks_fetch_records]",			
				"value"=>isset($frm["folks_fetch_records"]) ? $frm["folks_fetch_records"] : '',
				"text"=>_NUMFETCHRECORD,
				"required"=>true,
				"size"=>5));
	
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[folks_start_date]",			
				"value"=>isset($frm["folks_start_date"]) ? $frm["folks_start_date"] : '',
				"text"=>_STARTDATE."(<b>$range[from] - $range[until]</b>)",
				"size"=>18));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[folks_min_frekuensi]",			
				"value"=>isset($frm["folks_min_frekuensi"]) ? $frm["folks_min_frekuensi"] : '',
				"text"=>_MIN_FREKUENSI,
				"required"=>true,
				"size"=>8));				
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[folks_token_per_abjad]",			
				"value"=>isset($frm["folks_token_per_abjad"]) ? $frm["folks_token_per_abjad"] : '',
				"text"=>_TOKEN_PER_ABJAD, 
				"required"=>true,
				"size"=>2));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[folks_max_size_font]",			
				"value"=>isset($frm["folks_max_size_font"]) ? $frm["folks_max_size_font"] : '',
				"text"=>_MAX_FONT_SIZE,
				"required"=>true,
				"size"=>2));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[folks_min_size_font]",			
				"value"=>isset($frm["folks_min_size_font"]) ? $frm["folks_min_size_font"] : '',
				"text"=>_MIN_FONT_SIZE,
				"required"=>true,
				"size"=>2));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[folks_bg_color]",			
				"value"=>isset($frm["folks_bg_color"]) ? $frm["folks_bg_color"] : '',
				"text"=>_BG_COLOR,
				"required"=>true, 
				"size"=>6));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[folks_font_color]",			
				"value"=>isset($frm["folks_font_color"]) ? $frm["folks_font_color"] : '',
				"text"=>_FONT_COLOR,
				"required"=>true, 
				"size"=>6));
				
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"frm[submit]",
			"value"=>_SAVECHANGES)); 
			
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"frm[submit]",
			"value"=>"Default"));
			
	$content = $gdl_form->generate();
	return $content;	
}


function write_file_sync() {
	global $frm,$gdl_sys,$gdl_folksonomy;
	
	if ($frm["submit"]=="Default") {
		foreach ($frm as $IdxFrm => $ValFrm) {
			if ($IdxFrm <> "submit")
				$frm[$IdxFrm]= $gdl_sys[$IdxFrm];
		}
	}
	
	$message = '';
	if($frm["submit"] != "Default"){
		if ($gdl_folksonomy->save_configuration($frm))
			$message=_OPTIONSAVE;
		else
			$message=_OPTIONSAVEFAILED;
	}
		
	return $message;
}


function display_stopword() {
	global $gdl_content,$gdl_sys,$gdl_folksonomy,$gdl_folks;

	
	require_once("./class/repeater.php");
	
	$page = isset($_GET['page']) ? $_GET['page'] : null;
	if (!isset($page)){
	 	$page = 0 ;
	}else{
		$page = $page-1;
	}

	$num_show = $gdl_folks['folks_show_page'];
	$num_show = 10+(5*$num_show);
	
	$limit 		= $page * $num_show;
	$limitfinal = $limit.", $num_show";
		
	$stopworddata=$gdl_folksonomy->get_list("garbagetoken",$limitfinal);
	$count=count($stopworddata);

	$form = '';
	if (is_array($stopworddata)) {
			
		$grid=new repeater();
		
		$header[1]=_NO_TOKEN;
		$header[2]=_TOKEN;
		$header[3]=_STOPWORD_ACTION;
		
		$page 	= $page + 1;
		$total	= $gdl_folksonomy->getTotalStopWord();
		$pages 	= ceil($total/$num_show);
		$start 	= 1 + (($page-1)*$num_show);
		$url 	= "./gdl.php?mod=folksonomy&amp;op=garbage&amp;";
		$j		= $limit+1;
		
		$item = array();
		foreach ($stopworddata as $key => $val) {
			$field[1]=$j;
			$field[2]=$stopworddata[$key]["TOKEN"];
			$field[3]="<center><a href=\"./gdl.php?mod=folksonomy&amp;op=garbage&amp;id=".$stopworddata[$key]["ID"]."&amp;del=confirm\">"._STOPWORDDELETE."</center>";
			$j++;
			$item[]=$field;
		}
		
		$colwidth[1] = "50px";
		$colwidth[2] = "300px";
		$colwidth[3] = "50px";			
				
		$grid->header=$header;
		$grid->item=$item;
		$grid->colwidth=$colwidth;
		
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
		
		$end 	= $start + $count - 1;

		$form 	= "<p class=\"contentlisttop\">"._STOPWORDDISPLAYING." $start - $end "._OF." total $total Stop Word<br/>";
		if (empty ($searchkey))
			$form .= "<span><strong>$pref_nav</strong> | <strong>$next_nav</strong></span></p>";
		
		$form.= $grid->generate("400px");
		
		$page_nav ='';
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
		
		$form .= $page_nav;
	}
	return $form;	
}

function display_DistribusiFolksonomy() {
	global $gdl_content,$gdl_sys,$gdl_folksonomy,$gdl_folks;

	
	require_once("./class/repeater.php");
	
	$page 	= isset($_GET['page']) ? $_GET['page'] : null;
	$page 	= isset($_GET['page']) ? $_GET['page'] : null;
	$filter	= isset($_GET['filter']) ? $_GET['filter'] : null;
	
	if (!isset($page)){
	 	$page = 0 ;
	}else{
		$page = $page-1;
	}

	$num_show = $gdl_folks['folks_show_page'];
	$num_show = 10+(5*$num_show);
	
	$limit 		= $page *  $num_show;
	$limitfinal = $limit.", $num_show";
	
	
	$distribusidata	= $gdl_folksonomy->get_list("folksonomy",$limitfinal,$filter);
	$count		= count($distribusidata);
	
	$form = '';
	if (is_array($distribusidata)) {
			
		$grid=new repeater();
		
		$header[1]=_NO_TOKEN;
		$header[2]=_TOKEN;
		$header[3]=_FREKUENSI;
		$header[4]=_FOLKSONOMY_ACTION;
		
		$page 	= $page + 1;
		$total	= $gdl_folksonomy->getTotalFolksonomy($filter);
		$pages 	= ceil($total/$num_show);
		$start 	= 1 + (($page-1)*$num_show);
		$url = "./gdl.php?mod=folksonomy&amp;op=update&amp;filter=$filter&amp;";
		$j=$limit+1;
		
		$item = array();
		foreach ($distribusidata as $key => $val) {
			$field[1]=$j;
			$field[2]=$distribusidata[$key]["TOKEN"];
			$field[3]=$distribusidata[$key]["FREKUENSI"];
			$field[4]="<center><a href=\"./gdl.php?mod=folksonomy&amp;op=update&filter=$filter&amp;id=".$distribusidata[$key]["TOKEN"]."&amp;del=confirm\">"._TOKENDELETE."</center>";
			$j++;
			$item[]=$field;
		}
		
		$colwidth[1] = "10px";
		$colwidth[2] = "50px";
		$colwidth[3] = "50px";
		$colwidth[4] = "50px";			
				
		$grid->header=$header;
		$grid->item=$item;
		$grid->colwidth=$colwidth;
		
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
		
		$end 	= $start + $count - 1;
		
		if($total > 300) $total = 300;
		$form 	= "<p class=\"contentlisttop\">"._FOLKSONOMYISPLAYING." $start - $end "._OF." total $total "._FOLKSONOMYWORD."<br/>";
		$form 	.= "<span><strong>$pref_nav</strong> | <strong>$next_nav</strong></span></p>";
		
		$filter_nav = '';
		for($i='A';$i<'Z';$i++){
			if($i == $filter) $filter_nav .= "<b>[$i]</b>";
			else $filter_nav .= "<a href=\"$url"."filter=$i\">$i</a>&nbsp;";
		}
		$form 	.= $filter_nav;
		$form	.= $grid->generate("500px","center");
		
		$page_nav ='';
		if($pages<>""){
			$page_nav = _PAGE." : ";
			$i=1;
			while ($i <= $pages && ($i<31)) {
				if ($i==$page){
					$page_nav .= "<b>[$i]</b> ";
				}else{
					$page_nav .= "<a href=\"$url"."page=$i\">$i</a> ";
				}
				$i++; 
			}
		}
		$form .= $page_nav;
	}
	return $form;	
}

function add_stopword_form ()
{
	global $gdl_form;

	$gdl_form->set_name("Add Stop Word");
	
	$gdl_form->action="./gdl.php?mod=folksonomy&amp;op=garbage";
		
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"stopword",			
				"value"=>isset($_POST['stopword']) ? "$_POST[stopword]" : '',
				"text"=>_INS_STOPWORD,
				"size"=>30));
				
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>"",
			"value"=>_ADD_STOPWORD));			

	$content = $gdl_form->single_line("30%");	
	return $content;
}

function display_navigator_update(){
	$url 	= "./gdl.php?mod=folksonomy&amp;op=update";
	$html 	= "<center><table border=\"0\">
				<tr align=\"center\" bgcolor=\"#6666CC\" style=\"color:#ffffff;\">
					<td><strong>"._RESET_FOLKSONOMY."</strong></td>
					<td><strong>"._UPDATE_FOLKSONOMY."</strong></td>
					<td><strong>"._CLEAN_STOPWORD."</strong></td>
				</tr>
				<tr align=\"center\" bgcolor=\"#CCCCFF\">
					<td><a href=\"$url&sub=reset\">CLEAN</a></td>
					<td><a href=\"$url&sub=update\">UPDATE</a></td>
					<td><a href=\"$url&sub=clean\">FILTERING</a></td>
				</tr>
			 </table></center>";
	return $html;
}
?>