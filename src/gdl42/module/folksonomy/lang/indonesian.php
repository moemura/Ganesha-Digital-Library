<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_FOLKSONOMY') or define("_FOLKSONOMY","Folksonomy");
defined('_GARBAGE') or define("_GARBAGE","Filter Kata");
defined('_UPDATE') or define("_UPDATE","Update");
defined('_AKTIVE_FOLKSONOMY_OPTION') or define("_AKTIVE_FOLKSONOMY_OPTION","Aktifkan Folksonomy");
defined('_MIN_FREKUENSI') or define("_MIN_FREKUENSI","Minimum frekuensi");
defined('_TOKEN_PER_ABJAD') or define("_TOKEN_PER_ABJAD","Token tiap abjad");
defined('_MAX_FONT_SIZE') or define("_MAX_FONT_SIZE","Tinggi maksimum token");
defined('_MIN_FONT_SIZE') or define("_MIN_FONT_SIZE","Tinggi minimum token");
defined('_BG_COLOR') or define("_BG_COLOR","Warna latar belakang");
defined('_FONT_COLOR') or define("_FONT_COLOR","Warna Dasar Token");
defined('_SAVECHANGES') or define("_SAVECHANGES","Simpan");
defined('_OPTIONSAVE') or define("_OPTIONSAVE","Data Berhasil disimpan");
defined('_OPTIONSAVEFAILED') or define("_OPTIONSAVEFAILED","Data Berhasil gagal disimpan");
defined('_INS_STOPWORD') or define("_INS_STOPWORD","Masukan kata baru untuk penyaringan");
defined('_ADD_STOPWORD') or define("_ADD_STOPWORD","Tambahkan");
defined('_STOPWORDDISPLAYING') or define("_STOPWORDDISPLAYING","Daftar kata untuk penyaringan ");
defined('_OF') or define("_OF","dari");
defined('_PAGE') or define("_PAGE","Halaman");
defined('_STOPWORDDELETE') or define("_STOPWORDDELETE","Hapus");
defined('_NO_TOKEN') or define("_NO_TOKEN","No");
defined('_TOKEN') or define("_TOKEN","Kata");
defined('_STOPWORD_ACTION') or define("_STOPWORD_ACTION","Aksi");
defined('_FOLKSONOMYISPLAYING') or define("_FOLKSONOMYISPLAYING","Daftar kata");
defined('_FOLKSONOMYWORD') or define("_FOLKSONOMYWORD","kata untuk folksonomy");
defined('_FREKUENSI') or define("_FREKUENSI","Frekuensi");
defined('_FOLKSONOMY_ACTION') or define("_FOLKSONOMY_ACTION","Aksi");
defined('_RESET_FOLKSONOMY') or define("_RESET_FOLKSONOMY","Hapus Folksonomy");
defined('_UPDATE_FOLKSONOMY') or define("_UPDATE_FOLKSONOMY","Susun Folksonomy");
defined('_CLEAN_STOPWORD') or define("_CLEAN_STOPWORD","Penyaringan Kata");
defined('_STOPWORDMANAGEMENT') or define("_STOPWORDMANAGEMENT","Manajemen Penyaringan Kata");
defined('_FOLKSONOMYMANAGEMENT') or define("_FOLKSONOMYMANAGEMENT","Manajemen Penyusunan Folksonomy");
defined('_TOKENDELETE') or define("_TOKENDELETE","Hapus");
defined('_NUMFETCHRECORD') or define("_NUMFETCHRECORD","Limit Update Record");
defined('_STARTDATE') or define("_STARTDATE","Batas Tanggal Awal");
defined('_SHOW_RECORDS') or define("_SHOW_RECORDS","Record Per Halaman");
defined('_SUCCESS_INSERT_STOPWORD') or define("_SUCCESS_INSERT_STOPWORD","Berhasil menambahkan kata sampah");
defined('_FAILED_INSERT_STOPWORD') or define("_FAILED_INSERT_STOPWORD","Gagal menambahkan kata sampah");
defined('_DUPLICATE_STOPWORD') or define("_DUPLICATE_STOPWORD","Kata sampah terduplikasi");
?>