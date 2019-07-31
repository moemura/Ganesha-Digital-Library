# Ganesha-Digital-Library

Originally developed by KMRG ITB (http://kmrg.itb.ac.id) ([archive](https://web.archive.org/web/20181103231159/http://kmrg.itb.ac.id/))

* This version of GDL compatible with PHP 7+ and MySQL 5.7+.
* Hash function for user password now use SHA2 512 bit replacing [OLD_PASSWORD](https://dev.mysql.com/doc/refman/5.7/en/password-hashing.html) function.
* OAI-PMH support has been updated to the lib from [OpenSearch](http://wiki.onesearch.id/doku.php?id=oai-gdl)

# How to Upgrade

* Backup old files.
* Replace everything except config and bin folder.
* Increase length of the password hash field to 128 using the following SQL.
ALTER TABLE `gdl_user` MODIFY COLUMN `password` varchar(128) NOT NULL DEFAULT '' AFTER `user_id`;
Replace gdl_user with actual table name.
