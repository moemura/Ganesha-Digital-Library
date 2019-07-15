<?php
/***************************************************************************
                          metadata.php  -  Metadata Object
                             -------------------
    begin                : May 28, 2004
    copyright            : (C) 2004 Hayun Kusumah, KMRG ITB
    email                : hayun@kmrg.itb.ac.id

 ***************************************************************************/
if (preg_match("/metadata.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

include ("./class/parser.php");

class metadata extends parser {
	
	var $identifier;
	var $total;
	var $count;
	
	function metadata(){
		$this->identifier = $_SESSION['gdl_identifier'];
	}
	
	function get_list($node="",$type="",$limit="",$count=""){
		global $gdl_db,$gdl_sync,$gdl_sys,$gdl_publisher;
		
		if(is_array($node)){
			for($i=0;$i<count($node);$i++){
				$where = ($i==0)?"(folder=$node[$i])":"$where or (folder=$node[$i])";
			}
			$where	= "($where)";
		}else
			if ($node <> "") $where = "folder=$node";
			
		if ($type <> "") {
			if ($where <> "") $where .= " and ";
			$where .= "type='$schema'";
		}
		
		if ($where <> "") $where .= " and ";
		
		$where .= "xml_data is not null AND xml_data <> 'deleted'";

		if(($gdl_sys["role"] == "NODE") && empty($_GET['node'])){
			$where .= " and identifier like '".$gdl_publisher['id']."-%'";
		}
		//dirubah benirio, agar metadata yang dihapus tidak mucul		
		//$where .= "xml_data <> 'deleted'";
		
		// hitung total metadata pada node tsb
		if ($count==true){
			$dbres = $gdl_db->select("metadata","count(identifier) as total","$where");
			$this->total = @mysql_result($dbres ,0,"total");
		}

		// list metadata per page

		$dbres = $gdl_db->select("metadata","identifier,owner,folder,xml_data,prefix","$where","date_modified","desc",$limit);
		while ($rows = @mysql_fetch_row($dbres)){
			$frm=$this->read_xml($rows[3]);

			$prefix = $rows[4];
			if($prefix == "general"){// general
				$result[$rows[0]]['TITLE']= $frm['TITLE'];
				$result[$rows[0]]['TYPE']= $frm['TYPE'];
				$result[$rows[0]]['DATE_MODIFIED']= $frm['DATE_MODIFIED'];
				$result[$rows[0]]['CREATOR']= $frm['CREATOR'];
				$result[$rows[0]]['RELATION_COUNT']= $frm['RELATION_COUNT'];
			}else if ($prefix == "oai_dc"){ // dublin core
				$result[$rows[0]]['TITLE']= $frm['DC:TITLE'];
				$result[$rows[0]]['TYPE']= $frm['DC:TYPE'];
				$result[$rows[0]]['DATE_MODIFIED']= $frm['DC:DATE'];
				$result[$rows[0]]['CREATOR']= $frm['DC:CREATOR'];
				$result[$rows[0]]['RELATION_COUNT']= $frm['RELATION_COUNT'];
			}
		}
		$this->count = @mysql_num_rows($dbres);
		// empty identifier to session
		$_SESSION['gdl_identifier'] = "";			
		return $result;
	}
	
	function read($id,$option=""){
		global $gdl_content,$gdl_folder,$gdl_file,$gdl_sync;
		
		require_once ("./class/db.php");
		$db = new database();
		
		$dbres = $db->select("metadata","identifier,folder,xml_data,prefix,repository","identifier='$id' and xml_data is not null");
		$gdl_folder->set_path(@mysql_result($dbres,0,"folder"));
		$xmldata 	= @mysql_result($dbres,0,"xml_data");
		$prefix 	= @mysql_result($dbres,0,"prefix");
		$identifier	= @mysql_result($dbres,0,"identifier");
		$repository	= @mysql_result($dbres,0,"repository");
		
		if (!empty($xmldata)){
			if(empty($option)){
				$frm = $this->read_xml($xmldata);
			}else
				$frm = $this->readXML($xmldata);
			
			if($prefix == "oai_dc"){
				$frm['DC:IDENTIFIER'] 	= $identifier;
				$frm['DC:PUBLISHER'] 	= $repository;
				$frm['DC:PREFIX']		= "oai_dc";
				if (empty($frm['DC:RELATION'])) $frm['DC:RELATION']=0;
			}else{
				$frm['PREFIX']		= "general";
				$relation	= $this->get_value($frm,"RELATION_COUNT");
				if (empty($relation)) $frm['RELATION_COUNT']=0;
				
			}
			
			$gdl_content->files=$gdl_file->get_relation($id);
			// save identifier to session
			$_SESSION['gdl_identifier'] = $id;			
			return $frm;
		}
	}
		
	function get_property ($id){
		global $gdl_folder,$gdl_db;
		$dbres = $gdl_db->select("metadata","folder,owner,xml_data","identifier='$id'");
		$arr=$this->read_xml(@mysql_result($dbres,0,"xml_data"));
		$frm['title'] = $this->get_value($arr,"TITLE");
		$frm['author'] = $this->get_value($arr,"AUTHOR");
		$frm['creator'] = $this->get_value($arr,"CREATOR");
		$frm['publisher'] = $this->get_value($arr,"PUBLISHER");
		$frm['description'] = $this->get_value($arr,"DESCRIPTION");
		$frm['date_modified'] = $this->get_value($arr,"DATE_MODIFIED");
		$frm['folder'] = @mysql_result($dbres,0,"folder");
		$frm['owner'] = @mysql_result($dbres,0,"owner");
		$gdl_folder->set_path($frm['folder']);
		return $frm;
	}

	function edit_property($values){
		global $gdl_db,$gdl_folder;
		// get old folder for update content count
		$dbres = $gdl_db->select("metadata","folder,prefix,xml_data","identifier='$values[id]'");
		$old_folder = @mysql_result($dbres,0,"folder");
		$prefix = @mysql_result($dbres,0,"prefix");
		$xmldata = @mysql_result($dbres,0,"xml_data");
		// update property
		$date = date("Y-m-d H:i:s");
		$path = $gdl_folder->get_path($values['folder']);
		$gdl_db->update("metadata","folder=$values[folder],path='$path',owner='$values[owner]',date_modified='$date'","identifier='$values[id]'");
		
		// refresh folder content count
		$folder = $old_folder;
		while ($folder <> 0){
			$dbres = $gdl_db->select("folder","parent","folder_id=$folder");
			$folder =  @mysql_result($dbres,0,"parent");
			$gdl_folder->refresh($folder);
		}
		$folder = $values['folder'];
		while ($folder <> 0){
			$dbres = $gdl_db->select("folder","parent","folder_id=$folder");
			$folder =  @mysql_result($dbres,0,"parent");
			$gdl_folder->refresh($folder);
		}

		if (ereg("general",$prefix)) {
			$frm=$this->read($values['id']);
			$frm['IDENTIFIER_HIERARCHY']=$gdl_folder->get_hierarchy($values['folder']);
			$this->write($frm,$values);
			
			$temp=$this->readXML($xmldata);	
			for ($i = 1; $i <= $frm['RELATION_COUNT']; $i++) {
				$frm['RELATION_NO'] = $i;
				$frm['RELATION_DATEMODIFIED'] = $temp['RELATION.DATEMODIFIED'][$i-1];
				$frm['RELATION_HASFILENAME'] = $temp['RELATION.HASFILENAME'][$i-1];
				$frm['RELATION_HASFORMAT'] = $temp['RELATION.HASFORMAT'][$i-1];
				$frm['RELATION_HASSIZE'] = $temp['RELATION.HASSIZE'][$i-1];
				$frm['RELATION_HASNOTE'] = $temp['RELATION.HASNOTE'][$i-1];
				$frm['RELATION_HASPART'] = $temp['RELATION.HASPART'][$i-1];
				$frm['RELATION_HASPATH'] = $temp['RELATION.HASPATH'][$i-1];
				$frm['RELATION_HASURI'] = $temp['RELATION.HASURI'][$i-1];
				$frm['TYPE_SCHEMA'] = "relation";
				$xmlrela .= $this->generate_xml($frm);
			}
			
			$this->update_relation($values['id'],$frm['RELATION_COUNT'],$xmlrela);				
		}

	}

	function delete($id){
		global $gdl_db,$gdl_file,$gdl_session,$gdl_folder;
		// get old folder for update content count
		$dbres = $gdl_db->select("metadata","folder","identifier='$id'");
		$folder = @mysql_result($dbres,0,"folder");
		// metode in tidak mendelete tetapi mengupdate untuk kepentingan
		// sinkronisasi metadata yg sudah di delete
		$date = date("Y-m-d H:i:s");
		$deluser = $gdl_session->user_id;
		//$gdl_db->update("metadata","owner='$deluser',type=null,folder=null,path=null,xml_data=null,date_modified='$date',status='deleted'","identifier='$id'");
		$gdl_file->delete($id);
		$gdl_db->delete("comment","identifier='".$id."'");
		$gdl_db->delete("bookmark","identifier='".$id."'");
		$gdl_db->insert("outbox","type,identifier,status,folder,datemodified","'metadata','".$id."','deleted','outbox',now()");
		$gdl_db->delete("metadata","identifier='".$id."'");		
		
		$gdl_folder->refresh($folder);
	}

	function write($frm,$property){
		global $gdl_db,$gdl_auth,$gdl_publisher,$gdl_sys,$gdl_form,$gdl_content,$gdl_folder,$gdl_session;
		
		if ($status=="") { $status = "null";
		}else{ $status = "'$status'";}
		
		// melengkapi metadata yg digenarate dr sistem
		$frm['PUBLISHER'] = $gdl_publisher['id'];
		$frm['DATE_MODIFIED']	= date("Y-m-d H:i:s");
		$frm['CONTRIBUTOR_MODIFIEDBY'] = $gdl_session->user_id;
		if ($frm['DATE']=="") $frm['DATE'] = date("Y-m-d");
		$path = $gdl_folder->get_path($property['folder']);
		
		if (empty($frm['IDENTIFIER'])){
			// upload new metadata
			$frm['IDENTIFIER'] = $this->get_identifier($frm['CREATOR']);
			// generate xml, need identifier
			$xmldata = $this->generate_xml($frm);
			$xmldata = $this->clear_badchars($xmldata);
			$gdl_db->insert("metadata","identifier,folder,path,type,xml_data,date_modified,owner,status,prefix,repository","'$frm[IDENTIFIER]',$property[folder],'$path','$frm[TYPE]','$xmldata','$frm[DATE_MODIFIED]','$property[owner]',$status,'general','$gdl_publisher[publisher]'");
		}else{
			// update metadata
			// generate xml
			$xmldata = $this->generate_xml($frm);
			$xmldata = $this->clear_badchars($xmldata);
			$gdl_db->update("metadata","type='$frm[TYPE]',xml_data='$xmldata',date_modified='$frm[DATE_MODIFIED]',path='$path'","identifier='$frm[IDENTIFIER]'");
			// update folder content count di edit property
		}
			
		// save identifier to session
		$_SESSION['gdl_identifier'] = $frm['IDENTIFIER'];
		$this->identifier = $frm['IDENTIFIER'];
		return true;
	}
	
	function get_identifier($author=""){
		global $gdl_publisher,$gdl_db;
		// format identifier : publisherid-author-num
		$author = str_replace(" ","",trim($author));
		$author = ereg_replace("[^<>[:alnum:]]","", $author);
		$author = substr($author,0,10);
		// count total metadata
		$dbres = $gdl_db->select("metadata","count(identifier) as total");
		$num = @mysql_result($dbres,0,"total");
		$num = $num + 1;
		// default
		if ($author == "") $author= "guest";
		$identifier = strtolower("$gdl_publisher[id]-$gdl_publisher[apps]-$author-$num");
		return $identifier;
	}
		
	function update_relation($id,$fcount,$xmlrela=""){
		global $gdl_db;
		$frm = $this->read($id);
		if (is_array($frm)){
			// metadata not deleted / null
			$frm['RELATION_COUNT']=$fcount;
			
			// generate xml, need identifier
			$xmldata = $this->generate_xml($frm);
			if ($xmlrela <> "") $xmldata = str_replace("<relation>#RELATION_EXTERNAL_ENTITIES#</relation>",$xmlrela,$xmldata);
			
			// clean up empty XML
			$str_meta = ereg_replace("#[[:alpha:]]+#","",$str_meta);
			$str_meta = ereg_replace("#[[:alpha:]]+_[[:alpha:]]+#","",$str_meta);
			$str_meta = ereg_replace("#[[:alpha:]]+_[[:alpha:]]+_[[:alpha:]]+#","",$str_meta);
			
			$gdl_db->update("metadata","xml_data='$xmldata'","identifier='$id'");
		}
	}
	
	function get_publisher($id){
		global $gdl_db;
		$dbres = $gdl_db->select("metadata","xml_data,repository","identifier='$id'");
		$frm=$this->read_xml(@mysql_result($dbres,0,"xml_data"));
		$publisher = $frm['PUBLISHER'];
		if(empty($publisher)) $publisher = @mysql_result($dbres,0,"repository");
		return $publisher;
	}
	
	function get_editor($id){
		global $gdl_publisher,$gdl_db;
		$publisher = $this->get_publisher($id);
		if ($gdl_publisher['id']==$publisher){
			$dbres = $gdl_db->select("metadata m,user u","u.name","m.owner=u.user_id and m.identifier='$id'");
			$editor = @mysql_result($dbres,0,"name");
		}else{
			$dbres = $gdl_db->select("metadata","owner","identifier='$id'");
			$owner = @mysql_result($dbres,0,"owner");
			if (substr($owner,0,1) != '#' && substr($owner,-1,1) != '#')
				$editor = $owner."@".strtolower($publisher);
			else
				$editor = strtolower($publisher);
		}
		return $editor;
	}

	function generate_form($schema){
		global $frm,$gdl_form,$gdl_content, $gdl_err, $gdl_folder,$gdl_db;
		
		$schema_values = "./schema/upload/".$schema."_values.php";
		if (file_exists($schema_values)) include $schema_values;
		if (file_exists("./schema/lang/".$gdl_content->language.".php")) include ("./schema/lang/".$gdl_content->language.".php");
		if (file_exists("./schema/upload/$schema.php")){
			include ("./schema/upload/$schema.php");
		}else{
			$gdl_content->set_error(_SCHEMANOTAVAILABLE,"","class.metadata.generate_form.$schema");
		}
		return $content;
	}
	
	// xml heading
	function xmlheading(){
		$xmlheading = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		return $xmlheading;
	}
	
	// clearing bad characters
	function clear_badchars($xmldata){
		//clearing character ©
		
		$xmldata = str_replace("©","(c)",$xmldata);
		
		return $xmldata;
	}
	
	// xmlns
	function oaipmp_xmlns(){
		$xmlns = "<OAI-PMP xmlns=\"http://www.indonesiadln.org/OAI/1.0/\"\n". 
				"xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n".
				"xsi:schemaLocation=\"http://www.indonesiadln.org/OAI/1.0/\n".
				"http://www.indonesiadln.org/OAI/1.0/OAI-PMP.xsd\">\n";
	   
	   return $xmlns;
		
	}
	
	// xmlns close
	function oaipmp_close(){
		$close = "\n</OAI-PMP>";
		return $close;
	}


	// xmlns
	function oaipmh_xmlns(){
		$xmlns = "<OAI-PMH xmlns=\"http://www.openarchives.org/OAI/2.0/\" 
				  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
				  xsi:schemaLocation=\"http://www.openarchives.org/OAI/2.0/
				  http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd\">\n";
	   
	   return $xmlns;	
	}
	
	// xmlns close
	function oaipmh_close(){
		$close = "\n</OAI-PMH>";
		
		return $close;
	}

	// generate request
	function generate_request_oaipmp($request_elements){
		$res  = $this->xmlheading();
		$res .= $this->oaipmp_xmlns();
		$res .= $this->requestDate();
		$res .= $request_elements;
		$res .= $this->oaipmp_close();
		
		return $res;
	
	}
	
	function generate_response_oaipmp($verb,$args,$xml_elements){

		$res	=	$this->xmlheading();
		$res 	.= 	$this->oaipmp_xmlns();
		$res 	.=	$this->responseDate();
		$res 	.= 	$this->request($verb,$args);
		$res 	.=	$xml_elements;
		$res 	.=	$this->oaipmp_close();
		
		return $res;
	}

	function generate_response_oaipmh($verb,$args,$xml_elements,$metadataPrefix=""){
		$res	=	$this->xmlheading();
		$res 	.= 	$this->oaipmh_xmlns();
		$res 	.=	$this->responseDate();
		$res 	.= 	$this->request($verb,$args,$metadataPrefix);
		$res 	.=	$xml_elements;
		$res 	.=	$this->oaipmh_close();
		
		return $res;
	}
	
	// responseDate
	function responseDate(){
		$ymd = date("Y-m-d");
		$his = date("H:i:s");
		$responseDate = 	"
		<responseDate>".$ymd."T".$his."Z</responseDate>\n";
		
		return $responseDate;
	}

	// requestDate
	function requestDate(){
		$ymd = date("Y-m-d");
		$his = date("H:i:s");
		$requestDate = 	"<requestDate>".$ymd."T".$his."Z</requestDate>\n";
		
		return $requestDate;
	}

	// request
	function request($verb,$args,$metadataPrefix=""){
		global $_SERVER,$HTTP_SESSION_VARS;
		
		$arr_available_resumptionToken	= array("ListIdentifiers","ListRecords");
		
		if (empty($HTTP_SESSION_VARS[sess_providerId])){	
			$requester = "Unknown";
		} else {
			$requester = $HTTP_SESSION_VARS[sess_providerId];
		}
		
		if(empty($metadataPrefix))
			$request = "\n<request requesterId=\"$requester\"";
		else
			$request = "\n<request ";
			
		/*
		$token = empty($args['resumptionToken'])?0:$args['resumptionToken'];
		if(($token >= 0) && ($metadataPrefix == "oai_dc") && (in_array($verb,$arr_available_resumptionToken))){
			$verb 		= $args['verb'];
			$set		= $args['set'];
			$arr_set	= explode(":",$set);
			$arr_set[2]	= empty($arr_set[2])?"0":$arr_set[2];
			$from		= empty($args['from'])?"0":$args['from'];
			$until		= empty($args['until'])?"0":$args['until'];
			
			$args			= null;
			$args['verb'] 	= $verb;
			if($arr_set[2] != "0"){
					$args['resumptionToken'] = "$from::$until::$arr_set[2]::$token::oai_dc";
			}

		}
		*/
		
		while (list($key,$val) = each($args)){
			$request .= "\n        $key=\"$val\"";
		}
		$request .= ">\n http://$_SERVER[SERVER_NAME]$_SERVER[PATH_INFO]\n</request>\n";
		
		return $request;
	}
	
	
	function setXML_oai_dc($array_field){
		
		$element = array("title","creator","subject","description","publisher","contributor","date","type","format","identifier","source","language","relation","coverage","rights");
		
		$xml = $this->header_oai_dc();
		
		$num_element = count($element);
		for($i=0;$i<$num_element;$i++){
			$key_element 	= $element[$i];
			$value			= $array_field[$key_element];

			if(!empty($value)){
				if(is_array($value)){
					foreach($value as $index => $val)
						$xml .= "\n<dc:$key_element>$val</dc:$key_element>\n";
				}else
					$xml .= "\n<dc:$key_element>$value</dc:$key_element>\n";
			}
			
		}
		$xml .= $this->footer_oai_dc();

		return $xml;
	}
	
	function header_oai_dc(){
	
		$html = "<oai_dc:dc 
         xmlns:oai_dc=\"http://www.openarchives.org/OAI/2.0/oai_dc/\" 
         xmlns:dc=\"http://purl.org/dc/elements/1.1/\" 
         xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" 
         xsi:schemaLocation=\"http://www.openarchives.org/OAI/2.0/oai_dc/ 
         http://www.openarchives.org/OAI/2.0/oai_dc.xsd\">\n";
		 
		 return $html;
	}
	
	function footer_oai_dc(){
		return "</oai_dc:dc>\n";
	}
	
	function get_value($frm,$key,$opt2="",$index=""){
		$result = $frm[$key];
		
		if(empty($result) && ($result != "0")){
			$key = str_replace("_",".",$key);
			$result = $frm[$key];
		}
		
		if(empty($result) && ($result != "0")){
			$key = "DC:".$key;
			$result = $frm[$key];
		}

		if(empty($result) && !empty($opt2) && ($result != "0")){
			$result = $frm[$opt2];
			if(empty($result)){
				$key = "DC:".$opt2;
				$result = $frm[$key];
			}
		}
		
		
		if(is_array($result)){

			if(empty($index))
				$result = $result[0];
			else{
				if(count($result) <= $index) $index=0;
				$result = $result[$index];
			}
		}

		return $result;
	}
	
function metadata_dump($server,$publisherid,$startdate) {
		global $gdl_db,$gdl_publisher;
		
		if ($server==1)
			$where=" AND xml_data LIKE '%<publisher>".$gdl_publisher['id']."</publisher>%'";
		elseif ($server==2)
			$where=" AND xml_data LIKE '%<publisher>".$publisherid."</publisher>%'";				
		
		$dbres=$gdl_db->select("metadata","identifier,date_modified,type,xml_data","date_modified > '".$startdate."'".$where);
		
		while ($rows = @mysql_fetch_array($dbres)){
		$dump .= "
<record>
<header>
  <identifier>".$rows["identifier"]."</identifier>
  <datestamp>".$rows["date_modified"]."</datestamp>
  <setSpec>".$rows["type"]."</setSpec>
</header>
<metadata>
 ".$rows["xml_data"]."
</metadata>
</record>";
		}
		
		
		return $dump;
	}

function convert_metadata_general_to_oai_dc($dataXML){
		global $gdl_db;
		
		$title			= $dataXML['TITLE'][0];
		$creator		= $dataXML['CREATOR'][0];
		$subject		= $dataXML['SUBJECT.HEADING'][0];
		if(empty($subject))
			$subject		= $dataXML['SUBJECT.KEYWORDS'][0];
		
		$key_desc 		= array("DESCRIPTION","DESCRIPTION.ALTERNATIVE","DESCRIPTION.NOTE");
		$desc			= array();
		for($i=0;$i<3;$i++){
			$key 	= $key_desc[$i];
			$data	= $dataXML[$key][0];
			if(!empty($data)){
				$data 	= htmlspecialchars(nl2br($data),ENT_QUOTES);
				$data	= addslashes($data);
				array_push($desc,$data);
			}
		}
		
		$id_publisher	= $dataXML['PUBLISHER'][0];
		if(!empty($id_publisher)){
			$dbres = $gdl_db->select("publisher","DC_PUBLISHER","DC_PUBLISHER_ID like '$id_publisher'");
			if(@mysql_num_rows($dbres) == 1){
				$row = mysql_fetch_row($dbres);
				$publisher = $row[0];
			}
		}
		$contributor	= $dataXML['CONTRIBUTOR'][0];
		$date			= $dataXML['DATE.MODIFIED'][0];
		$type			= $dataXML['TYPE'][0];
		$identifier		= $dataXML['SOURCE.URL'][0];
		$language		= $dataXML['LANGUAGE'][0];
		$relation		= $dataXML['RELATION.COUNT'][0];		
		$coverage		= $dataXML['COVERAGE'][0];
		$rights			= $dataXML['RIGHTS'][0];

		$array_field = array(
								"title"=>$title,
								"creator"=>$creator,
								"subject"=>$subject,
								"description"=>$desc,
								"contributor"=>$contributor,
								"date"=>$date,
								"type"=>$type,
								"identifier"=>$identifier,
								"language"=>$language,
								"relation"=>$relation,
								"coverage"=>$coverage,
								"rights"=>$rights
							);
		$metadata = "<metadata>\n".$this->setXML_oai_dc($array_field)."</metadata>\n";
	
		return $metadata;
	}

}

?>
