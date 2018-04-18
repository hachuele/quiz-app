<?php require_once('initialize.php'); ?>
<?php
/*************************************************************************
* DESCRIPTION: "edit_quiz_choices.php" allows the admin user to create,
* edit, or delete choices for a given quiz and question.
*                                   ---
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
*************************************************************************/
session_start();

/* --------------------------------- DATA RETRIEVAL --------------------------------- */



/* instantiate output array */
$output_edit_choice_quiz = array();
$output_edit_choice_quiz['error'] = 0;
$error_found = 0;
$set_choice_correct = 0;
/* retrieve serialized form data */
$quiz_choice_text = $_POST['quiz_choice_text_in'];
$quiz_choice_is_correct = $_POST['is_choice_corr_check'];
$quiz_choice_descr_text = $_POST['choice_descr_text_in'];
$quiz_quest_is_multi = $_POST['is_multi'];
/* get question ID */
$question_id_choice_edit = $_POST['question_id'];
/* check if choice is set to correct */
if($quiz_choice_is_correct != null){
    $set_choice_correct = 1;
}
/* check if question is single valued (radio) and correct choice already set */
if($quiz_quest_is_multi == 0){
    if(is_corr_choice_set($question_id_choice_edit) && ($set_choice_correct == 1)){
        $output_edit_choice_quiz['error'] = "The question is of single-valued type (radio) and the correct choice has already been set. Cannot have multiple correct values!";
        $error_found = 1;
    }
}
/* check if request is to create, edit, or delete question */
if(($_POST['request_type'] == 'new_choice') && ($error_found == 0)){
    /* check if question text already used */
    if(is_choice_text_used($question_id_choice_edit, $quiz_choice_text)){
        $output_edit_choice_quiz['error'] = "Error! The given choice text already exists in a different choice of the current question.";
    }
    else{
        /* attempt to add a new choice */
        $result_new_choice = add_new_quiz_choice($question_id_choice_edit, $quiz_choice_text, $set_choice_correct, $quiz_choice_descr_text);
        if($result_new_choice != true) {
                $output_edit_choice_quiz['error'] = $result_new_choice;
        }
    }
}
else if(($_POST['request_type'] == 'edit_choice') && ($error_found == 0)){
    /* get choice ID to edit */
    $choice_edit_id = $_POST['choice_id'];
    /* check if question text already used (in different question) */
    if(is_choice_text_used_diff($question_id_choice_edit, $choice_edit_id, $quiz_choice_text)){
        $output_edit_choice_quiz['error'] = "Error! The given choice text already exists in a different choice of the current question.";
    }
    else{
        /* attempt to edit question */
        $result_edit_choice = edit_quiz_choice($choice_edit_id, $quiz_choice_text, $set_choice_correct, $quiz_choice_descr_text);
        if($result_edit_choice != true) {
                $output_edit_choice_quiz['error'] = $result_edit_choice;
        }
    }
}
//TODO: MOVE DELETE TO FIRST OPTION, ELSE DO REST
else if($_POST['request_type'] == 'delete_choice'){








}


echo json_encode($output_edit_choice_quiz);

?>
