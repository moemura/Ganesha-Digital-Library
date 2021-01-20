<?php
/***************************************************************************
                          session.php  -  Session Function
                             -------------------
    begin                : June 01, 2004
    copyright            : (C) 2004 Hayun Kusumah, KMRG ITB
    email                : hayun@kmrg.itb.ac.id
	last modified        : August 12, 2004
	modified by			 : Hayun Kusumah | hayun@kmrg.itb.ac.id

 ***************************************************************************/
 
if (preg_match("/session.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class session{
	
	var $refresh;
	var $user_id;		// user@email.ext
	var $user_name;		// user si fulan
	var $group_id;		// public
	var $group_name;	// ..........
	var $authority;
	var $activate=1;
	
	var $remote_session;
	var $user_signature;
	
	function __construct(){
	
		global $gdl_sys,$gdl_content;
		
		require_once ("./class/db.php");
		$db = new database();
		session_start();
		$time = date("Y-m-d H:i:s");
		$user_id = isset($_SESSION['gdl_user']) ? $_SESSION['gdl_user'] : null;

		if (!isset($user_id)){			
			
			//identifikasi user sebagai pengunjung
			$this->user_id = "public";
			$_SESSION['gdl_user'] = "public";
			
			//simpan data session
			$values = "'".session_id()."','public','". $_SERVER['REMOTE_ADDR']."','$time','$time'";
			$db->insert("session","session_id,user_id,remote_ip,begin_visit,last_visit",$values);

		} else {
			
			//update last visit session
			$this->user_id = $user_id;
			$db->update("session","last_visit='$time'","session_id='".session_id()."'");
		}
		
		// simpan atribut user
		$dbres = $db->select("user u,group g","u.name as user_name,u.group_id,g.name as group_name,g.authority","u.group_id=g.group_id and u.user_id='".$this->user_id."'");
		if($dbres != null)
		{
			$row = @mysqli_fetch_assoc($dbres);
			$this->user_name = $row["user_name"];
			$this->group_id = $row["group_id"];
			$this->group_name = $row["group_name"];
			$this->authority = $row["authority"];
		}
		
		// identifikasi user online
		// sekaligus memberi status apakan user merefresh halaman
		// atau membuka halaman baru
		$dbres = $db->select("online","url","session_id='".session_id()."'");
		$url = $_SERVER['REQUEST_URI'];
		$this->refresh = false;
		
		if (@mysqli_num_rows($dbres)==0){
			$db->insert("online","session_id,time_stamp,url","'".session_id()."',".time().",'$url'");
		}else{
			if ($dbres != null) {
				$row = mysqli_fetch_assoc($dbres);
				$url = $row["url"];
				$this->refresh = true;
			}
			$db->update("online","time_stamp=".time().",url='$url'","session_id='".session_id()."'");
		}
		
		$timeout = time();
		$timeout = $timeout - $gdl_sys['timeout'];
		// hapus user yg off line lebih dr waktu time out
		$db->delete("online","time_stamp < $timeout");
		
		// delete bookmark user guest yg offline lebih dr time out
		$db->delete("bookmark","left(user_id,5)='guest' and time_stamp < $timeout");
		
		$this->set_language();
		$this->set_theme();
	}
	
	function set_language(){
		global $gdl_content,$gdl_sys;
		
		// Setting bahasa
		$lang = isset($_COOKIE['gdl_lang']) ? $_COOKIE['gdl_lang'] : null;
		$newlang = isset($_GET['newlang']) ? $_GET['newlang'] : null;
		
		if (isset($newlang)) {
			if (file_exists("./lang/$newlang.php")) {
				setcookie("gdl_lang",$newlang,time()+($gdl_sys['page_caching'] * 60));
				$gdl_content->language=$newlang;
			} else {
				setcookie("gdl_lang",$gdl_sys['language'],time()+($gdl_sys['page_caching'] * 60));
				$gdl_content->language=$gdl_sys['language'];
			}
			
		} elseif (isset($lang)) {
			$gdl_content->language=$lang;
		}else{
			setcookie("gdl_lang",$gdl_sys['language'],time()+($gdl_sys['page_caching'] * 60));
			$gdl_content->language=$gdl_sys['language'];
		}
	}
		
	function set_theme(){
		global $gdl_content, $gdl_sys;
		
		// Setting theme
		$theme = isset($_COOKIE['gdl_theme']) ? $_COOKIE['gdl_theme'] : null;
		$newtheme = isset($_GET['newtheme']) ? $_GET['newtheme'] : null;
		
		if (isset($newtheme)) {
			if (file_exists("./theme/$newtheme/theme.php")) {
				setcookie("gdl_theme",$newtheme,time()+($gdl_sys['page_caching'] * 60));
				$gdl_content->theme=$newtheme;
			} else {
				setcookie("gdl_theme",$gdl_sys['theme'],time()+($gdl_sys['page_caching'] * 60));
				$gdl_content->theme=$gdl_sys['theme'];
			}
			
		} elseif (isset($theme)) {
			$gdl_content->theme=$theme;
		}else{
			setcookie("gdl_theme",$gdl_sys['theme'],time()+($gdl_sys['page_caching'] * 60));
			$gdl_content->theme=$gdl_sys['theme'];
		}
	}
	
	function login($userid,$password) {
		global $gdl_auth,$gdl_sys;
		
		require_once ("./class/db.php");
		$db = new database();
		//$passwd= "old_password".('$password');
		
		$userid = mysqli_real_escape_string($db->con, $userid);
		$password = mysqli_real_escape_string($db->con, $password);
		$passwordhash = $db->mysql3password($password);
		
		$dbres = $db->select("user u,group g","u.name AS user_name,u.active, u.group_id,g.name AS group_name,g.authority","u.group_id=g.group_id AND u.user_id='$userid' AND (u.password=SHA2('$password', 512) OR u.password='$passwordhash')");
		if (@mysqli_num_rows($dbres)==0){
			return false;
		} else {
			$row = mysqli_fetch_assoc($dbres);
			if ($row["active"] == 0) {
				$this->activate=$row["active"];
				return false;
			} else {// simpan ke session
				$_SESSION['gdl_user'] = $userid;
				
				// simpan atribut user
				$this->user_id=$userid;
				$this->user_name=$row["user_name"];
				$this->group_id=$row["group_id"];
				$this->group_name=$row["group_name"];
				$this->authority=$row["authority"];
	
				// update user id session
				$db->update("session","user_id='$userid'","session_id='".session_id()."'");
				
				// update user online
				$db->update("online","user_id='$userid'","session_id='".session_id()."'");
				
				// update bookmark
				$db->update("bookmark","user_id='$userid'","user_id='guest ".session_id()."'");
				
				// set expire date
				$date = date("Y-m-d");
				$db->update("user","active=0","expire_date < '$date' and expire_date <>'0000-00-00'");
				return true;
			}
		}	
	}
	
	function logout(){
		//update user online
		global $gdl_sys;
		
		require_once ("./class/db.php");
		$db = new database();
		
		$db->update("online","user_id='guest'","session_id='".session_id()."'");

		$this->session_connect();
		$this->session_remote();
		
		$_SESSION['LastToken']	= 0;
		
		$_SESSION['gdl_user']="public";
	}
	
	function set_access_log(){
		global $gdl_op,$gdl_mod;
		
		include "./module/accesslog/conf.php";
	
		if (!empty($accesslog[$gdl_mod."_".$gdl_op.".php"]) && ($this->refresh==false)) {
			$url=strrchr($_SERVER['REQUEST_URI'],"/");
			$user_id=$this->user_id;
			$ipaddress=$_SERVER['REMOTE_ADDR'];
			$session=session_id();
			
			require_once ("./class/db.php");
			$db = new database();
			$db->insert("log","datestamp,user_id,ipaddress,session_id,url","now(),'".$user_id."','".$ipaddress."','".$session."','".$url."'");
		}				
	}
	
	function get_online(){
		require_once ("./class/db.php");
		$db = new database();

		$dbres = $db->select("online","count(user_id) as total","user_id='guest'");
		$row = @mysqli_fetch_assoc($dbres);
		$online['guest'] = $row["total"];
		
		$dbres = $db->select("online","count(user_id) as total","user_id<>'guest'");
		$row = @mysqli_fetch_assoc($dbres);
		$online['member'] = $row["total"];

		return $online;
	}
	
	// save connection session
	function oaipmp_create_session($providerId,$providerNetwork){
		global $HTTP_SESSION_VARS;
		
		$sess_id									= session_id();
		$HTTP_SESSION_VARS['sess_providerId'] 		= $providerId;
		$HTTP_SESSION_VARS['sess_providerNetwork'] 	= $providerNetwork;
		$HTTP_SESSION_VARS['sess_client_start']		= date("Y/m/d h:i:s");
		$HTTP_SESSION_VARS['session_client']		= $sess_id;
				
		return $sess_id;
	}
	
	function session_connect($sess_id="",$providerId="",$providerNetwork=""){
				
		global $HTTP_SESSION_VARS;
		//session_register("sess_connect_sessionid","sess_providerId","sess_providerNetwork");
		
		//echo "SESS_CONNECT-0 : [$sess_id][$providerId][$providerNetwork] <br/>";
		$HTTP_SESSION_VARS['sess_connect_sessionid']	= $sess_id;
		$HTTP_SESSION_VARS['sess_providerId'] 				= $providerId;
		$HTTP_SESSION_VARS['sess_providerNetwork'] 		= $providerNetwork;
		//echo "SESS_CONNECT-1 : $sess_id <br/>";
		
		
		$this->session_remote();
	}
	
	function cek_remote_session(){
		
		$remote = false;
		if(!empty($_COOKIE['session_remote'])){
			
			$remote_user['user_id']				=	$_COOKIE['sess_remote_user'];
			$remote_user['user_name']			=	$_COOKIE['sess_remote_name'];
			$remote_user['user_signature']		=	$_COOKIE['user_signature'];
			
			$sess_id	= $_COOKIE['remote_connect'];
			
			$rem_signature = $this->give_signatureRemoteLogin($sess_id,$_COOKIE['sess_remote_user']);
			//echo "REM : $rem_signature";
			if($_COOKIE['session_remote'] == $rem_signature){
				$remote	= true;
				$this->session_remote_login($remote_user);
			}
		}
		
		if(!$remote){
			$this->session_remote();
		}
		
		return $remote;
	}
	
	/**-------------------------------------------- Critical security start--------------------**/
	// Modify below function to make your security more secure.
	function give_user_signature($remoteUser,$password){
		global $gdl_sys;
		
		$app_sign	= $gdl_sys['application_signature'];
		$signature 	= (empty($app_sign))?"$remoteUser-$password":"$remoteUser-$password-$app_sign";
		
		return sha1($signature);
	}

	// To secure your sistem, you can modify this code.
	function give_signatureRemoteLogin($sessid,$id_user){
		global $gdl_publisher, $gdl_sys;

		$app_sign	= $gdl_sys['application_signature'];
		$remote_pub	= $gdl_publisher['serialno'];
		
		$signature	= (empty($app_sign))?"$sessid-$remote_pub-$id_user":"$sessid-$remote_pub-$id_user-$app_sign";

		return sha1($signature);
	}
	
	function give_mdSignature($signature,$epochTime){
		global $gdl_sys;
		
		$app_sign		= $gdl_sys['application_signature'];
		$i_signature	= "$signature-$epochTime";
		
		return sha1($i_signature);
	}
	/**-------------------------------------------- Critical security  end --------------------**/
	
	function session_remote($session_remote="",$remoteUser="",$remoteName=""){
		global $gdl_db,$HTTP_SESSION_VARS;
		
		setcookie("session_remote",$session_remote);
		setcookie("sess_remote_user",$remoteUser);
		setcookie("sess_remote_name",$remoteName);
				
		if(!empty($remoteUser)){
			$sessid	= $HTTP_SESSION_VARS['sess_connect_sessionid'];
			setcookie("remote_connect",$sessid);
			$this->remote_session	= $sessid;
			$this->user_signature	= $_COOKIE['user_signature'];
			if(empty($_COOKIE['user_signature'])){

				$dbres		= $gdl_db->select("user","password","user_id like '$remoteUser'");
				$row = @mysqli_fetch_assoc($dbres);
				$password	= $row["password"];
				//echo "REM_U : [$session_remote][$remoteUser][$remoteName] [$password]<br/>";
				if(!empty($password)){
					$userSignature	= $this->give_user_signature($remoteUser,$password);
					setcookie("user_signature",$userSignature);
					$this->user_signature = $userSignature;
				}
			}
		}else{
			$this->user_signature 	= "";
			$this->remote_session	= "";
			setcookie("user_signature","");
			setcookie("remote_connect","");
			//echo "FAIL";
		}
		
		//foreach ($_COOKIE as $index => $value)
		//	echo "====> [$index][$value] <br>";
		
	}
	
	function session_remote_login($remote_user){
		global $gdl_db,$gdl_sys,$gdl_session,$gdl_content;
		
		$lang 	= $_COOKIE['gdl_lang'];
		$theme 	= $_COOKIE['gdl_theme'];
		
		$lang	= (empty($lang))?$gdl_sys['language']:$lang;
		$theme	= (empty($theme))?$gdl_sys["theme"]:$theme;

		$signature			= $remote_user['user_signature'];

		$auth_remote		= $this->remote_authority($remote_user['user_id'],$signature);

		$gdl_session->group_id		= $auth_remote['group_id'];
		$gdl_session->group_name	= $auth_remote['group_name'];
		$gdl_session->authority		= $auth_remote['authority'];
		
		$gdl_session->user_id		= $remote_user['user_id'];
		$gdl_session->user_name		= $remote_user['user_name'];
		$gdl_content->language		= $lang;
		$gdl_content->theme			= $theme;
		$_SESSION['gdl_user']		= $remote_user['user_id'];
	}
	
	function remote_authority($user_id,$signature){
		global $gdl_db;
		
		$result = array();
		
		$valid	= 0;
		
		
		//echo "SIG : $signature  ID : $user_id<br/>";
		if(!empty($signature)){
			
			$dbres			= $gdl_db->select("user u,group g","u.password as password,u.group_id as group_id,g.name as name,g.authority as authority","u.user_id like '$user_id' and u.group_id = g.group_id");
			$row 			= @mysqli_fetch_assoc($dbres);
			$password		= $row["password"];

			$b_signature	= $this->give_user_signature($user_id,$password);

			if($signature == $b_signature){
				$valid	= 1;
				$result['group_id']		= $row["group_id"];
				$result['group_name']	= $row["name"];
				$result['authority']	= $row["authority"];
			}
		}
		
		if(!$valid){
			$result['group_id']		= "Remote";
			$dbres = $gdl_db->select("group","name,authority","group_id = 'Remote'");

			$row = @mysqli_fetch_assoc($dbres);
			$result['group_name']	= $row["name"];
			$result['authority']	= $row["authority"];
			
			$result['group_name']	= empty($result['group_name'])?"Remote User":$result['group_name'];
			$result['authority']	= empty($result['authority'])?"{browse->*}{bookmark->*}{search->*}{register->*}{partnership->*}":$result['authority'];
		}

		return $result;
	}
	
	function cek_posting_remoteLoginInfo($relog){
	
		$this->logout();
		
		$signature		= $relog['remote_signature'];
		$epochTime		= $relog['epochTime'];
		$md_signature	= $relog['md_signature'];
		$remote_session	= $relog['remote_session'];
		$user_id		= $relog['user_id'];
		$username		= $relog['username'];
		$user_signature	= $relog['user_signature'];
		
		$failed = false;
		
		// cek epochTime
		$curr_epoch	= date("U");
		$delta 		= $curr_epoch - (int)$epochTime;
		$failed		= ($delta < 120)?false:true;
		
		$msg = ($failed)?"Error":"True";
		//echo "MSG-0 : $msg<br/>";
		
		// cek md_signature
		if(!$failed){
			$md_signature_buff	= $this->give_mdSignature($signature,$epochTime);
			$failed	= ($md_signature_buff == $md_signature)?false:true;
		}
		
		$msg = ($failed)?"Error":"True";
		//echo "MSG-1 : $msg<br/>";
		
		// cek remote signature
		if(!$failed){
			$remote_signature = $this->give_signatureRemoteLogin($remote_session,$user_id);
			$failed	= ($signature == $remote_signature)?false:true;
		}
		$msg = ($failed)?"Error":"True";
		//echo "MSG-2 : $msg<br/>";
		
		if(!$failed){
			setcookie("user_signature",$user_signature);
			setcookie("remote_connect",$remote_session);
			setcookie("session_remote",$signature);
			setcookie("sess_remote_user",$user_id);
			setcookie("sess_remote_name",$username);
			
			$_COOKIE['user_signature'] 		= $user_signature;
			$_COOKIE['remote_connect'] 		= $remote_session;
			$_COOKIE['session_remote'] 		= $signature;
			$_COOKIE['sess_remote_user'] 	= $user_id;
			$_COOKIE['sess_remote_name'] 	= $username;

		}
		
		$msg = ($failed)?"Error":"True";
		//echo "MSG-3 : $msg<br/>";
	}

	function setup_indexing_option() {
		$restriction_shell_exec = array('shell_exec');
		$disabled_functions = ini_get('disable_functions');
		if (!empty($disabled_functions)) {
			$arr = explode(',', $disabled_functions);
			foreach ($arr as $index => $value) {
				$value = trim($value);
				if (in_array($value, $restriction_shell_exec)) {
					$_SESSION['index_by_database'] = true;
					break;
				}
			}
		} else {
			$_SESSION['index_by_database'] = false;
		}
	}
}
?>