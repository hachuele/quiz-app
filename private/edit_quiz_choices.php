<?php require_once('initialize.php'); ?>
<?php
/*************************************************************************
* DESCRIPTION: "edit_quiz_choices.php" allows the admin user to create,
* edit, or delete choices for a given quiz and question.
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
$output_edit_choice_quiz = array();
$output_edit_choice_quiz['error'] = 0;

/* check if request is to create, edit, or delete question */
if($_POST['request_type'] == 'new_choice'){








}
else if($_POST['request_type'] == 'edit_choice'){








}
else if($_POST['request_type'] == 'delete_choice'){








}


echo json_encode($output_edit_choice_quiz);

?>
