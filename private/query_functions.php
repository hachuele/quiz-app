<?php
/***************************************************************************
* DESCRIPTION: query_functions.php contains the php functions to
* interact with the MySQL database
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

/* ------------------------------------------------------------------------------------------ */
/* -------------------------------- Data Retrieval Functions -------------------------------- */
/* ------------------------------------------------------------------------------------------ */

/* retrieve all available assessments */
function find_all_visible_courses(){
    # bring in $db from outside scope
    global $db;
    $sql_assessments = "SELECT * FROM assessments "; //whitespace after assessments required
    $sql_assessments .= "WHERE assessment_visible='1' ";
    $sql_assessments .= " AND assessment_virtual_delete= '0' ";
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
    $sql_assessments .= "WHERE assessment_virtual_delete='0' ";
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
    $sql_assessment_row .= " AND assessment_virtual_delete= '0' ";
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
    $sql_questions .= " AND question_virtual_delete= '0' ";
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
    $sql_question_ids .= " AND question_virtual_delete= '0' ";
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
    $sql_questions .= " AND question_virtual_delete= '0' ";
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
    $sql_choices .= " AND question_choice_virtual_delete= '0' ";
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
/* virtually deleted quizzes are still shown to avoid data corruption */
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



/* ------------------------------------------------------------------------------------------ */
/* ---------------------------------- Questions about Data ---------------------------------- */
/* ------------------------------------------------------------------------------------------ */

/* function to check if quiz name already used */
function is_user_admin($user_id){
    global $db;
    $sql_user_admin = "SELECT admin_user_id FROM admin_users ";
    $sql_user_admin .= "WHERE admin_user_id='" . db_escape($db, $user_id) . "'";
    $result_user_admin = mysqli_query($db, $sql_user_admin);
    confirm_result_set($result_user_admin);
    $num_user_admin = mysqli_num_rows($result_user_admin);
    if($num_user_admin != 0){
        return true;
    } else{
        return false;
    }
}

/* function to check if quiz name already used */
function is_name_used($assessment_name){
    global $db;
    $sql_assessment_name = "SELECT assessment_name FROM assessments ";
    $sql_assessment_name .= "WHERE assessment_name='" . db_escape($db, $assessment_name) . "'";
    $sql_assessment_name .= " AND assessment_virtual_delete= '0' ";
    $result_assessment_name_set = mysqli_query($db, $sql_assessment_name);
    confirm_result_set($result_assessment_name_set);
    $num_assessments = mysqli_num_rows($result_assessment_name_set);
    if($num_assessments != 0){
        return true;
    } else{
        return false;
    }
}

/* function to check if quiz question text already used */
function is_question_text_used($assessment_id, $question_text){
    global $db;
    $sql_question_text = "SELECT question_text FROM questions ";
    $sql_question_text .= "WHERE assessment_id='" . db_escape($db, $assessment_id) . "' ";
    $sql_question_text .= " AND question_text='" . db_escape($db, $question_text) . "'";
    $sql_question_text .= " AND question_virtual_delete='0' ";
    $result_question_text_set = mysqli_query($db, $sql_question_text);
    confirm_result_set($result_question_text_set);
    $num_question_text = mysqli_num_rows($result_question_text_set);
    if($num_question_text != 0){
        return true;
    } else{
        return false;
    }
}

/* function to check if quiz question text already used (in different question) */
function is_question_text_used_diff($assessment_id, $question_id, $question_text){
    global $db;
    $sql_question_text = "SELECT question_text FROM questions ";
    $sql_question_text .= "WHERE assessment_id='" . db_escape($db, $assessment_id) . "' ";
    $sql_question_text .= " AND question_text='" . db_escape($db, $question_text) . "'";
    $sql_question_text .= " AND question_virtual_delete='0' ";
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


/* function to check if question is single valued and already has a correct choice set */
function is_corr_choice_set($question_id){
    global $db;
    $sql_is_corr_set = "SELECT question_choice_correct FROM question_choices ";
    $sql_is_corr_set .= "WHERE question_choice_correct='1' ";
    $sql_is_corr_set .= " AND question_id='" . db_escape($db, $question_id) . "'";
    $result_is_corr_set = mysqli_query($db, $sql_is_corr_set);
    confirm_result_set($result_is_corr_set);
    $num_corr = mysqli_num_rows($result_is_corr_set);
    if($num_corr != 0){
        return true;
    } else{
        return false;
    }
}


/* function to check if question has multiple correct answers set */
function is_mult_corr_choice_set($question_id){
    global $db;
    $sql_is_corr_set = "SELECT question_choice_correct FROM question_choices ";
    $sql_is_corr_set .= "WHERE question_choice_correct='1' ";
    $sql_is_corr_set .= " AND question_id='" . db_escape($db, $question_id) . "'";
    $result_is_corr_set = mysqli_query($db, $sql_is_corr_set);
    confirm_result_set($result_is_corr_set);
    $num_corr = mysqli_num_rows($result_is_corr_set);
    if($num_corr > 1){
        return true;
    } else{
        return false;
    }
}


/* function to check if choice text already used */
function is_choice_text_used($question_id, $choice_text){
    global $db;
    $sql_choice_text = "SELECT question_choice_text FROM question_choices ";
    $sql_choice_text .= "WHERE question_id='" . db_escape($db, $question_id) . "' ";
    $sql_choice_text .= " AND question_choice_text='" . db_escape($db, $choice_text) . "'";
    $sql_choice_text .= " AND question_choice_virtual_delete='0'";
    $result_choice_text_set = mysqli_query($db, $sql_choice_text);
    confirm_result_set($result_choice_text_set);
    $num_choice_text = mysqli_num_rows($result_choice_text_set);
    if($num_choice_text != 0){
        return true;
    } else{
        return false;
    }
}

/* function to check if choice text already used (in different choice) */
function is_choice_text_used_diff($question_id, $choice_id, $choice_text){
    global $db;
    $sql_choice_text = "SELECT question_choice_text FROM question_choices ";
    $sql_choice_text .= "WHERE question_id='" . db_escape($db, $question_id) . "' ";
    $sql_choice_text .= " AND question_choice_text='" . db_escape($db, $choice_text) . "'";
    $sql_choice_text .= " AND question_choice_virtual_delete='0'";
    $sql_choice_text .= " AND NOT question_choice_id='" . db_escape($db, $choice_id) . "'";
    $result_choice_text_set = mysqli_query($db, $sql_choice_text);
    confirm_result_set($result_choice_text_set);
    $num_choice_text = mysqli_num_rows($result_choice_text_set);
    if($num_choice_text != 0){
        return true;
    } else{
        return false;
    }
}


/* function to check if assessment has been submitted by any user */
function is_assessment_used_by_usr($assessment_id){
    global $db;
    $sql_used_assessment = "SELECT * FROM user_answers ";
    $sql_used_assessment .= " WHERE assessment_id='" . db_escape($db, $assessment_id) . "'";
    $result_used_assessment_set = mysqli_query($db, $sql_used_assessment);
    confirm_result_set($result_used_assessment_set);
    $num_used_assessments = mysqli_num_rows($result_used_assessment_set);
    if($num_used_assessments != 0){
        return true;
    } else{
        return false;
    }
}


/* function to check if question has been submitted by any user */
function is_question_used_by_usr($question_id){
    global $db;
    $sql_used_question = "SELECT * FROM user_answers ";
    $sql_used_question .= " WHERE question_id='" . db_escape($db, $question_id) . "'";
    $result_used_question_set = mysqli_query($db, $sql_used_question);
    confirm_result_set($result_used_question_set);
    $num_used_questions = mysqli_num_rows($result_used_question_set);
    if($num_used_questions != 0){
        return true;
    } else{
        return false;
    }
}


/* function to check if choice has been submitted by any user */
function is_choice_used_by_usr($choice_id){
    global $db;
    $sql_used_choice = "SELECT * FROM user_answers ";
    $sql_used_choice .= " WHERE question_choice_id='" . db_escape($db, $choice_id) . "'";
    $result_used_choice_set = mysqli_query($db, $sql_used_choice);
    confirm_result_set($result_used_choice_set);
    $num_used_choices = mysqli_num_rows($result_used_choice_set);
    if($num_used_choices != 0){
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

/* function to insert a new assessment into the database */
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

/* functuon to edit a quiz (name and description) */
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

/* function to delete a quiz */
function delete_quiz($assessment_id) {
    global $db;
    $sql_delete_quiz = "DELETE FROM assessments ";
    $sql_delete_quiz .= " WHERE assessment_id='" . db_escape($db, $assessment_id) . "'";
    $result_delete_quiz = mysqli_query($db, $sql_delete_quiz);
    /* check if delete is successfull */
    if($result_delete_quiz){
        return true;
    }
    else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}


function virtual_delete_quiz($assessment_id) {
    global $db;
    $sql_virt_del_assess = "UPDATE assessments ";
    $sql_virt_del_assess .= "SET assessment_virtual_delete='1' ";
    $sql_virt_del_assess .= " WHERE assessment_id='" . db_escape($db, $assessment_id) . "'";
    $result = mysqli_query($db, $sql_virt_del_assess);
    /* check if insert is successfull */
    if($result){
        return true;
    }
    else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}


/* ------------------------- question inputs ------------------------- */

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
    /* check if UPDATE is successfull */
    if($result){
        return true;
    }
    else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}


function delete_quiz_question($question_id) {
    global $db;
    $sql_delete_quest = "DELETE FROM questions ";
    $sql_delete_quest .= " WHERE question_id='" . db_escape($db, $question_id) . "'";
    $result_delete_quest = mysqli_query($db, $sql_delete_quest);
    /* check if delete is successfull */
    if($result_delete_quest){
        return true;
    }
    else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}


function virtual_delete_quiz_question($question_id) {
    global $db;
    $sql_virt_del_quest = "UPDATE questions ";
    $sql_virt_del_quest .= "SET question_virtual_delete='1' ";
    $sql_virt_del_quest .= " WHERE question_id='" . db_escape($db, $question_id) . "'";
    $result = mysqli_query($db, $sql_virt_del_quest);
    /* check if insert is successfull */
    if($result){
        return true;
    }
    else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}



/* ------------------------- question choice inputs ------------------------- */


function add_new_quiz_choice($question_id, $choice_text, $is_correct, $choice_details) {
    global $db;
    $sql_insert_new_choice = "INSERT INTO question_choices ";
    $sql_insert_new_choice .= "(question_id, question_choice_text, question_choice_correct, question_choice_reason) ";
    $sql_insert_new_choice .= "VALUES (";
    $sql_insert_new_choice .= "'" . db_escape($db, $question_id) . "',";
    $sql_insert_new_choice .= "'" . db_escape($db, $choice_text) . "',";
    $sql_insert_new_choice .= "'" . db_escape($db, $is_correct) . "',";
    $sql_insert_new_choice .= "'" . db_escape($db, $choice_details) . "'";
    $sql_insert_new_choice .= ")";
    $result = mysqli_query($db, $sql_insert_new_choice);
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


function edit_quiz_choice($choice_id, $choice_text, $is_correct, $choice_details) {
    global $db;
    $sql_edit_choice = "UPDATE question_choices ";
    $sql_edit_choice .= "SET question_choice_text='" . db_escape($db, $choice_text) . "', ";
    $sql_edit_choice .= " question_choice_correct='" . db_escape($db, $is_correct) . "', ";
    $sql_edit_choice .= " question_choice_reason='" . db_escape($db, $choice_details) . "' ";
    $sql_edit_choice .= " WHERE question_choice_id='" . db_escape($db, $choice_id) . "'";
    $result = mysqli_query($db, $sql_edit_choice);
    /* check if insert is successfull */
    if($result){
        return true;
    }
    else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}


function delete_quiz_choice($choice_id) {
    global $db;
    $sql_delete_choice = "DELETE FROM question_choices ";
    $sql_delete_choice .= " WHERE question_choice_id='" . db_escape($db, $choice_id) . "'";
    $result_delete_choice = mysqli_query($db, $sql_delete_choice);
    /* check if delete is successfull */
    if($result_delete_choice){
        return true;
    }
    else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}


function virtual_delete_quiz_choice($choice_id) {
    global $db;
    $sql_virt_del_choice = "UPDATE question_choices ";
    $sql_virt_del_choice .= "SET question_choice_virtual_delete='1' ";
    $sql_virt_del_choice .= " WHERE question_choice_id='" . db_escape($db, $choice_id) . "'";
    $result = mysqli_query($db, $sql_virt_del_choice);
    /* check if insert is successfull */
    if($result){
        return true;
    }
    else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}



?>
