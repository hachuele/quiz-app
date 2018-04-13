<?php
/***********************************************************************
 * DESCRIPTION: query_functions.php contains the php functions to
 * interact with the MySQL database
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

/* ------------------------------------------------------------------------------------------ */
/* -------------------------------- Data Retrieval Functions -------------------------------- */
/* ------------------------------------------------------------------------------------------ */

/* retrieve all available assessments */
function find_all_visible_courses(){
    # bring in $db from outside scope
    global $db;
    $sql_assessments = "SELECT * FROM assessments "; //whitespace after assessments required
    $sql_assessments .= "WHERE assessment_visible='1' ";
    $sql_assessments .= "ORDER BY assessment_id ASC";
    $result_course_set = mysqli_query($db, $sql_assessments);
    confirm_result_set($result_course_set);
    return $result_course_set;
}

/* retrieve all assessments */
function find_all_courses(){
    # bring in $db from outside scope
    global $db;
    $sql_assessments = "SELECT * FROM assessments "; //whitespace after assessments required
    $sql_assessments .= "ORDER BY assessment_id ASC";
    $result_course_set = mysqli_query($db, $sql_assessments);
    confirm_result_set($result_course_set);
    return $result_course_set;
}

/* retrieve the name of the assessment given its ID */
function get_assessment_name($assessment_id){
    global $db;
    $sql_assessment_name = "SELECT assessment_name FROM assessments ";
    $sql_assessment_name .= "WHERE assessment_id='" . db_escape($db, $assessment_id) . "'";
    $assessment_name_set = mysqli_query($db, $sql_assessment_name);
    confirm_result_set($assessment_name_set);
    $assessment_name = mysqli_fetch_assoc($assessment_name_set);
    return $assessment_name['assessment_name'];
}

/* retrieve the question text of the question given its ID */
function get_question_text($question_id){
    global $db;
    $sql_question_text = "SELECT question_text FROM questions ";
    $sql_question_text .= "WHERE question_id='" . db_escape($db, $question_id) . "'";
    $question_text_set = mysqli_query($db, $sql_question_text);
    confirm_result_set($question_text_set);
    $question_text = mysqli_fetch_assoc($question_text_set);
    return $question_text['question_text'];
}

/* retrieve assessment row set */
function get_assessment_row($assessment_id){
    global $db;
    $sql_assessment_row = "SELECT * FROM assessments ";
    $sql_assessment_row .= "WHERE assessment_id='" . db_escape($db, $assessment_id) . "'";
    $assessment_row_set = mysqli_query($db, $sql_assessment_row);
    confirm_result_set($assessment_row_set);
    $assessment_row = mysqli_fetch_assoc($assessment_row_set);
    return $assessment_row;
}

/* retrieve all questions for a given assessment ID */
function find_questions_by_assessment_id($assessment_id){
    global $db;
    $sql_questions = "SELECT * FROM questions ";
    $sql_questions .= "WHERE assessment_id='" . db_escape($db, $assessment_id) . "'";
    $sql_questions .=  "ORDER BY question_id ASC";
    $result_question_set = mysqli_query($db, $sql_questions);
    confirm_result_set($result_question_set);
    return $result_question_set;
}

/* retrieve all question IDs for a given assessment ID */
function get_question_ids_by_assessment_id($assessment_id){
    global $db;
    $sql_question_ids = "SELECT question_id FROM questions ";
    $sql_question_ids .= "WHERE assessment_id='" . db_escape($db, $assessment_id) . "'";
    $sql_question_ids .=  "ORDER BY question_id ASC";
    $result_question_id_set = mysqli_query($db, $sql_question_ids);
    confirm_result_set($result_question_id_set);
    return $result_question_id_set;
}

/* retrieve the number of questions for a given assessment ID */
function get_num_question_by_assessment_id($assessment_id){
    global $db;
    $sql_questions = "SELECT question_id FROM questions ";
    $sql_questions .= "WHERE assessment_id='" . db_escape($db, $assessment_id) . "'";
    $result_question_set = mysqli_query($db, $sql_questions);
    confirm_result_set($result_question_set);
    $num_questions = mysqli_num_rows($result_question_set);
    return $num_questions;
}

/* retrieve the choices for a given question ID */
function find_choices_by_question_id($question_id){
    global $db;
    $sql_choices = "SELECT * FROM question_choices ";
    $sql_choices .= "WHERE question_id='" . db_escape($db, $question_id) . "'";
    $sql_choices .=  "ORDER BY question_choice_id ASC";
    $result_choice_set = mysqli_query($db, $sql_choices);
    confirm_result_set($result_choice_set);
    return $result_choice_set;
}

