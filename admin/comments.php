<?php

/*
==> Manage Members Page
==> You Can | Edit | Delet|Ppprove Members From Her 
*/

session_start();

$pageTitle= 'comments';

if (isset($_SESSION['Username'])){

 include 'init.php';
 
 $do = isset($_GET['do']) ? $_GET['do'] :'Manage';
 //Start Manage Page
 if($do=='Manage'){


  // select database 7ata n7eta bl table
  $stmt = $con->prepare("SELECT comments.*, item.Name AS Item_Name,Users.Username AS Member
                         FROM 
                         comments
                          INNER JOIN item
                          ON item.ItemID= comments.item_id
                          INNER JOIN users
                          ON users.UserID= comments.user_id
                          ORDER BY c_id DESC");
  // Execute statement
  $stmt->execute();
  // fetchAll ye3ni jibe kel lbayenet mn data w 7eta bl row
  $rows= $stmt->fetchAll();
  if(! empty($rows)){
 ?>

 <h1 class="text-center">Manage Comment</h1>
   <div class= "container">
      <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
          <tr>
            <td>ID</td>
            <td>Comment</td>
            <td>Item Name</td>
            <td>User Name</td>
            <td>Added Date</td>
            <td>Control</td>
          </tr>
          <?php 
              foreach($rows as $row){
                echo"<tr>";
                  echo"<td>".$row['C_id']."</td>";
                  echo"<td>".$row['comment']."</td>";
                  echo"<td>".$row['Item_Name']."</td>";
                  echo"<td>".$row['Member']."</td>";
                  echo"<td>".$row['comment_date']. "</td>";
                  echo "<td>
                  <a href='comments.php?do=Edit&comid=".$row['C_id']."' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                  <a href='comments.php?do=Delete&comid=".$row['C_id']."' class='btn btn-danger confirm'><i class='fa fa-close '></i>Delete</a>";
                  // (If...) Activate haydi btjbli min bado Activate W bt3melo Activate
                  if($row['status']==0){
                   echo "<a href='comments.php?do=Approve&comid=".$row['C_id']."' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                  }

                     echo "</td>";
                echo"</tr>";  
              }
          ?>

        </table>
      </div>
       </div>
       <?Php }else{
          echo'<div class="container">';
          echo'<div class="nice-message">There\'s No Comments To Show</div>';
        
          echo'</div>';
  } ?>
  <?php
 }elseif ($do=='Edit'){ //Edit Page

  // Chec If The Request comid Is Numeric & Get  The Tnteger Value Of It
    $comid=isset($_GET['comid']) && is_numeric($_GET['comid']) ?   intval($_GET['comid']) :0;
       
    // Select All Data Depend On This Id
    $stmt = $con->prepare("SELECT * FROM comments WHERE C_id = ?  LIMIT 1");

    //Execute Query
    $stmt->execute(array($comid));

    //Fetch The Data
    $row= $stmt->fetch();

    // The Row Count
    $count = $stmt->rowCount();
    //If Thers Such ID Show The Form
    if($count>0){?>

          <h1 class="text-center">Edit Comment</h1>
              <div class= "container"> 
              <form class= "form-horizontal" action="?do=Update" method= "POST">
                <input type="hidden" name="comid" value="<?php echo $comid ?>"/>
            <!-- start comment filed -->
              <div class="form-group form-group-lg">
                <label class="col-sm-2 control-lable"> Comments</label>
                <div class="col-sm-10 col-md-6">
                 <textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
                </div>
              </div>
              <!-- End comment filed -->
              <div class="form-group form-group-lg">
              
                <div class="col-sm-offset-2 col-sm-10">
                  <input type="submit" value="save" class= "btn btn-primary btn-lg"/>
                </div>
              </div>
              
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

    echo "<h1 class='text-center'>Update Comment</h1>";
    echo"<div class='container'>";
     if($_SERVER['REQUEST_METHOD']=='POST'){
       //Get Variable From The Form
       $comid   = $_POST['comid'];
       $comment  = $_POST['comment'];
     
        
        
//Update DataBase With this Info A5adta min texte 3ala database
           
       $stmt = $con->prepare("UPDATE comments SET comment =?  WHERE C_id =?");
       $stmt->execute(array($comment,$comid));
       //echo succes message
       $theMsg=  "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Upadte</div>';
        redirectHome( $theMsg,'back',3);
      
       
     }else {
      $theMsg= '<div class="alert alert-danger">Sorry You Cant Browse This Page Directory</div>';
       redirectHome( $theMsg,'back');
       
     }
      echo"</div>";
   } elseif($do=='Delete'){//Delet Member Page
        echo "<h1 class='text-center'>Delete Comment</h1>";
        echo"<div class='container'>";
          
          // Chec If The Request comid Is Numeric & Get  The Tnteger Value Of It
          $comid=isset($_GET['comid']) && is_numeric($_GET['comid']) ?   intval($_GET['comid']) :0;
            
          // Select All Data Depend On This ID btchouf isa fi ID 
         $check= checkItem('C_id','comments',$comid);
           
           
          //If Thers Such ID Show The Form
         if( $check >0){
            $stmt= $con->prepare("DELETE FROM comments WHERE C_id= :zid");
            $stmt->bindParam(":zid",$comid);
            $stmt->execute();
            $theMsg= "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Delete</div>';
            redirectHome( $theMsg,'back');

        }else{
          $theMsg='<div class="alert alert-danger">This Id  Is Note Exist</div>';
          redirectHome( $theMsg);
        }
        echo'</div>';

  } elseif($do=='Approve'){

    echo "<h1 class='text-center'>Approve Comments</h1>";
    echo"<div class='container'>";
      
      // Chec If The Request comid Is Numeric & Get  The Tnteger Value Of It(intval :ye3ni btjib id)
      $comid=isset($_GET['comid']) && is_numeric($_GET['comid']) ?   intval($_GET['comid']) :0;
        
      // Select All Data Depend On This ID
     $check= checkItem('C_id','comments',$comid);
       
       
      //If Thers Such ID Show The Form
     if( $check >0){
        $stmt= $con->prepare("UPDATE comments SET status=1 WHERE C_id=?");
  
        $stmt->execute(array($comid));

        $theMsg= "<div class='alert alert-success'>". $stmt->rowCount().'Comment Approve</div>';
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