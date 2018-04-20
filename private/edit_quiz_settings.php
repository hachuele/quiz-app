<?php require_once('initialize.php'); ?>
<?php
/***************************************************************************
* DESCRIPTION: "edit_quiz_settings.php" allows the admin user to update
* general settings such as whether the quiz should be active for the
* users, or the number of questions to show
*                                   ---
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
session_start();

/* --------------------------------- DATA RETRIEVAL --------------------------------- */

/* instantiate output array */
$output_settings_quiz = array();
$output_settings_quiz['error'] = 0;

/* get the assessment to edit */
$assessment_settings_id = $_POST['assessment_id'];

/* get form data */
$num_quest_to_show = $_POST['num_quest_show_sel'];
$is_quiz_active = $_POST['is_quiz_active_check'];
$set_quiz_active = 0;

if($num_quest_to_show == null){
    $output_settings_quiz['error'] = "Error! Please add questions to the quiz.";
}
else {
    /* check form values */
    if($is_quiz_active != null){
        $set_quiz_active = 1;
    }
    /* attempt to edit the given quiz */
    $result_settings_quiz = edit_settings_quiz($assessment_settings_id, $num_quest_to_show, $set_quiz_active);
    if($result_settings_quiz != true) {
        $output_settings_quiz['error'] = $result_settings_quiz;
    }
}

echo json_encode($output_settings_quiz);

?>
