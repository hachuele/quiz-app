<?php
/***************************************************************************
 * DESCRIPTION: functions.php includes misc. functions to improve
 * the site's usability
 *                             ----
 * @author: Eric J. Hachuel
 * Copyright 2018 University of Southern California. All rights reserved.
 *
* DISCLAIMER.  USC MAKES NO EXPRESS OR IMPLIED WARRANTIES, EITHER IN FACT OR
* BY OPERATION OF LAW, BY STATUTE OR OTHERWISE, AND USC SPECIFICALLY AND
* EXPRESSLY DISCLAIMS ANY EXPRESS OR IMPLIED WARRANTY OF MERCHANTABILITY OR
* FITNESS FOR A PARTICULAR PURPOSE, VALIDITY OF THE SOFTWARE OR ANY OTHER
* INTELLECTUAL PROPERTY RIGHTS OR NON-INFRINGEMENT OF THE INTELLECTUAL
* PROPERTY OR OTHER RIGHTS OF ANY THIRD PARTY. SOFTWARE IS MADE AVAILABLE
* AS-IS.
****************************************************************************/

/** example usage: <img id="page_title_icon_img" src="<?php echo url_for('images/quiz_logo_1.png')?>"> **/
function url_for($script_path) {
  /* add the leading '/' if not present */
  if($script_path[0] != '/') {
    $script_path = "/" . $script_path;
  }
  return WWW_ROOT . $script_path;
}

/* escape special characters */
function h($string=""){
    return htmlspecialchars($string);

}

function u($string=""){
    return urlencode($string);
}

/* retrieve User ID */
function get_shib_ID(){
    $user_shib_id = "";
    /* USC Authentic ID Verification*/
    if($_SERVER["ShibuscNetID"]){
        /* retrieve the user ID */
        $user_shib_id = $_SERVER["ShibuscNetID"];
        /* error check to ensure user ID is found */
        if($user_shib_id == ""){
            echo "User not found. Please send an e-mail to hpc@usc.edu for further assistance.";
            exit();
        }
    }
    /* error checking: user ID environment variable */
    else{
        echo "Error has occured when retrieving User ID. Please send an e-mail to hpc@usc.edu for further assistance.";
        exit();
    }
    return $user_shib_id;
}

function error_404(){
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit();
}

function error_500(){
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    exit();
}

function redirect_to($location) {
    header("Location: " . $location);
    exit;
}

function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}


?>