/* retrieve the user assessment information for a given user ID */
function find_completed_quizzes_by_user($user_id){
    global $db;
    $sql_completed = "SELECT * FROM user_assessments ";
    $sql_completed .= "WHERE user_id='" . db_escape($db, $user_id) . "'";
    $sql_completed .= " AND user_assessment_is_complete= '1' ";
    $sql_completed .= "ORDER BY user_assessment_end_stamp DESC";
    $result_completed_set = mysqli_query($db, $sql_completed);
    confirm_result_set($result_completed_set);
    return $result_completed_set;
}

/* retrieve the user completed quiz IDs for a given user ID */
function find_completed_quiz_ids_by_user($user_id){
    global $db;
    $sql_completed = "SELECT assessment_id FROM user_assessments ";
    $sql_completed .= "WHERE user_id='" . db_escape($db, $user_id) . "'";
    $sql_completed .= " AND user_assessment_is_complete= '1' ";
    $sql_completed .= "ORDER BY user_assessment_end_stamp DESC";
    $result_completed_set = mysqli_query($db, $sql_completed);
    confirm_result_set($result_completed_set);
    return $result_completed_set;
}

/* retrieve the user in-progress quizzes for a given user ID */
function find_in_progress_quizzes_by_user($user_id){
    global $db;
    $sql_ip = "SELECT * FROM user_assessments ";
    $sql_ip .= "WHERE user_id='" . db_escape($db, $user_id) . "'";
    $sql_ip .= " AND user_assessment_is_complete= '0' ";
    $sql_ip .= "ORDER BY user_assessment_start_stamp DESC";
    $result_ip_set = mysqli_query($db, $sql_ip);
    confirm_result_set($result_ip_set);
    return $result_ip_set;
}

/* retrieve the user in-progress quizzes for a given assessment and user ID */
function get_in_progress_by_assessment_id($assessment_id, $user_id){
    global $db;
    $sql_ip = "SELECT * FROM user_assessments ";
    $sql_ip .= "WHERE assessment_id='" . db_escape($db, $assessment_id) . "'";
    $sql_ip .= "and user_id='" . db_escape($db, $user_id) . "'";
    $sql_ip .= " AND user_assessment_is_complete= '0' ";
    $result_ip_set = mysqli_query($db, $sql_ip);
    confirm_result_set($result_ip_set);
    return $result_ip_set;
}

/* function to get user answers for a particular user assessment id */
function get_user_answers_by_user_assessment_id($user_assessment_id){
    global $db;
    $sql_user_answers = "SELECT * FROM user_answers ";
    $sql_user_answers .= "WHERE user_assessment_id='" . db_escape($db, $user_assessment_id) . "'";
    $result_user_answers_set = mysqli_query($db, $sql_user_answers);
    confirm_result_set($result_user_answers_set);
    return $result_user_answers_set;
}

/* function to get user answers for a particular user assessment id and question id */
function get_user_answers_by_ua_q_id($user_assessment_id, $question_id){
    global $db;
    $sql_user_answers = "SELECT * FROM user_answers ";
    $sql_user_answers .= "WHERE user_assessment_id='" . db_escape($db, $user_assessment_id) . "'";
    $sql_user_answers .= " AND question_id='" . db_escape($db, $question_id) . "'";
    $result_user_answers_set = mysqli_query($db, $sql_user_answers);
    confirm_result_set($result_user_answers_set);
    return $result_user_answers_set;
}

/* function to get user assessment row for a particular user assessment id */
function get_user_assessment_by_ua_id($user_assessment_id){
    global $db;
    $sql_user_assessment = "SELECT * FROM user_assessments ";
    $sql_user_assessment .= "WHERE user_assessment_id='" . db_escape($db, $user_assessment_id) . "'";
    $result_user_assessment_set = mysqli_query($db, $sql_user_assessment);
    confirm_result_set($result_user_assessment_set);
    return $result_user_assessment_set;
}

/* function to check if quiz name already used */
function is_name_used($assessment_name){
    global $db;
    $sql_assessment_name = "SELECT assessment_name FROM assessments ";
    $sql_assessment_name .= "WHERE assessment_name='" . db_escape($db, $assessment_name) . "'";
    $result_assessment_name_set = mysqli_query($db, $sql_assessment_name);
    confirm_result_set($result_assessment_name_set);
    $num_assessments = mysqli_num_rows($result_assessment_name_set);
    if($num_assessments != 0){
        return true;
    } else{
        return false;
    }

}

/* function to get assessment description */
function get_assessment_descr($assessment_id){
    global $db;
    $sql_assessment_descr = "SELECT assessment_description FROM assessments ";
    $sql_assessment_descr .= "WHERE assessment_id='" . db_escape($db, $assessment_id) . "'";
    $result_assessment_descr_set = mysqli_query($db, $sql_assessment_descr);
    confirm_result_set($result_assessment_descr_set);
    $assessment_descr = mysqli_fetch_assoc($result_assessment_descr_set);
    return $assessment_descr['assessment_description'];

}

