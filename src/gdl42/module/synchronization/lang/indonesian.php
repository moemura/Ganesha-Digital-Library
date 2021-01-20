<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_SEARCHREPOSITORY') or define("_SEARCHREPOSITORY","Pencarian");
defined('_REPOSITORYSEARCH') or define("_REPOSITORYSEARCH","Cari");
	
defined('_FAILEDCONNECTION') or define("_FAILEDCONNECTION","Gagal Koneksi");
defined('_CONFIGURATION') or define("_CONFIGURATION","Konfigurasi");
defined('_HARVESTING') or define("_HARVESTING","Panen Data");
defined('_CONNECTION') or define("_CONNECTION","Koneksi");
defined('_DISCONNECTION') or define("_DISCONNECTION","Diskoneksi");
defined('_SAVECHANGES') or define("_SAVECHANGES","Eksekusi");
defined('_TARGETSERVERNAME') or define("_TARGETSERVERNAME","Nama Server Tujuan");
defined('_USEPROXY') or define("_USEPROXY","Menggunakan Proxy");
defined('_PROXYADDRESS') or define("_PROXYADDRESS","Alamat Proxy");
defined('_OAISCRIPT') or define("_OAISCRIPT","Skrip OAI");
defined('_NUMOFRECORD') or define("_NUMOFRECORD","Jumlah record tiap posting atau tiap rekues harvesting");
defined('_FRAGMENTSIZE') or define("_FRAGMENTSIZE","Besar fragmen untuk pengiriman file (dalam bytes)");
defined('_SERVERRESPONSEDETAIL') or define("_SERVERRESPONSEDETAIL","Detil respon server (dalam xml)");
defined('_HARVESTALLRECORDSUNDERNODEID') or define("_HARVESTALLRECORDSUNDERNODEID","Harvest semua record di bawah id node (kosong atau \"0\" berarti semua record pada hub server akan diharvest) ");
defined('_OPTIONSAVE') or define("_OPTIONSAVE","Konfigurasi telah berhasil disimpan..");
defined('_OPTIONSAVEFAILED') or define("_OPTIONSAVEFAILED","Konfigurasi gagal disimpan..");
defined('_SYNCHRINDEX') or define("_SYNCHRINDEX","<p>Ada dua cara untuk melakukan sinkronisasi data, yaitu:</p>
<ol>
   <li> Export Metadata ke dalam file terkompresi, lalu kirim ke administrator hub  server menggunakan CD-ROM atau floppy disk. Dan  sebaliknya, dapatkan file hasil export metadata lalu import ke server anda.</li>
   <li> Sinkronisasi secara online. Pengiriman dan pengambilan metadata terhadap server target dapat dilakukan secara online jika server anda terhubung  ke internet.</li>
</ol>
<p>Catatan: Pastikan anda sudah melakukan sinkronisasi data Publisher sebelum mengambil (harvest) metadata dari server target.</p> ");
defined('_SHOW') or define("_SHOW","Tampilkan");
defined('_HIDE') or define("_HIDE","Sembunyikan");
defined('_CONNECTED') or define("_CONNECTED","Tersambung");
defined('_TOTARGETSERVER') or define("_TOTARGETSERVER","ke server tujuan");
defined('_EXPORT') or define("_EXPORT","Ekspor");
defined('_IMPORT') or define("_IMPORT","Impor");
defined('_SETSTARTINGLASTMODIFIED') or define("_SETSTARTINGLASTMODIFIED","Set tanggal perubahan terakhir Metadata");
defined('_EXPORTFROMSERVER') or define("_EXPORTFROMSERVER","Ekspor Metadata dari server");
defined('_MYGDLSERVER') or define("_MYGDLSERVER","GDL Server");
defined('_ALLSERVER') or define("_ALLSERVER","Semua Server");
defined('_SERVERWITHPUBLISHERID') or define("_SERVERWITHPUBLISHERID","Server dengan ID publisher tertentu");
defined('_STARTINGDATE') or define("_STARTINGDATE","Tanggal mulai (format DD-MM-YYYY)");
defined('_IFEMPTY') or define("_IFEMPTY","Jika kosong, diasumsikan semua metadata akan diekspor.");
defined('_PUBLISHERID') or define("_PUBLISHERID","ID Publisher");
defined('_EXPORTSUCCESS') or define("_EXPORTSUCCESS","Metadata berhasil dikompres dan diarsip");
defined('_EXPORTFAILED') or define("_EXPORTFAILED","Metadata gagal diekspor");
defined('_FILENAME') or define("_FILENAME","Nama File");
defined('_FILESIZE') or define("_FILESIZE","Ukuran File");
defined('_DOWNLOADMETADATA') or define("_DOWNLOADMETADATA","Download Arsip Metadata");
defined('_TODOWNLOAD') or define("_TODOWNLOAD","Untuk mendownload file, gunakan <b>klik kanan</b> pada mouse lalu pilih <b>Save Target As</b>.<p>");
defined('_METADATANOTARCHIVED') or define("_METADATANOTARCHIVED","Metadata belum diarsip. Klik menu <b>Ekspor Metadata</b> dan ikuti langkah-langkahnya");
defined('_UPLOADARCHIVEDMETADATA') or define("_UPLOADARCHIVEDMETADATA","Langkah 1 : Upload file arsip Metadata yang terkompres");
defined('_IMPORTUPLOADEDFILE') or define("_IMPORTUPLOADEDFILE","Langkah 2 : Impor file yang telah diupload ke dalam database");
defined('_FROM') or define("_FROM","Dari");
defined('_ACTION') or define("_ACTION","Aksi");
defined('_GZIPUPLOADERROR') or define("_GZIPUPLOADERROR","Upload file gagal, format file harus dalam bentuk gzip (.gz)");
defined('_METADATAUPLOADERROR') or define("_METADATAUPLOADERROR","Upload file gagal, format file harus dalam bentuk 'metadata-PUBLISHERID.gz'");
defined('_UPLOADFILESUCCESS') or define("_UPLOADFILESUCCESS","Upload file berhasil");
defined('_UPLOADFILEERROR') or define("_UPLOADFILEERROR","Upload file gagal karena jaringan komunikasi yang jelek");
defined('_DELETESUCCESS') or define("_DELETESUCCESS"," berhasil dihapus");
defined('_DELETEFAILED') or define("_DELETEFAILED","gagal menghapus ");
defined('_NOTFOUND') or define("_NOTFOUND","tidak ditemukan");
defined('_UPLOADFILESIZEERROR') or define("_UPLOADFILESIZEERROR","Upload file gagal, ukuran file tidak boleh melebihi");
	
defined('_REPOSITORYNUMBER') or define("_REPOSITORYNUMBER","No");
defined('_REPOSITORYNAME') or define("_REPOSITORYNAME","Nama Repository");
defined('_BASEURL') or define("_BASEURL","Host Repository");
defined('_PREFIX') or define("_PREFIX","Prefix");
defined('_REPOSITORYUPDATE') or define("_REPOSITORYUPDATE","Update Repository");
defined('_REPOSITORYACTION') or define("_REPOSITORYACTION","Aksi");
defined('_REPOSITORYADD') or define("_REPOSITORYADD","Repository Baru");
defined('_REPOSITORYFROMPUBLISHER') or define("_REPOSITORYFROMPUBLISHER","Update dari data publisher");
defined('_CURRENTREPOSITORYCONNECTION') or define("_CURRENTREPOSITORYCONNECTION","Repository Sekarang");
defined('_REPOSITORYDISPLAYING') or define("_REPOSITORYDISPLAYING","Daftar Repository");
defined('_OF') or define("_OF","dari");
defined('_PAGE') or define("_PAGE","Halaman");
defined('_REQUESTFORMAT') or define("_REQUESTFORMAT","Format <i>request</i>");
defined('_CURRENTREPOSITORY') or define("_CURRENTREPOSITORY","Repository yang anda gunakan ");
defined('_OPTION_SCRIPT') or define("_OPTION_SCRIPT","Metadata Prefix");
defined('_SETOPTION') or define("_SETOPTION","Selektif <i>Harvesting</i>");
defined('_OPTIONFROM') or define("_OPTIONFROM","Batas Waktu Awal");
defined('_OPTIONUNTIL') or define("_OPTIONUNTIL","Batas Waktu Akhir");
defined('_TYPEACTION') or define("_TYPEACTION","Jenis Aksi");
	
defined('_POSTING') or define("_POSTING","Kirim Data");
defined('_NOFILE') or define("_NOFILE","Nomor");
defined('_POSTFILE') or define("_POSTFILE","Check");
defined('_LASTDATE') or define("_LASTDATE","Modifikasi Terakhir");
defined('_SIZE') or define("_SIZE","Ukuran");
defined('_QUEUENUMBER') or define("_QUEUENUMBER","Nomor");
defined('_QUEUEPATH') or define("_QUEUEPATH","Path");
defined('_QUEUESTATUS') or define("_QUEUESTATUS","Status");
defined('_QUEUEACTION') or define("_QUEUEACTION","Aksi");
defined('_STARTPOSTING') or define("_STARTPOSTING","Posting Data Dijalankan");
defined('_POSTINGFILES') or define("_POSTINGFILES","Eksekusi");
defined('_POSTINGDISPLAYING') or define("_POSTINGDISPLAYING","Daftar antrian ");
	
defined('_OUTBOX_NUMBER') or define("_OUTBOX_NUMBER","No");
defined('_OUTBOX_FOLDER') or define("_OUTBOX_FOLDER","Folder");
defined('_OUTBOX_SUM') or define("_OUTBOX_SUM","Jumlah");
defined('_OUTBOX_ACTION') or define("_OUTBOX_ACTION","Aksi");
	
defined('_BOX_STATUS_POSTING') or define("_BOX_STATUS_POSTING","Daftar status pengiriman file");
defined('_BOX_NUMBER') or define("_BOX_NUMBER","No");
defined('_BOX_PATH') or define("_BOX_PATH","Path file");
defined('_BOX_STATUS') or define("_BOX_STATUS","Status pengiriman");
defined('_BOX_ACTION') or define("_BOX_ACTION","Aksi");
?>