<?php

//Category => [Manage | Edit |Update | Add | Insert | Delet | Stats]

$do = isset($_GET['do']) ? $_GET['do'] :'Manage';


//If the page is Main Page
if($do=='Manage'){

    echo 'Welcome you are in Manage Categoris Page';
    echo '<a href ="page.php?do=Add">Add New Category +</a>';

}elseif ($do=='Add'){

    echo 'Welcome you are in Add Category Page';
}else {
    echo 'Error there\'s No page withs this Name';
}

?>