<?php

if (preg_match("/javascripts.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

echo "function menu_jump(path) {\n"
	."window.location.href = path.options[path.selectedIndex].value\n"
	."}\n";

?>
