<?php
if (preg_match("/myprofile.php/i",$_SERVER['PHP_SELF'])) {
	die();
}

header ("Location: ./gdl.php?mod=member&op=edit&a=".$_SESSION['gdl_user']."");
?>