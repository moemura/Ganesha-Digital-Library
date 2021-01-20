<?php

if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_ADVANCESEARCH') or define("_ADVANCESEARCH","Pencarian Metadata");
defined('_ALLMETADATA') or define("_ALLMETADATA","Semua Artikel");
defined('_CREATOR') or define("_CREATOR","Pencipta / Author");
defined('_DOCUMENT') or define("_DOCUMENT","Dokumen");
defined('_IMAGE') or define("_IMAGE","Image");
defined('_KEYWORD') or define("_KEYWORD","Kata Kunci");
defined('_MULTIMEDIA') or define("_MULTIMEDIA","Multimedia");
defined('_METADATATYPE') or define("_METADATATYPE","Tipe");
defined('_ORGANIZATION') or define("_ORGANIZATION","Organisasi");
defined('_PEOPLE') or define("_PEOPLE","Profesional");
defined('_SEARCHRESULTFOR') or define("_SEARCHRESULTFOR","Hasil pencarian untuk ");
defined('_SEARCHALL') or define("_SEARCHALL","Semua");
defined('_SEARCHNOTFOUND') or define("_SEARCHNOTFOUND","Pencarian tidak ditemukan");
defined('_CATALOGS') or define("_CATALOGS","Katalog");
defined('_AUTHOR') or define("_AUTHOR","Penulis");
defined('_YEAR') or define("_YEAR","Tahun");
defined('_PUBLISHER') or define("_PUBLISHER","Publisher");
defined('_ISBN') or define("_ISBN","ISBN");
defined('_SUBJECTHEADING') or define("_SUBJECTHEADING","Subyek");
defined('_CLASSIFICATION') or define("_CLASSIFICATION","Klasifikasi");
defined('_CALLNUMBER') or define("_CALLNUMBER","Nomor Panggil");
defined('_ABSTRACT') or define("_ABSTRACT","Abstraksi");
defined('_FULLNAME') or define("_FULLNAME","Nama Lengkap");
defined('_ADDRESS') or define("_ADDRESS","Alamat");
defined('_INTEREST') or define("_INTEREST","Minat");
defined('_EXPERTISE') or define("_EXPERTISE","Keahlian");
defined('_EXPERIENCE') or define("_EXPERIENCE","Pengalaman");
defined('_ALL') or define("_ALL","All");
defined('_NAME') or define("_NAME","Name");
defined('_PERIOD') or define("_PERIOD","Periode");
defined('_LOCATION') or define("_LOCATION","Lokasi");
defined('_DDCEDITION') or define("_DDCEDITION","Edisi DDC");
defined('_LOCALCLASSIFICATION') or define("_LOCALCLASSIFICATION","Klasifikasi Lokal");
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
defined('_TYPE') or define("_TYPE","Tipe");
?>