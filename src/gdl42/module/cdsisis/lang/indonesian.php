<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_NEW') or define("_NEW","Database Baru");
defined('_ACTION') or define("_ACTION","Aksi");
defined('_DBNAME') or define("_DBNAME","Nama Database");
defined('_CANTFINDFOLDER') or define("_CANTFINDFOLDER","Tidak dapat menemukan folder");
defined('_SUCCESSDELETE') or define("_SUCCESSDELETE","Berhasil menghapus database dalam folder");
defined('_CONFIRMATION') or define("_CONFIRMATION","Konfirmasi penghapusan");
defined('_DELETECDSISISCONFIRMATION') or define("_DELETECDSISISCONFIRMATION","Apakah Anda yakin ingin menghapus database CDS/ISIS ini ?");
defined('_DELETEYES') or define("_DELETEYES","Ya, saya yakin");
defined('_DELETECDSISIS') or define("_DELETECDSISIS","Penghapusan database CDS/ISIS");
defined('_FAILEDDELETE') or define("_FAILEDDELETE","Gagal menghapus database dalam folder");
defined('_NEWCDSISIS') or define("_NEWCDSISIS","Penambahan database CDS/ISIS");
defined('_EDITCDSISIS') or define("_EDITCDSISIS","Perubahan database CDS/ISIS");
defined('_DATABASEOWNER') or define("_DATABASEOWNER","Pemilik database");
defined('_ORGANIZATIONNAME') or define("_ORGANIZATIONNAME","Nama organisasi");
defined('_DATABASENAME') or define("_DATABASENAME","Nama database");
defined('_LIBRARIANEMAIL') or define("_LIBRARIANEMAIL","E-mail pustakawan");
defined('_FILES') or define("_FILES","File database");
defined('_SUBMIT') or define("_SUBMIT","Kirim");
defined('_FAILEDCREATEFOLDER') or define("_FAILEDCREATEFOLDER","Gagal menciptakan folder");
defined('_CREATEFOLDERSUCCESS') or define("_CREATEFOLDERSUCCESS","Berhasil menciptakan folder");
defined('_UPLOADFILEFAILED') or define("_UPLOADFILEFAILED","Gagal upload file");
defined('_UPLOADFILESUCCESS') or define("_UPLOADFILESUCCESS","Berhasil upload file");
defined('_EDIT') or define("_EDIT","Ubah");
defined('_FILENAME') or define("_FILENAME","Nama file");
defined('_SIZE') or define("_SIZE","Ukuran");
defined('_DATEMODIFIED') or define("_DATEMODIFIED","Tanggal Perubahan");
defined('_ADDCDSISISSUCCESS') or define("_ADDCDSISISSUCCESS","Penambahan database CDS/ISIS berhasil");
defined('_ADDCDSISISFAILED') or define("_ADDCDSISISFAILED","Penambahan database CDS/ISIS gagal");
defined('_EDITCDSISISSUCCESS') or define("_EDITCDSISISSUCCESS","Perubahan database CDS/ISIS berhasil");
defined('_EDITCDSISISFAILED') or define("_EDITCDSISISFAILED","Perubahan database CDS/ISIS gagal");
defined('_CONFIGURE') or define("_CONFIGURE","Konfigurasi");
defined('_TEST') or define("_TEST","Tes");
defined('_BUILDINDEX') or define("_BUILDINDEX","Bangun Indeks");
defined('_BUILDUNIONINDEX') or define("_BUILDUNIONINDEX","Bangun Indeks gabungan");
defined('_CONFIGURECDSISIS') or define("_CONFIGURECDSISIS","Konfigurasi database CDS/ISIS");
defined('_FAILEDCDSISIS') or define("_FAILEDCDSISIS","Gagal membaca rekord pertama database CDS/ISIS, tolong cek semua file databasenya. Semua kebutuhan file database harus dipenuhi");
defined('_READSUCCESS') or define("_READSUCCESS","<p><b>Selamat!</b> Kami dapat membaca database. 
				Sekarang, kami akan melihat rekord pertama dari database.
				Tolong isi field yang dibutuhkan pada label yang bersesuaian.
				Anda dapat mengisi kosong, satu atau lebih field ke label tersebut.
				</p>");
defined('_NEXTRECORD') or define("_NEXTRECORD","Rekord Selanjutnya");
defined('_PERIOD') or define("_PERIOD","Periode");
defined('_LOCATION') or define("_LOCATION","Lokasi");
defined('_CLASSIFICATION') or define("_CLASSIFICATION","Klasifikasi");
defined('_CALLNUMBER') or define("_CALLNUMBER","Nomor Hubung");
defined('_DDCEDITION') or define("_DDCEDITION","Edisi DDC");
defined('_LOCALCLASSIFICATION') or define("_LOCALCLASSIFICATION","Klasifikasi Lokal");
defined('_AUTHOR') or define("_AUTHOR","Penerbit");
defined('_AUTHORCORPORATE') or define("_AUTHORCORPORATE","Perusahaan Penerbit");
defined('_CONFERENCE') or define("_CONFERENCE","Konferensi");
defined('_TITLEOFJOURNAL') or define("_TITLEOFJOURNAL","Judul Jurnal");
defined('_TITLE') or define("_TITLE","Judul");
defined('_ALTERNATIVETITLE') or define("_ALTERNATIVETITLE","Judul Alternatif");
defined('_DESCRIPTION') or define("_DESCRIPTION","Deskripsi");
defined('_EDITION') or define("_EDITION","Edisi");
defined('_PLACEOFPUBLISHER') or define("_PLACEOFPUBLISHER","Tempat Publisher");
defined('_DIMENTION') or define("_DIMENTION","Dimensi");
defined('_ILLUSTRATION') or define("_ILLUSTRATION","Ilustrasi");
defined('_HEIGHT') or define("_HEIGHT","Height");
defined('_SERIES') or define("_SERIES","Seri");
defined('_NOTE') or define("_NOTE","Catatan");
defined('_BIBLIOGRAPHY') or define("_BIBLIOGRAPHY","Bibliografi");
defined('_SUMMARY') or define("_SUMMARY","Kesimpulan atau Kutipan");
defined('_SUBJECT') or define("_SUBJECT","Subyek");
defined('_COAUTHOR') or define("_COAUTHOR","Co-Author dan Editor");
defined('_COAUTHORCORPORATE') or define("_COAUTHORCORPORATE","Perusahaan Co-Author");
defined('_IDENTIFICATION') or define("_IDENTIFICATION","Identifikasi");
defined('_SAVE') or define("_SAVE","Simpan");
defined('_CONFIGURATIONSAVEDSUCCESS') or define("_CONFIGURATIONSAVEDSUCCESS","Konfigurasi database berhasil disimpan");
defined('_CONFIGURATIONSAVEDFAILED') or define("_CONFIGURATIONSAVEDFAILED","Gagal untuk menyimpan konfigurasi");
defined('_SELECTLABEL') or define("_SELECTLABEL","Pilih Label");
defined('_CDSISISTEST') or define("_CDSISISTEST","Tes konfigurasi");
defined('_TESTRECORD') or define("_TESTRECORD","Setiap rekord akan diekspor dari database CDS/ISIS dalam format XML untuk dilakukan indexing oleh SWISH-E seperti contoh berikut<hr>");
defined('_FOLLOW') or define("_FOLLOW","Ikuti langkah berikut untuk membuat indeks dari database");
defined('_EXPORTDATABASE') or define("_EXPORTDATABASE","Ekspor database ke file temporer");
defined('_BUILDINDEX') or define("_BUILDINDEX","Bangun index");
defined('_RECORDEXPORTED') or define("_RECORDEXPORTED","rekord telah diekspor ke file temporer");
defined('_SWISHENOTEXIST') or define("_SWISHENOTEXIST","File program <b>SWISH-E</b> tidak ditemukan");
defined('_NOIDXFILEFOUND') or define("_NOIDXFILEFOUND","Tidak ditemukan file indeks CDS/ISIS di GDL Server");
defined('_FOLLOWINGIDX') or define("_FOLLOWINGIDX","File indeks CDS/ISIS berikut akan disatukan untuk membangun satu indeks gabungan katalog");
defined('_STARTMERGING') or define("_STARTMERGING","Mulai menyatukan indeks");
defined('_EXPORTINGINPROGRESS') or define("_EXPORTINGINPROGRESS","Ekspor database CDS/ISIS masih dalam proses...");
defined('_EXPORTINGFINISHED') or define("_EXPORTINGFINISHED","Ekspor database CDS/ISIS telah selesai");
defined('_BUILDFINALUNIONINDEX') or define("_BUILDFINALUNIONINDEX","Bangun Indeks Final");
defined('_FINALUNIONDESCRIPTION') or define("_FINALUNIONDESCRIPTION","<p>Proses ini akan membangun indeks gabungan final dari indeks metadata (<b>gdl42.idx</b>) dan indeks gabungan CDS/ISIS (<b>all_isis.idx</b>). Indeks final ini (<b>all.idx</b>) dibutuhkan oleh mesin pencarian dalam melakukan query");
defined('_INDEXCDSISIS') or define("_INDEXCDSISIS","Nyalakan CDS/ISIS Indexing");
?>