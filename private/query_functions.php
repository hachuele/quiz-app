<?php

/******************************************************************
 * DESCRIPTION:
 *
 *                             ----
 * @author: Eric J. Hachuel
 * University of Southern California, High-Performance Computing
 ******************************************************************/


/* -------------------------------- Data Retrieval Functions -------------------------------- */

    function find_all_visible_courses(){
        # bring in $db from outside scope (since not passed in as argument)
        global $db;
        $sql_assessments = "SELECT * FROM assessments "; //whitespace after assessments required
        $sql_assessments .= "WHERE assessment_visible=1 ";
        $sql_assessments .= "ORDER BY assessment_id ASC";
        # GET COURSE SET FROM TABLE
        $result_course_set = mysqli_query($db, $sql_assessments);
        confirm_result_set($result_course_set);
        return $result_course_set;

    }

    function get_assessment_name($assessment_id){
        global $db;
        $sql_assessment_name = "SELECT assessment_name FROM assessments ";
        $sql_assessment_name .= "WHERE assessment_id='" . $assessment_id . "'";
        $assessment_name_set = mysqli_query($db, $sql_assessment_name);
        confirm_result_set($assessment_name_set);
        $assessment_name = mysqli_fetch_assoc($assessment_name_set);
        return $assessment_name['assessment_name'];
    }

    function find_questions_by_assessment_id($assessment_id){
        global $db;
        $sql_questions = "SELECT * FROM questions ";
        $sql_questions .= "WHERE assessment_id='" . $assessment_id . "'";
        $sql_questions .=  "ORDER BY question_id ASC";
        # GET QUESTION SET FROM TABLE
        $result_question_set = mysqli_query($db, $sql_questions);
        confirm_result_set($result_question_set);
        return $result_question_set;
    }

    function get_question_ids_by_assessment_id($assessment_id){
        global $db;
        $sql_question_ids = "SELECT question_id FROM questions ";
        $sql_question_ids .= "WHERE assessment_id='" . $assessment_id . "'";
        $sql_question_ids .=  "ORDER BY question_id ASC";
        # GET QUESTION SET FROM TABLE
        $result_question_id_set = mysqli_query($db, $sql_question_ids);
        confirm_result_set($result_question_id_set);
        return $result_question_id_set;
    }


    function get_num_question_by_assessment_id($assessment_id){
        global $db;
        $sql_questions = "SELECT question_id FROM questions ";
        $sql_questions .= "WHERE assessment_id='" . $assessment_id . "'";
        $result_question_set = mysqli_query($db, $sql_questions);
        confirm_result_set($result_question_set);
        $num_questions = mysqli_num_rows($result_question_set);
        return $num_questions;
    }


    function find_choices_by_question_id($question_id){
        global $db;
        $sql_choices = "SELECT * FROM question_choices "; //NOTE: Space after table name CRITICAL
        $sql_choices .= "WHERE question_id='" . $question_id . "'";
        $sql_choices .=  "ORDER BY question_choice_id ASC";
        # GET CHOICE SET FROM TABLE
        $result_choice_set = mysqli_query($db, $sql_choices);
        confirm_result_set($result_choice_set);
        return $result_choice_set;
    }


    function find_completed_quizzes_by_user($user_id){
        global $db;
        $sql_completed = "SELECT * FROM user_assessments "; //NOTE: Space after table name CRITICAL
        $sql_completed .= "WHERE user_id='" . $user_id . "'";
        $sql_completed .= " AND user_assessment_is_complete= 1 ";
        $sql_completed .= "ORDER BY user_assessment_end_stamp DESC";
        # GET COMPLETED SET FROM TABLE
        $result_completed_set = mysqli_query($db, $sql_completed);
        confirm_result_set($result_completed_set);
        return $result_completed_set;
    }

    function find_completed_quizz_ids_by_user($user_id){
        global $db;
        $sql_completed = "SELECT assessment_id FROM user_assessments "; //NOTE: Space after table name CRITICAL
        $sql_completed .= "WHERE user_id='" . $user_id . "'";
        $sql_completed .= " AND user_assessment_is_complete= 1 ";
        $sql_completed .= "ORDER BY user_assessment_end_stamp DESC";
        # GET COMPLETED IDs SET FROM TABLE
        $result_completed_set = mysqli_query($db, $sql_completed);
        confirm_result_set($result_completed_set);
        return $result_completed_set;
    }


    function find_in_progress_quizzes_by_user($user_id){
        global $db;
        $sql_ip = "SELECT * FROM user_assessments "; //NOTE: Space after table name CRITICAL
        $sql_ip .= "WHERE user_id='" . $user_id . "'";
        $sql_ip .= " AND user_assessment_is_complete= 0 ";
        $sql_ip .= "ORDER BY user_assessment_start_stamp DESC";
        # GET IN PROGRESS SET FROM TABLE
        $result_ip_set = mysqli_query($db, $sql_ip);
        confirm_result_set($result_ip_set);
        return $result_ip_set;
    }








/* -------------------------------- Data Insertion Functions -------------------------------- */

//NEED FOLLOWING DATA: user_assessment_id, assessment_id, question_id, question_choice_id [USER_ANSWERS TABLE]
//NEED FOLLOWING: assessment_id, user_id [USER ASESSMENTS]


//The SQL UPDATE Statement to add to existing rows


    function insert_user_answer($assessment_id, $question_id, $question_choice_id){
        global $db;
        $sql_insert_answer = "INSERT INTO user_answers ";
        $sql_insert_answer .= "(assessment_id, question_id, question_choice_id) ";
        $sql_insert_answer .= "VALUES (";
        $sql_insert_answer .= "'" . db_escape($db, $subject['menu_name']) . "',";
        $sql_insert_answer .= "'" . db_escape($db, $subject['position']) . "',";
        $sql_insert_answer .= "'" . db_escape($db, $subject['visible']) . "'";
        $sql_insert_answer .= ")";
        $result = mysqli_query($db, $sql);





        $sql_insert_answer .= "WHERE question_id='" . $question_id . "'";
        $sql_insert_answer .=  "ORDER BY question_choice_id ASC";
        # GET CHOICE SET FROM TABLE
        $result_choice_set = mysqli_query($db, $sql_insert_answer);
        confirm_result_set($result_choice_set);
        return $result_choice_set;
    }





?>
