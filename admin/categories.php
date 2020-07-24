<?php

/*
==> 
*/
ob_start();
session_start();

$pageTitle= 'Categories';

if (isset($_SESSION['Username'])){

 include 'init.php';
 
 $do = isset($_GET['do']) ? $_GET['do'] :'Manage';
 if($do=='Manage'){
   $sort='ASC';
   $sort_array = array('ASC','DESC');
   if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)) {
    $sort= $_GET['sort'];
   }
    $stmt2=$con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
    $stmt2->execute();
    $cats= $stmt2->fetchAll(); ?>
    <h1 class="text-center"> Manage Categories</h1>
       <div class="container categories">
          <div class="panel panel-default">
            <div class="panel-heading">
             <i class="fa fa-edit"></i> Manage Categories
              <div class="option pull-right">
               <i class="fa fa-sort"></i> Ordering: [
                <a class="<?php if($sort=='ASC'){echo 'active';}?>" href="?sort=ASC">Asc</a> |
                <a class="<?php if($sort=='DESC'){echo 'active';}?>" href="?sort=DESC">Desc</a>]
                <i class="fa fa-eye"></i> View: [
                <span class="active" data-view="full">Full</span>|
                <span>Classic</span>]
            </div>
            <div class="panel-body">
           <?php
           foreach($cats as $cat){
             echo "<div class='cat'>";
                echo "<div class='hidden-buttons'>";
                  echo "<a href='categories.php?do=Edit&catid=".$cat['ID']."' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>Edit</a>";
                  echo "<a href='categories.php?do=Delete&catid=".$cat['ID']."' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i>Delete</a>";
                  echo"</div>";
                echo "<h3>" . $cat['Name'].'</h3>';
                echo "<div class='full-view'>";
                    echo"<p>";if( $cat['Description']==''){echo'this categories has no description';}else{echo $cat['Description'];}echo"</p>";
                    if($cat['Visibility']==1){echo '<span class="visibility cat-span"><i class="fa fa-eye">Hidden</i></span>' ;} 
                    if($cat['Allow_Comment']==1){echo '<span class="commenting cat-span"><i class="fa fa-close">Comment Disabled</i></span>' ;}
                    if($cat['Allow_Ads']==1){echo '<span class="advertises cat-span"><i class="fa fa-close">Ads Disabled</i></span>' ;}
                echo"</div>";
             echo "</div>";
             echo"<hr>";
            }
           ?> 
            
            </div>
          </div></div>
          <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New Categories</a>
       

