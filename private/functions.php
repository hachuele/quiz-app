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

?>
