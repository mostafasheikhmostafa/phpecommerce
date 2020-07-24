<!DOCTYPE html>
<html>
   <head>
     <meta charset ="UTF-8"/>
      <title><?php getTitle() ?></title>
      
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" href="layout/css/jquery.selectBoxIt.css">
  <link rel="stylesheet" href="layout/css/fronte.css">
 
   </head>
<body>
<div class="upper-bar">
  <div class="container">
    <?php
      if (isset($_SESSION['user'])){
        echo 'welcom'. $_SESSION['user'].' ';
        echo '<a href="profile.php">My Profile</a>';
        echo ' - <a href="newad.php">New Add</a>';
        echo ' - <a href="logout.php">Logout</a>';
        $userstaatus=checkUserStatus( $sessionUser);
         if(  $userstaatus){
        //User Is Note Active
     } 
    }else{
    ?>
      <a href="login.php">
          <span class="pull-right">Login/Signup</span>
      </a>
      <?php } ?>
  </div>
</div>
<nav class="navbar navbar-inverse">
  <div class="container">
    
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">HomePage </a>
    </div>

   
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav navbar-right">
        <?php 
          foreach(getCat() as $cat){
            echo '<li><a href="categories.php?pageid='.$cat['ID'].'">
            '. $cat['Name'].'</a></li>';
          }
        ?>
      </ul>
    </div>
  </div>
</nav>