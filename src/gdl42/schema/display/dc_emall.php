<?php

if (preg_match("/dc_emall.php/i",$_SERVER['PHP_SELF'])) {
    die();
}
global $gdl_metadata;
function manipulasi($value){
	$result="";
	if (!empty($value) && (substr($value,0,1) != '#') && (substr($value,-1,1) != '#'))
		$result = trim($value);
	return $result;
}

$array		= array("PRICE"=>0,"MODEL"=>1,"DIMENSION"=>2,"UNIT"=>4,"STOCK"=>5,"COUNT"=>0);
$arrange 	= array("TYPE","TITLE","DESCRIPTION_PRICE","DESCRIPTION_MODEL","DESCRIPTION_DIMENSION","DESCRIPTION_UNIT",
				 "DESCRIPTION_STOCK","PUBLISHER","DATE_MODIFIED","CREATOR","DATE","RELATION_COUNT");
include ("./config/type.php");

$content="";
$content2="";
$content3="";
for($i=0;$i<count($arrange);$i++){
	$key	= $arrange[$i];
	$exp	= explode("_",$key);
	$idx	= $array[$exp[1]];
	$val	= manipulasi($gdl_metadata->get_value($frm,$key,$exp[0],$idx));
	
	if(!empty($val)){
		switch($key){
			case $arrange[0]:$content2 .= $gdl_type[strtolower($val)]." from ";
							break;
			case $arrange[1]:$content .= "<p><b>"._COMODITYNAME."</b> : $val<br/>";
							break;
			case $arrange[2]:$content .= "<b>"._PRICEPERUNIT."</b> : $val<br/>";
							break;
			case $arrange[3]:$content .= "<b>Model</b> : $val<br/>";
							break;
			case $arrange[4]:$content .= "<b>"._SIZE."</b> : $val<br/>";
							break;
			case $arrange[5]:$content .= "<b>Unit</b> : $val<br/>";
							break;
			case $arrange[6]:$content .= "<b>"._STOCK."</b> : $val<br/>";
							break;
			case $arrange[7]:$content2 .="$val / ";
							break;
			case $arrange[8]:$content2 .="$val<br>\n";
							break;
			case $arrange[9]:$content3 .= ucfirst(_BY)." : $val<br/>\n";
							break;
			case $arrange[10]:$content3 .= ucfirst(_CREATED)." : $val ";
							break;
			case $arrange[11]:$content3 .= ","._WITH." $val "._FILES."<br/><br/>\n";
							break;
		}
	}
}

$content = "$content<br><br><span class=\"note\">$content2</span>$content3";
// relation file located in relation		
related_file();

?>