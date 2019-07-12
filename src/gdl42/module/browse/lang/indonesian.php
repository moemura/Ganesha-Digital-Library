<?php

if (eregi("indonesian.php",$_SERVER['PHP_SELF'])) {
    die();
}

define("_COMMENT","Komentar");
define("_DISPLAYINGMETADATA","Menampilkan");
define("_DATE","Tanggal");
define("_DOWNLOAD","Download");
define("_DOWNLOADNOTE","Download hanya untuk member.");
define("_EDITOR","Editor");
define("_EMAIL","E Mail");
define("_ERRORREADMETADATA","Error menampilkan metadata");
define("_FORMOREINFORMATION","Informasi lebih lanjut, hubungi :");
define("_FILES","file");
define("_GIVECOMMENT","Beri Komentar");
define("_LASTNEWS","Artikel Sebelumnya");
define("_LOGINFAIL","Login Tidak Berhasil");
define("_LOGINFAILNOTE","User ID atau Password Anda tidak benar.<br/>Silahkan diulangi lagi ..");
define("_LOGINNOTE","Masukkan User ID dan Password Anda dengan benar ...<br/>Lupa password ? kirim email ke $gdl_publisher[admin]");
define("_LOGINACTIVATE", "Maaf, account anda belum diaktifkan. <br/>Silahkan hubungi administrator ($gdl_publisher[admin]) <br/>atau lakukan aktivasi di sini:
		<a href=\"./gdl.php?mod=register&amp;op=activate\" >Mengaktifkan Account</a>.");
define("_METADATAS","artikel");
define("_METADATAINFOLDER","Artikel dalam folder ");
define("_NEWARTICLES","Artikel Baru");
define("_NAME","Nama");
define("_OF","dari");
define("_PASSWORD","Kata Kunci");
define("_PAGE","Halaman");
define("_READCOMMENT","Baca Komentar");
define("_READARTICLE","Baca Artikel");
define("_SUBFOLDERON","Sub Folder ");
define("_USERLOGIN","Login Pengguna");
define("_USERID","User ID");
define("_SUBJECT","Subjek");
define("_BY","oleh");
define("_PUBLISHERID","ID Publisher");
define("_PUBLISHERNAME","Nama");
define("_PUBLISHERCITY","Kota");
define("_PUBLISHERNETWORK","Jaringan");
define("_PUBLISHERHUBID","ID HUB");
define("_PUBLISHERSERIALNUMBER","Nomor Serial Publisher");
define("_PUBLISHERNETWORK","Jaringan Publisher");
define("_PUBLISHERID","ID Publisher");
define("_PUBLISHERAPP","Kode Aplikasi");
define("_PUBLISHERTYPE","Tipe Publisher");
define("_PUBLISHERNAME","Nama Publisher");
define("_PUBLISHERORGNAME","Nama Organisasi");
define("_PUBLISHERCONTYPE","Tipe Koneksi");
define("_PUBLISHERHOSTNAME","Nama Host / Nama Server");
define("_PUBLISHERIPADDRESS","Alamat IP Server");
define("_PUBLISHERCONTACTNAME","Nama Kontak");
define("_PUBLISHERADDRESS","Alamat");
define("_PUBLISHERCITY","Kota");
define("_PUBLISHERREGION","Daerah");
define("_PUBLISHERCOUNTRY","Negara");
define("_PUBLISHERPHONE","Telepon");
define("_PUBLISHERFAX","Fax");
define("_PUBLISHERADMINEMAIL","E-mail Administrator");
define("_PUBLISHERCKOEMAIL","E-mail CKO");
define("_PUBLISHERPROPERTY","Properti");
define("_PROPERTYVALUE","Nilai Properti");
define("_VERIFICATION","Verifikasi");
define ("_REGISTRATION_ERROR_VERIFICATION", "Kode Verifikasi salah<br/>");

define("_CONFIRMDOWNLOAD","Live CD !!! Apabila tidak berhasil, file berkaitan tidak tersedia di CD ini. Hubungi publisher yang menerbitkan content ini.");
?>