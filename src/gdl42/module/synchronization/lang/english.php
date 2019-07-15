<?php
if (preg_match("/english.php/i",$_SERVER['PHP_SELF'])) {
    die();
}
	
	define("_SEARCHREPOSITORY","Searching");
	define("_REPOSITORYSEARCH","Search");
	
	define("_FAILEDCONNECTION","Failed Connection");
	define("_CONFIGURATION","Configuration");
	define("_CONNECTION","Connection");
	define("_DISCONNECTION","Disconnection");
	define("_HARVESTING","harvest Data");
	define("_SAVECHANGES","Execute");
	define("_TARGETSERVERNAME","Target Server Name");
	define("_USEPROXY","Use Proxy");
	define("_PROXYADDRESS","Proxy Address");
	define("_OAISCRIPT","OAI-Script");
	define("_NUMOFRECORD","Number of records per posting or harvesting request");
	define("_FRAGMENTSIZE","Fragment size for sending file (in bytes)");
	define("_SERVERRESPONSEDETAIL","Server's response detail (in xml)");
	define("_HARVESTALLRECORDSUNDERNODEID","Harvest all records under node id (Empty or \"0\" means all records in the hub server will be harvested)");
	define("_OPTIONSAVE","Option setting is successfully saved..");
	define("_OPTIONSAVEFAILED","Option setting is not successfully saved..");
	define("_SYNCHRINDEX","<p>There are two ways to do synchronization:</p>
<ol>
   <li> Export the metadata into a compressed archive file. Then, send the file to the administrator of the hub server using CD-ROM or Floppy Disk. To import, get the archive file from other server, and then import to your server.</li>
   <li> Online synchronization. You can do posting and harvesting of metadata collection with hub server directly if your server connected to the internet (dedicated, dial-up, or behind  proxy).</li>
</ol>
<p>Note: Make sure that you have update your Publisher data before harvesting metadata from target server.</p>");
	define("_SHOW","Show");
	define("_HIDE","Hide");
	define("_CONNECTED","Connected");
	define("_TOTARGETSERVER","to target server");
	define("_EXPORT","Eksport");
	define("_IMPORT","Import");
	define("_SETSTARTINGLASTMODIFIED","Set Starting  Last Modified Date of the Metadata");
	define("_EXPORTFROMSERVER","Export Metadata from following server");
	define("_MYGDLSERVER","My GDL Server");
	define("_ALLSERVER","All Servers");
	define("_SERVERWITHPUBLISHERID","Server with publisher ID");
	define("_STARTINGDATE","Starting date (format DD-MM-YYYY)");
	define("_IFEMPTY","If empty,it will be assumed that all metadata will be archived.");
	define("_PUBLISHERID","Publisher ID");
	define("_EXPORTSUCCESS","Metadata are successfully compressed and archived");
	define("_EXPORTFAILED","Failed to export Metadata");
	define("_FILENAME","File name");
	define("_FILESIZE","File size");
	define("_DOWNLOADMETADATA","Download Metadata Archive");
	define("_TODOWNLOAD","To download file, please using <b>right click</b> on mouse then choose <b>Save Target As</b>");
	define("_METADATANOTARCHIVED","Metadata is not yet archived. Please click <b>Export Metadata</b> menu and follow the steps");
	define("_UPLOADARCHIVEDMETADATA","<b>Step 1 : Upload the compressed Metadata archived file</b>");
	define("_IMPORTUPLOADEDFILE","<b>Step 2 : Import the uploaded file into database</b>");
	define("_FROM","From");
	define("_ACTION","Action");
	define("_GZIPUPLOADERROR","Failed to upload file, the file format should be gzip file (.gz)");
	define("_METADATAUPLOADERROR","Failed to upload file, the file format should be 'metadata-PUBLISHERID.gz'");
	define("_UPLOADFILESUCCESS","Upload file success");
	define("_UPLOADFILEERROR","Failed to upload file due to bad communication line");
	define("_DELETESUCCESS"," deleted successfully");
	define("_DELETEFAILED","failed to delete ");
	define("_NOTFOUND","not found");
	define("_UPLOADFILESIZEERROR","Failed to upload file, file size cannot more than");
	
	define("_REPOSITORYNUMBER","No");
	define("_REPOSITORYNAME","Repository Name");
	define("_BASEURL","Repository Host");
	define("_PREFIX","Prefix");
	define("_REPOSITORYUPDATE","Update Repository");
	define("_REPOSITORYACTION","Action");
	define("_REPOSITORYADD","Add Repository");
	define("_REPOSITORYFROMPUBLISHER","Update from Publisher Data");
	define("_CURRENTREPOSITORYCONNECTION","Current Repository");
	define("_REPOSITORYDISPLAYING","List of Repository");
	define("_OF","from");
	define("_PAGE","Page");
	define("_OPTION_SCRIPT","Metadata Prefix");
	define("_SETOPTION","Selective <i>Harvesting</i>");
	define("_OPTIONFROM","Internal Time Boundary");
	define("_OPTIONUNTIL","External Time Boundary");
	define("_TYPEACTION","Type of Action");
	define("_REQUESTFORMAT","Format Request");
	define("_CURRENTREPOSITORY","Your Connection using Repository");

	define("_OUTBOX_NUMBER","No");
	define("_OUTBOX_FOLDER","Folder");
	define("_OUTBOX_SUM","Sum");
	define("_OUTBOX_ACTION","Action");
	
	define("_POSTING","Posting File");
	define("_NOFILE","No");
	define("_POSTFILE","Check");
	define("_LASTDATE","Last Modification");
	define("_SIZE","Size");
	define("_QUEUENUMBER","No");
	define("_QUEUEPATH","Path");
	define("_QUEUESTATUS","Status");
	define("_QUEUEACTION","Action");
	define("_STARTPOSTING","Start Posting File");
	define("_POSTINGFILES","Execute");
	define("_POSTINGDISPLAYING","List of queue ");
	
	define("_BOX_STATUS_POSTING","List of posting file status");
	define("_BOX_NUMBER","No");
	define("_BOX_PATH","Path file");
	define("_BOX_STATUS","Status of posting");
	define("_BOX_ACTION","Action");
?>