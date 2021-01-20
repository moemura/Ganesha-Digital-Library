<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_CURRENTMETADATA') or define("_CURRENTMETADATA","Metadata saat ini");
defined('_DESCRIPTION') or define("_DESCRIPTION","Deskripsi");
defined('_EDIT') or define("_EDIT","Ubah");
defined('_EXPERTISE') or define("_EXPERTISE","Profesional, Ahli, Peneliti, dll");
defined('_FOLDER') or define("_FOLDER","Folder");
defined('_FILE') or define("_FILE","File");
defined('_IMAGE') or define("_IMAGE","Image, Photo, Picture, dll");
defined('_MODE') or define("_MODE","Mode Akses");
defined('_NEXT') or define("_NEXT","Selanjutnya");
defined('_NEWMETADATAPROPERTY') or define("_NEWMETADATAPROPERTY","Properti / Profile Metadata Baru");
defined('_MULTIMEDIA') or define("_MULTIMEDIA","Multimedia");
defined('_OWNER') or define("_OWNER","Pemilik");
defined('_OTHERS') or define("_OTHERS","Lain - Lain");
defined('_ORGANIZATION') or define("_ORGANIZATION","Organisasi, Institusi, dll");
defined('_RESET') or define("_RESET","Kosongkan");
defined('_STEP1') or define("_STEP1","Step 1. Pilih Skema Metadata");
defined('_STEP2') or define("_STEP2","Step 2. Buat / Update Metadata");
defined('_STEP3') or define("_STEP3","Step 3. Upload / Update File");
defined('_SOURCEPATH') or define("_SOURCEPATH","Alamat File");
defined('_SUBMIT') or define("_SUBMIT","Submit");
defined('_THISMETADATAHASBEENUPLOAD') or define("_THISMETADATAHASBEENUPLOAD","Metadata ini sudah di-upload");
defined('_THISMETADATAHASBEENUPDATE') or define("_THISMETADATAHASBEENUPDATE","Metadata ini sudah di-update");
defined('_TITLE') or define("_TITLE","Judul");
defined('_UPLOADOREDIT') or define("_UPLOADOREDIT","Upload / Edit Metadata");
defined('_UPLOADFAIL') or define("_UPLOADFAIL","Upload tidak berhasil");
defined('_UPLOADINFO') or define("_UPLOADINFO","Metadata Anda mempunyai properti sebagai berikut, lakukan perubahan yg dikehendaki.");
defined('_UPLOADNEFILE') or define("_UPLOADNEFILE","Upload baru ...");
defined('_CURRENTFOLDER') or define("_CURRENTFOLDER","Folder Saat ini");
defined('_WHATSCHEMA') or define("_WHATSCHEMA","Apa yang akan anda upload ?");
defined('_DIRECTORYERROR') or define("_DIRECTORYERROR","Anda tidak dapat upload metadata pada direktori ini, Anda dapat upload pada direktori <a href='./gdl.php?mod=mydocs'>My Documents</a>");
?>