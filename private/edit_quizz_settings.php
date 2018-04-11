<?php require_once('initialize.php'); ?>
<?php
/*************************************************************************
* DESCRIPTION: "edit_quizz_settings.php" allows the admin user to update
* general settings such as whether the quizz should be active for the
* users, or the number of questions to show
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
$output_settings_quizz = array();
$output_settings_quizz['error'] = 0;

/* get the assessment to edit */
$assessment_settings_id = $_POST['assessment_id'];

/* get form data */
$num_quest_to_show = $_POST['num_quest_show_sel'];
$is_quizz_active = $_POST['is_quizz_active_check'];
$set_quizz_active = 0;

if($num_quest_to_show == null){
    $output_settings_quizz['error'] = "Error! Please add questions to the quizz.";
}
else {
    /* check form values */
    if($is_quizz_active != null){
        $set_quizz_active = 1;
    }
    /* attempt to edit the given quizz */
    $result_settings_quizz = edit_settings_quizz($assessment_settings_id, $num_quest_to_show, $set_quizz_active);
    if($result_settings_quizz != true) {
        $output_settings_quizz['error'] = $result_settings_quizz;
    }
}

echo json_encode($output_settings_quizz);

?>
