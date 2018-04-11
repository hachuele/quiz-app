<?php
/***********************************************************************
 * DESCRIPTION: database.php contains all the functions required
 * to connect and (safely) interact with the MySQL database
 *                             ----
 * @author: Eric J. Hachuel
 * Copyright 2018 University of Southern California. All rights reserved.
 *
 * This software is experimental in nature and is provided on an AS-IS basis only.
 * The University SPECIFICALLY DISCLAIMS ALL WARRANTIES, EXPRESS AND IMPLIED,
 * INCLUDING WITHOUT LIMITATION ANY WARRANTY AS TO MERCHANTIBILITY OR FITNESS
 * FOR A PARTICULAR PURPOSE.
 *
 * This software may be reproduced and used for non-commercial purposes only,
 * so long as this copyright notice is reproduced with each such copy made.
 *
 * ----------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ***********************************************************************/

require_once('db_credentials.php');

/* function to create a connection to the database */
function db_connect(){
    $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    confirm_db_connect();
    return $connection;
}

/* function to disconnect from the database (if connection is active) */
function db_disconnect($connection){
    if(isset($connection)){
        mysqli_close($connection);
    }
}

/* function to confirm no error in db connection */
function confirm_db_connect() {
    if(mysqli_connect_errno()) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
    }
}

/* function to confirm data was retrieved through db query (in query_functions.php) */
function confirm_result_set($result_set) {
    if (!$result_set) {
        exit("The database query has failed, please try again.");
    }
}

/* prepare items prior to insert or update of a db table (avoid SQL injection) */
function db_escape($connection, $string) {
    return mysqli_real_escape_string($connection, $string);
}


?>
