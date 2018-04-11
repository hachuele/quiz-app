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
$output_new_quizz = array();
$output_new_quizz['error'] = 0;

if($_POST['request_type'] == 'new_quizz'){
    /* check if quizz name already in use */
    if(is_name_used($quizz_name_txt)){
        $output_new_quizz['error'] = "Error! The given quizz name already exists in the database.";
    }
    else {
        $result_new_quizz = create_new_quizz($quizz_name_txt, $quizz_descr_text_area);

        if($result_new_quizz === true) {
            $new_quizz_id = mysqli_insert_id($db);
            $output_new_quizz['new_assessment_id'] = $new_quizz_id;
        }
        else {
            $output_new_quizz['error'] = $result;
        }
    }
} else if($_POST['request_type'] == 'edit_quizz'){



}


echo json_encode($output_new_quizz);

?>
