<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_FOLDERCHOICE') or define("_FOLDERCHOICE","Pilih Folder");
defined('_JOBVIEW') or define("_JOBVIEW","Lihat Pekerjaan");
defined('_EXPORTFILE') or define("_EXPORTFILE","File Live CD");
defined('_NOMOR') or define("_NOMOR","Nomor");
defined('_FOLDERNAME') or define("_FOLDERNAME","Nama Folder");
defined('_FOLDERCOUNT') or define("_FOLDERCOUNT","Jumlah Data");
defined('_FOLDERACTION') or define("_FOLDERACTION","Masukkan Daftar");
defined('_JOBFOLDER') or define("_JOBFOLDER","Tambah");
defined('_FOLDERNODE') or define("_FOLDERNODE","Node Folder");
defined('_JOBACTION') or define("_JOBACTION","Eksekusi");
defined('_JOBRESET') or define("_JOBRESET","HapusSemua");
defined('_JOBREMOVE') or define("_JOBREMOVE","HapusNode");
defined('_CONNINFO') or define("_CONNINFO","Entitas");
defined('_CONNVALUE') or define("_CONNVALUE","Nilai");
defined('_FILENAME') or define("_FILENAME","Nama File");
defined('_FILESIZE') or define("_FILESIZE","Ukuran");
defined('_ACTION') or define("_ACTION","Aksi");
defined('_DELETE') or define("_DELETE","Hapus");
defined('_TITLESTARTLIVECD') or define("_TITLESTARTLIVECD","Konfirmasi pembuatan Live CD");
defined('_STARTBUILDLIVECD') or define("_STARTBUILDLIVECD","EksekusiPembuatanLiveCD");
defined('_LIVECDINCLUDEFILE') or define("_LIVECDINCLUDEFILE","Apakah file relasi disertakan ?");
defined('_LIVECDINCLUDEFOLKSONOMY') or define("_LIVECDINCLUDEFOLKSONOMY","Apakah folksonomy disertakan ?");

defined('_LIVECDWARNINGCOMMENT') or define("_LIVECDWARNINGCOMMENT","Live CD !!! Link komentar tidak diaktifkan.");
defined('_LIVECDWARNINGREADCOMMENT') or define("_LIVECDWARNINGREADCOMMENT","Live CD !!! Link baca komentar tidak diaktifkan.");
defined('_LIVECDWARNINGBOOKMARK') or define("_LIVECDWARNINGBOOKMARK","Live CD !!! Link bookmark tidak diaktifkan.");

defined('_WELCOMELIVECD') or define("_WELCOMELIVECD","Selamat datang.");
defined('_MODULEINFOLIVECD') or define("_MODULEINFOLIVECD","Fitur ini akan membuat koleksi digital library menjadi koleksi versi live CD yang bisa dijalankan tanpa memerlukan server maupun database.");


defined('_THEMECOLLECTION') or define("_THEMECOLLECTION","Theme yang mendukung Live CD : ");
defined('_CHANGETHEME') or define("_CHANGETHEME","Ganti Theme");
defined('_THEMESTATUS') or define("_THEMESTATUS","Theme yang akan digunakan adalah");
defined('_THEMESTATUSCON') or define("_THEMESTATUSCON","dengan status");
defined('_THEMESUPPORTLIVECD') or define("_THEMESUPPORTLIVECD","mendukung Live CD");
defined('_THEMENOTSUPPORTLIVECD') or define("_THEMENOTSUPPORTLIVECD","tidak mendukung Live CD");
defined('_THEMENOTE') or define("_THEMENOTE","Apabila theme yang anda gunakan sekarang tidak mendukung Live CD, maka proses pembuatan Live CD akan dibatalkan.");
defined('_LIVECDSTEP') or define("_LIVECDSTEP","Langkah-langkah untuk membuat Live CD");
defined('_LIVECDSTEP1') or define("_LIVECDSTEP1","Pilih theme yang mendukung Live CD.");
defined('_LIVECDSTEP2') or define("_LIVECDSTEP2","Pilih folder yang ingin anda buat Live CD pada menu Pilih Folder.");
defined('_LIVECDSTEP3') or define("_LIVECDSTEP3","Tekan tombol Eksekusi untuk melihat informasi pembuatan Live CD.");
defined('_LIVECDSTEP4') or define("_LIVECDSTEP4","Pastikan bahwa informasi yang ditampilkan sudah valid.");
defined('_LIVECDSTEP5') or define("_LIVECDSTEP5","Pilih opsi apakah file relasi yang berkaitan dengan metadata akan disertakan pada Live CD atau tidak.");
defined('_LIVECDSTEP6') or define("_LIVECDSTEP6","Apabila telah yakin, maka tekan tombol eksekusipembuatanlivecd.");
defined('_LIVECDSTEP7') or define("_LIVECDSTEP7","Apabila proses selesai maka akan ditampilkan daftar file Live CD yang pernah dibuat.");
defined('_LIVECDSTEP8') or define("_LIVECDSTEP8","Lakukan download file untuk mengambil file atau hapus untuk menghapus file Live CD yang pernah dibuat.");
defined('_LIVECDSTEP9') or define("_LIVECDSTEP9","Apabila anda ekstrak, secara default letak file live CD ada pada <b>files/tmp/liveCD</b>.");
defined('_LIVECDSTEP10') or define("_LIVECDSTEP10","Pindahkan seluruh file dan folder yang berada pada <b>files/tmp/liveCD (hasil ekstraksi)</b> ke CD(Compact Disc), flashdisk, dll.");


defined('_LISTFOLDER') or define("_LISTFOLDER","Daftar Folder Digital Library");
defined('_LISTJOBFOLDER') or define("_LISTJOBFOLDER","Daftar Folder Untuk Koleksi Live CD");
defined('_LISTLIVECDFILE') or define("_LISTLIVECDFILE","Daftar File Live CD");
defined('_CONFIRMATIONJOB') or define("_CONFIRMATIONJOB","Informasi Pre-Eksekusi Pembuatan Live CD");
?>