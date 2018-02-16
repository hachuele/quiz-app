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


    function find_choices_by_question_id($question_id){
        global $db;
        $sql_choices = "SELECT * FROM question_choices ";
        $sql_choices .= "WHERE question_id='" . $question_id . "'";
        $sql_choices .=  "ORDER BY question_choice_id ASC";
        # GET CHOICE SET FROM TABLE
        $result_choice_set = mysqli_query($db, $sql_choices);
        confirm_result_set($result_choice_set);
        return $result_choice_set;
    }


    // doesnt work : need to insert first, data may not be there
//    function get_latest_question($assessment_id){
//        global $db;
//        $sql_latest_question = "SELECT MAX(latest_quest_sequential_num) FROM user_assessments ";
//        $sql_latest_question .= "WHERE assessment_id='" . $assessment_id . "'";
//        $sql_latest_question .= "AND user_assessment_id= MAX(SELECT user_assessment_id FROM user_assessments)";
//        # GET CHOICE SET FROM TABLE
//        $latest_question_set = mysqli_query($db, $sql_latest_question);
//        confirm_result_set($latest_question_set);
//        return $latest_question_set;
//    }





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
