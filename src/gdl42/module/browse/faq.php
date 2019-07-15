<?php

/***************************************************************************
                         /module/browse/faq.php
                             -------------------
    copyright            : (C) 2007 Beni Rio Hermanto, KMRG ITB
    email                : benirio@kmrg.itb.ac.id
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/
if (preg_match("/faq.php/i",$_SERVER['PHP_SELF'])) die();

$main = "<p>Author: 
			<ul>
				<li>Ismail Fahmi, KMRG ITB, ismail{at}itb.ac.id</li>
				<li>Beni Rio Hermanto, KMRG ITB, benirio{at}kmrg.itb.ac.id (additional)</li>
			</ul></p><br/>\n";
$main .= "<p><b>What is Ganesha Digital Library?</b><br/>\n"
			."Ganesha Digital Library or GDL is a tool for managing and distributing
			digital collection using&nbsp; web-based technology. It is developed by KMRG ITB
			since 2000 and has been widely used for IndonesiaDLN network. Now days, more
			than 40 institutions have joined the network, and around 90 nodes have been
			registered. The number is expected to be increased since the releasing of GDL
			4.0 version. This&nbsp; version supports the Network of Networks (NeONs)
			topology model.</p>
			<p>The last version of GDL is GDL4.2. The development was supported by funding from INHERENT-DIKTI. And now,
			KMRG has to keep it usable and maintainable.&nbsp;</p>";

$main .= "<p><b>Where can I download it?</b><br/>\n"
			."You can download it from:
				KMRG web site: <a href=\"http://kmrg.itb.ac.id\">http://kmrg.itb.ac.id</a>
				(managed by Beni Rio Hermanto), </p>";

$main .= "<p><b>Is it free and how about the warranty?</b><br/>\n
			Yes, it is free. No warranty in using it. You can use it with your own risks.</p>";
$main .= "<p><b>Where can I get support to implement it in my organization?</b><br/>\n
		You can get support from KMRG ITB ( <a href=\"mailto:kmrg@kmrg.itb.ac.id\">kmrg@kmrg.itb.ac.id</a>). This service probably is not free. Well, they also need your contribution to
		keep the maintenance going on the way. Anyway.. just contact them.&nbsp;</p>";
$main .= "<p><b>What implementations can&nbsp; it be used?</b><br/>\n
			GDL can be used for:</p>
			<ul>
			  <li>University's digital library: to organize dan publish ETD (electronic
				theses and dissertations). Of course for scholars' papers, journal, article,
				research reports, etc.</li>
			  <li>Children's digital library: to make a creativity progress of the children
				at their Play Group or Kindergarten, or share the theachers techniques in
				building children creativity and so on. Just make the children's painting,
				handmade, and thecher's articles and writing in digital format and put them
				in the GDL. Share it with others.</li>
			  <li>NGO's digital&nbsp; library: to share news, articles, reports, etc among
				them or to publish publicly.</li>
			  <li>Heritage digital library: to manage and share old artifact, heritage
				informations, archives of our nation's past, and so&nbsp; on.</li>
			  <li>Agriculture digital library: to collect and share appropriate technology,
				commodities and platation techniques, pests and their management techniques,
				products, expert and organization on agriculture, etc.</li>
			  <li>Health digital library: to organize and share information about health,
				medicine, treatment, current trend, etc to medical communities (doctors,
				nurses, students) and to public.</li>
			  <li>Company's archives: to organize and reuse letters, mou, decisions, etc for
				internal use.</li>
			  <li>and so on.</li>
			</ul>";

$main .= "<p><b>Who are the intended users of GDL?</b><br/>\n".
		"Digital library is very broad. It is depended on the community that use it.
		As long as the community has users, GDL can be used for them. It can be used
		directly or you do some modification such&nbsp; as on interface, font, color,
		etc that fulfill your users' need.</p>".
		"<p><b>What kind of resources can be managed by GDL?</b><br/>\n".
		"GDL can manage any type of digital resources, such as:</p>
		<ul>
		  <li>text</li>
		  <li>image</li>
		  <li>audio</li>
		  <li>video</li>
		  <li>software</li>
		</ul>
		<p>Unfortunately GDL doesn't touch the resources. It only receive and store them
		in a file systems, and make link from their associated metadata. GDL will create
		metadata for each resources, and start to work on this metadata - index, search,
		disseminate, displey, and so on.</p>";

$main .= "<p><b>What services are needed to run GDL?</b><br/>\n".
			"You need Apache, PHP version 4 or above, and&nbsp; MySQL. It is very
			important to check your PHP.INI file and modify according to GDL's need. There
			are some unusual setting for PHP.INI that it need.</p>".
		"<p><b>How to access collection in a GDL server?</b><br/>\n".
		"You can access using searching and browsing the collection. The method should
		be intuitive if you familiar enough with Yahoo, Altavista, or other directory
		and search engine.</p>".
		"<p><b>Do I need to register to a GDL server to download the files?</b><br/>\n".
		"Yes, you have to register to DOWNLOAD the files. If you only want to search
		and browse, the login&nbsp; is not required.</p>".
		"<p><b>How to activate my account?</b><br/>\n".
		"After you fill the registration form, an email should be sent to your
		account. But, it happen only if the GDL server using SMTP and activate the email
		sending. Follow the instruction in the email, or input your email and activation
		code at GDL server web page. </p>".
		"<p>If you didn't receive email, you must ask the administrator to activate
		your&nbsp; account. </p>".
		"<p><b>There are so many GDL server. Shall I register to all of the servers?</b><br/>\n".
		"Practically, No. You didn't need to go to other GDL servers as long as your
		GDL server has download/harvest all metadata collections from other servers. You
		can search and&nbsp; browse other GDL collections from your own server. If you
		need to download a file, your server will tell your login session to the server
		that store the file, so you don't have to&nbsp; login again.</p>".
		"<p>But, if you really have to visit other GDL servers, for this time you have to
		register again.</p>".
		"<p><b>What is the different between GDL 4.0 and GDL 4.2?</b><br/>\n".
		"GDL4.2 is developed using standard of application development (analysis, design, implement, testing).
			The code adopt the object oriented concept, so others developers can reuse the classes to develop their application
			with own enviroment. The other things in GDL4.2 is support to change the theme easilly  </p>".
		"<p>The features are most likely same.  
			The main different is that GDL 4.2 try to adopt Web2.0 standard, 
			they are RSS and Folksonomy.</p>";

$main .= "<p><b>How to migrate my existing database from GDL 4.0 to GDL 4.2?</b><br/>\n".
			"It is very easy to answer. Just consult our GDL manual or you can contact KMRG team for assits.</p>";

$main .= "<p><b>What is the meaning of metadata?</b><br/>\n"
	."Metadata is a piece information about a data. It is data about data. It is not the real data that you need. For example, you need a data, in the form of file about medical. The metadata will give you information about its title, author, abstract, and links to that file. So, I hope you get the idea.</p>\n";
$main .= "<p><b>What is the different between metadata and data?</b><br/>\n"
	."It is different. Read above description. But some time metadata = data. For example, if I put my data containing a news into a metadata, so the metadata will become data. Because what I need is there.</p>\n";

$main .= "<p><b>What is the example of data in term of GDL?</b><br/>\n".
			"Data in GDL could be a file of theses document, a file of my picture, a file
			of my voice, or a file of my video when I am bicycling here. In GDL, each
			metadata will refer to one or more files. But sometimes the administrator is too
			lazy so he only put metadata without any files. Don't be disappointed because of
			this. Oke?</p>".
		"<p><b>So, there are two types of object in GDL namely metadata and files?</b><br/>\n".
			"Yes.</p>".
		"<p><b>How GDL manages metadata and its associated files?</b>
			Metadata will be stored and managed in database. While the files will be
			stored in a file system. Metadata contains links to its associated files. So,
			when you read a metadata, you also will be pointed to the files it described.</p>".
		"<p><b>Does GDL support networking among GDL servers?</b><br/>\n".
			"Yes.</p>";




$main = gdl_content_box($main,_FAQ);
$gdl_content->set_main($main);
$gdl_content->path="<a href=\"index.php\">Home</a> $gdl_sys[folder_separator] <a href=\"./gdl.php?mod=browse&amp;op=faq\">F.A.Q.</a>";
?>