<?php

function getTitle(){
    global $pageTitle;
    if(isset($pageTitle)){
        echo $pageTitle;
    }else {
        echo 'Defaulte';
    }
 }

 /*
 ** Home Redirecte Function v1.0
 [This Function Accepte Parametre]
 **$theMsg = echo the errore Message[Error| Success|Warnig]
 **$url = The Link You To Redirect To
**$seconde = Seconde Before Message
*/
function redirectHome($theMsg, $url = null, $seconds=3){
    if ($url===null){
        $url= 'indexe.php';
        $link='Homepage';
    }else{
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!==''){
            $url= $_SERVER['HTTP_REFERER'];
            $link='previous page';
        }else{
            $url='indexe.php';
            $link='Homepage';
        }
       
    }
    echo $theMsg;
    echo"<div class='alert alert-info'>You Will Be to Redirectore to  $link After $seconds Seconds.</div>";
    header("refresh:$seconds;url= $url");
    exit();
}
/*
** Check Items Function v1.0 
** Function To Check Items In DataBase[Function Accepte Parametre]
** $select= The Iteme To Select From [Example:user,iteme,category]
**$from=yhe table to select from [Example:user,Items,category]
** $value= the value of select [Example:Osama,Boxe,Electronics]
*/
function checkItem($select,$from,$value){
    global $con;
     $statement=$con->prepare("SELECT $select FROM $from WHERE $select= ?");
     $statement->execute(array($value));
     $count =$statement->rowCount();
     return $count; 
}
/* Haydi Bte7soube 3adad L Members MN Ldata w Bet7eto bl totale Members
**Count Number Of Iteme Function V1.0
**Function To Count Number OPf Iteme rOWS
**$item= The Items To Count
**$table= The Table To Chouse Form
*/
function countItems($item,$table){
    global $con;
    $stm2= $con->prepare("SELECT COUNT($item) FROM $table");
    $stm2->execute();
   return $stm2->fetchColumn();
}
/*
**Get Latest Record Function V1.0
**Function To Get Items From Database[Users,Items,Comments]
**$select= Field To Select
**$table = The Table To Choose From
**$order= the desc tertib mn l2ekhir
**$limit = Number Of Record To Get
*/
function getLatest($select,$table,$order,$limit=5){
    global $con;
    $getStmt= $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getStmt->execute();
    $rows= $getStmt->fetchAll();
    return $rows;
}