<?php
/***************************************************************************
    last modified		: Jan 15, 2007
    copyleft          		: (L) 2006 KMRG ITB
    email                	: mymails_supra@yahoo.co.uk (GDL 4.2 , design,programmer)
								  benirio@itb.ac.id (GDL 4.2, reviewer)

 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
 if (preg_match("/oaiFactory.php/i",$_SERVER['PHP_SELF'])) {
    die();
}


class oaiFactory{
	
	var $metadataPrefix;
	var $array_metadataPrefix;
	
	function __construct($prefix){
		$this->array_metadataPrefix = array("general","oai_dc");
		
		if(in_array($prefix,$this->array_metadataPrefix))
			$this->metadataPrefix = $prefix;
		else 
			$this->metadataPrefix = "general";
	}
	
	function createFactory_oaipmh(){
		switch($this->metadataPrefix){
			case "general":	$result = new oaipmh_GN();
							break;
			case "oai_dc" : $result = new oaipmh_DC();
							break;
		}
		return $result;	
	}
	
	function createFactory_oaipmp(){
		switch($this->metadataPrefix){
			case "general":	$result = new oaipmp_GN();
							break;
			case "oai_dc" : $result = new oaipmp_DC();
							break;
		}
		return $result;	
	}
}
?>