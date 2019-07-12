<?php
if (eregi("indonesian.php",$_SERVER['PHP_SELF'])) {
    die();
}


	define("_SEARCHREPOSITORY","Pencarian");
	define("_REPOSITORYSEARCH","Cari");
	
	define("_FAILEDCONNECTION","Gagal Koneksi");
	define("_CONFIGURATION","Konfigurasi");
	define("_HARVESTING","Panen Data");
	define("_CONNECTION","Koneksi");
	define("_DISCONNECTION","Diskoneksi");
	define("_SAVECHANGES","Eksekusi");
	define("_TARGETSERVERNAME","Nama Server Tujuan");
	define("_USEPROXY","Menggunakan Proxy");
	define("_PROXYADDRESS","Alamat Proxy");
	define("_OAISCRIPT","Skrip OAI");
	define("_NUMOFRECORD","Jumlah record tiap posting atau tiap rekues harvesting");
	define("_FRAGMENTSIZE","Besar fragmen untuk pengiriman file (dalam bytes)");
	define("_SERVERRESPONSEDETAIL","Detil respon server (dalam xml)");
	define("_HARVESTALLRECORDSUNDERNODEID","Harvest semua record di bawah id node (kosong atau \"0\" berarti semua record pada hub server akan diharvest) ");
	define("_OPTIONSAVE","Konfigurasi telah berhasil disimpan..");
	define("_OPTIONSAVEFAILED","Konfigurasi gagal disimpan..");
	define("_SYNCHRINDEX","<p>Ada dua cara untuk melakukan sinkronisasi data, yaitu:</p>
<ol>
   <li> Export Metadata ke dalam file terkompresi, lalu kirim ke administrator hub  server menggunakan CD-ROM atau floppy disk. Dan  sebaliknya, dapatkan file hasil export metadata lalu import ke server anda.</li>
   <li> Sinkronisasi secara online. Pengiriman dan pengambilan metadata terhadap server target dapat dilakukan secara online jika server anda terhubung  ke internet.</li>
</ol>
<p>Catatan: Pastikan anda sudah melakukan sinkronisasi data Publisher sebelum mengambil (harvest) metadata dari server target.</p> ");
	define("_SHOW","Tampilkan");
	define("_HIDE","Sembunyikan");
	define("_CONNECTED","Tersambung");
	define("_TOTARGETSERVER","ke server tujuan");
	define("_EXPORT","Ekspor");
	define("_IMPORT","Impor");
	define("_SETSTARTINGLASTMODIFIED","Set tanggal perubahan terakhir Metadata");
	define("_EXPORTFROMSERVER","Ekspor Metadata dari server");
	define("_MYGDLSERVER","GDL Server");
	define("_ALLSERVER","Semua Server");
	define("_SERVERWITHPUBLISHERID","Server dengan ID publisher tertentu");
	define("_STARTINGDATE","Tanggal mulai (format DD-MM-YYYY)");
	define("_IFEMPTY","Jika kosong, diasumsikan semua metadata akan diekspor.");
	define("_PUBLISHERID","ID Publisher");
	define("_EXPORTSUCCESS","Metadata berhasil dikompres dan diarsip");
	define("_EXPORTFAILED","Metadata gagal diekspor");
	define("_FILENAME","Nama File");
	define("_FILESIZE","Ukuran File");
	define("_DOWNLOADMETADATA","Download Arsip Metadata");
	define("_TODOWNLOAD","Untuk mendownload file, gunakan <b>klik kanan</b> pada mouse lalu pilih <b>Save Target As</b>.<p>");
	define("_METADATANOTARCHIVED","Metadata belum diarsip. Klik menu <b>Ekspor Metadata</b> dan ikuti langkah-langkahnya");
	define("_UPLOADARCHIVEDMETADATA","Langkah 1 : Upload file arsip Metadata yang terkompres");
	define("_IMPORTUPLOADEDFILE","Langkah 2 : Impor file yang telah diupload ke dalam database");
	define("_FROM","Dari");
	define("_ACTION","Aksi");
	define("_GZIPUPLOADERROR","Upload file gagal, format file harus dalam bentuk gzip (.gz)");
	define("_METADATAUPLOADERROR","Upload file gagal, format file harus dalam bentuk 'metadata-PUBLISHERID.gz'");
	define("_UPLOADFILESUCCESS","Upload file berhasil");
	define("_UPLOADFILEERROR","Upload file gagal karena jaringan komunikasi yang jelek");
	define("_DELETESUCCESS"," berhasil dihapus");
	define("_DELETEFAILED","gagal menghapus ");
	define("_NOTFOUND","tidak ditemukan");
	define("_UPLOADFILESIZEERROR","Upload file gagal, ukuran file tidak boleh melebihi");
	
	define("_REPOSITORYNUMBER","No");
	define("_REPOSITORYNAME","Nama Repository");
	define("_BASEURL","Host Repository");
	define("_PREFIX","Prefix");
	define("_REPOSITORYUPDATE","Update Repository");
	define("_REPOSITORYACTION","Aksi");
	define("_REPOSITORYADD","Repository Baru");
	define("_REPOSITORYFROMPUBLISHER","Update dari data publisher");
	define("_CURRENTREPOSITORYCONNECTION","Repository Sekarang");
	define("_REPOSITORYDISPLAYING","Daftar Repository");
	define("_OF","dari");
	define("_PAGE","Halaman");
	define("_REQUESTFORMAT","Format <i>request</i>");
	define("_CURRENTREPOSITORY","Repository yang anda gunakan ");
	define("_OPTION_SCRIPT","Metadata Prefix");
	define("_SETOPTION","Selektif <i>Harvesting</i>");
	define("_OPTIONFROM","Batas Waktu Awal");
	define("_OPTIONUNTIL","Batas Waktu Akhir");
	define("_TYPEACTION","Jenis Aksi");
	
	define("_POSTING","Kirim Data");
	define("_NOFILE","Nomor");
	define("_POSTFILE","Check");
	define("_LASTDATE","Modifikasi Terakhir");
	define("_SIZE","Ukuran");
	define("_QUEUENUMBER","Nomor");
	define("_QUEUEPATH","Path");
	define("_QUEUESTATUS","Status");
	define("_QUEUEACTION","Aksi");
	define("_STARTPOSTING","Posting Data Dijalankan");
	define("_POSTINGFILES","Eksekusi");
	define("_POSTINGDISPLAYING","Daftar antrian ");
	
	define("_OUTBOX_NUMBER","No");
	define("_OUTBOX_FOLDER","Folder");
	define("_OUTBOX_SUM","Jumlah");
	define("_OUTBOX_ACTION","Aksi");
	
	define("_BOX_STATUS_POSTING","Daftar status pengiriman file");
	define("_BOX_NUMBER","No");
	define("_BOX_PATH","Path file");
	define("_BOX_STATUS","Status pengiriman");
	define("_BOX_ACTION","Aksi");
?>