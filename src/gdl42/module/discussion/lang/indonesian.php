<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_SEARCHDISCUSSION') or define("_SEARCHDISCUSSION","Pencarian komentar dengan id user, judul dan subjek");
defined('_USERID') or define("_USERID","Id User");
defined('_SUBJECT') or define("_SUBJECT","Subjek");
defined('_COMMENTDISPLAYING') or define("_COMMENTDISPLAYING","Menampilkan komentar");
defined('_COMMENTS') or define("_COMMENTS","Komentar");
defined('_NO') or define("_NO","No.");
defined('_OF') or define("_OF","Dari");
defined('_PAGE') or define("_PAGE","Halaman");
defined('_CONFIRMATION') or define("_CONFIRMATION","Konfirmasi Penghapusan");
defined('_DELETECOMMENTCONFIRMATION') or define("_DELETECOMMENTCONFIRMATION","Apakah Anda yakin ingin menghapus komentar ini ? ");
defined('_DELETEYES') or define("_DELETEYES","Ya");
defined('_DELETECOMMENT') or define("_DELETECOMMENT","Penghapusan Komentar");
defined('_SUCCESS') or define("_SUCCESS","Penghapusan Berhasil");
defined('_NOTFOUND') or define("_NOTFOUND","Tidak ditemukan id komentar ");
defined('_OPTION') or define("_OPTION","Pilihan");
?>