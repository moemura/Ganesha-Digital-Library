<?php
if (eregi("english.php",$_SERVER['PHP_SELF'])) {
    die();
}

define("_SEARCHDISCUSSION","Search comments by user id, title, or subject");
define("_USERID","User ID");
define("_SUBJECT","Subject");
define("_COMMENTDISPLAYING","Displaying Comments");
define("_COMMENTS","Comments");
define("_OF","of");
define("_PAGE","Page");
define("_CONFIRMATION","Deletion Confirmation");
define("_DELETECOMMENTCONFIRMATION","Are you sure you want to delete this comment ? ");
define("_DELETEYES","Yes");
define("_DELETECOMMENT","Comment Deletion");
define("_SUCCESS","Deletion Success");
define("_NOTFOUND","Cannot found comment id ");
define("_OPTION","Option");

?>