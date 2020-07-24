<?php
  session_start();
  $pageTitle='Profile';
  include 'init.php';
  if(isset($_SESSION['user'])){
    //  $sessionUser= Username
    $getUser= $con->prepare("SELECT * FROM users WHERE Username=?");
    $getUser->execute(array( $sessionUser));
    $info= $getUser->fetch();
  
?>
<h1 class="text-center">My Profile</h1>
<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
           <div class="panel-heading">My Information</div>
           <div class="panel-body">
           <ul class="list-unstyled">
          <li><i class="fa fa-unlock-alt fa-fw"></i>
              <span>Login Name</span>: <?php echo $info['Username'] ?></li>
          <li><i class="fa fa-envelope-o fa-fw"></i>
              <span>Email</span>: <?php echo $info['Email'] ?></li>
          <li><i class="fa fa-user fa-fw"></i>
              <span>Full Name</span>: <?php echo $info['FullName'] ?></li>
          <li><i class="fa fa-calendar fa-fw"></i>
              <span>Register Date</span>: <?php echo $info['Date'] ?></li>
          <li><i class="fa fa-tags fa-fw"></i>
             <span> Fav Category</span>:</li>
           </ul>
           </div>
        </div>
    </div>
</div>
<div class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
           <div class="panel-heading">My Ads</div>
           <div class="panel-body">
           
                <?php 
                // jebli item lmember yali UserID taba3o= Member_ID
                if(! empty(getItems('Member_ID', $info['UserID']))){
                    echo '<div class= "row">';
                foreach(getItems('Member_ID', $info['UserID']) as $item){
                    echo '<div class="col-sm-6 col-md-3">';
                    echo '<div class="card item-box" style="width: 18rem;">';
                        echo '<span class="price-tag">'.$item['Price'].'$</span>';
                        echo '<img class="img-responsive  img-thumbnail" src="apple.jpg" alt="">';
                        echo ' <div class="card-body">';
                        echo '<h4 class="card-title"><a href="items.php?itemid='.$item['ItemID'].'">'.$item['Name'].'</a></h5>';
                        echo '<p class="card-text">'.$item['Description'].'</p>';
                        echo '<div class="date">'.$item['Add_Date'].'</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                }
                echo '<div>';
            }else{
                echo 'Sorry There\' No Ads To Show, Crerate <a href="newad.php">New Add</a>';
            }

                ?>
                
           </div>
        </div>
    </div>
</div>
<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
           <div class="panel-heading">Latest Comments</div>
           <div class="panel-body">
           <?php
 
  $stmt = $con->prepare("SELECT comment FROM comments WHERE user_id=? ");
  // Execute statement
  $stmt->execute(array($info['UserID']));
  // fetchAll ye3ni jibe kel lbayenet mn data w 7eta bl row
  $comments= $stmt->fetchAll();
  if(! empty($comments)){
      foreach($comments as $comment){
          echo '<p>'.$comment['comment'].'</p>';
      }

  }else{
      echo 'there\'s No Comments To Show';
  }

  ?>
           </div>
        </div>
    </div>
</div>


<?php
  }else{
      header('Location:login.php');
      exit();
  }
include $tpl .'footer.php'; ?>
