<?php require_once('initialize.php'); ?>
<?php


/******************************************************************
 * DESCRIPTION: Processes user submitted answers through the Ajax
 * calls, returns answer details as data to the user, stores
 * user answers.
 *                             ----
 * @author: Eric J. Hachuel
 * University of Southern California, High-Performance Computing
 ******************************************************************/

/**
-DATA: WHAT THE USER SELECTS (CHOICES), THE QUESTION, THE ASSESSMENT ID
-PERFORMS: ADDS THE DATA TO THE TABLE FOR THE SPECIFIC USER
-RETURN: THE ANSWER DETAILS FOR DISPLAY

*/

//INITIALIZE VARIABLES
$data = array();

//used to add Classes when answers submitted
$data['correct-glyphicon-class'] = 'glyphicon-ok solution_glyphicon_correct';
$data['incorrect-glyphicon-class'] = 'glyphicon-remove solution_glyphicon_incorrect';


//$assessment_id = $_POST['assessment_id'];


?>
