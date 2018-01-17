<?php

    require_once('db_credentials.php');


    define("DB_USERNAME", "root");
    define("DB_PASSWORD", "root");
    define("DB_HOST", "localhost");
    define("DB_DATABASE", "hpc_quizzing_db");

    //Function to create a connection to the database
    function db_connect(){
//        $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $connection = mysqli_connect('localhost', 'root', 'root', 'hpc_quizzing_db');
        confirm_db_connect();
        return $connection;
    }

    //Function to disconnect from the database (if connection is active)
    function db_disconnect($connection){
        if(isset($connection)){
            mysqli_close($connection);
        }
    }

    function confirm_db_connect() {
        if(mysqli_connect_errno()) {
          $msg = "Database connection failed: ";
          $msg .= mysqli_connect_error();
          $msg .= " (" . mysqli_connect_errno() . ")";
          exit($msg);
        }
    }

    function confirm_result_set($result_set) {
        if (!$result_set) {
            exit("Database query failed.");
        }
    }



?>
