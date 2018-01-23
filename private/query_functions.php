<?php


    function find_all_visible_courses(){
        # bring in $db from outside scope (since not passed in as argument)
        global $db;
        $sql_assessments = "SELECT * FROM assessments "; //whitespace after assessments required
        $sql_assessments .= "WHERE assessment_visible=1";
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
        # GET QUESTION SET FROM TABLE
        $result_question_set = mysqli_query($db, $sql_questions);
        confirm_result_set($result_question_set);
        return $result_question_set;
    }


    function find_choices_by_question_id($question_id){
        global $db;
        $sql_choices = "SELECT * FROM question_choices ";
        $sql_choices .= "WHERE question_id='" . $question_id . "'";
        # GET CHOICE SET FROM TABLE
        $result_choice_set = mysqli_query($db, $sql_choices);
        confirm_result_set($result_choice_set);
        return $result_choice_set;
    }





?>
