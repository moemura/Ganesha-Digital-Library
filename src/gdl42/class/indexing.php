<?php

if (preg_match("/indexing.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class indexing{
	
	var $dump_count;
	
	// extracting metadata into files
	function dump(){
		global $gdl_metadata;

		require_once ("./class/db.php");
		$db = new database();
		
		$tmpdir = "./files/tmp/indexing";

		$dbres = $db->select("metadata","identifier,prefix,xml_data","xml_data is not null AND xml_data<>'deleted'");
			
		while ($row = mysql_fetch_array($dbres)){
			$dump_id .= "$row[identifier] ";
			$count++;
			
			$id = "$row[identifier]";
			$id = ereg_replace("\/","_slash_",$id);
			
			$data = $row[xml_data];
			$data = $this->indexing_cleanupxml($data);
		
			$size = strlen($data);
		
			if ($size > 40000) {
				$data1 = substr($data,0,20000);	
				$data2 = substr($data,-20000);
				$data = "$data1 $data2";
				$size = strlen($data);	
			}

			if (ereg("oai_dc",$row['prefix'])) {
				$data=str_replace(substr($data,0,strpos($data,">")+1),"<dc>",$data);
				$data=str_replace("<oai_dc:dc xmlns:oai_dc=http://www.openarchives.org/OAI/2.0/oai_dc/ xmlns:dc=http://purl.org/dc/elements/1.1/ xmlns:xsi=http://www.w3.org/2001/XMLSchema-instance xsi:schemaLocation=http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd>","<dc>",$data);
				$data=str_replace("<dc:","<",$data);				
				$data=str_replace("</dc:","</",$data);
				$data=str_replace("</oai_dc:dc>","</dc>",$data);

			}
			// Store metadata into files
			$fp = @fopen("$tmpdir/$id","w");
			@fputs ($fp, $data);
			@fclose($fp);

		}
		
		// Count records have been extracted from the database.
		$this->dump_count = $count;
		return $dump_id;		
	}
	
	// removing bad characters
	function indexing_cleanupxml($xml_data){
		global $gdl_metadata;
		
		$alien_char = array(";","‘","’","&","'","\"","“","”","~","»","%","©","°","´","–");
		
		$data = $xml_data;		
		while(list($key,$val) = each($alien_char)){
			$data = str_replace($val,"",$data);
		}
						

		return $data;
	}
	
	// index the metadata files
	function build(){
		global $gdl_sys,$gdl_err,$gdl_content;
		
		$swishe = $this->indexing_var();
		
		if (!file_exists($swishe['bin'])) {
			// check on /usr/bin/swish-e
			if (!file_exists("/usr/bin/swish-e")) {
				if (!file_exists("/usr/local/bin/swish-e")) {
				$gdl_content->set_error("Fatal error: SWISH-E program file (<b>$swishe[bin]</b>) is not found.
					Please check your system configuration file.",_ERROR,"class.indexing.indexing_bulid.1");
					return;
				} else {
					$swishe['bin'] = "/usr/local/bin/swish-e";
				}
			} else {
				$swishe['bin'] = "/usr/bin/swish-e";
			}
		}
		if (!file_exists($swishe['cfg'])){
			$gdl_content->set_error("Fatal error: SWISH-E configuration file (<b>$swishe[cfg]</b>) is not found.
				Please check your system configuration file.",_ERROR,"class.indexing.indexing_bulid.2");
			return;
		}

		//berhubung swish-e bin enggak jalan di freebsd
/**
		if ($gdl_sys['os'] == "freebsd") {
			$str_cmd = "/usr/local/bin/swish-e -c $swishe[cfg]";
		} else{
			$str_cmd = "/usr/bin/swish-e -c $swishe[cfg]";
			//$str_cmd = "$swishe[bin] -c $swishe[cfg]";
		}
*/
		$str_cmd = "$swishe[bin] -c $swishe[cfg]";
		$main .= "<pre>$str_cmd\n";
		
		$cmd = `$str_cmd`;
		
		$res = explode("\n",$cmd);
		
		for ($i = 0;$i< sizeof($res);$i++){
			if (ereg("!!!Adding",$res[$i])){
				$meta = explode("'",$res[$i]);
				$main .= "MetaName $meta[1]\n";			
			} elseif (ereg("XML parse error",$res[$i])){
				$errline = substr($res[$i],35);
				$main .= "File $errline\n";
			} else {
				$main .= "$res[$i]\n";
			}
		}
		
		$main .= $this->indexing_del_swtmp();
		$main .= "</pre>\n";
		
		// delete previous files
		$main .= $this->indexing_init();
		
		return $main;
	}

	
	// swishe program and configurations
	function indexing_var(){
		global $gdl_sys,$schema;
		
		if ($gdl_sys['os'] == "win"){
			$swishe['bin'] = ".\bin\win32\swishe.exe";
			$swishe['cfg'] = ".\bin\swishe.cfg";
			$swishe['cfg2']= ".\bin\swisheisis.cfg";
			if (file_exists("./bin/all.idx") && $schema == "dc")
				$swishe['idx'] = ".\bin\all.idx";
			else
				$swishe['idx'] = ".\bin\gdl42.idx";
		} else {
			$swishe['bin'] = "./bin/$gdl_sys[os]/swishe.bin";
			$swishe['cfg'] = "./bin/swishe.cfg";
			$swishe['cfg2']= "./bin/swisheisis.cfg";
			if (file_exists("./bin/all.idx") && $schema == "dc")
				$swishe['idx'] = "./bin/all.idx";
			else
				$swishe['idx'] = "./bin/gdl42.idx";
		}
		
		return $swishe;
	}
	
	// deleting swish temporary
	function indexing_del_swtmp(){
		$tmpdir = "./";
		if ($dir = opendir($tmpdir)) {
			while (($file = readdir($dir)) !== false) {
				if (substr($file,0,9) == "swtmpfnum"){
					if (@unlink("$tmpdir/$file")) $main = "Removing $file file..ok\n";
				}
			}  
			closedir($dir);
		}
		return $main;
	}
	
	// deleting temporary files
	function indexing_init(){
		
		$tmpdir = "./files/tmp/indexing";
		if ($dir = opendir($tmpdir)) {
			while (($file = readdir($dir)) !== false) {
				if (strlen($file)>5){
					@unlink("$tmpdir/$file");
				}
			}  
			closedir($dir);
			$main = "Removing temporary files..ok.\n";
		}
		return $main;
	}
	
	function indexing_merge($tmp_idx,$dbs_idx,$merge_idx) {
	$swishe = $this->indexing_var();
	
	$str_cmd = $swishe["bin"]." -v 1 -M ".$dbs_idx." ".$tmp_idx;
	
	// removing temporary idx
	if (file_exists($tmp_idx)) {
		unlink($tmp_idx);
		unlink($tmp_idx.".prop");
	}
	
	// merge
	$merge_result = `$str_cmd`;
	
	// update the merge result
	if (copy($tmp_idx,$merge_idx) && copy($tmp_idx.".prop",$merge_idx.".prop")){
		unlink($tmp_idx);
		unlink($tmp_idx.".prop");
	}
	
	// print
	$mergeres = explode("\n",$merge_result);
	while (list($k,$v) = each($mergeres)){
		if (ereg("Replaced",$v)){
			$replaced++;
		} elseif (ereg("Processing",$v)) {
			continue;
		} else {
			$return.= $v."<br>";
		}
	}	
	if ($replaced > 0) $return=$replaced." files were replaced.<br>";
	
	$this->indexing_del_swtmp();
	
	return $return;	
	}
	
	function indexing_union() {
		$gdl_file="./bin/gdl42.idx";
		$isis_file="./bin/all_isis.idx";
		
		if (file_exists($isis_file)){
			$dblist_idx = $isis_file;
		}
		
		if (file_exists($gdl_file)){
			$dblist_idx .= " ".$gdl_file;
		}
		
		$tmp_idx = "./bin/tmp_union.idx";
		$merge_idx = "./bin/all.idx";
		
		
		$return=$this->indexing_merge($tmp_idx,$dblist_idx,$merge_idx);
		
		return $return;	
	}

	// ---------------------------- index using database --------------------------
	function clear_indexing_record() {
		global $gdl_db;
		$gdl_db->delete('index_record');
	}

	function build_indexing_record($delay, $page_size) {
		global $gdl_db, $gdl_metadata;

		$default_value = array('#TITLE#', '#TITLE_ALTERNATIVE#', '#TITLE_SERIES#', '#CREATOR#', '#CREATOR_ORGNAME#',
							'#CREATOR_EMAIL#', '#PUBLISHER#', '#SUBJECT#', '#SUBJECT_HEADING#', '#SUBJECT_KEYWORDS#',
							'#SUBJECT_DDC#', '#DESCRIPTION#', '#DESCRIPTION_NOTE#', '#DATE#', '#TYPE#', '#TYPE_SCHEMA#',
							'#SOURCE#', '#SOURCE_URL#', '#COVERAGE#', '#PERSON_FULLNAME#', '#ORGANIZATION_NAME#',
							'#DESCRIPTION_EXPERIENCE#', '#DESCRIPTION.EXPERTISE#', '#DESCRIPTION_INTEREST#',
							'#PERSON_ADDRESS#', '#ORGANIZATION_ADDRESS#');
		$page 		= $_GET['page'];
		$page 		= empty($page) ? 0 : $page;
		$url_page 	= "./gdl/gdl.php?mod=indexing&amp;op=indexing&amp;page=";

		$records = $gdl_db->select("metadata","identifier,xml_data","","","",($page*$page_size).",".$page_size,"");
		if ($page == 0) {
			$this->clear_indexing_record();
		}

		$identifiers = array();
		while($row = @mysql_fetch_row($records)) {
			if (!empty($row[1])) {
				$xml = $gdl_metadata->readXML($row[1]);
				array_push($identifiers, $row[0]);
				$title 			= (!in_array($xml['TITLE'][0], $default_value)) ? $xml['TITLE'][0] : null;
				$alternative 	= (!in_array($xml['TITLE.ALTERNATIVE'][0], $default_value)) ? $xml['TITLE.ALTERNATIVE'][0] : null;
				$series 		= (!in_array($xml['TITLE.SERIES'][0], $default_value)) ? $xml['TITLE.SERIES'][0] : null;
				$creator 		= (!in_array($xml['CREATOR'][0], $default_value)) ? $xml['CREATOR'][0] : null;
				$orgname		= (!in_array($xml['CREATOR.ORGNAME'][0], $default_value)) ? $xml['CREATOR.ORGNAME'][0] : null;
				$email			= (!in_array($xml['CREATOR.EMAIL'][0], $default_value)) ? $xml['CREATOR.EMAIL'][0] : null;
				$publisher		= (!in_array($xml['PUBLISHER'][0], $default_value)) ? $xml['PUBLISHER'][0] : null;
				$subject		= (!in_array($xml['SUBJECT'][0], $default_value)) ? $xml['SUBJECT'][0] : null;
				$heading		= (!in_array($xml['SUBJECT.HEADING'][0], $default_value)) ? $xml['SUBJECT.HEADING'][0] : null;
				$keywords		= (!in_array($xml['SUBJECT.KEYWORDS'][0], $default_value)) ? $xml['SUBJECT.KEYWORDS'][0] : null;
				$ddc			= (!in_array($xml['SUBJECT.DDC'][0], $default_value)) ? $xml['SUBJECT.DDC'][0] : null;
				$description	= (!in_array($xml['DESCRIPTION'][0], $default_value)) ? $xml['DESCRIPTION'][0] : null;
				$note			= (!in_array($xml['DESCRIPTION.NOTE'][0], $default_value)) ? $xml['DESCRIPTION.NOTE'][0] : null;
				$date			= (!in_array($xml['DATE'][0], $default_value)) ? $xml['DATE'][0] : null;
				$type			= (!in_array($xml['TYPE'][0], $default_value)) ? $xml['TYPE'][0] : null;
				$schema			= (!in_array($xml['TYPE.SCHEMA'][0], $default_value)) ? $xml['TYPE.SCHEMA'][0] : null;
				$source			= (!in_array($xml['SOURCE'][0], $default_value)) ? $xml['SOURCE'][0] : null;
				$url			= (!in_array($xml['SOURCE.URL'][0], $default_value)) ? $xml['SOURCE.URL'][0] : null;
				$coverage		= (!in_array($xml['COVERAGE'][0], $default_value)) ? $xml['COVERAGE'][0] : null;

				$fullname		= (!in_array($xml['PERSON.FULLNAME'][0], $default_value)) ? $xml['PERSON.FULLNAME'][0] : null;
				$experience		= (!in_array($xml['DESCRIPTION_EXPERTISE'][0], $default_value)) ? $xml['DESCRIPTION_EXPERTISE'][0] : null;
				$expertise		= (!in_array($xml['DESCRIPTION.EXPERIENCE'][0], $default_value)) ? $xml['DESCRIPTION.EXPERIENCE'][0] : null;
				$interest		= (!in_array($xml['DESCRIPTION.INTEREST'][0], $default_value)) ? $xml['DESCRIPTION.INTEREST'][0] : null;
				$address		= (!in_array($xml['PERSON.ADDRESS'][0], $default_value)) ? $xml['PERSON.ADDRESS'][0] : null;
				if (empty($address)) {
					$address		= (!in_array($xml['ORGANIZATION_ADDRESS'][0], $default_value)) ? $xml['PORGANIZATION_ADDRESS'][0] : null;
				}
				$name			= (!in_array($xml['ORGANIZATION.NAME'][0], $default_value)) ? $xml['ORGANIZATION.NAME'][0] : null;

				if(empty($gdl_db->prefix))
					$table = "index_record";
				else
					$table = $gdl_db->prefix."_index_record";
						
				$check = @mysql_query("SHOW TABLES LIKE '".$table."'");
				
				if(mysql_num_rows($check) == 0) {
					@mysql_query("CREATE TABLE `".$table."` (
								  `identifier` varchar(100) NOT NULL,
								  `title` varchar(512) default NULL,
								  `alternative` varchar(512) default NULL,
								  `series` varchar(512) default NULL,
								  `creator` varchar(512) default NULL,
								  `orgname` varchar(512) default NULL,
								  `email` varchar(100) default NULL,
								  `publisher` varchar(1024) default NULL,
								  `subject` varchar(512) default NULL,
								  `heading` varchar(512) default NULL,
								  `keywords` varchar(1024) default NULL,
								  `ddc` varchar(1024) default NULL,
								  `description` text,
								  `note` text,
								  `date` varchar(100) default NULL,
								  `type` varchar(100) default NULL,
								  `schema` varchar(50) default NULL,
								  `source` tinytext,
								  `url` varchar(512) default NULL,
								  `coverage` tinytext,
								  `fullname` varchar(512) default NULL,
								  `experience` tinytext,
								  `expertise` tinytext,
								  `interest` tinytext,
								  `address` tinytext,
								  `name` varchar(1024) default NULL
								) ENGINE=InnoDB");						
				}

				$gdl_db->insert("index_record","","'$row[0]', '$title', '$alternative', '$series', '$creator', '$orgname', '$email',"
							    ."'$publisher', '$subject', '$heading', '$keywords', '$ddc',"
								."'$description', '$note', '$date', '$type', '$schema', '$source', '$url', '$coverage',"
								."'$fullname', '$experience', '$expertise', '$interest', '$address', '$name'");
			}
		}

		return $this->handle_index_response($identifiers, $page, $delay);
	}

	function handle_index_response($identifiers, $page, $delay) {
		global $gdl_stdout;

		$response = array();
		$response['redirect'] 	= (count($identifiers) == 0) ? 0 : 1;
		$response['url']		= $gdl_stdout->header_redirect($delay, "./gdl.php?mod=indexing&amp;op=indexing&amp;page=".($page + 1));
		$response['identifier'] = $identifiers;
		return $response;
	}
}
?>
