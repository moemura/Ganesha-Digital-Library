<?php

if (preg_match("/auth.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class authentication{
		
	function module (){
		global $gdl_mod, $gdl_sys;
		
		if ($this->is_authorized($gdl_mod)){
			if ($gdl_sys['modul_list']) $this->set_module();
			return true;
		}else{
			return false;
		}
	}
	
	function operation (){
		global $gdl_mod,$gdl_op;
		
		if ($this->is_authorized($gdl_mod,$gdl_op)){
			$this->set_menu();
			return true;
		}else{
			return false;
		}
	}
	
	function is_authorized($mod,$op=""){
		global $gdl_session;
		
		if ($gdl_session->authority=="*") {
			// user punya otoritas all
			return true;
		}else{
			// cek hak akses modul
			if (strchr($gdl_session->authority,"{".$mod)){
				// user punya hak akses ke modul
				if ($op == ""){
					// jika hanya otorisasi modul
					return true;
				}else{
					// cek otorisasi operasi
					// mencari otorisasi operasi modul
					$arr_module = explode("}",$gdl_session->authority);
					foreach ($arr_module as $value) {
						if (strchr($value,"{".$mod)){
							$action = preg_replace("/{".$mod."->/", "", $value);
							// cek apakah mempunyai semua operasi modul
							if (($action == "*")or($action == " *")){
								return true;
							}else{
								// cek apakah punya operasi modul
								if (strchr($action,$op)){
									return true;
								}else{
									return false;
								}
							}
						}
					}
				}
				
			}else{
				// user tidak punya hak akses ke modul
				return false;
			}
		}		
	}
	
	function set_module(){
		global $gdl_content,$gdl_session,$gdl_sys;;
		// modul-modul yang boleh dioperasikan oleh user
		$d = dir("./module");
		while (false !== ($entry = $d->read())) {
			if (($entry <> ".") and ($entry <> "..")){
				if (strchr($gdl_session->authority,"{".$entry) or $gdl_session->authority=="*"){
					
					// define language per module
					if (file_exists("./module/$entry/lang/".$gdl_content->language.".php")) {
						include("./module/$entry/lang/".$gdl_content->language.".php");
					}
					
					if (file_exists("./module/$entry/conf.php") && file_exists("./files/misc/install.lck")) {
						include ("./module/$entry/conf.php");
						if (!empty ($gdl_modul['name'])) {
							$modul[$entry] = "$gdl_modul[name]";
						} 
						$gdl_modul['name']="";
					}
				}
			}
		}
		$d->close();
		if (!empty($modul)){
			$gdl_content->module=$modul;
		}
	}
	
	function set_menu(){
		global $gdl_content,$gdl_mod,$gdl_op,$gdl_sys,$gdl_session;
		
		//menu yang ditampilkan untuk operasi modul
		if (file_exists("./module/$gdl_mod/conf.php")) {
			include ("./module/$gdl_mod/conf.php");
			$url = "./gdl.php?mod=$gdl_mod&amp;";
			
			// mencari otorisasi operasi modul
			if (is_array($gdl_menu)){
				if ($gdl_session->authority=="*"){
					foreach ($gdl_menu as $key => $val) {
						$menu_action[$key] = "<a href=\"$url"."op=$key\">$val</a>";
					}
				}else{
				
					$arr_module = explode("}",$gdl_session->authority);
					foreach ($arr_module as $value) {
						if (strchr($value,"{".$mod)){
							$action = preg_replace("/{".$mod."->/", "", $value);
							if (($action == "*")or($action == " *")){
								$action = "";
								$d = dir("./module/$mod");
								while (false !== ($entry = $d->read())) {
									if (strchr($entry,".php")){
										$action .= preg_replace("/.php/", "", $entry).",";
									}
								}
								$d->close();
							}
						}
					}
		
					foreach ($gdl_menu as $key => $val) {
						if (strchr($action,$key)){
							$menu_action[$key] = "<a href=\"$url"."op=$key\">$val</a>";
						}
					}
					
				}
			}
		}
		
		// set menu
		if (!empty($menu_action)) $gdl_content->menu = $menu_action;
	}
}

?>