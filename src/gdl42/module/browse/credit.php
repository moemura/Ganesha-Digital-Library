<?

/***************************************************************************
                         /module/bookmark/credit.php
                             -------------------
    copyright            : (C) 2007 Beni Rio Hermanto, KMRG ITB
    email                : benirio@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/

if (eregi("credit.php",$_SERVER['PHP_SELF'])) die();

$main = "<p>
GDL or Ganesha Digital Library is a web-based application for a simple digital library. It is easy to use. You can manage your electronic document, images, photos, audio, and video using GDL. Metadata is stored in the database using XML format. Digital collections are stored in a file system format. <br/>(source: http://odur.let.rug.nl/fahmi/#softwares)</p>";


$main .="<p>GDL4.2 is the series of GDL application. The development of GDL4.2 is funded by INHERENT-DIKTI</p>";
$main .= "<p>Personels who in charge in GDL4.2 development process are:</p>";

$main .= "<ul>
				<li>Leader: Beni Rio Hermanto (benirio@kmrg.itb.ac.id)</li>
				<li>System Analyst: Aulia Rahma Amin aulia_ra@yahoo.com)</li>
				<li>Application Design: Febrian Aris Rosadi (katon17@yahoo.com)</li>
				<li>Programmer: Hayun W. Kusumah (hayunks@gmail.com)</li>
				<li>Programmer: Lastiko Wibisono (leonhart_4321@yahoo.com)</li>
				<li>Programmer: Arif Suprabowo (mymails_supra@yahoo.co.uk)</li>
				<li>GUI: Widianto Nugroho (wnugroho@itb.ac.id)</li>
			</ul><br/>\n";
			
$main .= "<p><strong>Powered by:</strong></p>"
		."<p>"
		."<img src=\"./img/apache.jpg\" alt=\"Apache\" />"
		."<img src=\"./img/php-power-white.gif\" alt=\"php\" />"
		."<img src=\"./img/mysql.png\" alt=\"mysql\" />"
		."<img src=\"./img/powered-swish-e.gif\" alt=\"swish-e\" />"
		."<img src=\"./img/captcha_logo_light.jpg\" alt=\"captcha\" />"
		."</p>";
		
		
$main .= "<p><strong>Technology</strong></p>"
		."<p>"
		."<img src=\"./img/valid-xhtml11.png\" alt=\"XHTML1.1\" />"
		."<img src=\"./img/vcss.png\" alt=\"Cassading Style Sheet\" />"
		."<img src=\"./img/valid-rss.png\" alt=\"Really Simple Syndication\" />"
		."</p>";


$main = gdl_content_box($main,_CREDIT);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=browse&amp;op=credit\">Credit</a>";
?>