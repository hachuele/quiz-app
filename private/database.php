<?php

    require_once('db_credentials.php');

    //Function to create a connection to the database
    function db_connect(){
        $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        return $connection;
    }

    //Function to disconnect from the database (if connection is active)
    function db_disconnect($connection){
        if(isset($connection)){
            mysqli_close($connection);
        }
    }



?>
