<?php
if (eregi("indonesian.php",$_SERVER['PHP_SELF'])) {
    die();
}

define("_NEW","Database Baru");
define("_ACTION","Aksi");
define("_DBNAME","Nama Database");
define("_CANTFINDFOLDER","Tidak dapat menemukan folder");
define("_SUCCESSDELETE","Berhasil menghapus database dalam folder");
define("_CONFIRMATION","Konfirmasi penghapusan");
define("_DELETECDSISISCONFIRMATION","Apakah Anda yakin ingin menghapus database CDS/ISIS ini ?");
define("_DELETEYES","Ya, saya yakin");
define("_DELETECDSISIS","Penghapusan database CDS/ISIS");
define("_FAILEDDELETE","Gagal menghapus database dalam folder");
define("_NEWCDSISIS","Penambahan database CDS/ISIS");
define("_EDITCDSISIS","Perubahan database CDS/ISIS");
define("_DATABASEOWNER","Pemilik database");
define("_ORGANIZATIONNAME","Nama organisasi");
define("_DATABASENAME","Nama database");
define("_LIBRARIANEMAIL","E-mail pustakawan");
define("_FILES","File database");
define("_SUBMIT","Kirim");
define("_FAILEDCREATEFOLDER","Gagal menciptakan folder");
define("_CREATEFOLDERSUCCESS","Berhasil menciptakan folder");
define("_UPLOADFILEFAILED","Gagal upload file");
define("_UPLOADFILESUCCESS","Berhasil upload file");
define("_EDIT","Ubah");
define("_FILENAME","Nama file");
define("_SIZE","Ukuran");
define("_DATEMODIFIED","Tanggal Perubahan");
define("_ADDCDSISISSUCCESS","Penambahan database CDS/ISIS berhasil");
define("_ADDCDSISISFAILED","Penambahan database CDS/ISIS gagal");
define("_EDITCDSISISSUCCESS","Perubahan database CDS/ISIS berhasil");
define("_EDITCDSISISFAILED","Perubahan database CDS/ISIS gagal");
define("_CONFIGURE","Konfigurasi");
define("_TEST","Tes");
define("_BUILDINDEX","Bangun Indeks");
define("_BUILDUNIONINDEX","Bangun Indeks gabungan");
define("_CONFIGURECDSISIS","Konfigurasi database CDS/ISIS");
define("_FAILEDCDSISIS","Gagal membaca rekord pertama database CDS/ISIS, tolong cek semua file databasenya. Semua kebutuhan file database harus dipenuhi");
define("_READSUCCESS","<p><b>Selamat!</b> Kami dapat membaca database. 
				Sekarang, kami akan melihat rekord pertama dari database.
				Tolong isi field yang dibutuhkan pada label yang bersesuaian.
				Anda dapat mengisi kosong, satu atau lebih field ke label tersebut.
				</p>");
define("_NEXTRECORD","Rekord Selanjutnya");
define("_PERIOD","Periode");
define("_LOCATION","Lokasi");
define("_CLASSIFICATION","Klasifikasi");
define("_CALLNUMBER","Nomor Hubung");
define("_DDCEDITION","Edisi DDC");
define("_LOCALCLASSIFICATION","Klasifikasi Lokal");
define("_AUTHOR","Penerbit");
define("_AUTHORCORPORATE","Perusahaan Penerbit");
define("_CONFERENCE","Konferensi");
define("_TITLEOFJOURNAL","Judul Jurnal");
define("_TITLE","Judul");
define("_ALTERNATIVETITLE","Judul Alternatif");
define("_DESCRIPTION","Deskripsi");
define("_EDITION","Edisi");
define("_PLACEOFPUBLISHER","Tempat Publisher");
define("_DIMENTION","Dimensi");
define("_ILLUSTRATION","Ilustrasi");
define("_HEIGHT","Height");
define("_SERIES","Seri");
define("_NOTE","Catatan");
define("_BIBLIOGRAPHY","Bibliografi");
define("_SUMMARY","Kesimpulan atau Kutipan");
define("_SUBJECT","Subyek");
define("_COAUTHOR","Co-Author dan Editor");
define("_COAUTHORCORPORATE","Perusahaan Co-Author");
define("_IDENTIFICATION","Identifikasi");
define("_SAVE","Simpan");
define("_CONFIGURATIONSAVEDSUCCESS","Konfigurasi database berhasil disimpan");
define("_CONFIGURATIONSAVEDFAILED","Gagal untuk menyimpan konfigurasi");
define("_SELECTLABEL","Pilih Label");
define("_CDSISISTEST","Tes konfigurasi");
define("_TESTRECORD","Setiap rekord akan diekspor dari database CDS/ISIS dalam format XML untuk dilakukan indexing oleh SWISH-E seperti contoh berikut<hr>");
define("_FOLLOW","Ikuti langkah berikut untuk membuat indeks dari database");
define("_EXPORTDATABASE","Ekspor database ke file temporer");
define("_BUILDINDEX","Bangun index");
define("_RECORDEXPORTED","rekord telah diekspor ke file temporer");
define("_SWISHENOTEXIST","File program <b>SWISH-E</b> tidak ditemukan");
define("_NOIDXFILEFOUND","Tidak ditemukan file indeks CDS/ISIS di GDL Server");
define("_FOLLOWINGIDX","File indeks CDS/ISIS berikut akan disatukan untuk membangun satu indeks gabungan katalog");
define("_STARTMERGING","Mulai menyatukan indeks");
define("_EXPORTINGINPROGRESS","Ekspor database CDS/ISIS masih dalam proses...");
define("_EXPORTINGFINISHED","Ekspor database CDS/ISIS telah selesai");
define("_BUILDFINALUNIONINDEX","Bangun Indeks Final");
define("_FINALUNIONDESCRIPTION","<p>Proses ini akan membangun indeks gabungan final dari indeks metadata (<b>gdl42.idx</b>) dan indeks gabungan CDS/ISIS (<b>all_isis.idx</b>). Indeks final ini (<b>all.idx</b>) dibutuhkan oleh mesin pencarian dalam melakukan query");
define("_INDEXCDSISIS","Nyalakan CDS/ISIS Indexing");
?>