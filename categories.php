<?php include 'init.php';?>
 
<div class="container">
<h1 class="text-center">Show Category </h1>
<div class="row">
<?php 
   foreach(getItems('Cat_ID', $_GET['pageid']) as $item){
       echo '<div class="col-sm-6 col-md-3">';
       echo '<div class="card item-box" style="width: 18rem;">';
         echo '<span class="price-tag">'.$item['Price'].'</span>';
        echo '<img class="img-responsive  img-thumbnail" src="apple.jpg" alt="">';
        echo ' <div class="card-body">';
         echo '<h4 class="card-title"><a href="items.php?itemid='.$item['ItemID'].'">'.$item['Name'].'</a></h4>';
           echo '<p class="card-text">'.$item['Description'].'</p>';
           echo '</div>';
           echo '</div>';
           echo '</div>';
   }

?>
</div>
</div>

<?php include $tpl .'footer.php'; ?>
