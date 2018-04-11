<?php require_once('initialize.php'); ?>
<?php
/*************************************************************************
* DESCRIPTION: "create_new_quizz.php" creates a new admin generated quizz
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

$quizz_name_txt = $_POST['quizz_name_text_in'];
$quizz_descr_text_area = $_POST['quizz_descr_text_in'];
/* instantiate output array */
$output_edit_quizz = array();
$output_edit_quizz['error'] = 0;

if($_POST['request_type'] == 'new_quizz'){
    /* check if quizz name already in use */
    if(is_name_used($quizz_name_txt)){
        $output_edit_quizz['error'] = "Error! The given quizz name already exists in the database.";
    }
    else {
        /* attempt to create the given quizz */
        $result_new_quizz = create_new_quizz($quizz_name_txt, $quizz_descr_text_area);

        if($result_new_quizz === true) {
            $new_quizz_id = mysqli_insert_id($db);
            $output_edit_quizz['new_assessment_id'] = $new_quizz_id;
        }
        else {
            $output_edit_quizz['error'] = $result_new_quizz;
        }
    }
} else if($_POST['request_type'] == 'edit_quizz'){
    /* get the assessment to edit */
    $assessment_edit_id = $_POST['assessment_id'];
    $assessment_edit_description = get_assessment_descr($assessment_edit_id);
    /* check if quizz name already in use */
    if(is_name_used($quizz_name_txt)){
        if($assessment_edit_description == $quizz_descr_text_area){
            $output_edit_quizz['error'] = "Oops! Please make sure you have made changes to the name or the description.";
        } else{
            /* attempt to edit the given quizz */
            $result_edit_quizz = edit_general_quizz($assessment_edit_id, $quizz_name_txt, $quizz_descr_text_area);
            if($result_edit_quizz != true) {
                $output_edit_quizz['error'] = $result_edit_quizz;
            }
        }
    }
    else {
        /* attempt to edit the given quizz */
        $result_edit_quizz = edit_general_quizz($assessment_edit_id, $quizz_name_txt, $quizz_descr_text_area);
        if($result_edit_quizz != true) {
            $output_edit_quizz['error'] = $result_edit_quizz;
        }
    }
}


echo json_encode($output_edit_quizz);

?>
