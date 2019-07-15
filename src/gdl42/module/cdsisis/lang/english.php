<?php
if (preg_match("/english.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

define("_NEW","New Database");
define("_ACTION","Action");
define("_DBNAME","Database Name");
define("_CANTFINDFOLDER","Cannot find folder");
define("_SUCCESSDELETE","Success deleting database in folder");
define("_CONFIRMATION","Deletion confirmation");
define("_DELETECDSISISCONFIRMATION","Are you sure you want to delete this CDS/ISIS database ?");
define("_DELETEYES","Yes, I'm sure");
define("_DELETECDSISIS","CDS/ISIS Database deletion");
define("_FAILEDDELETE","Cannot deleting database in folder");
define("_NEWCDSISIS","Add new CDS/ISIS database");
define("_EDITCDSISIS","Edit CDS/ISIS database");
define("_DATABASEOWNER","Database owner");
define("_ORGANIZATIONNAME","Organization name");
define("_DATABASENAME","Database Name");
define("_LIBRARIANEMAIL","Librarian's email");
define("_FILES","Database file");
define("_SUBMIT","Submit");
define("_FAILEDCREATEFOLDER","Failed to create folder");
define("_CREATEFOLDERSUCCESS","Success to create folder");
define("_UPLOADFILEFAILED","Failed to upload file");
define("_UPLOADFILESUCCESS","Success to upload file");
define("_EDIT","Edit");
define("_FILENAME","File name");
define("_SIZE","Size");
define("_DATEMODIFIED","Date modified");
define("_ADDCDSISISSUCCESS","Adding new CDS/ISIS database success");
define("_ADDCDSISISFAILED","Adding new CDS/ISIS database failed");
define("_EDITCDSISISSUCCESS","Editing CDS/ISIS database success");
define("_EDITCDSISISFAILED","Editing CDS/ISIS database failed");
define("_CONFIGURE","Configure");
define("_TEST","Test");
define("_BUILDINDEX","Build Index");
define("_BUILDUNIONINDEX","Build Union Index");
define("_CONFIGURECDSISIS","Configure CDS/ISIS database");
define("_FAILEDCDSISIS","Failed to read first record of CDS/ISIS database, please check the database files. All of the required files must be uploaded");
define("_READSUCCESS","<p><b>Congratulation!</b> We are able to read the database. 
				Now, we are diagnosting the first record of the database.
				Please assign the required fields to their correspondent labels.
				You may assign none, one or more fields to a label.
				</p>");
define("_NEXTRECORD","Next Record");
define("_PERIOD","Period");
define("_LOCATION","Location");
define("_CLASSIFICATION","Classification");
define("_CALLNUMBER","Call Number");
define("_DDCEDITION","DDC Edition");
define("_LOCALCLASSIFICATION","Local Classification");
define("_AUTHOR","Author");
define("_AUTHORCORPORATE","Author Corporate");
define("_CONFERENCE","Conference");
define("_TITLEOFJOURNAL","Title of Journal");
define("_TITLE","Title");
define("_ALTERNATIVETITLE","Alternative Title");
define("_DESCRIPTION","Description");
define("_EDITION","Edition");
define("_PLACEOFPUBLISHER","Place of Publisher");
define("_DIMENTION","Dimention");
define("_ILLUSTRATION","Illustration");
define("_HEIGHT","Height");
define("_SERIES","Series");
define("_NOTE","Note");
define("_BIBLIOGRAPHY","Bibliography");
define("_SUMMARY","Summary or Annotation");
define("_SUBJECT","Subject");
define("_COAUTHOR","Co-Author and Editor");
define("_COAUTHORCORPORATE","Co-Author Corporate");
define("_IDENTIFICATION","Identification");
define("_SAVE","Save");
define("_CONFIGURATIONSAVEDSUCCESS","Database Configuration has been saved successfully");
define("_CONFIGURATIONSAVEDFAILED","Failed to save database configuration");
define("_SELECTLABEL","Select Label");
define("_CDSISISTEST","Test the configuration");
define("_TESTRECORD","Each record will be exported from the CDS/ISIS database in XML format to the 
	 	SWISH-E indexing software like the following example.<hr>");
define("_FOLLOW","Please follow these steps to create index of the database");
define("_EXPORTDATABASE","Export the database into the temporary files");
define("_BUILDINDEX","Build the index");
define("_RECORDEXPORTED","records has been exported into temporary files");
define("_SWISHENOTEXIST","Fatal error, <b>SWISH-E</b> program file is not found");
define("_NOIDXFILEFOUND","There's no CDS/ISIS index file in your GDL server");
define("_FOLLOWINGIDX","These following CDS/ISIS index files will be merged to build union catalog index");
define("_STARTMERGING","Start merging the indexes");
define("_EXPORTINGINPROGRESS","Exporting CDS/ISIS database still in progress...");
define("_EXPORTINGFINISHED","Exporting CDS/ISIS database finished");
define("_BUILDFINALUNIONINDEX","Build Final Index");
define("_FINALUNIONDESCRIPTION","<p>This process will build the final union index from
			both the metadata index (<b>gdl42.idx</b>) and the CDS/ISIS union index (<b>all_isis.idx</b>). 
			This final index (<b>all.idx</b>) is required by the search engine to execute the queries.");

define ("_INDEXCDSISIS","Enable CDS/ISIS Indexing");		
?>