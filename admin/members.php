<?php

/*
==> Manage Members Page
==> You Can Add | Edit | Delet Members From Her 
*/

session_start();

$pageTitle= 'Members';

if (isset($_SESSION['Username'])){

 include 'init.php';
 
 $do = isset($_GET['do']) ? $_GET['do'] :'Manage';
 //Start Manage Page

 if($do=='Manage'){//Manage Members Page 
  $query='';
  // isset($_GET['page']) hayda bijib get Request lal saf7a
  if(isset($_GET['page'])&& $_GET['page']=='pending'){
    $query='AND RegStatus = 0';
  }
    

  // select database 7ata n7eta bl table
  $stmt = $con->prepare("SELECT * FROM users WHERE GroupID!=1 $query ORDER BY UserID DESC");
  // Execute statement
  $stmt->execute();
  // fetchAll ye3ni jibe kel lbayenet mn data w 7eta bl row
  $rows= $stmt->fetchAll();
  if(!empty($rows)){
 ?>

 <h1 class="text-center">Manage Member</h1>
   <div class= "container">
      <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
          <tr>
            <td>#ID</td>
            <td>Username</td>
            <td>Email</td>
            <td>FullName</td>
            <td>Register Date</td>
            <td>Control</td>
          </tr>
          <?php 
              foreach($rows as $row){
                echo"<tr>";
                  echo"<td>".$row['UserID']."</td>";
                  echo"<td>".$row['Username']."</td>";
                  echo"<td>".$row['Email']."</td>";
                  echo"<td>".$row['FullName']."</td>";
                  echo"<td>".$row['Date']. "</td>";
                  echo "<td>
                  <a href='members.php?do=Edit&userid=".$row['UserID']."' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                  <a href='members.php?do=Delete&userid=".$row['UserID']."' class='btn btn-danger confirm'><i class='fa fa-close '></i>Delete</a>";
                  // (If...) Activate haydi btjbli min bado Activate W bt3melo Activate
                  if($row['RegStatus']==0){
                   echo "<a href='members.php?do=Activate&userid=".$row['UserID']."' class='btn btn-info activate'><i class='fa fa-check'></i>Activat</a>";
                  }

                     echo "</td>";
                echo"</tr>";  
              }
          ?>
        
            
        </table>

      </div>
            
     <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Members</a>
  </div>
  <?Php }else{
          echo'<div class="container">';
          echo'<div class="nice-message">There\'s No Member To Show</div>';
         echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Members</a>';
          echo'</div>';
  } ?>

 <?php }elseif($do=='Add'){ //Add Members Page ?>
   
   <h1 class="text-center">Add New Member</h1>
   <div class= "container"> 
   <form class= "form-horizontal" action="?do=Insert" method= "POST">
    
 <!-- start username filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Username</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="username"  class= "form-control" autocomplete="off" required="requird" placeholder="Username To login Into Shop"/>
     </div>
   </div>
   <!-- End username filed -->
   <!-- start password filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Password</label>
     <div class="col-sm-10 col-md-6">
       
       <input type="password" name="password" class= " password form-control" autocomplete ="new-password"required="requird" placeholder="Password Must Be Hard & Complex" />
       <i class= "show-pass fa fa-eye fa-2x"></i>
     </div>
   </div>
   <!-- End upassword filed -->
   <!-- start Email filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Email</label>
     <div class="col-sm-10 col-md-6">
       <input type="email" name="email"   class= "form-control" required="requird" placeholder="Email Must Be Valid"/>
     </div>
   </div>
   <!-- End username filed -->
   <!-- start full name filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Full Name</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="full"  class= "form-control" required="requird" placeholder="Full Name APpaer In Your Profile Page"/>
     </div>
   </div>
   <!-- End username filed -->
   <!-- start s filed -->
   <div class="form-group form-group-lg">
   
     <div class="col-sm-offset-2 col-sm-10">
       <input type="submit" value="Add Member" class= "btn btn-primary btn-lg"/>
     </div>
   </div>
   <!-- End username filed -->
 </form>
</div>
  
  <?php 
  }elseif($do=='Insert'){
    //Insert Member Page
    
     if($_SERVER['REQUEST_METHOD']=='POST'){
      echo "<h1 class='text-center'>Insert Member</h1>";
      echo"<div class='container'>";
       //Get Variable From The Form
       
       $user  = $_POST['username'];
       $pass  = $_POST['password'];
       $email = $_POST['email'];
        $name = $_POST['full'];
        $hashpass= sha1($_POST['password']);
       
        //Validet the forme
        $formErrors=array();
        if(empty($user)){
          $formErrors[]= ' Username Cant Be <strong>empty</strong>';
        }
        if(empty($pass)){
          $formErrors[]= ' Password Cant Be <strong>empty</strong>';
        
        }
        if(empty($name)){
          $formErrors[]= 'Full Name Cant Be <strong>empty</strong>';
        }
        if(empty($email)){
          $formErrors[]= ' Email Cant Be <strong>empty</strong>';
        }
        //Loop Into Errors Array And echo
        foreach($formErrors as $error){
          echo'<div class="alert alert-danger">'. $error.'</div>' ;  
        }
        // Check If Theres No Error Proceed The Update Operation
        if(empty($formErrors)){
           //Check If Exist in Database
           $check= checkItem("Username","users",$user);
           if($check==1){
            $theMsg='<div class="alert alert-danger"> Sorry This User is Exist</div>';
             redirectHome( $theMsg,'back');
           } else{
        //Insert DataBase With this Info A5adta min texte 3ala database
                $stmt=$con->prepare("INSERT INTO
                                      users(Username,Password,Email,FullName,RegStatus,Date)
                                      VALUES(:zuser,:zpass,:zemail,:zname,0,now())");
                  $stmt->execute(array(
                    //*** *jib mn l form($user) w 7eta bl zuser Aw fina na3mel 
                  'zuser' => $user,
                  'zpass' =>  $hashpass,
                  'zemail' => $email,
                  'zname' => $name
                  ));                    
              
              //echo succes message
                $theMsg= "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Insert</div>';
                redirectHome( $theMsg,'back');
              }
            }
       
     }else {
       echo "<div class='container'>";
       $theMsg= '<div class="alert alert-danger">Sorry You Cant Browse This Page Directory</div>';
       redirectHome( $theMsg,'back');
       echo "</div>";
     }
      echo"</div>";
  
}elseif ($do=='Edit'){ //Edit Page

  // Chec If The Request userid Is Numeric & Get  The Tnteger Value Of It
    $userid=isset($_GET['userid']) && is_numeric($_GET['userid']) ?   intval($_GET['userid']) :0;
       
    // Select All Data Depend On This Id
    $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");

    //Execute Query
    $stmt->execute(array($userid));

    //Fetch The Data
    $row= $stmt->fetch();

    // The Row Count
    $count = $stmt->rowCount();
    //If Thers Such ID Show The Form
    if($count>0){?>

          <h1 class="text-center">Edit Member</h1>
              <div class= "container"> 
              <form class= "form-horizontal" action="?do=Update" method= "POST">
                <input type="hidden" name="userid" value="<?php echo $userid ?>"/>
            <!-- start username filed -->
              <div class="form-group form-group-lg">
                <label class="col-sm-2 control-lable"> Username</label>
                <div class="col-sm-10 col-md-6">
                  <input type="text" name="username" value="<?php echo $row['Username'] ?>" class= "form-control" autocomplete="off" required="required"/>
                </div>
              </div>
              <!-- End username filed -->
              <!-- start password filed -->
              <div class="form-group form-group-lg">
                <label class="col-sm-2 control-lable"> Password</label>
                <div class="col-sm-10 col-md-6">
                  <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
                  <input type="password" name="newpassword" class= "form-control" autocomplete ="new-password"placeholder="Leave Blank If You Dont Want To Change Password " />
                </div>
              </div>
              <!-- End upassword filed -->
              <!-- start Email filed -->
              <div class="form-group form-group-lg">
                <label class="col-sm-2 control-lable"> Email</label>
                <div class="col-sm-10 col-md-6">
                  <input type="email" name="email"  value="<?php echo $row['Email'] ?>" class= "form-control"required="required"/>
                </div>
              </div>
              <!-- End username filed -->
              <!-- start full name filed -->
              <div class="form-group form-group-lg">
                <label class="col-sm-2 control-lable"> Full Name</label>
                <div class="col-sm-10 col-md-6">
                  <input type="text" name="full"  value="<?php echo $row['FullName'] ?>" class= "form-control"required="required"/>
                </div>
              </div>
              <!-- End username filed -->
              <!-- start s filed -->
              <div class="form-group form-group-lg">
              
                <div class="col-sm-offset-2 col-sm-10">
                  <input type="submit" value="save" class= "btn btn-primary btn-lg"/>
                </div>
              </div>
              <!-- End username filed -->
            </form>
        </div>

 <?php 
       //else Show Error Message
      }else{
        echo "<div class='container'>";
        $theMsg='<div class="alert alert-danger">Theres No Such ID</div>';
        redirectHome( $theMsg,'back');
        echo "</div>";
      }
   }elseif ($do=='Update'){

    echo "<h1 class='text-center'>Update Member</h1>";
    echo"<div class='container'>";
     if($_SERVER['REQUEST_METHOD']=='POST'){
       //Get Variable From The Form
       $id    = $_POST['userid'];
       $user  = $_POST['username'];
       $email = $_POST['email'];
        $name = $_POST['full'];
        // Trick Password
        $pass='';
        if(empty($_POST['newpassword'])){
           $pass= $_POST['oldpassword'];
        }else{
          $pass= sha1($_POST['newpassword']);
        }
        //Validet the forme
        $formErrors=array();
        if(empty($user)){
          $formErrors[]= ' Username Cant Be <strong>empty</strong>';
        
        }
        if(empty($name)){
          $formErrors[]= 'Full Name Cant Be <strong>empty</strong>';
        }
        if(empty($email)){
          $formErrors[]= ' Email Cant Be <strong>empty</strong>';
        }
        //Loop Into Errors Array And echo
        foreach($formErrors as $error){
          echo'<div class="alert alert-danger">'. $error.'</div>' ;  
        }
        // Check If Theres No Error Proceed The Update Operation
        if(empty($formErrors)){
             $stmt2= $con->prepare("SELECT * FROM users WHERE Username=? AND UserID!=?");
             $stmt2->execute(array($user,$id));
             $count= $stmt2->rowCount();
             if($count==1){
              $theMsg= '<div class="alert alert-danger">Sorry This Is Exist</div>';
               redirectHome( $theMsg,'back',3);
             }else{
               //Update DataBase With this Info A5adta min texte 3ala database
           
       $stmt = $con->prepare("UPDATE users SET Username =? ,Email= ?, FullName= ?,Password=? WHERE UserID =?");
       $stmt->execute(array($user,$email,$name,$pass,$id));
       //echo succes message
       $theMsg=  "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Upadte</div>';
        redirectHome( $theMsg,'back',3);
    
             }
        }
     }else {
      $theMsg= '<div class="alert alert-danger">Sorry You Cant Browse This Page Directory</div>';
       redirectHome( $theMsg);
       
     }
      echo"</div>";
   } elseif($do=='Delete'){//Delet Member Page
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo"<div class='container'>";
          
          // Chec If The Request userid Is Numeric & Get  The Tnteger Value Of It
          $userid=isset($_GET['userid']) && is_numeric($_GET['userid']) ?   intval($_GET['userid']) :0;
            
          // Select All Data Depend On This ID
         $check= checkItem('userid','users',$userid);
           
           
          //If Thers Such ID Show The Form
         if( $check >0){
            $stmt= $con->prepare("DELETE FROM users WHERE UserID= :zuser");
            $stmt->bindParam(":zuser",$userid);
            $stmt->execute();
            $theMsg= "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Delete</div>';
            redirectHome( $theMsg,'back');

        }else{
          $theMsg='<div class="alert alert-danger">This Id  Is Note Exist</div>';
          redirectHome( $theMsg);
        }
        echo'</div>';

  } elseif($do=='Activate'){

    echo "<h1 class='text-center'>Activate Member</h1>";
    echo"<div class='container'>";
      
      // Chec If The Request userid Is Numeric & Get  The Tnteger Value Of It(intval :ye3ni btjib id)
      $userid=isset($_GET['userid']) && is_numeric($_GET['userid']) ?   intval($_GET['userid']) :0;
        
      // Select All Data Depend On This ID
     $check= checkItem('userid','users',$userid);
       
       
      //If Thers Such ID Show The Form
     if( $check >0){
        $stmt= $con->prepare("UPDATE users SET RegStatus=1 WHERE UserID=?");
  
        $stmt->execute(array($userid));

        $theMsg= "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Update</div>';
        redirectHome( $theMsg,'back');

    }else{
      $theMsg='<div class="alert alert-danger">This Id  Is Note Exist</div>';
      redirectHome( $theMsg);
    }
    echo'</div>'; 
  }
 include $tpl .'footer.php'; 
}else {
 header('Location: indexe.php');
 exit();
}
?>