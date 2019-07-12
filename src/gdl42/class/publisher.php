<?

if (eregi("publisher.php",$_SERVER['PHP_SELF'])) {
    die();
}

class publisher {

	function add_new($serialnumber,$network,$ID,$typeid,$name,$orgname,$contypeid,$hostname,$ipserver,$contact,$address,$city,$region,$country,$phone,$fax,$adminemail,$ckoemail) {
		global $gdl_db,$gdl_sys,$gdl_publisher;
		$type=array("INSTITUTION","PERSONAL","WARNET");
		$contype=array("DEDICATED","TEMPORARY");
		$gdl_db->insert("publisher","DC_PUBLISHER_SERIALNO".
									",DC_PUBLISHER_NETWORK".
									",DC_PUBLISHER_APPS".
									",DC_PUBLISHER_ID".
									",DC_PUBLISHER_TYPE".
									",DC_PUBLISHER".
									",DC_PUBLISHER_ORGNAME".
									",DC_PUBLISHER_CONNECTION".
									",DC_PUBLISHER_HOSTNAME".
									",DC_PUBLISHER_IPADDRESS".
									",DC_PUBLISHER_CONTACT".
									",DC_PUBLISHER_ADDRESS".
									",DC_PUBLISHER_CITY".
									",DC_PUBLISHER_REGION".
									",DC_PUBLISHER_COUNTRY".
									",DC_PUBLISHER_PHONE".
									",DC_PUBLISHER_FAX".
									",DC_PUBLISHER_ADMIN".
									",DC_PUBLISHER_CKO".									
									",DC_PUBLISHER_HUBSERVER".
									",DC_PUBLISHER_DATEMODIFIED"
									,"'$serialnumber','$network','".$gdl_sys['apps']."','$ID','".$type[$typeid]."','$name','$orgname','".$contype[$contypeid]."','$hostname','$ipserver','$contact','$address','$city','$region','$country','$phone','$fax','$adminemail','$ckoemail','".$gdl_publisher['hubserver']."',NOW()");
		
	
	}
	
	function get_property($PUBLISHER_ID) {
		global $gdl_db;
		
		$where="DC_PUBLISHER_ID='$PUBLISHER_ID'";
	
		$dbres = $gdl_db->select("publisher","DC_PUBLISHER_SERIALNO".
									",DC_PUBLISHER_NETWORK".
									",DC_PUBLISHER_APPS".
									",DC_PUBLISHER_ID".
									",DC_PUBLISHER_TYPE".
									",DC_PUBLISHER".
									",DC_PUBLISHER_ORGNAME".
									",DC_PUBLISHER_CONNECTION".
									",DC_PUBLISHER_HOSTNAME".
									",DC_PUBLISHER_IPADDRESS".
									",DC_PUBLISHER_CONTACT".
									",DC_PUBLISHER_ADDRESS".
									",DC_PUBLISHER_CITY".
									",DC_PUBLISHER_REGION".
									",DC_PUBLISHER_COUNTRY".
									",DC_PUBLISHER_PHONE".
									",DC_PUBLISHER_FAX".
									",DC_PUBLISHER_ADMIN".
									",DC_PUBLISHER_CKO".									
									",DC_PUBLISHER_HUBSERVER".
									",DC_PUBLISHER_DATEMODIFIED","$where","DC_PUBLISHER_DATEMODIFIED","desc",$limit);
		while ($rows = @mysql_fetch_row($dbres)){
			$result[_PUBLISHERSERIALNUMBER]= $rows ['0'];
			$result[_PUBLISHERNETWORK]= $rows ['1'];
			$result[_PUBLISHERAPP]= $rows['2'];
			$result[_PUBLISHERID]= $rows['3'];
			$result[_PUBLISHERTYPE]= $rows['4'];
			$result[_PUBLISHERNAME]= $rows['5'];
			$result[_PUBLISHERORGNAME]= $rows['6'];
			$result[_PUBLISHERCONTYPE]= $rows['7'];
			$result[_PUBLISHERHOSTNAME]= $rows['8'];
			$result[_PUBLISHERIPADDRESS]= $rows['9'];
			$result[_PUBLISHERCONTACTNAME]= $rows['10'];
			$result[_PUBLISHERADDRESS]= $rows['11'];
			$result[_PUBLISHERCITY]= $rows['12'];
			$result[_PUBLISHERREGION]= $rows['13'];
			$result[_PUBLISHERCOUNTRY]= $rows['14'];
			$result[_PUBLISHERPHONE]= $rows['15'];
			$result[_PUBLISHERFAX]= $rows['16'];
			$result[_PUBLISHERADMINEMAIL]= $rows['17'];
			$result[_PUBLISHERCKOEMAIL]= $rows['18'];
			$result[_PUBLISHERHUBSERVER]= $rows['19'];
			$result[_DATEMODIFIED]= $rows['20'];
		}
		
		return $result;
	}
	
