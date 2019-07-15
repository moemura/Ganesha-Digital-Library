<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

define("_SEARCHDISCUSSION","Pencarian komentar dengan id user, judul dan subjek");
define("_USERID","Id User");
define("_SUBJECT","Subjek");
define("_COMMENTDISPLAYING","Menampilkan komentar");
define("_COMMENTS","Komentar");
define("_NO","No.");
define("_OF","Dari");
define("_PAGE","Halaman");
define("_CONFIRMATION","Konfirmasi Penghapusan");
define("_DELETECOMMENTCONFIRMATION","Apakah Anda yakin ingin menghapus komentar ini ? ");
define("_DELETEYES","Ya");
define("_DELETECOMMENT","Penghapusan Komentar");
define("_SUCCESS","Penghapusan Berhasil");
define("_NOTFOUND","Tidak ditemukan id komentar ");
define("_OPTION","Pilihan");
?>