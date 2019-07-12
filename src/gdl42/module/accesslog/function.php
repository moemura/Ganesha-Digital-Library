<?php 

/***************************************************************************
                         /module/accesslog/function.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (eregi("function.php",$_SERVER['PHP_SELF'])) die();

function display_configuration() {
	//global $accesslog;
	$dirhandle=@opendir("./module");
	if ($dirhandle) {
			require_once("./class/repeater.php");
			include("./module/accesslog/conf.php");
			$main.="<form method=post action='./gdl.php?mod=accesslog'>";
			$grid=new repeater();
			$header[1]=_MODULE;
			$header[2]=_OPERATION;
			
			while (false !== ($module = readdir($dirhandle))) {
				if (is_dir("./module/".$module) && $module != "." && $module != "..") {
					$moddirhandle=@opendir("./module/".$module);
					if ($moddirhandle) {
						$op="";
						while (false !== ($operation = readdir($moddirhandle))) {
							if (is_file("./module/".$module."/".$operation) && $operation != "function.php" && $operation != "conf.php" && substr($operation,-3,3) == "php") {
									if ($accesslog[$module."_".$operation])
										$checked="CHECKED";
									else
										$checked="";
									$op.="<input type=checkbox name=frm[".$module."_".$operation."] value='true' ".$checked.">".$operation."<br/>";							
								}
						}
					}					
					$field[1]=$module;
					$field[2]=$op;
					$item[]=$field;
				}
			}
	}
	
	$colwidth[1] = "25px";
	$colwidth[2] = "25px";
	
	$grid->header=$header;
	$grid->item=$item;
	$grid->colwidth=$colwidth;
	
	$main.=$grid->generate();
	$main.="<input type=submit name=submit value="._SUBMIT."></form>";
	return $main;
}

function write_configuration() {
	global $frm;
	
	$str="<?
if (eregi(\"conf.php\",\$_SERVER['PHP_SELF'])) {
    die();
}

\$gdl_modul['name'] = _ACCESSLOG;
";

	if (is_array($frm)) {
		foreach ($frm as $idxFrm => $valFrm) {
			if (ereg("true",$valFrm)) {
$str.="\$accesslog[\"".$idxFrm."\"] = true;
";
			}
			else {
$str.="\$accesslog[\"".$idxFrm."\"] = false;
";
			}			
		}		
	}
	
	$str.="?>";
	
	$filehandle=@fopen("./module/accesslog/conf.php","w");
	if ($filehandle) {
		fputs($filehandle,$str);
		$res=fclose($filehandle);
	}

	return $res;	
}