	function get_list($search,$limit) {
		global $gdl_db;
		
		if ($search)
			$where="DC_PUBLISHER_ID like '%$search%' or DC_PUBLISHER like '%$search%'";
	
		$dbres = $gdl_db->select("publisher","DC_PUBLISHER_ID, DC_PUBLISHER, DC_PUBLISHER_CITY, DC_PUBLISHER_NETWORK, DC_PUBLISHER_HUBSERVER","$where","DC_PUBLISHER_DATEMODIFIED","desc",$limit);
		while ($rows = @mysql_fetch_row($dbres)){
			$result[$rows[0]]['ID']= $rows ['0'];
			$result[$rows[0]]['NAME']= $rows ['1'];
			$result[$rows[0]]['CITY']= $rows['2'];
			$result[$rows[0]]['NETWORK']= $rows['3'];
			$result[$rows[0]]['HUBSERVER']= $rows['4'];
		}
		
		return $result;
	}
	
	function delete ($PUBLISHER_ID) {
		global $gdl_db;
		$dbres = $gdl_db->delete("publisher","DC_PUBLISHER_ID='$PUBLISHER_ID'");
	}	
	
	function update($newdata,$PUBLISHERID) {
		global $gdl_db,$gdl_sys,$gdl_publisher;					 
		$type=array("INSTITUTION","PERSONAL","WARNET");
		$contype=array("DEDICATED","TEMPORARY");
		
		$type=$type[$newdata['type']];
		$contype=$contype[$newdata['contype']];
		
		$gdl_db->update("publisher","DC_PUBLISHER_SERIALNO='$newdata[serialnumber]'".
									",DC_PUBLISHER_NETWORK='$newdata[network]'".
									",DC_PUBLISHER_APPS='$gdl_sys[apps]'".
									",DC_PUBLISHER_ID='$newdata[ID]'".
									",DC_PUBLISHER_TYPE='$type'".
									",DC_PUBLISHER='$newdata[name]'".
									",DC_PUBLISHER_ORGNAME='$newdata[orgname]'".
									",DC_PUBLISHER_CONNECTION='$contype'".
									",DC_PUBLISHER_HOSTNAME='$newdata[hostname]'".
									",DC_PUBLISHER_IPADDRESS='$newdata[ipserver]'".
									",DC_PUBLISHER_CONTACT='$newdata[contact]'".
									",DC_PUBLISHER_ADDRESS='$newdata[address]'".
									",DC_PUBLISHER_CITY='$newdata[city]'".
									",DC_PUBLISHER_REGION='$newdata[region]'".
									",DC_PUBLISHER_COUNTRY='$newdata[country]'".
									",DC_PUBLISHER_PHONE='$newdata[phone]'".
									",DC_PUBLISHER_FAX='$newdata[fax]'".
									",DC_PUBLISHER_ADMIN='$newdata[adminemail]'".
									",DC_PUBLISHER_CKO='$newdata[ckoemail]'".									
									",DC_PUBLISHER_HUBSERVER='$gdl_publisher[hubserver]'".
									",DC_PUBLISHER_DATEMODIFIED=NOW()",		
					"DC_PUBLISHER_ID='$PUBLISHERID'");
	}
	
