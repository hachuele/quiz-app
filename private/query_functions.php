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

    function find_questions_by_assessment_id($id){
        global $db;
        $sql_questions = "SELECT * FROM questions ";
        $sql_questions .= "WHERE assessment_id='" . $id . "'";
        # GET QUESTION SET FROM TABLE
        $result_question_set = mysqli_query($db, $sql_questions);
        confirm_result_set($result_question_set);
        return $result_question_set;
    }

//    function find choices_by_question_id($id){
//
//
//    }





?>
