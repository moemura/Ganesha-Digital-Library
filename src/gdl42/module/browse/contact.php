<?

/***************************************************************************
                         /module/browse/contact.php
                             -------------------
    copyright            : (C) 2007 Beni Rio Hermanto, KMRG ITB
    email                : benirio@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/

if (eregi("contact.php",$_SERVER['PHP_SELF'])) die();

$style = "span.title {\n"
		."width: 100px;\n"
		."float: left;\n"
		."}\n";
$gdl_content->set_style( $style);

$main = "<p><span class=\"title\">DL Name</span>: $gdl_publisher[publisher]<br/>\n"
		."<span class=\"title\">PublisherID</span>: $gdl_publisher[id]<br/>\n"
		."<span class=\"title\">Organization</span>: $gdl_publisher[orgname]<br/>\n"
		."<span class=\"title\">Contact</span>: $gdl_publisher[contact]<br/>\n"
		."<span class=\"title\">Address</span>: $gdl_publisher[address]<br/>\n"
		."<span class=\"title\">City</span>: $gdl_publisher[city]<br/>\n"
		."<span class=\"title\">Region</span>: $gdl_publisher[region]<br/>\n"
		."<span class=\"title\">Phone</span>: $gdl_publisher[phone]<br/>\n"
		."<span class=\"title\">Fax</span>: $gdl_publisher[fax]<br/>\n"
		."<span class=\"title\">Admin Email</span>: ".str_replace("@","{at}",$gdl_publisher['admin'])."<br/>\n"
		."<span class=\"title\">CKO Email</span>: ".str_replace("@","{at}",$gdl_publisher['cko'])."<br/>\n"
		."<span class=\"title\">Network</span>: $gdl_publisher[network]<br/>\n"
		."<span class=\"title\">Hub Server</span>: $gdl_publisher[hubserver]</p>\n";

$main = gdl_content_box($main,$gdl_publisher['publisher']);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=browse&amp;op=contact\">"._CONTACTUS."</a>";
?>