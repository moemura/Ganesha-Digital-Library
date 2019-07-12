<?php

/***************************************************************************
                        /module/accesslog/lang/english.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/

if (eregi("english.php",$_SERVER['PHP_SELF'])) {
    die();
}

define("_MODULE","Module");
define("_OPERATION","Operation");
define("_ACCESSHEADER","Below is the modules and operations that can be accessed in GDL 4.2, please check operations that you wants to log the access");
define("_ACCESSLOGSUCCESS","<b>Configuration for access log successfully saved</b>");
define("_ACCESSLOGFAILED","<b>Configuration for access log failed to save</b>");
define("_SUBMIT","Save");
?>