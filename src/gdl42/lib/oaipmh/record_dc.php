<?php
/** \file
 * \brief Definition of Dublin Core handler.
 *
 * It is not working as it does not provide any content to the metadata node. It only included
 * to demonstrate how a new metadata can be supported. For a working
 * example, please see record_rif.php.
 *
 * @author: Ismail Fahmi, ismail.fahmi@gmail.com
 *
 * \sa oaidp-config.php 
	*/

function create_metadata($outputObj, $cur_record, $identifier, $setspec, $db) {
	global $parser;

	if (!defined('GDL_SERVER_NAME')) define('GDL_SERVER_NAME', $_SERVER['SERVER_NAME']);
	if (!defined('GDL_BASE_URL')) define('GDL_BASE_URL', 'http://'.GDL_SERVER_NAME.dirname($_SERVER['SCRIPT_NAME']));

	$metadata_node = $outputObj->create_metadata($cur_record);

    $oai_node = $outputObj->addChild($metadata_node, "oai_dc:dc");
	$oai_node->setAttribute("xmlns:oai_dc","http://www.openarchives.org/OAI/2.0/oai_dc/");
	$oai_node->setAttribute("xmlns:dc","http://purl.org/dc/elements/1.1/");
	$oai_node->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
	$oai_node->setAttribute("xsi:schemaLocation", "http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd");

	$record = get_record($identifier, $db);
	$authors = array();

	//print_r($record['xml_data']);
	$metadata = $parser->read_xml($record['xml_data']);
	//print_r($metadata); 

	$outputObj->addChild($oai_node,'dc:title', xml_safe($metadata['TITLE']));
	foreach ($authors as $author){
		$outputObj->addChild($oai_node,'dc:creator', xml_safe($metadata['CREATOR']));
	}
	$subjects = explode(';', @$metadata['SUBJECT']);
	foreach ($subjects as $subject){
		$outputObj->addChild($oai_node,'dc:subject', xml_safe(trim($subject)));
	}
	$subjects = explode(';', @$metadata['SUBJECT_KEYWORDS']);
	foreach ($subjects as $subject){
		$outputObj->addChild($oai_node,'dc:subject', xml_safe(trim($subject)));
	}

	$outputObj->addChild($oai_node,'dc:publisher', xml_safe($metadata['CREATOR_ORGNAME']));
	$outputObj->addChild($oai_node,'dc:date', date_safe($metadata['DATE']));
	$outputObj->addChild($oai_node,'dc:language', xml_safe($metadata['LANGUAGE']));
	$outputObj->addChild($oai_node,'dc:format', xml_safe($metadata['TYPE']));
	$outputObj->addChild($oai_node,'dc:identifier', xml_safe($metadata['IDENTIFIER']));
	$outputObj->addChild($oai_node,'dc:description', xml_safe($metadata['DESCRIPTION']));

	if (isset($metadata['RELATION_HASURI'])) {
		if (is_array($metadata['RELATION_HASURI'])){
			foreach ($metadata['RELATION_HASURI'] as $relation){
				$outputObj->addChild($oai_node,'dc:identifier', xml_safe(GDL_BASE_URL . $relation));
			}
		} else {
			$outputObj->addChild($oai_node,'dc:identifier', xml_safe(GDL_BASE_URL . $metadata['RELATION_HASURI']));
		}
	}
}

function xml_safe($string){
	$string = preg_replace('/[\x00-\x1f]/','',htmlspecialchars($string));
	return utf8_encode($string);
}

function date_safe($string){
	if (preg_match("/(\d{4})/", $string, $matches)){
		return $matches[0];
	} else {
		return xml_safe($string);
	}
}

function get_record ($identifier, $db){
	global $SQL;

	$query = "SELECT " . $SQL['identifier'] . "," . $SQL['datestamp'] . "," . $SQL['set'] . ",xml_data" .
		" FROM ".$SQL['table'] . " WHERE identifier = '" . $identifier ."'";

	$res = $db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$r = $res->execute();
 	if ($r===false) {
		if (SHOW_QUERY_ERROR) {
			echo __FILE__.','.__LINE__."<br />";
			echo "Query: $query<br />\n";
			print_r($db->errorInfo());
			exit();
		} else {
			return array();
		}		
	} else {
		$record = $res->fetch(PDO::FETCH_ASSOC);
		return $record;
	}
}


function get_digital_files($identifier, $db) {

	// digital files
        $attachment_q = $this->obj_db->query('SELECT att.*, f.* FROM biblio_attachment AS att
            LEFT JOIN files AS f ON att.file_id=f.file_id WHERE att.biblio_id='.$this->detail_id.' AND att.access_type=\'public\' LIMIT 20');
        if ($attachment_q->num_rows > 0) {
          while ($attachment_d = $attachment_q->fetch_assoc()) {
              $_xml_output .= '<dc:relation><![CDATA[';
              // check member type privileges
              if ($attachment_d['access_limit']) { continue; }
              $_xml_output .= preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]+);/S',
                  'utility::convertXMLentities', htmlspecialchars(trim($attachment_d['file_title'])));
              $_xml_output .= ']]></dc:relation>'."\n";
          }
        }
}