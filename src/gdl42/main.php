<?php

if (preg_match("/main.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

include_once ("./config/system.php");
include_once ("./config/publisher.php");
include_once ("./class/content.php");
include_once ("./class/db.php");
include_once ("./class/auth.php");
include_once ("./class/session.php");
include_once ("./class/metadata.php");
include_once ("./class/folder.php");
include_once ("./class/file.php");
include_once ("./class/form.php");
include_once ("./class/user.php");
include_once ("./class/parser.php");
include_once ("./class/publisher.php");
include_once ("./class/synchronization.php");
include_once ("./class/stdout.php");
include_once ("./class/import.php");
include_once ("./class/isisdb.php");
include_once ("./class/folksonomy.php");
include_once ("./class/captcha.php");
include_once ("./class/partnership.php");
include_once ("./class/liveCD.php");

//hanya untuk pengetesan koneksi
if(file_exists("./config/sync.php"))
	include_once ("./config/sync.php");

if(file_exists("./config/folks.php"))
	include_once("./config/folks.php");

/*3*/	$gdl_content 			= new content();
/*1*/	$gdl_db 				= new database();
/*6*/	$gdl_metadata 			= new metadata();
/*4*/	$gdl_session 			= new session();
/*2*/	$gdl_form 				= new form();
/*5*/	$gdl_auth 				= new authentication();
/*7*/	$gdl_folder 			= new folder();
/*8*/	$gdl_file 				= new file_relation();
/*9*/	$gdl_account 			= new user ();
/*10*/	$gdl_publisher2 		= new publisher();	
/*11*/ 	$gdl_synchronization	= new synchronization();
/*17*/	$gdl_xmlParser			= new parser();
/*17*/	$gdl_stdout				= new stdout();
/*17*/	$gdl_import				= new import();
/*17*/	$gdl_isisdb				= new isisdb();		
/*18*/	$gdl_folksonomy			= new folksonomy();
/*19*/	$gdl_captcha			= new ocr_captcha();
//error bila object liveCD dibuat
/*20*/	$gdl_liveCD				= new liveCD();
include_once ("./class/oai/global_include.php");
$gdl_partner			= new partnership();

$frm_relog	= $_POST['relog'];
if(is_array($frm_relog))
	$gdl_session->cek_posting_remoteLoginInfo($frm_relog);

$gdl_session->cek_remote_session();
$gdl_session->setup_indexing_option();


// set general language
if (file_exists("./lang/".$gdl_content->language.".php")) {
	include("./lang/".$gdl_content->language.".php");
}

// set general function
if (file_exists("./theme/".$gdl_content->theme."/function.php")) {
	include("./theme/".$gdl_content->theme."/function.php");
}

// modul and operation authentication
if (preg_match("/\.\./",$gdl_mod)|| preg_match("/\.\./",$gdl_op)) {
		    $gdl_content->set_error(_YOUCANACCESSDIRECLY);
		} else {
			
			if (!file_exists("./files/misc/install.lck") && (!preg_match("/install/",$gdl_mod))) {
					$gdl_content->main=gdl_content_box("<p>"._INSTALLATIONPAGE."</p>",_INSTALLATION);
			} else {			
					if ($gdl_auth->module() || (preg_match("/install/",$gdl_mod) && !file_exists("./files/misc/install.lck"))){
						// define language per modul
						if (file_exists("./module/$gdl_mod/lang/".$gdl_content->language.".php")) {
							include("./module/$gdl_mod/lang/".$gdl_content->language.".php");
						}
						
						if ($gdl_auth->operation() || (preg_match("/install/",$gdl_mod) && !file_exists("./files/misc/install.lck"))){
							if (file_exists("./module/$gdl_mod/$gdl_op.php")) {
								include("./module/$gdl_mod/$gdl_op.php");
							}else{
								$gdl_content->set_error(_OPERATIONNOTEXIST,_ERROR,"main.operation_file");
							}			
						} else {
							$gdl_content->set_error(_YOUHAVENOTAUTHORITY,_EXCLAMATION,"main.operation_authority");
						}					
					}else{
						$gdl_content->set_error(_YOUHAVENOTAUTHORITY,_EXCLAMATION,"main.module_authority");
					}
				}
		}
$request_uri = $_SERVER["REQUEST_URI"];
$dinamic_title = $_SESSION['DINAMIC_TITLE'];
if(empty($dinamic_title) || ($request_uri == "/index.php")){ $dinamic_title = "WELCOME";}

// display error, replace all content
$gdl_session->set_access_log();
if ($gdl_content->error<>"" && (!preg_match("/install/",$gdl_mod)) && (file_exists("./files/misc/install.lck"))) $gdl_content->main = $gdl_content->error;
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n\n"
	."<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\n"
	."<head>\n"
	."<title>$gdl_publisher[publisher] - $dinamic_title | Powered by GDL4.2</title>\n"
	."<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"/>\n"
	."<link rel=\"shortcut icon\" href=\"./favicon.ico\" />\n";
if ($gdl_content->meta<>"") echo $gdl_content->meta;
echo "<link rel=\"stylesheet\" href=\"./theme/".$gdl_content->theme."/".$gdl_content->theme.".css\" type=\"text/css\" media=\"screen\"/>\n";
if ((file_exists("./theme/".$gdl_content->theme."/".$gdl_content->theme."_print.css"))&& ($_GET['mod']== "browse") && ($_GET['op']=="read") && (! empty ($_GET['id'])))
	echo "<link rel=\"stylesheet\" href=\"./theme/".$gdl_content->theme."/".$gdl_content->theme."_print.css\" type=\"text/css\" media=\"print\"/>\n";

$is_state_offline = $_GET['state'];

if($is_state_offline != "offline"){
	if (file_exists("gdl.xml"))	
		echo "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"GDL | Metadata\" href=\"gdl.xml\" />";
}

if(file_exists("./theme/".$gdl_content->theme."/offlinegdl")){
	// Current theme support live CD service.
	echo "<!-- stylesheet for live CD service -->\n";
	echo "<link href=\"css/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
}

// script for style type text/css
if ($gdl_content->style <> ""){
	echo  "<style type=\"text/css\">\n";
	echo $gdl_content->style."\n";
	echo "</style>\n";
}

// script for javascript
if (($gdl_content->javascript <> "") or (file_exists("./theme/".$gdl_content->theme."/javascripts.php"))) {
	echo "<script type=\"text/javascript\">\n";
	if (file_exists("./theme/".$gdl_content->theme."/javascripts.php")) include("./theme/".$gdl_content->theme."/javascripts.php");
	echo $gdl_content->javascript."\n"
		."</script>\n";
}

echo "</head>\n";
echo "<body>\n\n";
include("./theme/".$gdl_content->theme."/theme.php");
echo "</body>\n"
	."</html>";

?>
