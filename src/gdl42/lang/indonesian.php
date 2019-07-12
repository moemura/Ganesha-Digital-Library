<?php

if (eregi("indonesian.php",$_SERVER['PHP_SELF'])) {
    die();
}

// modul name
define("_BOOKMARK","Bookmark");
define("_CONTACTUS","Kontak");
define("_EXIM","Ekspor/Impor");
define("_EDITPROFILE","Edit Profil");
define("_EXPLORER","Explorer");
define("_FAQ","Frequently Asked Question");
define("_INDEXING","Update Index");
define("_MIGRATION","Migrasi");
define("_MIGRATION40TO42", "Migration 4.0 ke 4.2");
define("_UPLOAD","Upload / Edit");
define("_SYNCHRONIZATION","Sinkronisasi");
define("_SEARCH","Pencarian");
define("_USERMANAGEMENT","User");
define("_UPLOAD","Upload Metadata");
define("_WORKGROUP","Workgroup");
define("_USER","Pengguna");
if ($gdl_session->authority == "*")
	define("_MEMBER","Anggota");
else
	define("_MEMBER","Ubah Profil");
define("_ACCESSLOG","Konfigurasi Pencatatan Akses");
define("_PUBLISHER","Publisher");
define("_REGISTRATION", "Registerasi");
define("_ACTIVATE", "Aktivasi");
define("_PUBLISHER","Publisher");
define("_CONFIGURATION","Konfigurasi");
define("_ORGANIZATION","Organisasi");
define("_CDSISIS","Database CDS/ISIS");
define("_USERREQUEST","Permintaan Pengguna");
define("_INSTALLATION","Instalasi");
define("_INSTALLATIONPAGE","Jika Anda melihat halaman ini berarti GDL 4.2 belum diinstal. Klik <a href='./gdl.php?mod=install'>sini</a> untuk menjalani proses instalasi");
define("_DISCUSSION","Diskusi / Komentar");
define("_MIGRATION4042","Migrasi GDL 4.0 ke 4.2");
// general language
define("_CANCEL","Batal");
define("_DELETE","Hapus");
define("_ERROR","Error");
define("_ENGLISH","English");
define("_EXCLAMATION","Peringatan !");
define("_GUEST","Pengunjung");
define("_INDONESIAN","Indonesian");
define("_INFORMATION","Informasi");
define("_LANGUAGE","Bahasa");
define("_LOGIN","Login");
define("_LOGOUT","Keluar");
define("_MAINMENU","Menu");
define("_OK","OK");
define("_OPERATIONNOTEXIST","Operasi ini tidak ada ...");
define("_PRINTTHISPAGE","Print ...");
define("_SCHEMANOTAVAILABLE","Schema metadata tidak tersedia");
define("_YOUHAVENOTAUTHORITY","Anda tidak mempunyai hak akses");
define("_YOUCANACCESSDIRECLY","Anda tidak dapat meng-akses langsung");
define("_YOUARE","Anda");
define("_WELCOMETOTHE","Selamat datang di");
define("_WELCOME","Selamat datang");
define("_YES","Ya");
define("_NO","Tidak");
define("_NOT","Tidak");
define("_DATE","Tanggal");

define ("_SIGNINAS","Login sebagai ");
define ("_LINKS", "Links");
define("_PARTNERSHIP","Partner");
define("_CREDIT","Kredit");

define("_LIVECD","LIVE CD");
define("_LIVECDVERSIONCOMEFROM", "Versi liveCD dari koleksi perpustakaan ");
define("_ADDRESS", "Alamat: ");
define("_MOREINFO", "Info lebih lanjut");
?>
