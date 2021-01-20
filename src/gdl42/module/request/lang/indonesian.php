<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_SEARCHREQUEST') or define("_SEARCHREQUEST","Pencarian Permintaan dengan ID User");
defined('_NO') or define("_NO","No.");
defined('_TITLE') or define("_TITLE","Identifier / Judul");
defined('_SENT') or define("_SENT","Dikirim");
defined('_COMMENT') or define("_COMMENT","Komentar");
defined('_ACTION') or define("_ACTION","Aksi");
defined('_REQUESTDISPLAYING') or define("_REQUESTDISPLAYING","Menampilkan Permintaan");
defined('_SEARCH') or define("_SEARCH","Cari");
defined('_FROM') or define("_FROM","Dari");
defined('_REQUESTDELETESUCCESS') or define("_REQUESTDELETESUCCESS","Penghapusan permintaan berhasil");
defined('_REQUESTDELETEFAILED') or define("_REQUESTDELETEFAILED","Penghapusan permintaan gagal");
defined('_CANNOTFOUNDREQUESTDATA') or define("_CANNOTFOUNDREQUESTDATA","Tidak dapat menemukan data permintaan");
defined('_AUTHOR') or define("_AUTHOR","Pengarang");
defined('_GIVEYOURMESSAGE') or define("_GIVEYOURMESSAGE","Berikan pesan Anda");
defined('_SEND') or define("_SEND","Kirim");
defined('_INSERTCOMMENTSUCCESS') or define("_INSERTCOMMENTSUCCESS","Pengiriman komentar berhasil");
defined('_INSERTCOMMENTFAILED') or define("_INSERTCOMMENTFAILED","Pengiriman komentar gagal");
defined('_OF') or define("_OF","Dari");
defined('_PAGE') or define("_PAGE","Halaman");
defined('_ADD') or define("_ADD","Tambah");
defined('_EDIT') or define("_EDIT","Ubah");
?>