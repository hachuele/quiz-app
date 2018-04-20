<?php require_once('initialize.php'); ?>
<?php
/***************************************************************************
* DESCRIPTION: "edit_quiz_general.php" creates a new admin generated quiz
* or edits an existing one
*                                   ---
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
session_start();

/* --------------------------------- DATA RETRIEVAL --------------------------------- */

$quiz_name_txt = $_POST['quiz_name_text_in'];
$quiz_descr_text_area = $_POST['quiz_descr_text_in'];
/* instantiate output array */
$output_edit_quiz = array();
$output_edit_quiz['error'] = 0;
/* check if request is to create new quiz or edit an existing one */
if($_POST['request_type'] == 'new_quiz'){
    /* check if quiz name already in use */
    if(is_name_used($quiz_name_txt)){
        $output_edit_quiz['error'] = "Error! The given quiz name already exists in the database.";
    }
    else {
        /* attempt to create the given quiz */
        $result_new_quiz = create_new_quiz($quiz_name_txt, $quiz_descr_text_area);

        if($result_new_quiz === true) {
            $new_quiz_id = mysqli_insert_id($db);
            $output_edit_quiz['new_assessment_id'] = $new_quiz_id;
        }
        else {
            $output_edit_quiz['error'] = $result_new_quiz;
        }
    }
} else if($_POST['request_type'] == 'edit_quiz'){
    /* get the assessment to edit */
    $assessment_edit_id = $_POST['assessment_id'];
    $assessment_edit_description = get_assessment_descr($assessment_edit_id);
    /* check if quiz name already in use */
    if(is_name_used($quiz_name_txt)){
        if($assessment_edit_description == $quiz_descr_text_area){
            $output_edit_quiz['error'] = "Oops! Please make sure you have made changes to the name or the description.";
        } else{
            /* attempt to edit the given quiz */
            $result_edit_quiz = edit_general_quiz($assessment_edit_id, $quiz_name_txt, $quiz_descr_text_area);
            if($result_edit_quiz != true) {
                $output_edit_quiz['error'] = $result_edit_quiz;
            }
        }
    }
    else {
        /* attempt to edit the given quiz */
        $result_edit_quiz = edit_general_quiz($assessment_edit_id, $quiz_name_txt, $quiz_descr_text_area);
        if($result_edit_quiz != true) {
            $output_edit_quiz['error'] = $result_edit_quiz;
        }
    }
}


echo json_encode($output_edit_quiz);

?>
