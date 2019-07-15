<?php

if (preg_match("/content.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class content{

	var $path="";
	var	$main;
	var $files=array();
	var $relation;
	var $module=array();
	var $menu=array();
	var $banner;
	var $advertising;
	var $language="";
	var $theme="";
	var $style;
	var $meta;
	var $javascript;
	var $error="";
	
	function set_main($main){
		$previous = $this->main;
		$this->main = $previous . $main;
	}

	function set_style($style){
		$previous = $this->style;
		$this->style = $previous . $style;
	}
	
	function set_javascript($script){
		$previous = $this->javascript;
		$this->javascript = $previous . $script;
	}
	
	function set_advertising($advertising){
		$previous = $this->advertising;
		$this->advertising = $previous . $advertising;
	}

	function set_banner($banner){
		$previous = $this->banner;
		$this->banner = $previous . $banner;
	}
	
	function set_contributor($contributor){
		$previous = $this->contributor;
		$this->contributor = $previous .$contributor;
	}
	
	function set_relation($relation){
		$previous = $this->relation;
		$this->relation = $previous .$relation;
	}
	
	function set_message($message,$title=""){
		$previous = $this->main;
		if ($title=="") $title = _INFORMATION;
		$message = gdl_content_box($message,$title);
		$this->main = $message . $previous;
	}
	
	function set_meta($meta){
		$previous = $this->meta;
		$this->meta = $previous .$meta;
	}
	
	function set_error($description,$title,$code=""){
		global $gdl_mod,$gdl_op;
		$error_msg 	= "<h3>$title</h3>\n"
					."<div class=\"contentbox\">\n";
		$error_msg .= "<p>$description<br/><br/>\n";
		if ($code == ""){
			$error_msg .= "<span class=\"note\"># $gdl_mod.$gdl_op #</span></p>\n";
		}else{
			$error_msg .= "<span class=\"note\"># $code #</span></p>\n";
		}
		$error_msg .= "</div>\n";
		$this->error = $error_msg;
	}
}

?>