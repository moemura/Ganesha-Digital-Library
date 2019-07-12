<?php

include ("./config/system.php");
include ("./config/publisher.php");
include ("./class/db.php");
include ("./class/auth.php");
include ("./class/session.php");
include("./class/metadata.php");
include("./class/publisher.php");



$gdl_db = new database();
$gdl_session = new session();
$gdl_auth = new authentication();
$gdl_metadata = new metadata();
$gdl_publisher2= new publisher();
$file_id = $_GET['id'];

function download_redirect(){
	
	global $file_id,$gdl_db,$gdl_metadata,$gdl_publisher,$gdl_session,$gdl_publisher2;
	
	$dbres = $gdl_db->select("relation","part,path,identifier,uri","relation_id=$file_id");
	$file_target=@mysql_result($dbres,0,"path");
	$file_part=@mysql_result($dbres,0,"part");
	$publisher = $gdl_metadata->get_publisher(@mysql_result($dbres,0,"identifier"));
	
   if ($gdl_publisher['id']==$publisher){
	if (file_exists($file_target)){
		echo  "<html>\n"
			."<head>\n"
			."<meta http-equiv=\"Content-Language\" content=\"en-us\">\n"
			."<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n"
			."<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=$file_target\">\n"
			."<title>Redirected to $file_part</title>\n"
			."</head>\n"
			."<body>\n"
			."<h1>Download Redirected</h1>\n"
			."<hr>\n"
			."<p>Please wait...<br/>\n"
			."The page will be redirected to the destination in <font size=\"5\">1</font> second.</p>\n"
			."<p>If it is not working, please click this link:</p>\n"
			."<h3><a href=\"$file_target\">$file_part</a></h3>\n"
			."<hr>\n"
			."<center><form><input type=button value=\"Close\" onClick=\"javascript:window.close();\"></form></center>\n"
			."</body>\n"
			."</html>\n";
	}else{
		echo  "<html>\n"
			."<head>\n"
			."<meta http-equiv=\"Content-Language\" content=\"en-us\">\n"
			."<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n"
			."<title>Redirected to $file_part</title>\n"
			."</head>\n"
			."<body>\n"
			."<center><h1>Download Error</h1>\n"
			."<hr>\n"
			."<p>Sory, This file isn't exists, please contact the Publisher</p>$file_target\n"
			."<hr>\n"
			."<form><input type=button value=\"Close\" onClick=\"javascript:window.close();\"></form></center>\n"
			."</body>\n"
			."</html>\n";
	}
  } else {
		$file_target= str_replace("files/","files/$publisher/",$file_target);
		if (!file_exists($file_target)) {
			if ($publisher == "#PUBLISHER#"){
				$pub_property[_PUBLISHERHOSTNAME] = $gdl_publisher['hostname'];
			} else {	
				$pub_property=$gdl_publisher2->get_property($publisher);					
			}
			
			if (ereg("files/",@mysql_result($dbres,0,"path")))
				$file_target="http://".$pub_property[_PUBLISHERHOSTNAME]."/".@mysql_result($dbres,0,"path");
			else
				$file_target="http://".$pub_property[_PUBLISHERHOSTNAME]."/files/".@mysql_result($dbres,0,"path");			
		}
			
		echo  "<html>\n"
			."<head>\n"
			."<meta http-equiv=\"Content-Language\" content=\"en-us\">\n"
			."<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n"
			."<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=$file_target\">\n"
			."<title>Redirected to $file_target</title>\n"
			."</head>\n"
			."<body>\n"
			."<h1>Download Redirected</h1>\n"
			."<hr>\n"
			."<p>Please wait...<br/>\n"
			."The page will be redirected to the destination in <font size=\"5\">1</font> second.</p>\n"
			."<p>If it is not working, please click this link:</p>\n"
			."<h3><a href=\"$file_target\">$file_part</a></h3>\n"
			."<hr>\n"
			."<center><form><input type=button value=\"Close\" onClick=\"javascript:window.close();\"></form></center>\n"
			."</body>\n"
			."</html>\n";

  }

}

function access_denied(){
	
	global $file_id, $gdl_db;
	
	$dbres = $gdl_db->select("relation","name,note","relation_id=$file_id");
	$file_name=@mysql_result($dbres,0,"name");
	$file_note=@mysql_result($dbres,0,"note");
	
	echo  "<html>\n"
		."<head>\n"
		."<meta http-equiv=\"Content-Language\" content=\"en-us\">\n"
		."<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n"
		."<title>Redirected to $file_name</title>\n"
		."</head>\n"
		."<body>\n"
		."<h1>Access Denied</h1>\n"
		."<hr>\n"
		."<p>You are not authorize user to download this file.</p>\n"
		."<p><b>$file_name</b><br/>$file_note</p>\n"
		."<hr>\n"
		."<center><form><input type=button value=\"Close\" onClick=\"javascript:window.close();\"></form></center>\n"
		."</body>\n"
		."</html>\n";

}

if ($gdl_sys['public_download']){
		download_redirect();
}else{
	if ($gdl_session->user_id=="public"){
		$redirfrom=$_GET["redirfrom"];
		if (empty($redirfrom))
			access_denied();
		else
			download_redirect();
	}else{
		download_redirect();
	}
}

?>