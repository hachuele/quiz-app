<?php


function find_all_visible_courses(){
    //bring in $db from outside scope (since not passed in as argument)
    global $db;
    # GET AVAILABLE COURSES FROM THE DATABASE 'hpc_quizzing_db'
    $sql_assessments = "SELECT * FROM assessments "; //whitespace after assessments required
//    $sql_assessments .= "WHERE assessment_visible=1";
    # GET COURSE SET FROM TABLE
    $result_course_set = mysqli_query($db, $sql_assessments);
    confirm_result_set($result_course_set);
    return $result_course_set;

}





?>
