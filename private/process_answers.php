<?php require_once('initialize.php'); ?>
<?php
/***********************************************************************
 * DESCRIPTION: Processes user submitted answers through the Ajax
 * calls, returns answer details as data to the user, stores
 * user answers.
 * --------------------------------------------------------------
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
session_start();

/* --------------------------------- ANSWER RETRIEVAL --------------------------------- */

/* variable to store whether correct answer submitted */
$is_incorrect = 0;
/* Instantiate arrays */
$choices_array = array(); // the set of choices for a particular question
$results_array = array(); // what the user has selected
$answers_data = array(); // the output
$response_text_array = array(); // to store the answer details for display
$output_array = array(); // array to encode for ajax
$choice_ids = array(); // store choice ids
/* Get the question type */
$question_type = $_POST['question_type'];
$question_id = $_POST['question_id'];
//make database calls
$choice_set = find_choices_by_question_id($_POST['question_id']);
$num_choices = mysqli_num_rows($choice_set);

while($choice = mysqli_fetch_array($choice_set, MYSQLI_BOTH)){
    array_push($choices_array, $choice);
}
/* Format for checkbox parameters  */
if($question_type == 'checkbox'){
    for($i = 0; $i < $num_choices; $i++){
        array_push($choice_ids, $choices_array[$i]['question_choice_id']);
        array_push($results_array, $_POST['check_' . ($i + 1)]); // value is null if not selected; value is 1 if selected ($results_array[$i] == 1)
        if(($results_array[$i] == 1) && ($choices_array[$i]['question_choice_correct'] == 1)){
            array_push($answers_data, 'correct_selected'); // a correct answer that was correctly selected (CORRECT ANSWER)
        } else if (($results_array[$i] == 0) && ($choices_array[$i]['question_choice_correct'] == 0)){
            array_push($answers_data, 'incorrect_not_selected'); // an incorrect answer that was correctly NOT selected (CORRECT ANSWER)
        } else if (($results_array[$i] == 1) && ($choices_array[$i]['question_choice_correct'] == 0)){
            array_push($answers_data, 'incorrect_selected'); // an incorrect answer that was incorrectly selected (WRONG ANSWER)
            $is_incorrect = 1;
        }else{
            array_push($answers_data, 'correct_not_selected'); // a correct answer that was incorrectly NOT selected (WRONG ANSWER)
            $is_incorrect = 1;
        }
        array_push($response_text_array, $choices_array[$i]['question_choice_reason']);
    }
} else {
    array_push($results_array, $_POST['radio_1']); // the value is the selected radio button ($results_array[0])
    for($i = 0; $i < $num_choices; $i++){
        array_push($choice_ids, $choices_array[$i]['question_choice_id']);
        if(($results_array[0] == ($i + 1)) && ($choices_array[$i]['question_choice_correct'] == 1)){
            array_push($answers_data, 'correct_selected'); // a correct answer that was correctly selected
        }
        else if(($results_array[0] != ($i + 1)) && ($choices_array[$i]['question_choice_correct'] == 1)){
            array_push($answers_data, 'correct_not_selected'); // a correct answer that was incorrectly not selected
            $is_incorrect = 1;
        }
        else if(($results_array[0] == ($i + 1)) && ($choices_array[$i]['question_choice_correct'] == 0)){
            array_push($answers_data, 'incorrect_selected'); // an incorrect answer that was incorrectly selected
            $is_incorrect = 1;
        }
        else{
            array_push($answers_data, 'incorrect_not_selected'); // an incorrect answer that was correctly NOT selected
        }
        array_push($response_text_array, $choices_array[$i]['question_choice_reason']);
    }
}

$output_array['num_choices'] = $num_choices;
$output_array['choice_ids'] = $choice_ids;
$output_array['user_selection_details'] = $answers_data;
$output_array['reponse_details'] = $response_text_array;

/* ----------------------------------- DATA INSERT ----------------------------------- */

$assessment_id = $_SESSION["assessment_id"];
$user_id = $_SESSION["user_id"];
$question_index = $_POST['question_index'];

$insert = '';
$is_complete = 0;
$end_stamp = NULL;

/* if this is the first completed question of the quizz, insert new row into user_assessments */
if($question_index == 'first'){
    $num_incorrect = 0;
    $num_correct = 0;
    if($is_incorrect){
        $num_incorrect = 1;
    } else{
        $num_correct = 1;
    }
    $insert = insert_new_user_assessment($assessment_id, $user_id, $num_correct, $num_incorrect);
    $new_user_assessment_id = mysqli_insert_id($db);
}
else{
    /* NOTE: only one row output (max) for a given in progress assessment id and user id */
    $user_assessments_set = get_in_progress_by_assessment_id($assessment_id, $user_id);
    /* fecth the completed information (response information and status) */
    $user_assessments_row = mysqli_fetch_assoc($user_assessments_set);
    $latest_q_seq = $user_assessments_row['latest_quest_sequential_num'];
    $latest_q_seq++;
    $user_assessment_id = $user_assessments_row['user_assessment_id'];
    $num_correct_so_far = $user_assessments_row['user_assessment_num_correct'];
    $num_incorrect_so_far = $user_assessments_row['user_assessment_num_incorrect'];

    if($is_incorrect){
        $num_correct = intval($num_correct_so_far);
        $num_incorrect = intval($num_incorrect_so_far) + 1;
    } else{
        $num_correct = intval($num_correct_so_far) + 1;
        $num_incorrect = intval($num_incorrect_so_far);
    }
    /* if last question, add timestamp and complete signal */
    if($question_index == 'last'){
        $end_stamp = date('Y-m-d G:i:s'); //TODO: NOT WORKING
        $is_complete = 1;
    }
    $insert = update_user_assessment($user_assessment_id, $latest_q_seq, $num_correct, $num_incorrect, $end_time, $is_complete);
    $new_user_assessment_id = $user_assessment_id;
}
/* if insert is successfull, get newly created ID for further inserts into user_answers table */
if($insert == true){
    /* if question type is checkbox, loop through potential answers and insert new row for each */
    if($question_type == 'checkbox'){
        for($i = 0; $i < $num_choices; $i++){
            /* if the choice is selected --> insert */
            if($results_array[$i] == 1){
                $q_choice_id = $choices_array[$i]['question_choice_id'];
                $insert_answer = insert_user_answer($new_user_assessment_id, $assessment_id, $question_id, $q_choice_id);
            }
        }
    }
    else{
        for($i = 0; $i < $num_choices; $i++){
            /* if the choice is selected --> insert */
            if($results_array[0] == ($i + 1)){
                $q_choice_id = $choices_array[$i]['question_choice_id'];
                $insert_answer = insert_user_answer($new_user_assessment_id, $assessment_id, $question_id, $q_choice_id);
                break;
            }
        }
    }
}

/* ----------------------------------- OUTPUT ----------------------------------- */
$output_array['user_assessment_id'] = $new_user_assessment_id;
echo json_encode($output_array);

?>