<?php } elseif($do=='Add'){ //1 ?>

<h1 class="text-center">Add New Categories</h1>
   <div class= "container"> 
   <form class= "form-horizontal" action="?do=Insert" method= "POST">
    
 <!-- start Name filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Name</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="name"  class= "form-control" autocomplete="off" required="requird" placeholder="Name Of Categories"/>
     </div>
   </div>
   <!-- End Name filed -->
   <!-- start Description filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Description</label>
     <div class="col-sm-10 col-md-6">
       
       <input type="text" name="description" class= " form-control"  placeholder="Describe The Categories" />
  
     </div>
   </div>
   <!-- End Description filed -->
   <!-- start Ordering filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Ordering</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="ordering"   class= "form-control"  placeholder="Number To Arrange The Categories "/>
     </div>
   </div>
   <!-- End Ordering filed -->
   <!-- start Vesibility filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Vesible</label>
     <div class="col-sm-10 col-md-6">
       <div>
         <input id="vis-yes" type="radio" name="visibility" value="0" checked/>
          <label for="vis-yes">Yes</label>
       </div>
       <div>
         <input id="vis-no" type="radio" name="visibility" value="1" />
          <label for="vis-no">No</label>
       </div>
     </div>
   </div>
   <!-- End Vesibility filed -->
   <!-- start Commenting filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Allow Commenting</label>
     <div class="col-sm-10 col-md-6">
       <div>
         <input id="com-yes" type="radio" name="commenting" value="0" checked/>
          <label for="com-yes">Yes</label>
       </div>
       <div>
         <input id="com-no" type="radio" name="commenting" value="1" />
          <label for="com-no">No</label>
       </div>
     </div>
   </div>
   <!-- End Commenting filed -->
    <!-- start Ads filed -->
    <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Allow Ads</label>
     <div class="col-sm-10 col-md-6">
       <div>
         <input id="ads-yes" type="radio" name="ads" value="0" checked/>
          <label for="ads-yes">Yes</label>
       </div>
       <div>
         <input id="ads-no" type="radio" name="ads" value="1" />
          <label for="ads-no">No</label>
       </div>
     </div>
   </div>
   <!-- End Ads filed -->
   <!-- start s filed -->
   <div class="form-group form-group-lg">
   
     <div class="col-sm-offset-2 col-sm-10">
       <input type="submit" value="Add Categories" class= "btn btn-primary btn-lg"/>
     </div>
   </div>
   <!-- End username filed -->
 </form>
</div>

<?php
 } elseif($do=='Insert'){ //2

    
 if($_SERVER['REQUEST_METHOD']=='POST'){
  echo "<h1 class='text-center'>Insert Categories</h1>";
  echo"<div class='container'>";
   //Get Variable From The Form
   
   $name      = $_POST['name'];
   $desc      = $_POST['description'];
   $order     = $_POST['ordering'];
    $visible  = $_POST['visibility'];
    $comment  = $_POST['commenting'];
    $ads      = $_POST['ads'];

     //Check If Exist in Database(bya3mil check 3ala name fi jadwal Categories iza mawjoud wher Name= $name)
    $check= checkItem("Name","categories",$name);
    if($check==1){
    $theMsg='<div class="alert alert-danger"> Sorry ThisCategories is Exist</div>';
      redirectHome( $theMsg,'back');
    } else{
//Insert Categories Info DataBase With this Info A5adta min texte 3ala database
        $stmt=$con->prepare("INSERT INTO
                              categories(Name,Description,Ordering,Visibility,Allow_Comment,Allow_Ads)
                              VALUES(:zname,:zdesc,:zorder,:zvisible,:zcomment,:zads)");
          $stmt->execute(array(
            //*** *jib mn l form($user) w 7eta bl zuser Aw fina na3mel 
          'zname'    => $name,
          'zdesc'    =>  $desc ,
          'zorder'   =>$order,
          'zvisible' => $visible,
          'zcomment' => $comment,
          'zads'     => $ads  
          ));                    
      
      //echo succes message
        $theMsg= "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Insert</div>';
        redirectHome( $theMsg,'back');
      }

 }else {
   echo "<div class='container'>";
   $theMsg= '<div class="alert alert-danger">Sorry You Cant Browse This Page Directory</div>';
   redirectHome( $theMsg,'back');
   echo "</div>";
 }
  echo"</div>";

} elseif($do=='Edit'){//3
   // Chec If The Request catid Is Numeric & Get  The Tnteger Value Of It=> ye3ni bjbli id
   $catid=isset($_GET['catid']) && is_numeric($_GET['catid']) ?   intval($_GET['catid']) :0;
       
   // Select All Data Depend On This Id
   $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");

   //Execute Query
   $stmt->execute(array( $catid));

   //Fetch The Data
   $cat= $stmt->fetch();

   // The Row Count
   $count = $stmt->rowCount();
   //If Thers Such ID Show The Form
   if($count>0){?>
<h1 class="text-center">Edit Categories</h1>
   <div class= "container"> 
   <form class= "form-horizontal" action="?do=Update" method= "POST">
   <input type="hidden" name="catid" value="<?php echo $catid ?>"/>
    
 <!-- start Name filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Name</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="name"  class= "form-control"  required="requird" placeholder="Name Of Categories" value="<?php echo $cat['Name'] ?>"/>
     </div>
   </div>
   <!-- End Name filed -->
   <!-- start Description filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Description</label>
     <div class="col-sm-10 col-md-6">
       
       <input type="text" name="description" class= " form-control"  placeholder="Describe The Categories" value="<?php echo $cat['Description'] ?>"/>
  
     </div>
   </div>
   <!-- End Description filed -->
   <!-- start Ordering filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Ordering</label>
     <div class="col-sm-10 col-md-6">
       <input type="text" name="ordering"   class= "form-control"  placeholder="Number To Arrange The Categories " value="<?php echo $cat['Ordering'] ?>"/>
     </div>
   </div>
   <!-- End Ordering filed -->
   <!-- start Vesibility filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Vesible</label>
     <div class="col-sm-10 col-md-6">
       <div>
         <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility']==0){echo 'checked';} ?>/>
          <label for="vis-yes">Yes</label>
       </div>
       <div>
         <input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility']==1){echo 'checked';} ?> />
          <label for="vis-no">No</label>
       </div>
     </div>
   </div>
   <!-- End Vesibility filed -->
   <!-- start Commenting filed -->
   <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Allow Commenting</label>
     <div class="col-sm-10 col-md-6">
       <div>
         <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment']==0){echo 'checked';} ?>/>
          <label for="com-yes">Yes</label>
       </div>
       <div>
         <input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment']==1){echo 'checked';} ?>/>
          <label for="com-no">No</label>
       </div>
     </div>
   </div>
   <!-- End Commenting filed -->
    <!-- start Ads filed -->
    <div class="form-group form-group-lg">
     <label class="col-sm-2 control-lable"> Allow Ads</label>
     <div class="col-sm-10 col-md-6">
       <div>
         <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads']==0){echo 'checked';} ?>/>
          <label for="ads-yes">Yes</label>
       </div>
       <div>
         <input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads']==1){echo 'checked';} ?>/>
          <label for="ads-no">No</label>
       </div>
     </div>
   </div>
   <!-- End Ads filed -->
   <!-- start s filed -->
   <div class="form-group form-group-lg">
   
     <div class="col-sm-offset-2 col-sm-10">
       <input type="submit" value="Save " class= "btn btn-primary btn-lg"/>
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
 
} elseif($do=='Update'){//4
  echo "<h1 class='text-center'>Update Categories</h1>";
  echo"<div class='container'>";
   if($_SERVER['REQUEST_METHOD']=='POST'){
     //Get Variable From The Form
      $id      = $_POST['catid'];
      $name    = $_POST['name'];
      $desc    = $_POST['description'];
      $order   = $_POST['ordering'];
      $visible = $_POST['visibility'];
      $comment = $_POST['commenting'];
      $ads     = $_POST['ads'];
    
//Update DataBase With this Info A5adta min texte 3ala database
         
     $stmt = $con->prepare("UPDATE Categories SET  
      Name =? , Description= ? , Ordering= ? , Visibility=?,Allow_Comment= ? , Allow_Ads= ?  WHERE ID =?");
     $stmt->execute(array($name,$desc,$order,$visible,$comment,$ads,$id));
     //echo succes message
     $theMsg=  "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Upadte</div>';
      redirectHome( $theMsg,'back',3);
   
   }else {
    $theMsg= '<div class="alert alert-danger">Sorry You Cant Browse This Page Directory</div>';
     redirectHome( $theMsg);
   }
   
   echo"</div>";
 
} elseif($do=='Delete'){
  echo "<h1 class='text-center'>Delete Categories</h1>";
        echo"<div class='container'>";
          
          // Chec If The Request catid Is Numeric & Get  The Tnteger Value Of It
          $catid=isset($_GET['catid']) && is_numeric($_GET['catid']) ?   intval($_GET['catid']) :0;
            
          // Select All Data Depend On This ID hone byt2aked mn ID isa nafsou yali 2ltlo 3ano delet
         $check= checkItem('ID','categories',$catid);
           
           
          //If Thers Such ID Show The Form
         if( $check >0){
            $stmt= $con->prepare("DELETE FROM categories WHERE ID= :zid");
            $stmt->bindParam(":zid",$catid);
            $stmt->execute();
            $theMsg= "<div class='alert alert-success'>". $stmt->rowCount().'Recorde Delete</div>';
            redirectHome( $theMsg,'back');
        
          }else{
            $theMsg='<div class="alert alert-danger">This Id  Is Note Exist</div>';
            redirectHome( $theMsg);
          }
          echo'</div>';    

}  

include $tpl .'footer.php'; 
}else{
 header('Location: indexe.php');
 exit();
}
ob_end_flush();
?>