<?php 
if (preg_match("/function.php/i",$_SERVER['PHP_SELF'])) die();

function edit_system_form() {
	global $gdl_form,$db_source,$frm;
	include ("./module/migration/conf.php");
	
	if (!isset($frm)) {
		foreach ($db_source as $IdxGdlSys => $ValGdlSys){
			if ($ValGdlSys===true)
				$frm[$IdxGdlSys]="true";
			elseif ($ValGdlSys===false)
				$frm[$IdxGdlSys]="false";
			else
				$frm[$IdxGdlSys]=$ValGdlSys;
			
		}
	}

	$gdl_form->set_name("edit_db_conn");
	$gdl_form->action="./gdl.php?mod=migration&amp;op=configuration";
		
	$gdl_form->add_field(array(
			"type"=>"title",
			"text"=>_MIGRATIONCONF));

	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[host]",			
				"value"=>isset($frm["host"]) ? $frm["host"] : '',
				"text"=>_HOST,
				"required"=>true,
				"size"=>20));						
				
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[uname]",			
				"value"=>isset($frm["uname"]) ? $frm["uname"] : '',
				"text"=>_USERNAME,
				"size"=>20,
				"required"=>true));
				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[password]",			
				"value"=>isset($frm["password"]) ? $frm["password"] : '',
				"text"=>_PASSWORD,
				"size"=>20));

				
	$gdl_form->add_field(array(
				"type"=>"text",
				"name"=>"frm[name]",			
				"value"=>isset($frm["name"]) ? $frm["name"] : '',
				"text"=>_DBNAME,
				"size"=>20,
				"required"=>true));

	$gdl_form->add_button(array(
			"type"=>"submit",
			"name"=>"submit",
			"column"=>"",
			"value"=>_EDIT)); 
	$content = $gdl_form->generate("30%");
	return $content;
}

function write_file_system() {
	global $frm;
	$file="module/migration/conf.php";
	$filehandle=fopen($file,"w");
	$content = '';
	if ($filehandle) {
		$str_system="<?php
";
		foreach ($frm as $idxFrm => $valFrm) {
			$str_system.="\$db_source[\"".$idxFrm."\"]=\"".$valFrm."\";
";
		}

		$str_system.="
\$gdl_modul['name'] = _MIGRATION4042;
\$gdl_menu['configuration'] = _CONFIGURATION;
";
		$str_system.="?>";
		if (fputs($filehandle,$str_system)) {
			$content.="<b>"._SYSTEMCONFSAVE."</b>";
		}
		fclose($filehandle);
	} else 
		$content.="<b>"._CANNOTOPENFILE."</b>";

	return $content;
}

function display_file_migration() {
	$folder1=get_folder($_SESSION["source_folder"],1);
	$folder2=get_folder($_SESSION["dest_folder"],2);
	$content="<table>
			   	<tr valign=top><td>".gdl_content_box($folder1,"")."</td><td>".gdl_content_box($folder2,"")."</td></tr>
			  </table>";
			  
	return $content;
}

function get_folder($path,$window) {
	global $gdl_content;
	require_once ("./class/repeater.php");
	$temp=getcwd();
	$bin=get_bin();
	$grid = new repeater();
	
	if ($window==1)
		$url="./gdl.php?mod=migration&amp;op=files&amp;folder1=";
	else
		$url="./gdl.php?mod=migration&amp;op=files&amp;folder2=";
	
	$form = "<p>Path : <br/><b>$path</b>";
	
	$header[1] = "&nbsp;";
	$header[2] = _FILESORFOLDER;
	$header[3] = _SIZE;
	
	$colwidth[1] = "15px";
	$colwidth[2] = "";
	$colwidth[3] = "100px";
	
	@chdir($path);
	
	$dirresult=`$bin[dir]`;
	@chdir($temp);
	$dirresult=explode("\n",$dirresult);
	
	if (is_array($dirresult)) {
		$field[1]="<img src=\"./theme/".$gdl_content->theme."/image/icon_dir_list.png\"/>";
		$field[2]="<a href='".$url."..'>..</a>";
		$field[3]="&nbsp;";
		$item[]=$field;
		$form.="<form method=post action='./gdl.php?mod=migration&amp;op=files'>";
		$i=0;
		foreach ($dirresult as $valdirresult) {
		  if (!empty($valdirresult)) {
				if (is_dir($path."/".$valdirresult))
					$field[1]="<img src=\"./theme/".$gdl_content->theme."/image/icon_dir_list.png\"/><input type=checkbox name=folder[$i] value='".$valdirresult."'>";
				else 
					$field[1]="<img src=\"./theme/".$gdl_content->theme."/image/icon_file_list.png\"/><input type=checkbox name=file[$i] value='".$valdirresult."'>";
					
				if (is_dir($path."/".$valdirresult))
					$field[2] = "<a href='".$url.$valdirresult."'>".$valdirresult."</a>";
				else
					$field[2] = $valdirresult;
				if (is_file($path."/".$valdirresult))
					$field[3]=round((filesize($path."/".$valdirresult) / 1000),1)." Kb";			
				else
					$field[3]="&nbsp;";
					
				$item[]=$field;
				$i++;
			}
		}
	}
	
	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	$form .= $grid->generate()."</p>";
	$form .= "<img src=\"./theme/".$gdl_content->theme."/image/arrow_ltr.gif\" alt=\"Delete\"/>";
	$form .= "<input type=hidden name=window value=".$window.">";
	$form .= "<input type=submit name=submit value='Copy'> <input type=submit name=submit value='Delete'></form>";
	$form .= "</form>";
	return $form;
}

function get_bin() {
	global $gdl_sys;
	if ($gdl_sys["os"]=="win") {
		$bin["dir"]="dir/b/ogn";
		$bin["copydir"]="xcopy /e/i";
		$bin["copyfile"]="xcopy";
		$bin["deldir"]="rmdir /s/q";
		$bin["delfile"]="del";
	} else {
		$bin["dir"]="ls";
		$bin["copydir"]="cp -r";
		$bin["copyfile"]="cp";
		$bin["deldir"]="rm -r";
		$bin["delfile"]="rm";
	}
	
	return $bin;
}
?>