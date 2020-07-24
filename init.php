<?php
//Error Reporting
ini_set('display_errors','On');
error_reporting(E_ALL);
include 'admin/connect.php';

$sessionUser='';
if(isset( $_SESSION['user'])){
    $sessionUser = $_SESSION['user'];
}
//Route
$tpl= 'includes/templates/'; // Template directory
$lang='includes/language/'; //Language directory
$func= 'includes/functions/'; //Functions directoryy

//include the important file

include $func.'functions.php';
include $lang.'english.php';
include $tpl .'header.php'; 

 
?>