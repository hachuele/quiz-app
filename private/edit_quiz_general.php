<?php require_once('initialize.php'); ?>
<?php
/*************************************************************************
* DESCRIPTION: "edit_quiz_general.php" creates a new admin generated quiz
* or edits an existing one
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

$quiz_name_txt = $_POST['quiz_name_text_in'];
$quiz_descr_text_area = $_POST['quiz_descr_text_in'];
/* instantiate output array */
$output_edit_quiz = array();
$output_edit_quiz['error'] = 0;
/* check if request is to create new quiz or edit an existing one */
if($_POST['request_type'] == 'new_quiz'){
    /* check if quiz name already in use */
    if(is_name_used($quiz_name_txt)){
        $output_edit_quiz['error'] = "Error! The given quiz name already exists in the database.";
    }
    else {
        /* attempt to create the given quiz */
        $result_new_quiz = create_new_quiz($quiz_name_txt, $quiz_descr_text_area);

        if($result_new_quiz === true) {
            $new_quiz_id = mysqli_insert_id($db);
            $output_edit_quiz['new_assessment_id'] = $new_quiz_id;
        }
        else {
            $output_edit_quiz['error'] = $result_new_quiz;
        }
    }
} else if($_POST['request_type'] == 'edit_quiz'){
    /* get the assessment to edit */
    $assessment_edit_id = $_POST['assessment_id'];
    $assessment_edit_description = get_assessment_descr($assessment_edit_id);
    /* check if quiz name already in use */
    if(is_name_used($quiz_name_txt)){
        if($assessment_edit_description == $quiz_descr_text_area){
            $output_edit_quiz['error'] = "Oops! Please make sure you have made changes to the name or the description.";
        } else{
            /* attempt to edit the given quiz */
            $result_edit_quiz = edit_general_quiz($assessment_edit_id, $quiz_name_txt, $quiz_descr_text_area);
            if($result_edit_quiz != true) {
                $output_edit_quiz['error'] = $result_edit_quiz;
            }
        }
    }
    else {
        /* attempt to edit the given quiz */
        $result_edit_quiz = edit_general_quiz($assessment_edit_id, $quiz_name_txt, $quiz_descr_text_area);
        if($result_edit_quiz != true) {
            $output_edit_quiz['error'] = $result_edit_quiz;
        }
    }
}


echo json_encode($output_edit_quiz);

?>
