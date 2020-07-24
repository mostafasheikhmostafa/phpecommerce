<?php 
 session_start();
 $pageTitle="Login";
 if (isset($_SESSION['user'])){
     header('Location: index.php');
    }
    include 'init.php';  
   // check if User Coming From Http post Request

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       if (isset($_POST['login'])){
     $user = $_POST['username'];
     $pass = $_POST['password'];
     $hashedPass = sha1($pass);
    
    // check if user exist on database
     $stmt = $con->prepare("SELECT UserID, Username , Password FROM users WHERE Username = ? AND Password = ? ");
     $stmt->execute(array($user, $hashedPass));
     $get= $stmt->fetch();
    
     $count = $stmt->rowCount();
     // if count > 0 this Mean the dataBase contain Record About this Username
     if ($count>0){
       $_SESSION['user'] = $user; //Register Session Name
       $_SESSION['uid']= $get['UserID'];// Register UserID
       //Register Session ID
       header('Location: index.php');// Redirect To Dashbord Page
       exit();
       
     }
}else{
    // bya3mil error lal text box iza fadyi ...
     $formErrors= array();
      
     $username = $_POST['username'];
     $password = $_POST['password'];
     $password2 = $_POST['password2'];
     $email = $_POST['email'];

     if(isset($username)){
          $filteruser= filter_var( $username,FILTER_SANITIZE_STRING);
      if(strlen($filteruser)<4){
           $formErrors[]= 'Username Must Be Larger Than 4 Characters';
      }
     }
     if(isset($password) && isset($password2)){
         if (empty($password)){
          $formErrors[]= 'Sorry Password Cant Be Empty'; 
         } 
        $pass1= sha1($password);
        $pass2= sha1($password2);
        if ($pass1 !== $pass2){
         
          $formErrors[]= 'Sorry Password Is Not Match';
        }
     }
     if(isset($email)){
          $filterEmail= filter_var($email,FILTER_SANITIZE_EMAIL);
          
          if(filter_var($filterEmail,FILTER_VALIDATE_EMAIL) != true){
               $formErrors[]='This Email Is Not Valide'; 
          }
     }
    //Check If Theres No Error Proceed The User Add
     if(empty($formErrors)){
          //Check If Exist in Database
          $check= checkItem("Username","users",$username);
          if($check==1){
               $formErrors[]='Sorry This User Is Exist';
            
          } else{
        
       //Insert DataBase With this Info A5adta min texte 3ala database
               $stmt=$con->prepare("INSERT INTO
                                     users(Username,Password,Email,RegStatus,Date)
                                     VALUES(:zuser,:zpass,:zemail,0,now())");
                 $stmt->execute(array(
                   //*** *jib mn l form($user) w 7eta bl zuser Aw fina na3mel 
                 'zuser' => $username,
                 'zpass' =>  sha1($password),
                 'zemail' => $email
                 
                 ));                    
             
             //echo succes message
               $succesMsg= 'Congrats You Are Now Regtstered User';
           
             }
           }

   }
 }

?>

<div class="container login-page">
<h1 class="text-center">
    <span class=" selected" data-class="login">Login</span> | <span data-class="signup">Signup</span>
</h1>
<!-- Start Login Form -->   
<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
  <div class="input-container">
   <input  class="form-control"
        type="text" name="username" 
        autocomplate="off" 
        placeholder="Type Your Username"required/>
        </div>
        <div class="input-container">
   <input class="form-control" 
        type="password" name="password" 
        autocomplate="new-password" 
        placeholder="Type Your Password"required /></div>
   <input class="btn btn-primary btn-block" name="login"
   type="submit" value="Login"/>
</form>
<!-- Start Login Form -->
<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
<div class="input-container">
   <input pattern=".{4,}" title="Username Must Be 4 Chars" class="form-control" 
        type="text" name="username" 
        autocomplate="off" 
        placeholder="Type Your Username" required /></div>
        <div class="input-container">
   <input minlength="4" class="form-control"
        type="password" name="password"
        autocomplate="new-password" 
        placeholder="Type Your Password" required /></div>
        <div class="input-container">
   <input minlength="4" class="form-control"
        type="password" name="password2" 
        autocomplate="new-password"
        placeholder="Type a Password again" required /></div>
        <div class="input-container">
   <input class="form-control"
        type="email" name="email" 
        placeholder="Type a valid email" required/></div>
   <input class="btn btn-success btn-block" name="signup" type="submit" value="Signup"/>
</form>
     <div class="the-errors text-center">
    <?php 
    if (!empty($formErrors)){
         foreach($formErrors as $error){
              echo '<div class= "msg error">'. $error . '</div>';
         }
    }
    if (isset($succesMsg)){
     echo '<div class= "msg success">'.$succesMsg . '</div>';
    }
    ?>
     </div>
</div>




<?php include $tpl .'footer.php'; ?>