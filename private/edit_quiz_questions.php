<?php require_once('initialize.php'); ?>
<?php
/**************************************************************************
* DESCRIPTION: "edit_quiz_questions.php" allows the admin user to create,
* edit, or delete questions for a given quiz.
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

/* instantiate output array */
$output_edit_quest_quiz = array();
$output_edit_quest_quiz['error'] = 0;

$set_question_multi = 0;
$set_question_req = 0;

/* get the assessment to edit and form content */
$assessment_settings_id = $_POST['assessment_id'];
$quiz_question_text = $_POST['quiz_question_text_in'];
$quiz_question_is_multi = $_POST['is_quest_multi_check'];
$quiz_question_is_req = $_POST['is_quest_req_check'];
/* check if question is set to multivalued */
if($quiz_question_is_multi != null){
    $set_question_multi = 1;
}
/* check if question is set to required */
if($quiz_question_is_req != null){
    $set_question_req = 1;
}
/* check if request is to create, edit, or delete question */
if($_POST['request_type'] == 'new_question'){
    /* check if question text already used */
    if(is_question_text_used($assessment_settings_id, $quiz_question_text)){
        $output_edit_quest_quiz['error'] = "Error! The given question text already exists in a different question of the current quiz.";
    }
    else{
        /* attempt to add a new question */
        $result_new_question = add_new_quiz_question($assessment_settings_id, $quiz_question_text, $set_question_multi, $set_question_req);
        if($result_new_question != true) {
                $output_edit_quest_quiz['error'] = $result_new_question;
        }
    }
}
else if($_POST['request_type'] == 'edit_question'){
    $question_edit_id = $_POST['question_id'];
    /* check if question text already used (in different question) */
    if(is_question_text_used_diff($assessment_settings_id, $question_edit_id, $quiz_question_text)){
        $output_edit_quest_quiz['error'] = "Error! The given question text already exists in a different question of the current quiz.";
    }
    /* check if changing to radio and alread more than one correct choice */
    else if($set_question_multi == 0 && is_mult_corr_choice_set($question_edit_id)){
        $output_edit_quest_quiz['error'] = "Error! You are attempting to change question to single-valued (radio), however, there are already multiple correct choices set. Please modify your choices so there is a single correct answer before changing the question type.";
    }
    else{
        /* attempt to edit question */
        $result_edit_question = edit_quiz_question($question_edit_id, $quiz_question_text, $set_question_multi, $set_question_req);
        if($result_edit_question != true) {
                $output_edit_quest_quiz['error'] = $result_edit_question;
        }
    }
}


echo json_encode($output_edit_quest_quiz);

?>
