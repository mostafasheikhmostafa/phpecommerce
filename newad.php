<?php
  session_start();
  $pageTitle='Creat New Item';
  include 'init.php';
  if(isset($_SESSION['user'])){
   
    if($_SERVER['REQUEST_METHOD']=='POST'){
      
      $formErrors= array();
      $name     = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
      $desc     = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
      $price    = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
      $country  = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
      $status   = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
      $category = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
    if(strlen($name)<3){
      $formErrors[]= 'Item Title Must Be At Least 3 Characters';
    }
    if(strlen($desc )<10){
      $formErrors[]= 'Item Description Must Be At Least 10 Characters';
    }
  
    if(strlen($country)<2){
      $formErrors[]= 'Item Country Must Be At Least 2 Characters';
    }
    if(empty($price)){
      $formErrors[]= 'Item Price Must Be Not Empty';
    }
    if(empty($status)){
      $formErrors[]= 'Item Status Must Be Not Empty';
    }
    if(empty($category)){
      $formErrors[]= 'Item category Must Be Not Empty';
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
                'zcat' =>   $category,
                'zmember' => $_SESSION['uid']

                ));                    
            
            //echo succes message
            if($stmt){
              echo 'Item Added';
            }
          }

    }
    
?>
<h1 class="text-center"><?php echo $pageTitle ?></h1>
<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
           <div class="panel-heading"><?php echo $pageTitle ?></div>
           <div class="panel-body">
           <div class="row">
             <div class="col-md-8">
             <form class= "form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method= "POST">
    
    <!-- start Name filed -->
      <div class="form-group form-group-lg">
        <label class="col-sm-2 control-lable"> Name</label>
        <div class="col-sm-10 col-md-9">
          <input type="text" name="name"  class= "form-control live-name"
             placeholder="Name Of Items" />
        </div>
      </div>
      <!-- End Name filed -->
      <!-- start Description filed -->
      <div class="form-group form-group-lg">
        <label class="col-sm-2 control-lable">Description</label>
        <div class="col-sm-10 col-md-9">
          <input type="text" name="description"  class= "form-control live-desc"   
           placeholder="Description Of Items"/>
        </div>
      </div>
      <!-- End Description filed -->
      <!-- start Price filed -->
      <div class="form-group form-group-lg">
        <label class="col-sm-2 control-lable">Price</label>
        <div class="col-sm-10 col-md-9">
          <input type="text" name="price"  class= "form-control live-price"  
           placeholder="Price Of Items" />
        </div>
      </div>
      <!-- End Price filed -->
      <!-- start Country filed -->
      <div class="form-group form-group-lg">
        <label class="col-sm-2 control-lable">Country</label>
        <div class="col-sm-10 col-md-9">
          <input type="text" name="country"  class= "form-control"    placeholder="Country Of the Made"/>
        </div>
      </div>
      <!-- End Country filed -->
    
       <!-- start Status filed -->
       <div class="form-group form-group-lg">
        <label class="col-sm-2 control-lable">Status</label>
        <div class="col-sm-10 col-md-9">
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
      
      <!-- End Member filed -->
       <!-- start Categoryu filed -->
       <div class="form-group form-group-lg">
        <label class="col-sm-2 control-lable">Category</label>
        <div class="col-sm-10 col-md-9">
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
             <div class="col-md-4">
                        <div class="card item-box live-preview" style="width: 18rem;">
                    <span class="price-tag "> 0$</span>
                <img class="img-responsive  img-thumbnail" src="apple.jpg" alt="">
                    <div class="card-body">
                    <h4 class="card-title ">Titele</h4>
                    <p class="card-text ">Description</p>
                    </div>
                    </div>
             </div>
           </div>
           <!-- Start Loopiong Trough Errors-->
           <?php 
             if(! empty($formErrors)){
               foreach($formErrors as $error){
                 echo '<div class="alert alert-danger">'. $error. '</div>';
               }
             }
           ?>
           <!-- End Loopiong Trough Errors-->
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
