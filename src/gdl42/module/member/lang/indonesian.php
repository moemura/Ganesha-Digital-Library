<?php
if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

define("_DISPLAYINGMEMBER", "Record");
define("_OF", "dari");
define("_MENBER", "Anggota");
define("_NO", "No.");
define("_NAME", "Nama Lengkap");
define("_ACCOUNT", "Account");
define("_LEVELGROUP", "Level Group");
define("_STATUS", "Status");
define("_ACTION", "Aksi");
define("_EDIT", "Edit");
define("_DELETE", "Hapus");
define("_ACTIVE", "Aktif");
define("_NOACTIVE", "Tidak Aktif");
define("_PAGE", "Halaman");
define("_DELETEMEMBER", "Hapus Member");
define("_CONFIRMATION", "Konfirmasi");
define("_DELETEMEMBERCONFIRMATION", "Anda yakin menghapus Member ini?");
define("_YESDELETE", "Ya, hapus");
define("_USEREDIT", "Edit informasi umum anggota");

define("_USER_ID","USER ID");
define ("_USER_EMAIL", "Email");
define ("_USER_PASSWD", "Password");
define ("_USER_PASSWD_CONFIRM", "Confirm Password");
define ("_USER_GENERAL", "General");
define ("_USER_MAIL", "E&ndash;mail");
define ("_USER_FULLNAME", "Nama Lengkap");
define ("_USER_ADDRESS", "Alamat");
define ("_USER_CITY", "Kota");
define ("_USER_COUNTRY","Negara");
define ("_USER_INSTITUTION","Institusi");
define ("_USER_ACCOUNT","Account");
define ("_USER_CODE","Kode");
define ("_TYPEOFUSER", "Tipe Anggota");
define ("_REGISTRATION", "Registrasi");
define ("_SUBMIT", "Update");
define ("_RESET", "Kembali");
define ("_VALIDATION", "Kode Aktivasi");
define ("_UPDATESUCCESS", "Profil Member telah ter update");
define ("_UPDATE_ERROR_PASSWORD", "Password dan Konfirmasi Password Berbeda");
define ("_ADDUSERSUCCESS","Penambahan Anggota berhasil");
define ("_MEMBERMANAGEMENT", "Pengelolaan Anggota");
define ("_SEARCHMEMBER", "Cari");
define ("_SEARCH_USER_MAIL", "Cari Nama atau E&ndash;mail");
define ("_ADDMEMBER","Tambah Anggota");
define ("_EDITMYPROFILE","Edit Profil");
define ("_USER_SECURITY","Tingkatan Anggota");
define ("_USER_JOB","Pekerjaan");
define ("_VERIFICATION","Verifikasi");
define ("_REGISTRATION_ERROR_EMAIL", "Email Anda Salah<br/>");
define ("_REGISTRATION_ERROR_PASSWORD", " Password dan Confirm Password berbeda<br/>");
define ("_REGISTRATION_ERROR_EMAIL_EXIST", "Account sudah ada yang punya<br/>");
define ("_REGISTRATION_ERROR_VERIFICATION", "Kode Verifikasi salah<br/>");
?>