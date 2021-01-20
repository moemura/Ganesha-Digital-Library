<?php

if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

// modul name
defined('_BOOKMARK') or define("_BOOKMARK","Bookmark");
defined('_CONTACTUS') or define("_CONTACTUS","Kontak");
defined('_EXIM') or define("_EXIM","Ekspor/Impor");
defined('_EDITPROFILE') or define("_EDITPROFILE","Edit Profil");
defined('_EXPLORER') or define("_EXPLORER","Explorer");
defined('_FAQ') or define("_FAQ","Frequently Asked Question");
defined('_INDEXING') or define("_INDEXING","Update Index");
defined('_MIGRATION') or define("_MIGRATION","Migrasi");
defined('_MIGRATION40TO42') or define("_MIGRATION40TO42", "Migration 4.0 ke 4.2");
defined('_UPLOAD') or define("_UPLOAD","Upload / Edit");
defined('_SYNCHRONIZATION') or define("_SYNCHRONIZATION","Sinkronisasi");
defined('_SEARCH') or define("_SEARCH","Pencarian");
defined('_USERMANAGEMENT') or define("_USERMANAGEMENT","User");
defined('_UPLOAD') or define("_UPLOAD","Upload Metadata");
defined('_WORKGROUP') or define("_WORKGROUP","Workgroup");
defined('_USER') or define("_USER","Pengguna");
if ($gdl_session->authority == "*")
	defined('_MEMBER') or define("_MEMBER","Anggota");
else
	defined('_MEMBER') or define("_MEMBER","Ubah Profil");
defined('_ACCESSLOG') or define("_ACCESSLOG","Konfigurasi Pencatatan Akses");
defined('_PUBLISHER') or define("_PUBLISHER","Publisher");
defined('_REGISTRATION') or define("_REGISTRATION", "Registerasi");
defined('_ACTIVATE') or define("_ACTIVATE", "Aktivasi");
defined('_PUBLISHER') or define("_PUBLISHER","Publisher");
defined('_CONFIGURATION') or define("_CONFIGURATION","Konfigurasi");
defined('_ORGANIZATION') or define("_ORGANIZATION","Organisasi");
defined('_CDSISIS') or define("_CDSISIS","Database CDS/ISIS");
defined('_USERREQUEST') or define("_USERREQUEST","Permintaan Pengguna");
defined('_INSTALLATION') or define("_INSTALLATION","Instalasi");
defined('_INSTALLATIONPAGE') or define("_INSTALLATIONPAGE","Jika Anda melihat halaman ini berarti GDL 4.2 belum diinstal. Klik <a href='./gdl.php?mod=install'>sini</a> untuk menjalani proses instalasi");
defined('_DISCUSSION') or define("_DISCUSSION","Diskusi / Komentar");
defined('_MIGRATION4042') or define("_MIGRATION4042","Migrasi GDL 4.0 ke 4.2");
// general language
defined('_CANCEL') or define("_CANCEL","Batal");
defined('_DELETE') or define("_DELETE","Hapus");
defined('_ERROR') or define("_ERROR","Error");
defined('_ENGLISH') or define("_ENGLISH","English");
defined('_EXCLAMATION') or define("_EXCLAMATION","Peringatan !");
defined('_GUEST') or define("_GUEST","Pengunjung");
defined('_INDONESIAN') or define("_INDONESIAN","Indonesian");
defined('_INFORMATION') or define("_INFORMATION","Informasi");
defined('_LANGUAGE') or define("_LANGUAGE","Bahasa");
defined('_LOGIN') or define("_LOGIN","Login");
defined('_LOGOUT') or define("_LOGOUT","Keluar");
defined('_MAINMENU') or define("_MAINMENU","Menu");
defined('_OK') or define("_OK","OK");
defined('_OPERATIONNOTEXIST') or define("_OPERATIONNOTEXIST","Operasi ini tidak ada ...");
defined('_PRINTTHISPAGE') or define("_PRINTTHISPAGE","Print ...");
defined('_SCHEMANOTAVAILABLE') or define("_SCHEMANOTAVAILABLE","Schema metadata tidak tersedia");
defined('_YOUHAVENOTAUTHORITY') or define("_YOUHAVENOTAUTHORITY","Anda tidak mempunyai hak akses");
defined('_YOUCANACCESSDIRECLY') or define("_YOUCANACCESSDIRECLY","Anda tidak dapat meng-akses langsung");
defined('_YOUARE') or define("_YOUARE","Anda");
defined('_WELCOMETOTHE') or define("_WELCOMETOTHE","Selamat datang di");
defined('_WELCOME') or define("_WELCOME","Selamat datang");
defined('_YES') or define("_YES","Ya");
defined('_NO') or define("_NO","Tidak");
defined('_NOT') or define("_NOT","Tidak");
defined('_DATE') or define("_DATE","Tanggal");

defined('_SIGNINAS') or define ("_SIGNINAS","Login sebagai ");
defined('_LINKS') or define ("_LINKS", "Links");
defined('_PARTNERSHIP') or define("_PARTNERSHIP","Partner");
defined('_CREDIT') or define("_CREDIT","Kredit");

defined('_LIVECD') or define("_LIVECD","LIVE CD");
defined('_LIVECDVERSIONCOMEFROM') or define("_LIVECDVERSIONCOMEFROM", "Versi liveCD dari koleksi perpustakaan ");
defined('_ADDRESS') or define("_ADDRESS", "Alamat: ");
defined('_MOREINFO') or define("_MOREINFO", "Info lebih lanjut");
?>
