<?php require_once('initialize.php'); ?>
<?php
/*************************************************************************
* DESCRIPTION: "delete_quiz_item.php" allows the admin user to delete
* question choices, questions, or an entire quiz.
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

/* instantiate output array */
$output_delete_quiz = array();
$output_delete_quiz['error'] = 0;
$output_delete_quiz['info'] = 0;

/* get delete type and row ID */
$delete_type = $_POST['delete_type'];
$delete_id = $_POST['delete_id'];

/* check what type of delete request we need to make */
if($delete_type == 'delete_quiz'){




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
