<?php
    #FUNCTION FOR EASY ACCESS OF PAGES WITHIN THE FOLDER STRUCTURE
    /** EXAMPLE TO RETRIEVE IMAGE: <img id="page_title_icon_img" src="<?php echo url_for('images/quizz_logo_1.png')?>"> **/
    function url_for($script_path) {
      // add the leading '/' if not present
      if($script_path[0] != '/') {
        $script_path = "/" . $script_path;
      }
      return WWW_ROOT . $script_path;
    }


    #USE TO ESCAPE SPECIAL CHARACTERS (XSS) - USED WHEN ECHO DYNAMIC DATA
    function h($string=""){
        return htmlspecialchars($string);

    }

    function u($string=""){
        return urlencode($string);
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

    /* helper function to push into an associative array */
    function array_push_assoc($array, $key, $value){
        $array[$key] = $value;
        return $array;
    }

?>
