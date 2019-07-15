<?php

/***************************************************************************
                          Class isisdb - Class for CDS/ISIS Database Indexing
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com

 ***************************************************************************/

class isisdb {
	
	var $bin;
	var $isisdbdir;
	
	function isisdb() {
		global $gdl_sys;
		
		if ($gdl_sys["os"] == "win"){
			$this->bin = "bin\win32\openisis.exe";
		} else {
			$this->bin = "bin/".$gdl_sys["os"]."/openisis";
		}
		
		$this->isisdbdir="./files/isisdb";
	}
	
	function get_record($db_name,$id) {
		$isis_dirname = $this->isisdbdir."/".$db_name;
		
		if (!empty($isis_dirname)){
			if (file_exists($isis_dirname."/owner.inc"))
				include($isis_dirname."/owner.inc");
			
			$dbname = $isis_dirname."/".$owner["dbname"];
			$strcmd = $this->bin." -db $dbname -id $id";

			$record = `$strcmd`;
			return $record;
			
		} else 
			return 0;					
	}	
	
	function insert_new_db($db_name,$frm) {
		if (!$db_name) 
				$dir=$this->isisdbdir."/".strtolower($frm["orgname"])."_".strtolower($frm["dbname"]);				
		else
			$dir=$this->isisdbdir."/".$db_name;
		
		$dirhandle=$this->check_create_dir($dir);
		
		if ($dirhandle) {
			$return["file"]=$this->upload_file($dir,$frm["dbname"]);
			while (false !== ($file = readdir($dirhandle))) { 
				if ($file != "." && $file != ".." && $file != "owner.inc" && file != "elements.cfg") {
					$ext=substr($file,-3,3);
					rename($dir."/".$file,$dir."/".$frm["dbname"].".".$ext);
				}
			}
			
			$filehandle=@fopen($dir."/owner.inc","w");
			if ($filehandle) {
				$filecontent="<?
		\$owner[\"orgname\"]=\"".$frm["orgname"]."\";
		\$owner[\"dbname\"]=\"".$frm["dbname"]."\";
		\$owner[\"email\"]=\"".$frm["email"]."\";
?>
				";
				
				if (@fputs($filehandle,$filecontent))
					{
						if (!$db_name)
							$return["add"]=true;							
						else
							$return["edit"]=true;
					}
				else
					{
						if (!$db_name)
							$return["add"]=false;
						else
							$return["edit"]=false;
					}
				fclose($filehandle);
			}
			
			closedir($dirhandle);
			
		}
		
		return $return;
	}
	
	function upload_file($dir,$db_name) {
		global $_FILES;
		
		foreach ($_FILES as $idxFile => $valFile) {
		   if ($_FILES[$idxFile]["name"]) {
			   	if (@is_uploaded_file($_FILES[$idxFile]["tmp_name"])) {
					$ext=strtolower(substr($idxFile,0,3));
					$uploadfile=$dir."/".$db_name.".".$ext;
					
					$this->check_file($dir,$ext);
					
					if (@copy($_FILES[$idxFile]["tmp_name"],$uploadfile)) {
						$return[$_FILES[$idxFile]["name"]]=true;						
				   } else
					$return[$_FILES[$idxFile]["name"]]=false;					
				} else
					$return[$_FILES[$idxFile]["name"]]=false;
		   }				
		}
		
		return $return;
	}
	
	function check_file($dir,$ext) {
		$dirhandle=@opendir($dir);
		while (false !== ($file=readdir($dirhandle))) {
			if ($file != "." && $file != ".." && $file != "owner.inc") {
				$ext_file=substr($file,-3,3);
				if ($ext==$ext_file)
					unlink($dir."/".$file);
			}
		}
		closedir($dirhandle);
	}
	
	function check_create_dir($dir) {			
		$dirhandle=@opendir($dir);
		
		if (!$dirhandle) {
			if(mkdir($dir)) {
				$dirhandle=@opendir($dir);						
			}
			else
				$dirhandle=0;				
		}
		
		return $dirhandle;
	}
	
	function delete_db($db_name) {
		$dir=$this->isisdbdir."/".$db_name;
		if ($dirhandle=opendir($dir)) {
			while (false !== ($file = readdir($dirhandle))) { 
				if ($file != "." && $file != "..") {
					unlink($dir."/".$file);
				}
			}
			closedir($dirhandle);
			
			if (rmdir($dir)) 
					$return=true;
			else
					$return=false;
		
		} else 
			$return=false;
			
		return $return;
	}
	
	function save_configuration($db_name,$frm) {
		$elementscfg = $this->isisdbdir."/".$db_name."/elements.cfg";
		$filehandle=fopen($elementscfg,"w");
		$strcfg="<?
";
		
		foreach ($frm as $idxFrm => $valFrm) {
		   if (preg_match("/ROW/i",$idxFrm))
				$strcfg.="\$frm[\"".$idxFrm."\"]=\"".$valFrm."\";
";
		}
		
		$strcfg.="
		?>";
		
		if (fputs($filehandle,$strcfg))
			$return=true;
		else
			$return=false;
			
		return $return;	
	}
	
	function get_xml_record($db_name,$id,$record) {
		global $gdl_metadata;
		
		$elementscfg=$this->isisdbdir."/".$db_name."/elements.cfg";
		$ownerfile=$this->isisdbdir."/".$db_name."/owner.inc";
		if (!file_exists($elementscfg))
			$return=false;
		else {
			include ($elementscfg);
			include ($ownerfile);
			if (!is_array($frm))
				$return=false;
			else {				
				$xml=$gdl_metadata->readXML($record);
				foreach ($frm as $idxFrm => $valFrm) {
					$xmlData.="
					<".$valFrm.">".$xml[$idxFrm][0]."</".$valFrm.">";
				}
				
					$xmlrecord = 
"<dc>
<schema>catalogs</schema>
<id>".$id."</id>
<dbname>".$owner["dbname"]."</dbname>".$xmlData."
</dc>";

				return $xmlrecord;
			}
		}
	}
};


?>