<?php

if (preg_match("/indonesian.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

//penambahan oleh benirio
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
defined('_TYPEOFUSER') or define("_TYPEOFUSER", "Pekerjaan");
defined('_REGISTRATION') or define("_REGISTRATION", "Registrasi");
defined('_SUBMIT') or define("_SUBMIT", "Submit");
defined('_RESET') or define("_RESET", "Reset");
defined('_REGISTRATIONNOTE') or define("_REGISTRATIONNOTE", "Silahkan Lengkapi Form berikut ini <br/> Catatan: * harus diisi.
			<font color=#FF0000>merah</font> = salah / tidak lengkap");
defined('_REGISTRATION_SUCCESS') or define("_REGISTRATION_SUCCESS", "Silahkan <a href=\"./gdl.php?mod=register&amp;op=activate\">aktivasi account </a>Anda. <br/> Kode Aktivasi akan dikirim melalui e-mail. <br/> Bila gagal silahkan hubungi administrator."); 
defined('_REGISTRATION_ADMIN') or define("_REGISTRATION_ADMIN", "Kontak administrator untuk mengaktivasi account Anda");
defined('_REGISTRATION_FAIL') or define("_REGISTRATION_FAIL", "Registerasi gagal. <br/> Silahkan hubungi administrator untuk pendaftaran");								
defined('_REGISTRATION_ERROR_EMAIL') or define("_REGISTRATION_ERROR_EMAIL", "Email Anda Salah<br/>");
defined('_REGISTRATION_ERROR_PASSWORD') or define("_REGISTRATION_ERROR_PASSWORD", " Password dan Confirm Password berbeda<br/>");
defined('_REGISTRATION_ERROR_EMAIL_EXIST') or define("_REGISTRATION_ERROR_EMAIL_EXIST", "Account sudah ada yang punya<br/>");
defined('_REGISTRATION_ERROR_VERIFICATION') or define("_REGISTRATION_ERROR_VERIFICATION", "Kode Verifikasi salah<br/>");

defined('_ACTIVATE') or define("_ACTIVATE", "Aktivasi");
defined('_ACTIVATENOTE') or define("_ACTIVATENOTE", "Silahkan masukkan account dan kode aktivasi account anda.<br/>
			Catatan: * harus diisi.
			<font color=#FF0000>merah</font> = salah / tidak lengkap");
defined('_ACTIVATE_TITLE') or define("_ACTIVATE_TITLE", "Mengaktifkan Account");
defined('_ACTIVATEFAIL') or define("_ACTIVATEFAIL","User ID atau Kode Aktivasi Anda tidak benar.<br/>Silahkan diulangi lagi ..");
defined('_ACTIVATESUCCESS') or define("_ACTIVATESUCCESS", "User ID Anda telah aktif");
defined('_USER_SECURITY') or define("_USER_SECURITY","Keamanan Anggota");
defined('_USER_JOB') or define("_USER_JOB","Pekerjaan");
defined('_VERIFICATION') or define("_VERIFICATION","Verifikasi");
?>