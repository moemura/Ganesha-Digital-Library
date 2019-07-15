<?php

if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

define("_FOLDER","Folder");
define("_GDL40DATABASECONFIG","Konfigurasi Database GDL 4.0");
define("_METADATA","Metadata");
define("_MIGRATIONNOTE","Untuk merubah konfigurasi diatas, edit file ./module/migration/conf.php");
define("_FILE","File");
define("_USER","User");
define("_PUBLISHER","Publisher");
define("_MIGRATIONSTEPS","Langkah-langkah untuk Migrasi");
define("_CONFIGURATION","Konfigurasi");
define("_MIGRATIONCONF","Konfigurasi Migrasi");
define("_HOST","Nama");
define("_USERNAME","Nama User");
define("_PASSWORD","Password");
define("_DBNAME","Nama Database");
define("_EDIT","Ubah");
define("_SYSTEMCONFSAVE","Konfigurasi tersimpan");
define("_TRYCONNECT","Mencoba tersambung ke sumber data");
define("_PLEASEWAIT","Mohon tunggu... ");
define("_LOCK","Database telah terkunci untuk migrasi, anda dapat melanjutkan dengan menghapus file ");
define("_NOWLOCK","Database sekarang dikunci untuk mencegah migrasi di kemudian hari, anda dapat melakukan migrasi ulang dengan menghapus file ");	
define("_RELATION","Relasi");
define("_SELECTFILE","Harap pindahkan file - file yang berhubungan dengan metadata dari GDL 4.0  ke folder <b>./files</b> pada GDL 4.2, harap diingat struktur folder harus sama seperti sumbernya");
define("_FILESORFOLDER","Folder / File");
define("_SIZE","Ukuran");
define("_LOCKFILE","Harap diingat bahwa anda HARUS menonaktifkan fitur migrasi file ini setelah anda mengkopi semua file relasi yang dibutuhkan oleh metadata, klik <a href='./gdl.php?mod=migration&amp;op=files&amp;lock=yes'>sini</a> untuk menonaktifkannya");
define("_ACCESSLOG2","Log Akses");
?>