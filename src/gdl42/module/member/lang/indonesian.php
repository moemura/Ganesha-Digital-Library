<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

defined('_DISPLAYINGMEMBER') or define("_DISPLAYINGMEMBER", "Record");
defined('_OF') or define("_OF", "dari");
defined('_MENBER') or define("_MENBER", "Anggota");
defined('_NO') or define("_NO", "No.");
defined('_NAME') or define("_NAME", "Nama Lengkap");
defined('_ACCOUNT') or define("_ACCOUNT", "Account");
defined('_LEVELGROUP') or define("_LEVELGROUP", "Level Group");
defined('_STATUS') or define("_STATUS", "Status");
defined('_ACTION') or define("_ACTION", "Aksi");
defined('_EDIT') or define("_EDIT", "Edit");
defined('_DELETE') or define("_DELETE", "Hapus");
defined('_ACTIVE') or define("_ACTIVE", "Aktif");
defined('_NOACTIVE') or define("_NOACTIVE", "Tidak Aktif");
defined('_PAGE') or define("_PAGE", "Halaman");
defined('_DELETEMEMBER') or define("_DELETEMEMBER", "Hapus Member");
defined('_CONFIRMATION') or define("_CONFIRMATION", "Konfirmasi");
defined('_DELETEMEMBERCONFIRMATION') or define("_DELETEMEMBERCONFIRMATION", "Anda yakin menghapus Member ini?");
defined('_YESDELETE') or define("_YESDELETE", "Ya, hapus");
defined('_USEREDIT') or define("_USEREDIT", "Edit informasi umum anggota");

defined('_USER_ID') or define("_USER_ID","USER ID");
defined('_USER_EMAIL') or define("_USER_EMAIL", "Email");
defined('_USER_PASSWD') or define("_USER_PASSWD", "Password");
defined('_USER_PASSWD_CONFIRM') or define("_USER_PASSWD_CONFIRM", "Confirm Password");
defined('_USER_GENERAL') or define("_USER_GENERAL", "General");
defined('_USER_MAIL') or define("_USER_MAIL", "E&ndash;mail");
defined('_USER_FULLNAME') or define("_USER_FULLNAME", "Nama Lengkap");
defined('_USER_ADDRESS') or define("_USER_ADDRESS", "Alamat");
defined('_USER_CITY') or define("_USER_CITY", "Kota");
defined('_USER_COUNTRY') or define("_USER_COUNTRY","Negara");
defined('_USER_INSTITUTION') or define("_USER_INSTITUTION","Institusi");
defined('_USER_ACCOUNT') or define("_USER_ACCOUNT","Account");
defined('_USER_CODE') or define("_USER_CODE","Kode");
defined('_TYPEOFUSER') or define("_TYPEOFUSER", "Tipe Anggota");
defined('_REGISTRATION') or define("_REGISTRATION", "Registrasi");
defined('_SUBMIT') or define("_SUBMIT", "Update");
defined('_RESET') or define("_RESET", "Kembali");
defined('_VALIDATION') or define("_VALIDATION", "Kode Aktivasi");
defined('_UPDATESUCCESS') or define("_UPDATESUCCESS", "Profil Member telah ter update");
defined('_UPDATE_ERROR_PASSWORD') or define("_UPDATE_ERROR_PASSWORD", "Password dan Konfirmasi Password Berbeda");
defined('_ADDUSERSUCCESS') or define("_ADDUSERSUCCESS","Penambahan Anggota berhasil");
defined('_MEMBERMANAGEMENT') or define("_MEMBERMANAGEMENT", "Pengelolaan Anggota");
defined('_SEARCHMEMBER') or define("_SEARCHMEMBER", "Cari");
defined('_SEARCH_USER_MAIL') or define("_SEARCH_USER_MAIL", "Cari Nama atau E&ndash;mail");
defined('_ADDMEMBER') or define("_ADDMEMBER","Tambah Anggota");
defined('_EDITMYPROFILE') or define("_EDITMYPROFILE","Edit Profil");
defined('_USER_SECURITY') or define("_USER_SECURITY","Tingkatan Anggota");
defined('_USER_JOB') or define("_USER_JOB","Pekerjaan");
defined('_VERIFICATION') or define("_VERIFICATION","Verifikasi");
defined('_REGISTRATION_ERROR_EMAIL') or define("_REGISTRATION_ERROR_EMAIL", "Email Anda Salah<br/>");
defined('_REGISTRATION_ERROR_PASSWORD') or define("_REGISTRATION_ERROR_PASSWORD", " Password dan Confirm Password berbeda<br/>");
defined('_REGISTRATION_ERROR_EMAIL_EXIST') or define("_REGISTRATION_ERROR_EMAIL_EXIST", "Account sudah ada yang punya<br/>");
defined('_REGISTRATION_ERROR_VERIFICATION') or define("_REGISTRATION_ERROR_VERIFICATION", "Kode Verifikasi salah<br/>");
?>