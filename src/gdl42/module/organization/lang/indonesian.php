<?php
if (preg_match("/indonesian.php/",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_ORGANIZATIONFOLDER') or define("_ORGANIZATIONFOLDER","Folder Organisasi");
defined('_DOESNOTEXISTDOYOUWANTTOCREATE') or define("_DOESNOTEXISTDOYOUWANTTOCREATE","tidak ada. Apa Anda ingin membuat folder tersebut ?");
defined('_ORGANIZATIONCREATED') or define("_ORGANIZATIONCREATED","Organisasi telah dibuat");
defined('_ORGANIZATIONEXIST') or define("_ORGANIZATIONEXIST","Folder Organization sudah ada");
defined('_ORGANIZATIONCREATEFAILED') or define("_ORGANIZATIONCREATEFAILED","Organisasi gagal dibuat");
defined('_ORGANIZATIONNAME') or define("_ORGANIZATIONNAME","Nama Organisasi");
defined('_ORGANIZATIONADDNEW') or define("_ORGANIZATIONADDNEW","Penambahan Organisasi");
defined('_ORGANIZATIONEDITING') or define("_ORGANIZATIONEDITING","Perubahan Organisasi");
defined('_ADDORGANIZATIONFAILED') or define("_ADDORGANIZATIONFAILED","Penambahan Organisasi gagal");
defined('_ADDORGANIZATIONSUCCESS') or define("_ADDORGANIZATIONSUCCESS","Penambahan Organisasi berhasil");
defined('_EDITORGANIZATIONFAILED') or define("_EDITORGANIZATIONFAILED","Perubahan Organisasi gagal");
defined('_EDITORGANIZATIONSUCCESS') or define("_EDITORGANIZATIONSUCCESS","Perubahan Organisasi berhasil");
defined('_DELETEORGANIZATIONCONFIRMATION') or define("_DELETEORGANIZATIONCONFIRMATION","Apakah Anda yakin ingin menghapus Organisasi ini ? ");
defined('_DELETEORGANIZATIONSUCCESS') or define("_DELETEORGANIZATIONSUCCESS","Penghapusan organisasi berhasil");
defined('_DELETEORGANIZATIONFAILED') or define("_DELETEORGANIZATIONFAILED","Penghapusan organisasi gagal");
defined('_YESSURE') or define("_YESSURE","Ya, Saya yakin");
defined('_CONFIRMATION') or define("_CONFIRMATION","Konfirmasi Penghapusan");
defined('_ACTION') or define("_ACTION","Aksi");
defined('_ADD') or define("_ADD","Tambah");
defined('_EDIT') or define("_EDIT","Ubah");
?>