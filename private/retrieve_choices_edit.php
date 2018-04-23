<?php require_once('initialize.php'); ?>
<?php
/**************************************************************************
* DESCRIPTION: Processes question ID on table row click to
* retrieve choice information (if available)
*                                ---
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

/* Get the question ID to view its choices */
$question_edit_id = $_POST['question_edit_id'];
$output_edit_array = array();
$output_edit_array['question_text'] = get_question_text($question_edit_id);

/* get choices */
$choice_edit_set = find_choices_by_question_id($question_edit_id);
$num_edit_choices = mysqli_num_rows($choice_edit_set);
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
