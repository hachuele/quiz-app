<?php
/*************************************************************************
 * DESCRIPTION: initialize.php defines critical file paths, includes
 * required files and initiates the database connection
 * NOTE: this file is included at the top of all public pages
 *                             ----
 * @author: Eric J. Hachuel
 * Copyright 2018 University of Southern California. All rights reserved.
 *************************************************************************/

ob_start(); //output buffering on
// Assign file paths to PHP constants
// __FILE__ returns the current path to this file
// dirname() returns the path to the parent directory
define("PRIVATE_PATH", dirname(__FILE__));
define("PROJECT_PATH", dirname(PRIVATE_PATH));
define("PUBLIC_PATH", PROJECT_PATH . '/public');
define("SHARED_PATH", PRIVATE_PATH . '/shared');

// Assign the root URL to a PHP constant
// Use same document root as webserver
$public_end = strpos($_SERVER['SCRIPT_NAME'], '/public') + 7;
$doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
define("WWW_ROOT", $doc_root);

require_once('functions.php'); // load functions.php file
require_once('database.php');  // make database functions available
require_once('query_functions.php'); // make query functions available

// Initiate a database connection when initialize.php gets loaded
// This ensures every page will immediately log into the server
$db = db_connect();
?>



