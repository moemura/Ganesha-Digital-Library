<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_FOLDER') or define("_FOLDER","Folder");
defined('_GDL40DATABASECONFIG') or define("_GDL40DATABASECONFIG","Konfigurasi Database GDL 4.0");
defined('_METADATA') or define("_METADATA","Metadata");
defined('_MIGRATIONNOTE') or define("_MIGRATIONNOTE","Untuk merubah konfigurasi diatas, edit file ./module/migration/conf.php");
defined('_FILE') or define("_FILE","File");
defined('_USER') or define("_USER","User");
defined('_PUBLISHER') or define("_PUBLISHER","Publisher");
defined('_MIGRATIONSTEPS') or define("_MIGRATIONSTEPS","Langkah-langkah untuk Migrasi");
defined('_CONFIGURATION') or define("_CONFIGURATION","Konfigurasi");
defined('_MIGRATIONCONF') or define("_MIGRATIONCONF","Konfigurasi Migrasi");
defined('_HOST') or define("_HOST","Nama");
defined('_USERNAME') or define("_USERNAME","Nama User");
defined('_PASSWORD') or define("_PASSWORD","Password");
defined('_DBNAME') or define("_DBNAME","Nama Database");
defined('_EDIT') or define("_EDIT","Ubah");
defined('_SYSTEMCONFSAVE') or define("_SYSTEMCONFSAVE","Konfigurasi tersimpan");
defined('_TRYCONNECT') or define("_TRYCONNECT","Mencoba tersambung ke sumber data");
defined('_PLEASEWAIT') or define("_PLEASEWAIT","Mohon tunggu... ");
defined('_LOCK') or define("_LOCK","Database telah terkunci untuk migrasi, anda dapat melanjutkan dengan menghapus file ");
defined('_NOWLOCK') or define("_NOWLOCK","Database sekarang dikunci untuk mencegah migrasi di kemudian hari, anda dapat melakukan migrasi ulang dengan menghapus file ");	
defined('_RELATION') or define("_RELATION","Relasi");
defined('_SELECTFILE') or define("_SELECTFILE","Harap pindahkan file - file yang berhubungan dengan metadata dari GDL 4.0  ke folder <b>./files</b> pada GDL 4.2, harap diingat struktur folder harus sama seperti sumbernya");
defined('_FILESORFOLDER') or define("_FILESORFOLDER","Folder / File");
defined('_SIZE') or define("_SIZE","Ukuran");
defined('_LOCKFILE') or define("_LOCKFILE","Harap diingat bahwa anda HARUS menonaktifkan fitur migrasi file ini setelah anda mengkopi semua file relasi yang dibutuhkan oleh metadata, klik <a href='./gdl.php?mod=migration&amp;op=files&amp;lock=yes'>sini</a> untuk menonaktifkannya");
defined('_ACCESSLOG2') or define("_ACCESSLOG2","Log Akses");
?>