/* function to check if quiz name already used */
function is_question_text_used($assessment_id, $question_text){
    global $db;
    $sql_question_text = "SELECT question_text FROM questions ";
    $sql_question_text .= "WHERE assessment_id='" . db_escape($db, $assessment_id) . "' ";
    $sql_question_text .= " AND question_text='" . db_escape($db, $question_text) . "'";
    $result_question_text_set = mysqli_query($db, $sql_question_text);
    confirm_result_set($result_question_text_set);
    $num_question_text = mysqli_num_rows($result_question_text_set);
    if($num_question_text != 0){
        return true;
    } else{
        return false;
    }
}

/* function to check if quiz name already used (in different question) */
function is_question_text_used_diff($assessment_id, $question_id, $question_text){
    global $db;
    $sql_question_text = "SELECT question_text FROM questions ";
    $sql_question_text .= "WHERE assessment_id='" . db_escape($db, $assessment_id) . "' ";
    $sql_question_text .= " AND question_text='" . db_escape($db, $question_text) . "'";
    $sql_question_text .= " AND NOT question_id='" . db_escape($db, $question_id) . "'";
    $result_question_text_set = mysqli_query($db, $sql_question_text);
    confirm_result_set($result_question_text_set);
    $num_question_text = mysqli_num_rows($result_question_text_set);
    if($num_question_text != 0){
        return true;
    } else{
        return false;
    }
}


/* ------------------------------------------------------------------------------------------ */
/* -------------------------------- Data Insertion Functions -------------------------------- */
/* ------------------------------------------------------------------------------------------ */

/* -------------------------------- USER INPUTS -------------------------------- */

