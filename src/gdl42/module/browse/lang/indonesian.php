<?php

if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_COMMENT') or define("_COMMENT","Komentar");
defined('_DISPLAYINGMETADATA') or define("_DISPLAYINGMETADATA","Menampilkan");
defined('_DATE') or define("_DATE","Tanggal");
defined('_DOWNLOAD') or define("_DOWNLOAD","Download");
defined('_DOWNLOADNOTE') or define("_DOWNLOADNOTE","Download hanya untuk member.");
defined('_EDITOR') or define("_EDITOR","Editor");
defined('_EMAIL') or define("_EMAIL","E Mail");
defined('_ERRORREADMETADATA') or define("_ERRORREADMETADATA","Error menampilkan metadata");
defined('_FORMOREINFORMATION') or define("_FORMOREINFORMATION","Informasi lebih lanjut, hubungi :");
defined('_FILES') or define("_FILES","file");
defined('_GIVECOMMENT') or define("_GIVECOMMENT","Beri Komentar");
defined('_LASTNEWS') or define("_LASTNEWS","Artikel Sebelumnya");
defined('_LOGINFAIL') or define("_LOGINFAIL","Login Tidak Berhasil");
defined('_LOGINFAILNOTE') or define("_LOGINFAILNOTE","User ID atau Password Anda tidak benar.<br/>Silahkan diulangi lagi ..");
defined('_LOGINNOTE') or define("_LOGINNOTE","Masukkan User ID dan Password Anda dengan benar ...<br/>Lupa password ? kirim email ke Administrator");
defined('_LOGINACTIVATE') or define("_LOGINACTIVATE", "Maaf, account anda belum diaktifkan. <br/>Silahkan hubungi administrator <br/>atau lakukan aktivasi di sini:
		<a href=\"./gdl.php?mod=register&amp;op=activate\" >Mengaktifkan Account</a>.");
defined('_METADATAS') or define("_METADATAS","artikel");
defined('_METADATAINFOLDER') or define("_METADATAINFOLDER","Artikel dalam folder ");
defined('_NEWARTICLES') or define("_NEWARTICLES","Artikel Baru");
defined('_NAME') or define("_NAME","Nama");
defined('_OF') or define("_OF","dari");
defined('_PASSWORD') or define("_PASSWORD","Kata Kunci");
defined('_PAGE') or define("_PAGE","Halaman");
defined('_READCOMMENT') or define("_READCOMMENT","Baca Komentar");
defined('_READARTICLE') or define("_READARTICLE","Baca Artikel");
defined('_SUBFOLDERON') or define("_SUBFOLDERON","Sub Folder ");
defined('_USERLOGIN') or define("_USERLOGIN","Login Pengguna");
defined('_USERID') or define("_USERID","User ID");
defined('_SUBJECT') or define("_SUBJECT","Subjek");
defined('_BY') or define("_BY","oleh");
defined('_PUBLISHERID') or define("_PUBLISHERID","ID Publisher");
defined('_PUBLISHERNAME') or define("_PUBLISHERNAME","Nama");
defined('_PUBLISHERCITY') or define("_PUBLISHERCITY","Kota");
defined('_PUBLISHERNETWORK') or define("_PUBLISHERNETWORK","Jaringan");
defined('_PUBLISHERHUBID') or define("_PUBLISHERHUBID","ID HUB");
defined('_PUBLISHERSERIALNUMBER') or define("_PUBLISHERSERIALNUMBER","Nomor Serial Publisher");
defined('_PUBLISHERAPP') or define("_PUBLISHERAPP","Kode Aplikasi");
defined('_PUBLISHERTYPE') or define("_PUBLISHERTYPE","Tipe Publisher");
defined('_PUBLISHERORGNAME') or define("_PUBLISHERORGNAME","Nama Organisasi");
defined('_PUBLISHERCONTYPE') or define("_PUBLISHERCONTYPE","Tipe Koneksi");
defined('_PUBLISHERHOSTNAME') or define("_PUBLISHERHOSTNAME","Nama Host / Nama Server");
defined('_PUBLISHERIPADDRESS') or define("_PUBLISHERIPADDRESS","Alamat IP Server");
defined('_PUBLISHERCONTACTNAME') or define("_PUBLISHERCONTACTNAME","Nama Kontak");
defined('_PUBLISHERADDRESS') or define("_PUBLISHERADDRESS","Alamat");
defined('_PUBLISHERREGION') or define("_PUBLISHERREGION","Daerah");
defined('_PUBLISHERCOUNTRY') or define("_PUBLISHERCOUNTRY","Negara");
defined('_PUBLISHERPHONE') or define("_PUBLISHERPHONE","Telepon");
defined('_PUBLISHERFAX') or define("_PUBLISHERFAX","Fax");
defined('_PUBLISHERADMINEMAIL') or define("_PUBLISHERADMINEMAIL","E-mail Administrator");
defined('_PUBLISHERCKOEMAIL') or define("_PUBLISHERCKOEMAIL","E-mail CKO");
defined('_PUBLISHERPROPERTY') or define("_PUBLISHERPROPERTY","Properti");
defined('_PROPERTYVALUE') or define("_PROPERTYVALUE","Nilai Properti");
defined('_VERIFICATION') or define("_VERIFICATION","Verifikasi");
defined('_REGISTRATION_ERROR_VERIFICATION') or define ("_REGISTRATION_ERROR_VERIFICATION", "Kode Verifikasi salah<br/>");

defined('_CONFIRMDOWNLOAD') or define("_CONFIRMDOWNLOAD","Live CD !!! Apabila tidak berhasil, file berkaitan tidak tersedia di CD ini. Hubungi publisher yang menerbitkan content ini.");
?>