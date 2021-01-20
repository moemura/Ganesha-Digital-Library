<?php

if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_ACTION') or define("_ACTION","Perintah");
defined('_ADDFOLDER') or define("_ADDFOLDER","Tambah Folder");
defined('_ADDNEWFOLDER') or define("_ADDNEWFOLDER","Tambah Folder Baru");
defined('_CONFIRMATION') or define("_CONFIRMATION","Konfirmasi");
defined('_CHILD') or define("_CHILD","Sub Folder");
defined('_DISPLAYINGMETADATA') or define("_DISPLAYINGMETADATA","Menampilkan metadata");
defined('_DATE') or define("_DATE","Tanggal");
defined('_DELETEFOLDER') or define("_DELETEFOLDER","Hapus Folder");
defined('_DELETEMETADATA') or define("_DELETEMETADATA","Hapus Metadata");
defined('_DELETEFOLDERCONFIRMATION') or define("_DELETEFOLDERCONFIRMATION","Anda yakin menghapus folder ini, termasuk metadata didalamnya");
defined('_DELETEMETADATACONFIRMATION') or define("_DELETEMETADATACONFIRMATION","Anda yakin menghapus metadata ini");
defined('_EDIT') or define("_EDIT","Ubah");
defined('_FOLDER') or define("_FOLDER","Folder");
defined('_METADATAINFOLDER') or define("_METADATAINFOLDER","Metadata dalam folder");
defined('_METADATAS') or define("_METADATAS","Metadata");
defined('_METADATA') or define("_METADATA","Metadata");
defined('_MODE') or define("_MODE","Mode");
defined('_NAME') or define("_NAME","Nama");
defined('_OF') or define("_OF","dari");
defined('_OWNER') or define("_OWNER","Pemilik");
defined('_PAGE') or define("_PAGE","Halaman");
defined('_PROPERTY') or define("_PROPERTY","Properti");
defined('_PARENT') or define("_PARENT","Folder Induk");
defined('_PROPERTYFOLDER') or define("_PROPERTYFOLDER","Properti Folder");
defined('_PROPERTYMETADATA') or define("_PROPERTYMETADATA","Properti Metadata");
defined('_SUBFOLDERON') or define("_SUBFOLDERON","Sub Folder");
defined('_SUBMIT') or define("_SUBMIT","Submit");
defined('_TITLE') or define("_TITLE","Identifier / Judul");
defined('_UPLOADMETADATA') or define("_UPLOADMETADATA","Upload Metadata");
defined('_WORKGROUP') or define("_WORKGROUP","Workgroup");
defined('_YESDELETE') or define("_YESDELETE","Ya, hapus");
defined('_RESET') or define("_RESET","Reset");
defined('_EDITFOLDER') or define("_EDITFOLDER","Edit Folder");
defined('_BACK') or define("_BACK","Kembali");
defined('_CANNOTDELETEFOLDERCONFIRMATION') or define("_CANNOTDELETEFOLDERCONFIRMATION","Folder ini mempunyai sub folder, Anda tidak bisa menghapusnya");
defined('_MULTIVIEW') or define("_MULTIVIEW","Tampilan ganda");
defined('_SINGLEVIEW') or define("_SINGLEVIEW","Tampilan satu");
defined('_MOVE') or define("_MOVE","Pindahkan");
defined('_DATEMODIFIED') or define("_DATEMODIFIED","Tanggal Perubahan");
?>