<?php

function lang( $phrase ){
    static $lang = array(

        //Navbar linck
        'Home_Admin'  => 'Home',
        'CATEGORISE'  => 'Categorise',
        'ITEMS'       => 'Items',
        'MEMBERS'     => 'Members',
        'COMMENTS'  => 'Comments',
        'STATISTICS'  => 'Statistics',
        'LOGS'        => 'Logs',
          
    );
    return $lang[$phrase];
}