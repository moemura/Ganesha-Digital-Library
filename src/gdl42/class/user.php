<?php

if (preg_match("/user.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class user {

	var $passwd;	
	var $job;
	var $validation;
	var $total;
	var $count;
	var $searchmember;

	function activate ($userid, $account) {
		global $gdl_auth,$gdl_sys;
		
		require_once ("./class/db.php");
		$db = new database();
		
		$dbres = $db->select("user","active","user_id='$userid' AND validation='$account'");
		if (@mysqli_num_rows($dbres)==0){
			return false;
		}
		else
		{
			$db->update ("user", "active='1'","user_id='$userid' AND validation='$account'");
			return true;
		}
	
	}
	
	function register ($userid, $password, $fullname, $address, $city, $country, $institution, $job) {
		global $gdl_auth,$gdl_sys;
		
		require_once ("./class/db.php");
		$db = new database();
		
		$vn = date ("U");
		
		$this->validation = $vn;
		$this->job = $job;
		
		$userid = mysqli_real_escape_string($db->con, $userid);
		$password = mysqli_real_escape_string($db->con, $password);
		$fullname = mysqli_real_escape_string($db->con, $fullname);
		$vn = mysqli_real_escape_string($db->con, $vn);
		$address = mysqli_real_escape_string($db->con, $address);
		$city = mysqli_real_escape_string($db->con, $city);
		$country = mysqli_real_escape_string($db->con, $country);
		$institution = mysqli_real_escape_string($db->con, $institution);
		$job = mysqli_real_escape_string($db->con, $job);
	  	
		$db->insert("user","user_id, password, active, group_id, name, validation, address, city, country, institution, job",		
					"'$userid', SHA2('$password', 512),'0', 'User', '$fullname','$vn', '$address', '$city', '$country', '$institution', '$job'");

	}
	
	function cek_mail($mail){
	 	if(preg_match("/^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z ]{2,4}$/",$mail))
			return true;
 		else 
			return false;	
	}
	
	function cek_existing_mail($email){
		global $gdl_db;
		
		$dbres = $gdl_db->select("user","user_id","user_id='$email'");
		if (@mysqli_num_rows($dbres)==0){
			return true;
		} else {
			return false;
		}	
	
	}		

	function cek_password ($passwd, $passwdconfrim) {
		if ($passwd == $passwdconfrim){
			return true;
		} else {
			return false;
		}
	}		 
	
	function sendmail_registration ($frm) {
		require_once "./config/usertype.php";
		
		global $gdl_sys, $gdl_publisher;
		
		if (($gdl_sys['os'] == "win32") || ($gdl_sys['activate_account'] == false)){
			return false;
		} else {
			$frm['NETWORK'] = $gdl_publisher['network'];
			$frm['PUBLISHER_ID'] = $gdl_publisher['id'];  
			$frm['PUBLISHER'] = $gdl_publisher['publisher'];
			$frm['DATESTAMP'] = date("Y-m-d");
			$frm['SERVER'] = $_SERVER['HTTP_HOST'];
			$frm['VALIDATION'] = $this->validation;
 			$frm['CKO'] = $gdl_publisher['cko'];
			$frm['JOBTYPE'] = $user_type [$this->job];
			
			$headers  = "From: $frm[CKO] \r\n"; 
			$subject = "[".$frm['NETWORK']."/".$frm['PUBLISHER_ID']."] Account activation is required for $frm[EMAIL]";

			$schema = "./schema/registration.txt";
			if (file_exists($schema)){
				$str_meta = implode('',file($schema));
					
				foreach ($frm as $key => $val) {
					$value = htmlspecialchars(nl2br($val),ENT_QUOTES);
					$str_meta = str_replace("#$key#",$value,$str_meta);
				}
			
			//$str_meta = addslashes($str_meta);
			$message = addslashes($str_meta);
			}
			if (@mail($frm['EMAIL'], $subject, $message, $headers))
				return true;
			else
				return false;						
		}	
	}	

	function get_list($search="", $limit="",$count=""){
		global $gdl_db,$gdl_session;
		
		if ($search <> "") $where = "user_id like '%$search%' or name like '%$search%'";
		if ($where <> "") $where .= " and ";
				$where .= "user_id!='Public' and user_id!='".$gdl_session->user_id."'";
		
		// hitung total member 
		if ($count==true){
			$dbres = $gdl_db->select("user","count(user_id) as total","$where");
			$row = @mysqli_fetch_assoc($dbres);
			$this->total = $row["total"];
		}
		// list member per page
		$dbres = $gdl_db->select("user","user_id, name","$where","date_modified","desc",$limit);
		while ($rows = @mysqli_fetch_row($dbres)){
			$result[$rows[0]]['EMAIL']= $rows ['0'];
			$result[$rows[0]]['FULLNAME']= $rows['1'];
		}
		
		$this->count = @mysqli_num_rows($dbres);
		// empty identifier to session
		//$_SESSION['gdl_identifier'] = "";			
		return $result;
	}


	function get_property ($id){
		global $gdl_db;
		$dbres = $gdl_db->select("user","user_id,active,group_id,name", "user_id='$id'");
		$row = @mysqli_fetch_assoc($dbres);
		$frm['USERID'] = $row["user_id"];
		$frm['FULLNAME'] = $row["name"];
		$frm['ACTIVE'] = $row["active"];
		$frm['GROUP'] = $row["group_id"];
		return $frm;
	}

	function delete ($a) {
		global $gdl_db;
		$dbres = $gdl_db->delete("user","user_id='$a'");
	}	
	
	function update ($newprofile, $a) {
		global $gdl_db;					 
		$date = date("Y-m-d H:i:s");
		
		foreach($newprofile as $key=>$value) {
			$newprofile[$key] = mysqli_real_escape_string($gdl_db->con, $value);
		}
		$a = mysqli_real_escape_string($gdl_db->con, $a);
		
		$gdl_db->update("user","password=SHA2($newprofile[PASSWORD], 512), group_id='$newprofile[GROUPLEVEL]', 
		
					name='$newprofile[FULLNAME]', address='$newprofile[ADDRESS]', city='$newprofile[CITY]', 
					
					country='$newprofile[COUNTRY]', institution='$newprofile[INSTITUTION]', job='$newprofile[JOB]', 
					
					active='$newprofile[ACTIVE]', date_modified='$date'",
		
					"user_id='$a'");mysqli_error($gdl_db->con);var_dump($newprofile);
		if (mysqli_affected_rows($gdl_db->con) >0) {
			return true;
		} else 	{
			return false;
		}	
	}

	function update_user($newprofile,$a) {
		global $gdl_db;					 
		$date = date("Y-m-d H:i:s");
		
		foreach($newprofile as $key=>$value) {
			$newprofile[$key] = mysqli_real_escape_string($gdl_db->con, $value);
		}
		
		$gdl_db->update("user","password=SHA2($newprofile[PASSWORD], 512),
		
					name='$newprofile[FULLNAME]', address='$newprofile[ADDRESS]', city='$newprofile[CITY]', 
					
					country='$newprofile[COUNTRY]', institution='$newprofile[INSTITUTION]', job='$newprofile[JOB]', 
					
					date_modified='$date'",
		
					"user_id='$a'");
		if (mysqli_affected_rows($gdl_db->con) >0) {
			return true;
		} else 	{
			return false;
		}	
	}

	function get_identity ($a) {
		global $gdl_db;
		
		$a = mysqli_real_escape_string($gdl_db->con, $a);
		
		$dbres = $gdl_db->select("user", "*", "user_id='$a'");
		
		while ($row = @mysqli_fetch_array ($dbres)){
			$frm['EMAIL'] = $row['user_id'];
			$frm['FULLNAME'] = $row['name'];
			$frm['PASSWORDORIGINAL'] = $row['password'];
			$frm['ACTIVE'] = $row['active'];
			$frm['GROUPLEVEL'] = $row['group_id'];
			$frm['ADDRESS'] = $row['address'];
			$frm['CITY'] = $row['city'];
			$frm['COUNTRY'] = $row ['country'];
			$frm['INSTITUTION'] = $row ['institution'];
			$frm['JOB'] = $row ['job'];
			$frm['VALIDATION'] = $row ['validation'];	
		}
			$this->passwd = $frm['PASSWORDORIGINAL'];
		
		return $frm;
	}	

}		
?>