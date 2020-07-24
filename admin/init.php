<?php
include 'connect.php';

//Route
$tpl= 'includes/templates/'; // Template directory
$lang='includes/language/'; //Language directory
$func= 'includes/functions/'; //Functions directory

//include the important file

include $func.'functions.php';
include $lang.'english.php';
include $tpl .'header.php'; 

//include Navbar on All pages Expect the one with $noNavbar varianble

if (!isset($noNavbar)) {include $tpl .'navbar.php';}

 
?>