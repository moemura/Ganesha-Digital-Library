<?
if (eregi("myprofile.php",$_SERVER['PHP_SELF'])) {
	die();
}

header ("Location: ./gdl.php?mod=member&op=edit&a=".$_SESSION['gdl_user']."");
?>