<?php header('Content-type: text/xml'); ?>
<?php
//session_start();

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


if(file_exists("./config/sync.php"))
	include_once ("./config/sync.php");

if(file_exists("./config/folks.php"))
	include_once("./config/folks.php");
	
include_once("class/oai/config_oai.php");

?>
