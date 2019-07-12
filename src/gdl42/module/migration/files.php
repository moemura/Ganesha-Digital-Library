<?
/***************************************************************************
                         /module/migration/files.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)

 ***************************************************************************/

if (eregi("files.php",$_SERVER['PHP_SELF'])) die();

include("./module/migration/conf.php");
include("./module/migration/function.php");
$title = _MIGRATION." "._FILE;

if (file_exists("./files/misc/files.lck")){
	$main = "<p>"._LOCK." <b>./files/misc/files.lck</b></p>";
}else{

		$main.="<p>"._SELECTFILE."</p>";
		$main.="<p><b>"._LOCKFILE."</b></p>";
		if (!isset($_SESSION["source_folder"]))
			$_SESSION["source_folder"]=getcwd();
		
		if (!isset($_SESSION["dest_folder"])) 
			$_SESSION["dest_folder"]=getcwd();
			
		$folder1=$_GET["folder1"];
		$folder2=$_GET["folder2"];
		$submit=$_POST["submit"];
		$lock=$_GET["lock"];
		$temp=getcwd();
		if (!empty($folder1)) {
			if (@chdir($_SESSION["source_folder"]."/".$folder1))
				$_SESSION["source_folder"]=getcwd();
		}		
		chdir($temp);
		if (!empty($folder2)) {
			if (@chdir($_SESSION["dest_folder"]."/".$folder2))
				$_SESSION["dest_folder"]=getcwd();
		}
		chdir($temp);

		if (!empty($submit)) {
			$window=$_POST["window"];
			$folder=$_POST["folder"];
			$file=$_POST["file"];
			$bin=get_bin();
			
			if (ereg("Copy",$submit)) {
					if ($window==1) {
						if (is_array($folder)) {			
							foreach ($folder as $valfolder) {
								if ($gdl_sys["os"]=="win")
									$cmd=$bin["copydir"]." \"".$_SESSION["source_folder"]."\\".$valfolder."\" \"".$_SESSION["dest_folder"]."\\".$valfolder."\"";
								else
									$cmd=$bin["copydir"]." \"".$_SESSION["source_folder"]."/".$valfolder."\" \"".$_SESSION["dest_folder"]."\"";
								$copyres.=`$cmd`;					
							}
						}
						
						if (is_array($file)) {
							foreach ($file as $valfile) {
								if ($gdl_sys["os"]=="win")
									$cmd=$bin["copyfile"]." \"".$_SESSION["source_folder"]."\\".$valfile."\" \"".$_SESSION["dest_folder"]."\"";
								else
									$cmd=$bin["copyfile"]." \"".$_SESSION["source_folder"]."/".$valfile."\" \"".$_SESSION["dest_folder"]."\"";
								
								$copyres.=`$cmd`;					
							}
						}

						
					} else {
						if (is_array($folder)) {
							foreach ($folder as $valfolder) {
								if ($gdl_sys["os"]=="win")
									$cmd=$bin["copydir"]." \"".$_SESSION["dest_folder"]."\\".$valfolder."\" \"".$_SESSION["source_folder"]."\"";
								else
									$cmd=$bin["copydir"]." \"".$_SESSION["dest_folder"]."/".$valfolder."\" \"".$_SESSION["source_folder"]."\"";
								$copyres.=`$cmd`;
							}
						}
						
						if (is_array($file)) {
							foreach ($file as $valfile) {
							if ($gdl_sys["os"]=="win")
								$cmd=$bin["copyfile"]." \"".$_SESSION["dest_folder"]."\\".$valfile."\" \"".$_SESSION["source_folder"]."\"";
							else
								$cmd=$bin["copyfile"]." \"".$_SESSION["source_folder"]."/".$valfolder."\" \"".$_SESSION["dest_folder"]."\"";
							$copyres.=`$cmd`;					
							}
						}
					}
			} elseif (ereg("Delete",$submit)) {
					if ($window==1) {
						if (is_array($folder)) {			
							foreach ($folder as $valfolder) {
								if ($gdl_sys["os"]=="win")
									$cmd=$bin["deldir"]." \"".$_SESSION["source_folder"]."\\".$valfolder."\"";
								else
									$cmd=$bin["deldir"]." \"".$_SESSION["source_folder"]."/".$valfolder."\"";
								$delres.=`$cmd`;					
							}
						}
						
						if (is_array($file)) {
							foreach ($file as $valfile) {
								if ($gdl_sys["os"]=="win")
									$cmd=$bin["delfile"]." \"".$_SESSION["source_folder"]."\\".$valfile."\"";
								else
									$cmd=$bin["delfile"]." \"".$_SESSION["source_folder"]."/".$valfile."\"";
								
								$copyres.=`$cmd`;					
							}
						}

						
					} else {
						if (is_array($folder)) {
							foreach ($folder as $valfolder) {
								if ($gdl_sys["os"]=="win")
									$cmd=$bin["deldir"]." \"".$_SESSION["dest_folder"]."\\".$valfolder."\"";
								else
									$cmd=$bin["deldir"]." \"".$_SESSION["dest_folder"]."/".$valfolder."\"";
								$copyres.=`$cmd`;
							}
						}
						
						if (is_array($file)) {
							foreach ($file as $valfile) {
								if ($gdl_sys["os"]=="win")
									$cmd=$bin["delfile"]." \"".$_SESSION["dest_folder"]."\\".$valfile."\"";
								else
									$cmd=$bin["delfile"]." \"".$_SESSION["dest_folder"]."/".$valfile."\"";
								$copyres.=`$cmd`;					
							}
						}
					}				
			}
		}

		if (!empty($lock)) {
			$lckfile = "./files/misc/files.lck";
			$fp = fopen($lckfile,w);
			if ($fp){
				$lckdate = date("Y-m-d h:i:s");
				fputs($fp,$lckdate);
				fclose($fp);
				$main .= "<p>"._NOWLOCK." <b>./files/misc/files.lck</b></p>";
			} else {
				$main .= "Failed to create lock file: $lckfile.";
			}
		} else		
		    $main.=display_file_migration();
}


$main = gdl_content_box($main,"");
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=migration\">"._MIGRATION."</a>";
?>