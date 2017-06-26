<?php
define( 'DB_SERVER' , 'localhost' );
define( 'DB_USERNAME' , 'YOUR USERNAME' );
define( 'DB_PASSWORD' , 'YOUR PASSWORD' );
define( 'DB_DATABASE' , 'SenseHat' );

class DB_Class {
    function __construct() {
        $connection = mysql_connect( DB_SERVER , DB_USERNAME , DB_PASSWORD ) or die( 'Oops connection error -> ' . mysql_error() );
        mysql_select_db( DB_DATABASE , $connection ) or die( 'Database error -> ' . mysql_error() );
    }
}
?>