	function save_configuration($frm) {
		$type=array("INSTITUTION","PERSONAL","WARNET");
		$contype=array("DEDICATED","TEMPORARY");
		
		$type=strtoupper($type[$frm["type"]]);
		$contype=strtoupper($contype[$frm["connection"]]);
		$pub_conf = "<?
			# Automatically generated on ".date("Y-m-d H:i:s")."
		
			\$gdl_publisher['id']  = \"$frm[id]\";
			\$gdl_publisher['serialno']  = \"$frm[serialno]\";
			\$gdl_publisher['type']  = \"$type\";
			\$gdl_publisher['connection']  = \"$contype\";
			\$gdl_publisher['apps']  = \"$frm[apps]\";		
			\$gdl_publisher['publisher']  = \"$frm[publisher]\";
			\$gdl_publisher['orgname']  = \"$frm[orgname]\";
			\$gdl_publisher['hostname']  = \"$frm[hostname]\";
			\$gdl_publisher['ipaddress']  = \"$frm[ipaddress]\";
			\$gdl_publisher['contact']   = \"$frm[contact]\";
			\$gdl_publisher['address']  = \"$frm[address]\";
			\$gdl_publisher['city']  = \"$frm[city]\";
			\$gdl_publisher['region']  = \"$frm[region]\";
			\$gdl_publisher['country']  = \"$frm[country]\";
			\$gdl_publisher['phone']  = \"$frm[phone]\";
			\$gdl_publisher['fax']  = \"$frm[fax]\";
			\$gdl_publisher['admin']  = \"$frm[admin]\";
			\$gdl_publisher['cko']  = \"$frm[cko]\";
			\$gdl_publisher['network']  = \"$frm[network]\";
			\$gdl_publisher['hubserver']  = \"$frm[hubserver]\";
			?>";
		
			// save to file
			$fp = fopen("config/publisher.php","w");
			$result=fputs($fp,$pub_conf);
			fclose($fp);
			
			return $result;
	}
	
	function save_configuration2($frm) {
		global $gdl_sys;
		$type=array("INSTITUTION","PERSONAL","WARNET");
		$contype=array("DEDICATED","TEMPORARY");
		
		$type=strtoupper($type[$frm["type"]]);
		$contype=strtoupper($contype[$frm["contype"]]);
		$pub_conf = "<?
			# Automatically generated on ".date("Y-m-d H:i:s")."
		
			\$gdl_publisher['id']  = \"$frm[ID]\";
			\$gdl_publisher['serialno']  = \"$frm[serialnumber]\";
			\$gdl_publisher['type']  = \"$type\";
			\$gdl_publisher['connection']  = \"$contype\";
			\$gdl_publisher['apps']  = \"".$gdl_sys['apps']."\";		
			\$gdl_publisher['publisher']  = \"$frm[name]\";
			\$gdl_publisher['orgname']  = \"$frm[orgname]\";
			\$gdl_publisher['hostname']  = \"$frm[hostname]\";
			\$gdl_publisher['ipaddress']  = \"$frm[ipserver]\";
			\$gdl_publisher['contact']   = \"$frm[contact]\";
			\$gdl_publisher['address']  = \"$frm[address]\";
			\$gdl_publisher['city']  = \"$frm[city]\";
			\$gdl_publisher['region']  = \"$frm[region]\";
			\$gdl_publisher['country']  = \"$frm[country]\";
			\$gdl_publisher['phone']  = \"$frm[phone]\";
			\$gdl_publisher['fax']  = \"$frm[fax]\";
			\$gdl_publisher['admin']  = \"$frm[adminemail]\";
			\$gdl_publisher['cko']  = \"$frm[ckoemail]\";
			\$gdl_publisher['network']  = \"$frm[network]\";
			\$gdl_publisher['hubserver']  = \"$frm[hubserver]\";
			?>";
		
			// save to file
			$fp = fopen("config/publisher.php","w");
			$result=fputs($fp,$pub_conf);
			fclose($fp);
			
			return $result;
	}

}		
?>