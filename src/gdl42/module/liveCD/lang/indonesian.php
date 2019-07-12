<?php

if (eregi("indonesian.php",$_SERVER['PHP_SELF'])) {
    die();
}

define("_FOLDERCHOICE","Pilih Folder");
define("_JOBVIEW","Lihat Pekerjaan");
define("_EXPORTFILE","File Live CD");
define("_NOMOR","Nomor");
define("_FOLDERNAME","Nama Folder");
define("_FOLDERCOUNT","Jumlah Data");
define("_FOLDERACTION","Masukkan Daftar");
define("_JOBFOLDER","Tambah");
define("_FOLDERNODE","Node Folder");
define("_JOBACTION","Eksekusi");
define("_JOBRESET","HapusSemua");
define("_JOBREMOVE","HapusNode");
define("_CONNINFO","Entitas");
define("_CONNVALUE","Nilai");
define("_FILENAME","Nama File");
define("_FILESIZE","Ukuran");
define("_ACTION","Aksi");
define("_DELETE","Hapus");
define("_TITLESTARTLIVECD","Konfirmasi pembuatan Live CD");
define("_STARTBUILDLIVECD","EksekusiPembuatanLiveCD");
define("_LIVECDINCLUDEFILE","Apakah file relasi disertakan ?");
define("_LIVECDINCLUDEFOLKSONOMY","Apakah folksonomy disertakan ?");

define("_LIVECDWARNINGCOMMENT","Live CD !!! Link komentar tidak diaktifkan.");
define("_LIVECDWARNINGREADCOMMENT","Live CD !!! Link baca komentar tidak diaktifkan.");
define("_LIVECDWARNINGBOOKMARK","Live CD !!! Link bookmark tidak diaktifkan.");

define("_WELCOMELIVECD","Selamat datang.");
define("_MODULEINFOLIVECD","Fitur ini akan membuat koleksi digital library menjadi koleksi versi live CD yang bisa dijalankan tanpa memerlukan server maupun database.");


define("_THEMECOLLECTION","Theme yang mendukung Live CD : ");
define("_CHANGETHEME","Ganti Theme");
define("_THEMESTATUS","Theme yang akan digunakan adalah");
define("_THEMESTATUSCON","dengan status");
define("_THEMESUPPORTLIVECD","mendukung Live CD");
define("_THEMENOTSUPPORTLIVECD","tidak mendukung Live CD");
define("_THEMENOTE","Apabila theme yang anda gunakan sekarang tidak mendukung Live CD, maka proses pembuatan Live CD akan dibatalkan.");
define("_LIVECDSTEP","Langkah-langkah untuk membuat Live CD");
define("_LIVECDSTEP1","Pilih theme yang mendukung Live CD.");
define("_LIVECDSTEP2","Pilih folder yang ingin anda buat Live CD pada menu Pilih Folder.");
define("_LIVECDSTEP3","Tekan tombol Eksekusi untuk melihat informasi pembuatan Live CD.");
define("_LIVECDSTEP4","Pastikan bahwa informasi yang ditampilkan sudah valid.");
define("_LIVECDSTEP5","Pilih opsi apakah file relasi yang berkaitan dengan metadata akan disertakan pada Live CD atau tidak.");
define("_LIVECDSTEP6","Apabila telah yakin, maka tekan tombol eksekusipembuatanlivecd.");
define("_LIVECDSTEP7","Apabila proses selesai maka akan ditampilkan daftar file Live CD yang pernah dibuat.");
define("_LIVECDSTEP8","Lakukan download file untuk mengambil file atau hapus untuk menghapus file Live CD yang pernah dibuat.");
define("_LIVECDSTEP9","Apabila anda ekstrak, secara default letak file live CD ada pada <b>files/tmp/liveCD</b>.");
define("_LIVECDSTEP10","Pindahkan seluruh file dan folder yang berada pada <b>files/tmp/liveCD (hasil ekstraksi)</b> ke CD(Compact Disc), flashdisk, dll.");


define("_LISTFOLDER","Daftar Folder Digital Library");
define("_LISTJOBFOLDER","Daftar Folder Untuk Koleksi Live CD");
define("_LISTLIVECDFILE","Daftar File Live CD");
define("_CONFIRMATIONJOB","Informasi Pre-Eksekusi Pembuatan Live CD");
?>
