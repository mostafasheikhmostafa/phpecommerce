<?php

/*
==> 
*/
ob_start();
session_start();

$pageTitle= 'Members';

if (isset($_SESSION['Username'])){

 include 'init.php';
 
 $do = isset($_GET['do']) ? $_GET['do'] :'Manage';
 if($do=='Manage'){
     echo 'welcome';

 } elseif($do=='Add'){
     
 } elseif($do=='Insert'){
 
} elseif($do=='Edit'){
 
} elseif($do=='Update'){
 
} elseif($do=='Delete'){
 
}  

include $tpl .'footer.php'; 
}else {
 header('Location: indexe.php');
 exit();
}
ob_end_flush();
/*
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
*/
?>