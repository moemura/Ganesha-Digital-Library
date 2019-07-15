<?php
/***************************************************************************
                          conf.php  -  configuration file for home module
                             -------------------
    begin                : Oct 4, 2006
    copyright            : (C) 2006 Beni Rio Hermanto, KMRG ITB
    email                : benirio@kmrg.itb.ac.id

 ***************************************************************************/
if (preg_match("/conf.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

$gdl_modul['name'] = _MEMBER;
$gdl_menu['add']=_ADDMEMBER;
$gdl_menu['myprofile']=_EDITMYPROFILE;
?>