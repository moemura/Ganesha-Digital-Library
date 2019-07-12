<?php

global $filtering_publisher;

$searchkey	= $_POST['searchkey'];
$filtering_publisher	=  "length(trim(p.dc_publisher_ipaddress)) <> 0 and p.dc_publisher_ipaddress <> '127.0.0.1' ";
$filtering_publisher	.= "and length(trim(p.dc_publisher_hostname)) <> 0 and p.dc_publisher_hostname <> 'localhost' and p.dc_publisher_hostname <> 'hostname' ";
$filtering_publisher	.= " and p.dc_publisher like '$searchkey%'";
$filtering_publisher	.= " and p.DC_PUBLISHER_CONNECTION <> 'TEMPORARY' and r.id_publisher = p.dc_publisher_id ";
	

function search_partner_form(){
	global $gdl_form;

	$gdl_form	= new form();
	$gdl_form->set_name("filteringRepository");
	
	$gdl_form->action="./gdl.php?mod=partnership";
		
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"searchkey",			
				"value"=>"$_POST[searchkey]",
				"text"=>_SEARCHPARTNER,
				"size"=>30));
	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>"",
			"value"=>_PARTNERSEARCH));			

	$content = $gdl_form->single_line("30%");	
	return $content;
}

function box_partnership(){
	global $gdl_content,$gdl_synchronization,$gdl_sys;

	require_once("./class/repeater.php");
	
	$page = $_GET['page'];
	if (!isset($page)){
	 	$page = 0 ;
	}else{
		$page	= (ereg("^[0-9]+$",$page))?(int)$page:0;
		$page	= ($page >0)?$page-1:0;
	}
	
	$limit	= $gdl_sys['perpage_browse'];
	$limit	= (ereg("^[0-9]+$",$limit))?$limit:15;
	$limit	= ($limit == 0)?15:$limit;
	
	$start 	= $page * $limit;
	$total	= getTotalPublisher();
	$publisherdata	= getDataPublisher($start,$limit);
	
	$count	= count($publisherdata);

			$grid		=	new repeater();
			
			$header[1]	=	_PARTNERNO;
			$header[2]	=	_PARTNERID;
			$header[3]	=	_PARTNERNAME;
			$header[4]	=	_HOSTNAME;
			$header[5]	=	_REMOTEUSER;
			
			$page = $page + 1;
			$pages = ceil($total/$limit);
			$start = 1 + (($page-1) * $limit);
			$url = "./gdl.php?mod=partnership&amp;";
			$j=$start;
			
			if(is_array($publisherdata))
				while (list($key,$val) = each($publisherdata)){

					$remote		= $url."remote=$val[idpublisher]";
					$field[1]	= $j;
					$field[2]	= $val['publisher_id'];
					$field[3]	= $val['dc_publisher'];
					$field[4]	= $val['dc_publisher_hostname'];
					$field[5]	= "<a href=\"$remote\">Remote User</a>";
					
					$j++;
					$item[]=$field;
				}
			
			
			$colwidth[1] = "15px";
			$colwidth[2] = "45px";
			$colwidth[3] = "150px";
			$colwidth[4] = "150px";
			$colwidth[5] = "50px";			
					
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
			
			$end = $start + $count - 1;
			$form = "<p class=\"contentlisttop\">"._PARTNERDISPLAYING." $start - $end "._OF." total $total partner<br/>";
			if (empty ($searchkey))
				$form .= "<span><strong>$pref_nav</strong> | <strong>$next_nav</strong></span></p>";
			
			$form.= $grid->generate();
			
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
			if (empty ($searchkey))
				$form .= "<p class=\"contentlistbottom\">$page_nav</p>\n";

	return $form;
}

function getTotalPublisher(){
	global $gdl_db,$filtering_publisher;

	
	$dbres		=  $gdl_db->select("publisher p,repository r","count(p.dc_publisher_id) as total",$filtering_publisher);
	$total		=  (int)@mysql_result($dbres,0,"total");
	
	return 	$total;
}

function getDataPublisher($cursor,$limit){
	global $gdl_db,$filtering_publisher;
	
	$result = array();
	//$gdl_db->print_script = true;
	$dbres	= $gdl_db->select("publisher p,repository r","*",$filtering_publisher,"","","$cursor,$limit");
	//echo "ERROR ".mysql_error();
	$i=0;
	while($row = @mysql_fetch_array($dbres)){
		/*$i++;
		if($i == 1)
			foreach($row as $index => $value)
				echo "[$index][$value]<br/>";
		*/
		$hostname	= $row['host_url'];
		$hostname	= (ereg("http",$hostname))?$hostname:"http://$hostname";
		
		$element	= array("idpublisher"=>$row['IDPUBLISHER'],
							"publisher_id"=>$row['DC_PUBLISHER_ID'],
							"dc_publisher"=>$row['DC_PUBLISHER'],
							"dc_publisher_hostname"=>$hostname);
		array_push($result,$element);
	}
	
	return $result;
}
?>