<?php

if (eregi("indonesian.php",$_SERVER['PHP_SELF'])) {
    die();
}

define("_MAININSTALL","Selamat datang di halaman instalasi Ganesha Digital Library (GDL) 4.2, sekarang
					   kami akan melakukan konfigurasi untuk membuat GDL 4.2 berjalan dengan baik pada server anda.
					   Anda harus mengikuti instruksi yang diberikan pada setiap langkah instalasi.");
define("_CHECKFILEPERMS","Cek hak akses file dan folder");
define("_DIRECTORYPERMISSION","Anda harus merubah hak akses untuk file dan direktori di bawah ini sehingga kami dapat mengakses dan menulis di file dan direktori yang dibutuhkan GDL 4.2 tersebut");
define("_DATABASECONF","Konfigurasi Database");
define("_DATABASEEXPL","Sekarang, kami akan mengkonfigurasi koneksi database, pastikan Anda dapat mengakses server MySql dan memiliki akses untuk membuat database dan table-nya");
define("_DBNAME","Nama Database");
define("_TABLEPREFIX","Prefiks Table");
define("_SAVE","Simpan");
define("_SUCCESSWRITE","Berhasil menyimpan file konfigurasi database ");
define("_SUCCESSWRITE","Gagal menyimpan file konfigurasi database ");
define("_CONFIGURATIONNAME","Nama Konfigurasi");
define("_VALUE","Nilai");
define("_TABLECONF","Konfigurasi Table");
define("_DATABASECONNECTIONERROR","Terdapat kesalahan saat mencoba tersambung ke server MySql. Pastikan Anda mengisi konfigurasi koneksi dengan benar");
define("_DATABASECONNECTIONSUCCESS","Tersambung ke server MySql");
define("_CREATETABLE","Sekarang kami akan membuat table (dan) databasenya di server MySql, di bawah ini adalah nama table yang dibutuhkan GDL 4.2");
define("_TABLENAME","Nama Table");
define("_CREATEDATABASE","Apakah Anda ingin membuat database baru ?");
define("_CREATE","Buat");
define("_CHOICE","Pilihan");
define("_CREATEDBSUCCESS","Berhasil membuat database");
define("_CREATEDBFAILED","Gagal membuat database");
define("_SELECTDBSUCCESS","Berhasil mengakses database");
define("_SELECTDBFAILED","Gagal mengakses database");
define("_CREATETABLESUCCESS","Berhasil membuat table");
define("_CREATETABLEFAILED","Gagal membuat table");
define("_FILLDATA","Pengisian data");
define("_FILLDATAMAIN","Sekarang kami akan memasukkan data awal ke server GDL Anda, beberapa data berasal dari masukan Anda pada form di bawah ini");
define("_ADMINISTRATORINFORMATION","Informasi Login Administrator");
define ("_CANNOTOPENFILE","Tidak bisa membuka file ");
define ("_USER_EMAIL", "Email");
define ("_USER_PASSWD", "Password");
define ("_USER_PASSWD_CONFIRM", "Confirm Password");
define ("_USER_GENERAL", "Informasi Umum");
define ("_USER_MAIL", "E&ndash;mail");
define ("_USER_FULLNAME", "Nama Lengkap");
define ("_USER_ADDRESS", "Alamat");
define ("_USER_CITY", "Kota");
define ("_USER_COUNTRY","Negara");
define ("_USER_INSTITUTION","Institusi");
define ("_USER_ACCOUNT","Account");
define ("_USER_CODE","Kode");
define ("_TYPEOFUSER", "Pekerjaan");
define ("_REGISTRATION", "Registrasi");
define ("_SUBMIT", "Submit");
define ("_RESET", "Reset");
define("_PUBLISHERID","ID Publisher");
define("_PUBLISHERNAME","Nama");
define("_PUBLISHERCITY","Kota");
define("_PUBLISHERNETWORK","Jaringan");
define("_PUBLISHERHUBID","ID HUB");
define("_PUBLISHERACTION","Aksi");
define("_PUBLISHEREDIT","Ubah");
define("_PUBLISHERDELETE","Hapus");
define("_PUBLISHERADD","Tambah");
define("_PUBLISHERDISPLAYING","Daftar Publisher");
define("_PUBLISHERSEARCH","Cari");
define("_PUBLISHERMANAGEMENT","Manajemen Publisher");
define("_PUBLISHERADDNEW","Tambah Publisher Baru");
define("_PUBLISHEREDITING","Perubahan Publisher");
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
define("_PUBLISHERHUBSERVER","Server HUB");
define("_SUCCESSADDLOGIN","Administrator login berhasil dimasukkan");
define("_FAILEDADDLOGIN","Administrator login gagal dimasukkan");
define("_SUCCESSSAVECONFIGURATION","Konfigurasi server (Publisher) berhasil disimpan di ");
define("_SUCCESSINSERTPUBLISHER","Informasi Publisher berhasil dimasukkan ke database");
define("_SUCCESSINSERTGROUPUSER","Informasi kelompok user berhasil dimasukkan ke database");
define("_FAILEDINSERTGROUPUSER","Information kelompok user gagal dimasukkan ke database");
define("_SUCCESSWRITEINSTALLLCK","File pengunci (<b>./files/misc/install.lck</b>) berhasil ditulis");
define("_FAILEDWRITEINSTALLLCK","File pengunci (<b>./files/misc/install.lck</b>) gagal ditulis, instalasi gagal, mohon instal kembali GDL");
define("_ALREADYINSTALLED","File pengunci (<b>./files/misc/install.lck</b>) ditemukan, tidak dapat mengakses modul ini");
define("_FINISHED","Selamat, Instalasi <b>Ganesha Digital Library 4.2 (GDL 4.2) </b> pada server Anda telah selesai, silahkan gunakan menu untuk mengakses GDL Anda");
define("_CANWRITE","dapat ditulis");
define("_CANTWRITE","tidak dapat ditulis, harap ganti hak aksesnya");
define ("_REGISTRATION_ERROR_EMAIL", "Email Anda Salah<br/>");
define ("_REGISTRATION_ERROR_PASSWORD", " Password dan Confirm Password berbeda<br/>");
define ("_REGISTRATION_ERROR_EMAIL_EXIST", "Account sudah ada yang punya<br/>");
define ("_REGISTRATION_ERROR_VERIFICATION", "Kode Verifikasi salah<br/>");
?>