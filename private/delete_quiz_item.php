<?php require_once('initialize.php'); ?>
<?php
/****************************************************************************
* DESCRIPTION: "delete_quiz_item.php" allows the admin user to delete
* question choices, questions, or an entire quiz.
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
$output_delete_quiz = array();
$output_delete_quiz['error'] = 0;
$output_delete_quiz['info'] = 0;

/* get delete type and row ID */
$delete_type = $_POST['delete_type'];
$delete_id = $_POST['delete_id'];

/* check what type of delete request we need to make */
if($delete_type == 'delete_quiz'){
    /* check if assessment has already been used by a user */
    if(is_assessment_used_by_usr($delete_id)){
        /* assessment is used, virtual delete instead */
        $result_virt_del_assessment = virtual_delete_quiz($delete_id);

        if($result_virt_del_assessment != true) {
                $output_delete_quiz['error'] = $result_virt_del_assessment;
        }
        else{
            $output_delete_quiz['info'] = "Assessment has already been submitted by a user. The assessment has been deleted 'virtually' to avoid data corruption.
            Please delete directly through MySQL server to remove the question's row from the database.";
        }
    }
    else{
        /* attempt to delete question */
        $result_delete_assessment = delete_quiz($delete_id);
        if($result_delete_assessment != true) {
                $output_delete_quiz['error'] = $result_delete_assessment;
        }
    }
}
else if($delete_type == 'delete_question'){
    /* check if question has already been used by a user */
    if(is_question_used_by_usr($delete_id)){
        /* question is used, virtual delete instead */
        $result_virt_del_quest = virtual_delete_quiz_question($delete_id);

        if($result_virt_del_quest != true) {
                $output_delete_quiz['error'] = $result_virt_del_quest;
        }
        else{
            $output_delete_quiz['info'] = "Question has already been submitted by a user. The question has been deleted 'virtually' to avoid data corruption.
            Please delete directly through MySQL server to remove the question's row from the database.";
        }
    }
    else{
        /* attempt to delete question */
        $result_delete_quest = delete_quiz_question($delete_id);
        if($result_delete_quest != true) {
                $output_delete_quiz['error'] = $result_delete_quest;
        }
    }
}
else if($delete_type == 'delete_choice'){
    /* check if choice has already been used by a user */
    if(is_choice_used_by_usr($delete_id)){
        /* choice is used, virtual delete instead */
        $result_virt_del_choice = virtual_delete_quiz_choice($delete_id);

        if($result_virt_del_choice != true) {
                $output_delete_quiz['error'] = $result_virt_del_choice;
        }
        else{
            $output_delete_quiz['info'] = "Choice has already been submitted by a user. The choice has been deleted 'virtually' to avoid data corruption.
            Please delete directly through MySQL server to remove the choice's row from the database.";
        }
    }
    else{
        /* attempt to delete choice */
        $result_delete_choice = delete_quiz_choice($delete_id);
        if($result_delete_choice != true) {
                $output_delete_quiz['error'] = $result_delete_choice;
        }
    }
}
else{
    $output_delete_quiz['error'] = "Error in request. Please try again.";
}


echo json_encode($output_delete_quiz);

?>
