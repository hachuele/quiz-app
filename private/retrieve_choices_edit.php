<?php require_once('initialize.php'); ?>
<?php
/***********************************************************************
 * DESCRIPTION: Processes question ID on table row click to
 * retrieve choice information (if available)
 *                                ---
 * @author: Eric J. Hachuel
 * Copyright 2018 University of Southern California. All rights reserved.
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
 ***********************************************************************/
session_start();

/* --------------------------------- DATA RETRIEVAL --------------------------------- */

/* Get the question ID to view its choices */
$question_edit_id = $_POST['question_edit_id'];

$output_edit_array = array();
$output_edit_array['question_text'] = get_question_text($question_edit_id);

/* get choices */
$choice_edit_set = find_choices_by_question_id($question_edit_id);
$num_edit_choices = mysqli_num_rows($choice_edit_set);

/* check if choices exist for the selected question row */

$output_edit_array['$num_edit_choices'] == $num_edit_choices;

$choices_edit_array = array();
/* push choices into array */
while($choice = mysqli_fetch_array($choice_edit_set, MYSQLI_BOTH)){
    array_push($choices_edit_array, $choice);
}
$output_edit_array['num_edit_choices'] = $num_edit_choices;
$output_edit_array['choices_edit_array'] = $choices_edit_array;


echo json_encode($output_edit_array);


?>
