<?php
  session_start();
  $pageTitle='Show Items';
  include 'init.php';
// Chec If The Request ItemID Is Numeric & Get  The Tnteger Value Of It
$itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?   intval($_GET['itemid']) :0;
       
// Select All Data Depend On This Id
$stmt = $con->prepare("SELECT 
                      item.*, categories.Name AS category_name,
                      users.Username
                      FROM item 
                      INNER JOIN categories
                      ON categories.ID = item.Cat_ID
                      INNER JOIN users
                      ON users.UserID= item.Member_ID
                      WHERE ItemID = ? ");

//Execute Query
$stmt->execute(array($itemid));

$count= $stmt->rowCount();
if($count>0){
//Fetch The Data
$item= $stmt->fetch();
   
  
?>

<h1 class="text-center"><?php echo $item['Name'] ?></h1>
<div class="container">
    <div class="row">
    <div class="col-md-3">
    <img class="img-responsive img-thumbnail " src="apple.jpg" alt="">
    </div>
    <div class="col-md-9 item-info">
    <h2><?php echo $item['Name'] ?></h2>
    <p><?php echo $item['Description'] ?></p>
    <ul class="list-unstyled">
        <li><i class="fa fa-calendar fa-fw"></i>
          <span>Added Date </span><?php echo $item['Add_Date'] ?></li>
        <li><i class="fa fa-money fa-fw"></i>
          <span>Price :</span><?php echo $item['Price'] ?>$</li>
        <li><i class="fa fa-building fa-fw"></i>
          <span>Made In : </span><?php echo $item['Country_Made'] ?></li>
        <li><i class="fa fa-tags fa-fw"></i>
          <span>Category : </span><a href ="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['category_name'] ?></a></li>
        <li><i class="fa fa-user fa-fw"></i>
          <span>Added By : </span><a href ="#"><?php echo $item['Username'] ?></a></li>
    </ul>
    </div>
    </div>
    <hr class="custom-hr">
    <?php  if(isset($_SESSION['user'])){// iza de5el 3an tari2e l user ?>
    <!-- Start Add Comment -->
    <div class="row">
      <div class="col-md-offset-3">
      <div class="add-comment">
      <h3>Add Your Comment </h3>
      <form action="<?php echo $_SERVER['PHP_SELF'].'?itemid='. $item['ItemID'] ?>" method="POST">
        <textarea name="comment"></textarea>
        <input class="btn btn-primary" type="submit" value="Add comment">
      </form>
      <?php 
         if($_SERVER['REQUEST_METHOD']=='POST'){
          $comment= filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
          $itemid=$item['ItemID'];
          $userid=$item['Member_ID'];
          if(! empty($comment)){
            $stmt= $con->prepare("INSERT INTO 
                                 comments(comment, status, comment_date, item_id, user_id ) 
                                 VALUE(:zcomment,0,NOW(),:zitemid ,:zuserid" );
            $stmt->execute(array(
             
              'zcomment'=>$comment,
              'zitemid'=>$itemid,
              'zuserid'=>$userid

            ));
            if($stmt){
              echo '<div class="alert alert-success">Comment Added</div>';
            }                   
                                 
          }
         }
      ?>
      </div>
      </div>
    </div>
      <!-- End Add Comment -->
    <?php } else{
      echo '<a href="login.php">Login</a> or Register to Add Comment';
    }
    ?>
    <hr class="custom-hr">
    <div class="row">
      <div class="col-md-3">
        User Image
    </div>
    <div class="col-md-9">
      User Comment
    </div>
    </div>
</div>

<?php
}else{
     echo 'there\'s No Such ID';
}
include $tpl .'footer.php'; 
?>
