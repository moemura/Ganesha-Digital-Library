<?php
/***************************************************************************
                     /module/accesslog/lang/indonesian.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/


if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_MODULE') or define("_MODULE","Modul");
defined('_OPERATION') or define("_OPERATION","Operasi");
defined('_ACCESSHEADER') or define("_ACCESSHEADER","Di bawah ini adalah modul dan operasi yang dapat diakses di GDL 4.2, berikan centang pada operasi yang ingin dicatat aksesnya");
defined('_ACCESSLOGSUCCESS') or define("_ACCESSLOGSUCCESS","<b>Konfigurasi untuk pencatatan akses berhasil disimpan</b>");
defined('_ACCESSLOGFAILED') or define("_ACCESSLOGFAILED","<b>Konfigurasi untuk pencatatan akses gagal disimpan</b>");
defined('_SUBMIT') or define("_SUBMIT","Simpan");
?>