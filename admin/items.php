
<?php

/*
==> 
*/
ob_start();
session_start();

$pageTitle= 'Items';

if (isset($_SESSION['Username'])){

 include 'init.php';
 
 $do = isset($_GET['do']) ? $_GET['do'] :'Manage';
 if($do=='Manage'){
    
  
    

  $stmt = $con->prepare("SELECT 
                              item.*, categories.Name AS category_name,
                             users.Username
                             FROM item 
                            INNER JOIN categories
                            ON categories.ID = item.Cat_ID
                            INNER JOIN users
                            ON users.UserID= item.Member_ID
                            ORDER BY ItemID DESC");
  // Execute statement
  $stmt->execute();
  // fetchAll ye3ni jibe kel lbayenet mn data w 7eta bl row
  $items= $stmt->fetchAll();
  if(! empty($items)){
 ?>

 <h1 class="text-center">Manage Items</h1>
   <div class= "container">
      <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
          <tr>
            <td>#ID</td>
            <td>Name</td>
            <td>Description</td>
            <td>Price</td>
            <td>Adding Date</td>
           
            <td>Category</td>
            <td>Username</td>
            <td>Control</td>
          </tr>
          <?php 
              foreach($items as $item){
                echo"<tr>";
                  echo"<td>".$item['ItemID']."</td>";
                  echo"<td>".$item['Name']."</td>";
                  echo"<td>".$item['Description']."</td>";
                  echo"<td>".$item['Price']."</td>";
                  echo"<td>".$item['Add_Date']. "</td>";
                 
                  echo"<td>".$item['category_name']. "</td>";
                  echo"<td>".$item['Username']. "</td>";
                  echo "<td>
                  <a href='items.php?do=Edit&itemid=".$item['ItemID']."' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                  <a href='items.php?do=Delete&itemid=".$item['ItemID']."' class='btn btn-danger confirm'><i class='fa fa-close '></i>Delete</a>";
                  if($item['Approve']==0){
                    echo "<a href='items.php?do=Approve&itemid=".$item['ItemID']."' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                   }
                 

                     echo "</td>";
                echo"</tr>";  
              }
          ?>
        
            
        </table>
      </div>
      <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Items</a>
     
  </div>

            <?php }else{
              echo'<div class="container">';
              echo'<div class="nice-message">There\'s No Item To Show</div>';
            echo'<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Items</a>';
              echo'</div>';
            }

 } elseif($do=='Add'){ ?>
    <h1 class="text-center">Add New Items</h1>
   <div class= "container"> 
   <form class= "form-horizontal" action="?do=Insert" method= "POST">
    
 <!-- start Name filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Name</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="name"  class= "form-control"   placeholder="Name Of Items"/>
     </div>
   </div>
   <!-- End Name filed -->
   <!-- start Description filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable">Description</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="description"  class= "form-control"   placeholder="Description Of Items"/>
     </div>
   </div>
   <!-- End Description filed -->
   <!-- start Price filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable">Price</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="price"  class= "form-control"   placeholder="Price Of Items"/>
     </div>
   </div>
   <!-- End Price filed -->
   <!-- start Country filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable">Country</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="country"  class= "form-control"   placeholder="Country Of the Made"/>
     </div>
   </div>
   <!-- End Country filed -->
 
    <!-- start Status filed -->
    <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable">Status</label>
     <div class="col-sm-10 col-md-6">
    <select name="status">
    <option value="">...</option>
    <option value="1">new</option>
    <option value="2">like new</option>
    <option value="3">Used</option>
    <option value="4">Very Old</option>
    </select>
     </div>
   </div>
   <!-- End Status filed -->
   <!-- start Member filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable">member</label>
     <div class="col-sm-10 col-md-6">
    <select name="member">
    <option value="">...</option>
    <?php 
    $stmt= $con->prepare("SELECT * FROM users");
    $stmt->execute();
    $users= $stmt->fetchAll();
    foreach($users as $user){
      echo "<option value='". $user['UserID'] ."'>". $user['Username'] ."</option>";
    }

?>
    </select>
     </div>
   </div>
   <!-- End Member filed -->
    <!-- start Categoryu filed -->
    <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable">Category</label>
     <div class="col-sm-10 col-md-6">
    <select name="category">
    <option value="">...</option>
    <?php 
    $stmt2= $con->prepare("SELECT * FROM categories");
    $stmt2->execute();
    $cats= $stmt2->fetchAll();
    foreach($cats as $cat){
      echo "<option value='". $cat['ID'] ."'>". $cat['Name'] ."</option>";
    }

?>
    </select>
     </div>
   </div>
   <!-- End Category filed -->
 
   <!-- End Rating filed -->
   <!-- start s filed -->
   <div class="form-group form-group-lg">
   
     <div class="col-sm-offset-2 col-sm-10">
       <input type="submit" value="Add Items" class= "btn btn-primary btn-sm"/>
     </div>
   </div>
   <!-- End username filed -->
 </form>
</div>

<?php 

 } elseif($do=='Insert'){
    
   if($_SERVER['REQUEST_METHOD']=='POST'){
    echo "<h1 class='text-center'>Insert Items</h1>";
    echo"<div class='container'>";
     //Get Variable From The Form
     
     $name  = $_POST['name'];
     $desc  = $_POST['description'];
     $price = $_POST['price'];
      $country = $_POST['country'];
      $status = $_POST['status'];
      $member = $_POST['member'];
      $cat = $_POST['category'];
   
     
      //Validet the forme
      $formErrors=array();
      if(empty($name)){
        $formErrors[]= ' name Cant Be <strong>empty</strong>';
      }
      if(empty($desc)){
        $formErrors[]= 'Description Cant Be <strong>empty</strong>';
      
      }
      if(empty($price)){
        $formErrors[]= 'price Cant Be <strong>empty</strong>';
      }
      if(empty($country)){
        $formErrors[]= ' country Cant Be <strong>empty</strong>';
      }
      if($status==""){
        $formErrors[]= ' status Cant Be <strong>empty</strong>';
      }
      if($member==""){
        $formErrors[]= ' You Must Choose <strong>Member</strong>';
      }
      if($cat==""){
        $formErrors[]= ' You Must Choose <strong>Categories</strong>';
      }
      
      //Loop Into Errors Array And echo
      foreach($formErrors as $error){
        echo'<div class="alert alert-danger">'. $error.'</div>' ;  
      }
      // Check If Theres No Error Proceed The Update Operation
      if(empty($formErrors)){
        
      //Insert DataBase With this Info A5adta min texte 3ala database
              $stmt=$con->prepare("INSERT INTO
                                    item(Name,Description,Price,Country_Made,Status,Add_Date,Cat_ID,Member_ID)
                                    VALUES(:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember)");
                $stmt->execute(array(
                  //*** *jib mn l form($user) w 7eta bl zuser Aw fina na3mel 
                'zname' => $name,
                'zdesc' =>  $desc,
                'zprice' => $price,
                'zcountry' => $country,
                'zstatus' => $status,
                'zcat' => $cat,
                'zmember' => $member

                ));                    
            
            //echo succes message
              $theMsg= "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Insert</div>';
              redirectHome( $theMsg,);
            
          }
     
   }else {
     echo "<div class='container'>";
     $theMsg= '<div class="alert alert-danger">Sorry You Cant Browse This Page Directory</div>';
     redirectHome( $theMsg,'back');
     echo "</div>";
   }
    echo"</div>";
 
} elseif($do=='Edit'){
   // Chec If The Request ItemID Is Numeric & Get  The Tnteger Value Of It
   $itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?   intval($_GET['itemid']) :0;
       
   // Select All Data Depend On This Id
   $stmt = $con->prepare("SELECT * FROM item WHERE ItemID = ? ");

   //Execute Query
   $stmt->execute(array($itemid));

   //Fetch The Data
   $item= $stmt->fetch();

   // The Row Count
   $count = $stmt->rowCount();
   //If Thers Such ID Show The Form
   if($count>0){?>
   <h1 class="text-center">Edit Items</h1>
   <div class= "container"> 
   <form class= "form-horizontal" action="?do=Update" method= "POST">
   <input type="hidden" name="itemid" value="<?php echo $itemid?>"/>
 <!-- start Name filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Name</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="name"  class= "form-control"   placeholder="Name Of Items" value="<?php echo $item['Name'] ?>"/>
     </div>
   </div>
   <!-- End Name filed -->
   <!-- start Description filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable">Description</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="description"  class= "form-control"   placeholder="Description Of Items" value="<?php echo $item['Description'] ?>"/>
     </div>
   </div>
   <!-- End Description filed -->
   <!-- start Price filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable">Price</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="price"  class= "form-control"   placeholder="Price Of Items" value="<?php echo $item['Price'] ?>"/>
     </div>
   </div>
   <!-- End Price filed -->
   <!-- start Country filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable">Country</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="country"  class= "form-control"   placeholder="Country Of the Made" value="<?php echo $item['Country_Made'] ?>"/>
     </div>
   </div>
   <!-- End Country filed -->
 
    <!-- start Status filed -->
    <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable">Status</label>
     <div class="col-sm-10 col-md-6">
    <select name="status">
   
    <option value="1" <?php if($item['Status']==1){echo'selected';} ?>>new</option>
    <option value="2" <?php if($item['Status']==2){echo'selected';} ?>>like new</option>
    <option value="3" <?php if($item['Status']==3){echo'selected';} ?>>Used</option>
    <option value="4" <?php if($item['Status']==4){echo'selected';} ?>>Very Old</option>
    </select>
     </div>
   </div>
   <!-- End Status filed -->
   <!-- start Member filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable">member</label>
     <div class="col-sm-10 col-md-6">
    <select name="member">
   
    <?php 
    $stmt= $con->prepare("SELECT * FROM users");
    $stmt->execute();
    $users= $stmt->fetchAll();
    foreach($users as $user){
      echo "<option value='". $user['UserID'] ."'"; 
      if($item['Member_ID']==$user['UserID']){echo'selected';} echo">". $user['Username'] ."</option>";
    }

?>
    </select>
     </div>
   </div>
   <!-- End Member filed -->
    <!-- start Categoryu filed -->
    <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable">Category</label>
     <div class="col-sm-10 col-md-6">
    <select name="category">
    <option value="">...</option>
    <?php 
    $stmt2= $con->prepare("SELECT * FROM categories");
    $stmt2->execute();
    $cats= $stmt2->fetchAll();
    foreach($cats as $cat){
      echo "<option value='". $cat['ID'] ."'";
      if($item['Cat_ID']==$cat['ID']){echo'selected';} echo">". $cat['Name'] ."</option>";
    }

?>
    </select>
     </div>
   </div>
   <!-- End Category filed -->
 
   <!-- End Rating filed -->
   <!-- start s filed -->
   <div class="form-group form-group-lg">
   
     <div class="col-sm-offset-2 col-sm-10">
       <input type="submit" value="Add Items" class= "btn btn-primary btn-sm"/>
     </div>
   </div>
   <!-- End username filed -->
 </form>
<?php
 // select database 7ata n7eta bl table
  $stmt = $con->prepare("SELECT comments.*, Users.Username AS Member
                         FROM 
                         comments
                          
                          INNER JOIN users
                          ON users.UserID= comments.user_id
                         WHERE item_id=? ");
  // Execute statement
  $stmt->execute(array($itemid));
  // fetchAll ye3ni jibe kel lbayenet mn data w 7eta bl row
  $rows= $stmt->fetchAll();
  if(!empty($rows)){
 ?>

 <h1 class="text-center">Manage [<?php echo $item['Name']  ?>] Comment</h1>
  
      <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
          <tr>
            <td>Comment</td>
            <td>User Name</td>
            <td>Added Date</td>
            <td>Control</td>
          </tr>
          <?php 
              foreach($rows as $row){
                echo"<tr>";
                  echo"<td>".$row['comment']."</td>";
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
            <?php  } ?>
</div>
      

<?php 
      //else Show Error Message
     }else{
       echo "<div class='container'>";
       $theMsg='<div class="alert alert-danger">Theres No Such ID</div>';
       redirectHome( $theMsg,'back');
       echo "</div>";
     }
 
} elseif($do=='Update'){
  echo "<h1 class='text-center'>Update Items</h1>";
  echo"<div class='container'>";
   if($_SERVER['REQUEST_METHOD']=='POST'){
     //Get Variable From The Form
     $id    = $_POST['itemid'];
     $name = $_POST['name'];
     $desc = $_POST['description'];
      $price = $_POST['price'];
      $country = $_POST['country'];
      $status = $_POST['status'];
      $member = $_POST['member'];
      $cat = $_POST['category'];
     
     
      //Validet the forme
      $formErrors=array();
      if(empty($name)){
        $formErrors[]= ' name Cant Be <strong>empty</strong>';
      }
      if(empty($desc)){
        $formErrors[]= 'Description Cant Be <strong>empty</strong>';
      
      }
      if(empty($price)){
        $formErrors[]= 'price Cant Be <strong>empty</strong>';
      }
      if(empty($country)){
        $formErrors[]= ' country Cant Be <strong>empty</strong>';
      }
      if($status==""){
        $formErrors[]= ' status Cant Be <strong>empty</strong>';
      }
      if($member==""){
        $formErrors[]= ' You Must Choose <strong>Member</strong>';
      }
      if($cat==""){
        $formErrors[]= ' You Must Choose <strong>Categories</strong>';
      }
      //Loop Into Errors Array And echo
      foreach($formErrors as $error){
        echo'<div class="alert alert-danger">'. $error.'</div>' ;  
      }
      // Check If Theres No Error Proceed The Update Operation
      if(empty($formErrors)){
//Update DataBase With this Info A5adta min texte 3ala database
         
     $stmt = $con->prepare("UPDATE item SET Name =? , Description= ?,Price=?,Country_Made=?,Status=?,Cat_ID=?,Member_ID=? WHERE ItemID =?");
     $stmt->execute(array($name,$desc,$price,$country,$status,$cat,$member,$id));
     //echo succes message
     $theMsg=  "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Upadte</div>';
      redirectHome( $theMsg,'back',3);
    }
     
   }else {
    $theMsg= '<div class="alert alert-danger">Sorry You Cant Browse This Page Directory</div>';
     redirectHome( $theMsg);
     
   }
    echo"</div>";
 
} elseif($do=='Delete'){
  echo "<h1 class='text-center'>Delete Items</h1>";
  echo"<div class='container'>";
    
    // Chec If The Request itemid Is Numeric & Get  The Tnteger Value Of It
    $itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?   intval($_GET['itemid']) :0;
      
    // Select All Data Depend On This ID
   $check= checkItem('ItemID','item',$itemid);
      
     
    //If Thers Such ID Show The Form
   if( $check >0){
      $stmt= $con->prepare("DELETE FROM item WHERE ItemID= :zid");
      $stmt->bindParam(":zid",$itemid);
      $stmt->execute();
      $theMsg= "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Delete</div>';
      redirectHome( $theMsg,'back');

  }else{
    $theMsg='<div class="alert alert-danger">This Id  Is Note Exist</div>';
    redirectHome( $theMsg);
  }
  echo'</div>';
  
}  elseif($do=='Approve'){
  echo "<h1 class='text-center'>Approve Items</h1>";
    echo"<div class='container'>";
      
      // Chec If The Request itemid Is Numeric & Get  The Tnteger Value Of It(intval :ye3ni btjib id)
      $itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?   intval($_GET['itemid']) :0;
        
      // Select All Data Depend On This ID
     $check= checkItem('ItemID','item',$itemid);
       
       
      //If Thers Such ID Show The Form
     if( $check >0){
        $stmt= $con->prepare("UPDATE item SET Approve=1 WHERE ItemID=?");
  
        $stmt->execute(array($itemid));

        $theMsg= "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Approve</div>';
        redirectHome( $theMsg);

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
ob_end_flush();
?>