<?php

if (eregi("indonesian.php",$_SERVER['PHP_SELF'])) {
    die();
}

//penambahan oleh benirio
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
define ("_TYPEOFUSER", "Pekerjaan");
define ("_REGISTRATION", "Registrasi");
define ("_SUBMIT", "Submit");
define ("_RESET", "Reset");
define ("_REGISTRATIONNOTE", "Silahkan Lengkapi Form berikut ini <br/> Catatan: * harus diisi.
			<font color=#FF0000>merah</font> = salah / tidak lengkap");
define ("_REGISTRATION_SUCCESS", "Silahkan <a href=\"./gdl.php?mod=register&amp;op=activate\">aktivasi account </a>Anda. <br/> Kode Aktivasi akan dikirim melalui e-mail. <br/> Bila gagal silahkan hubungi administrator ($gdl_publisher[admin])."); 
define ("_REGISTRATION_ADMIN", "Kontak administrator ($gdl_publisher[admin]) untuk mengaktivasi account Anda");
define ("_REGISTRATION_FAIL", "Registerasi gagal. <br/> Silahkan hubungi administrator ($gdl_publisher[admin]) untuk pendaftaran");								
define ("_REGISTRATION_ERROR_EMAIL", "Email Anda Salah<br/>");
define ("_REGISTRATION_ERROR_PASSWORD", " Password dan Confirm Password berbeda<br/>");
define ("_REGISTRATION_ERROR_EMAIL_EXIST", "Account sudah ada yang punya<br/>");
define ("_REGISTRATION_ERROR_VERIFICATION", "Kode Verifikasi salah<br/>");

define ("_ACTIVATE", "Aktivasi");
define ("_ACTIVATENOTE", "Silahkan masukkan account dan kode aktivasi account anda.<br/>
			Catatan: * harus diisi.
			<font color=#FF0000>merah</font> = salah / tidak lengkap");
define ("_ACTIVATE_TITLE", "Mengaktifkan Account");
define("_ACTIVATEFAIL","User ID atau Kode Aktivasi Anda tidak benar.<br/>Silahkan diulangi lagi ..");
define("_ACTIVATESUCCESS", "User ID Anda telah aktif");
define ("_USER_SECURITY","Keamanan Anggota");
define ("_USER_JOB","Pekerjaan");
define ("_VERIFICATION","Verifikasi");

?>