/* insert new user assessment into the database */
function insert_new_user_assessment($assessment_id, $user_id, $num_correct, $num_incorrect){
    global $db;
    $sql_insert_assessment = "INSERT INTO user_assessments ";
    $sql_insert_assessment .= "(assessment_id, user_id, user_assessment_num_correct, user_assessment_num_incorrect) ";
    $sql_insert_assessment .= "VALUES (";
    $sql_insert_assessment .= "'" . db_escape($db, $assessment_id) . "',";
    $sql_insert_assessment .= "'" . db_escape($db, $user_id) . "',";
    $sql_insert_assessment .= "'" . db_escape($db, $num_correct) . "',";
    $sql_insert_assessment .= "'" . db_escape($db, $num_incorrect) . "'";
    $sql_insert_assessment .= ")";
    $result = mysqli_query($db, $sql_insert_assessment);
    /* check if insert is successfull */
    if($result){
        return true;
    }
    else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

/* function to update a user assessment row */
function update_user_assessment($user_assessment_id, $latest_q_seq, $num_correct, $num_incorrect, $end_time, $is_complete){
    global $db;
    $sql_update_assessment = "UPDATE user_assessments SET ";
    $sql_update_assessment .= "latest_quest_sequential_num='" . db_escape($db, $latest_q_seq) . "', ";
    $sql_update_assessment .= "user_assessment_num_correct='" . db_escape($db, $num_correct) . "', ";
    $sql_update_assessment .= "user_assessment_num_incorrect='" . db_escape($db, $num_incorrect) . "', ";
    if($is_complete){
        $sql_update_assessment .= "user_assessment_end_stamp= CURRENT_TIMESTAMP, ";
    }
    else {
        $sql_update_assessment .= "user_assessment_end_stamp='" . db_escape($db, $end_time) . "', ";
    }
    $sql_update_assessment .= "user_assessment_is_complete='" . db_escape($db, $is_complete) . "' ";
    $sql_update_assessment .= "WHERE user_assessment_id='" . db_escape($db, $user_assessment_id) . "' ";
    $sql_update_assessment .= "LIMIT 1";
    $result = mysqli_query($db, $sql_update_assessment);
    /* check if update is successfull */
    if($result){
        return true;
    }
    else{
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

/* function to insert a new user submitted answers row */
function insert_user_answer($user_assessment_id, $assessment_id, $question_id, $question_choice_id){
    global $db;
    $sql_insert_answer = "INSERT INTO user_answers ";
    $sql_insert_answer .= "(user_assessment_id, assessment_id, question_id, question_choice_id) ";
    $sql_insert_answer .= "VALUES (";
    $sql_insert_answer .= "'" . db_escape($db, $user_assessment_id) . "',";
    $sql_insert_answer .= "'" . db_escape($db, $assessment_id) . "',";
    $sql_insert_answer .= "'" . db_escape($db, $question_id) . "',";
    $sql_insert_answer .= "'" . db_escape($db, $question_choice_id) . "'";
    $sql_insert_answer .= ")";
    $result = mysqli_query($db, $sql_insert_answer);
    /* check if insert is successfull */
    if($result){
        return true;
    }
    else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}



/* -------------------------------- ADMIN INPUTS -------------------------------- */


/* ------------------------- general quiz ------------------------- */

function validate_quiz_general($quiz_name, $quiz_description) {
    $errors = array();




}


function create_new_quiz($quiz_name, $quiz_description) {
    global $db;
    $sql_insert_new_quiz = "INSERT INTO assessments ";
    $sql_insert_new_quiz .= "(assessment_name, assessment_description) ";
    $sql_insert_new_quiz .= "VALUES (";
    $sql_insert_new_quiz .= "'" . db_escape($db, $quiz_name) . "',";
    $sql_insert_new_quiz .= "'" . db_escape($db, $quiz_description) . "'";
    $sql_insert_new_quiz .= ")";
    $result = mysqli_query($db, $sql_insert_new_quiz);
    /* check if insert is successfull */
    if($result){
        return true;
    }
    else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}



function edit_general_quiz($assessment_id, $quiz_name, $quiz_description) {
    global $db;
    $sql_edit_general_quiz = "UPDATE assessments ";
    $sql_edit_general_quiz .= "SET assessment_name='" . db_escape($db, $quiz_name) . "', ";
    $sql_edit_general_quiz .= " assessment_description='" . db_escape($db, $quiz_description) . "' ";
    $sql_edit_general_quiz .= " WHERE assessment_id='" . db_escape($db, $assessment_id) . "'";
    $result = mysqli_query($db, $sql_edit_general_quiz);
    /* check if insert is successfull */
    if($result){
        return true;
    }
    else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}


function edit_settings_quiz($assessment_id, $num_quest_show, $is_active) {
    global $db;
    $sql_edit_settings_quiz = "UPDATE assessments ";
    $sql_edit_settings_quiz .= "SET assessment_num_q_to_show='" . db_escape($db, $num_quest_show) . "', ";
    $sql_edit_settings_quiz .= " assessment_visible='" . db_escape($db, $is_active) . "' ";
    $sql_edit_settings_quiz .= " WHERE assessment_id='" . db_escape($db, $assessment_id) . "'";
    $result = mysqli_query($db, $sql_edit_settings_quiz);
    /* check if insert is successfull */
    if($result){
        return true;
    }
    else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}


/* ------------------------- question inputs ------------------------- */

function validate_quiz_question($q_text, $is_multivalued, $is_required) {
    $errors = array();


}


function add_new_quiz_question($assessment_id, $question_text, $is_multivalued, $is_required) {
    global $db;
    $sql_insert_new_quiz = "INSERT INTO questions ";
    $sql_insert_new_quiz .= "(assessment_id, question_text, question_multivalued, question_is_required) ";
    $sql_insert_new_quiz .= "VALUES (";
    $sql_insert_new_quiz .= "'" . db_escape($db, $assessment_id) . "',";
    $sql_insert_new_quiz .= "'" . db_escape($db, $question_text) . "',";
    $sql_insert_new_quiz .= "'" . db_escape($db, $is_multivalued) . "',";
    $sql_insert_new_quiz .= "'" . db_escape($db, $is_required) . "'";
    $sql_insert_new_quiz .= ")";
    $result = mysqli_query($db, $sql_insert_new_quiz);
    /* check if insert is successfull */
    if($result){
        return true;
    }
    else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}


function edit_quiz_question($question_id, $question_text, $is_multivalued, $is_required) {
    global $db;
    $sql_edit_question = "UPDATE questions ";
    $sql_edit_question .= "SET question_text='" . db_escape($db, $question_text) . "', ";
    $sql_edit_question .= " question_multivalued='" . db_escape($db, $is_multivalued) . "', ";
    $sql_edit_question .= " question_is_required='" . db_escape($db, $is_required) . "' ";
    $sql_edit_question .= " WHERE question_id='" . db_escape($db, $question_id) . "'";
    $result = mysqli_query($db, $sql_edit_question);
    /* check if insert is successfull */
    if($result){
        return true;
    }
    else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}


function delete_quiz_question($question_id) {


}



/* ------------------------- question choice inputs ------------------------- */


function validate_quiz_choice($choice_text, $c_details) {
    $errors = array();


}


function add_new_quiz_choice($question_id, $choice_text, $is_correct, $choice_details) {


}


function edit_quiz_choice($choice_id, $choice_text, $is_correct, $choice_details) {


}

function delete_quiz_choice($choice_id) {


}










?>
