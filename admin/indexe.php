<?php
  session_start();
  $noNavbar ='';
  $pageTitle="Login";
   if (isset($_SESSION['Username'])){
    header('Location: dashbaord.php');
   }
  include 'init.php';
  
  // check if User Coming From Http post Request

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $username = $_POST['user'];
      $password = $_POST['pass'];
      $hashedPass = sha1($password);
     
     // check if user exist on database
      $stmt = $con->prepare("SELECT UserID, Username , Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1");
      $stmt->execute(array($username, $hashedPass));
      $row= $stmt->fetch();
      $count = $stmt->rowCount();
      // if count > 0 this Mean the dataBase contain Record About this Username
      if ($count>0){
        $_SESSION['Username'] = $username; //Register Session Name
        $_SESSION['ID'] = $row['UserID'];  //Register Session ID
        header('Location: dashbaord.php');// Redirect To Dashbord Page
        exit();
        
      }
  }
  ?>




 <form class ="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class ="text-center"> Admin Login</h4>
    <input class="form-control" type= "text" name="user" placeholder="Username" autocomplete="off"/> 
    <input class="form-control" type= "password" name="pass" placeholder="password" autocomplete="new-password"/> 
    <input class="btn btn-primary btn-block" type="submit" value="Login"/>
</form>
<?php
  include $tpl .'footer.php'; 
  ?>
