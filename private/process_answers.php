<?php require_once('initialize.php'); ?>
<?php


/******************************************************************
 * DESCRIPTION: Processes user submitted answers through the Ajax *
 * calls, returns answer details as data to the user, stores      *
 * user answers.                                                  *
 * -------------------------------------------------------------- *
 * @author: Eric J. Hachuel                                       *
 * University of Southern California, High-Performance Computing  *
 ******************************************************************/

/**
-DATA: WHAT THE USER SELECTS (CHOICES), THE QUESTION, THE ASSESSMENT ID
-PERFORMS: ADDS THE DATA TO THE TABLE FOR THE SPECIFIC USER
-RETURN: THE ANSWER DETAILS FOR DISPLAY
*/



/* --------------------------------- DATA INSERT --------------------------------- */


//use assessment_id session variable for data insert
//NEED FOLLOWING DATA: user_assessment_id, assessment_id, question_id, question_choice_id
// latest_quest_sequential_num, user_assessment_score, user_assessment_is_complete [USER_ANSWERS TABLE]

//for score; normalize to 100, add equal amount of points per answered question
//  score per correct answer: 100 / num questions (round up, set max to 100)

//NEED FOLLOWING: assessment_id, user_id [USER ASESSMENTS]










/* --------------------------------- ANSWER DISPLAY --------------------------------- */


/* Instantiate arrays */
$choices_array = array(); // the set of choices for a particular question
$results_array = array(); // what the user has selected
$answers_data = array(); // the output
$response_text_array = array(); // to store the answer details for display
$output_array = array(); // array to encode for ajax
$choice_ids = array(); // store choice ids


//update current question session variable
$_SESSION["question_id"] = $_POST['question_id'];
/* Get the question type */
$question_type = $_POST['question_type'];

//make database calls
$choice_set = find_choices_by_question_id($_POST['question_id']);
$num_choices = mysqli_num_rows($choice_set);

while($choice = mysqli_fetch_array($choice_set, MYSQLI_BOTH)){
    array_push($choices_array, $choice);
}

/* Format for checkbox parameters  */
if($question_type == 'checkbox'){
    for($i = 0; $i < $num_choices; $i++){

        array_push($choice_ids, $choices_array[$i]['question_choice_id']);

        array_push($results_array, $_POST['check_' . ($i + 1)]); // value is null if not selected; value is 1 if selected ($results_array[$i] == 1)

        if(($results_array[$i] == 1) && ($choices_array[$i]['question_choice_correct'] == 1)){
            //correct response
            array_push($answers_data, 'correct_selected'); // a correct answer that was correctly selected
        } else if (($results_array[$i] == 0) && ($choices_array[$i]['question_choice_correct'] == 0)){
            //correct response
            array_push($answers_data, 'incorrect_not_selected'); // an incorrect answer that was correctly NOT selected
        } else if (($results_array[$i] == 1) && ($choices_array[$i]['question_choice_correct'] == 0)){
            //correct response
            array_push($answers_data, 'incorrect_selected'); // an incorrect answer that was incorrectly selected (WRONG ANSWER)
        }else{
            array_push($answers_data, 'correct_not_selected'); // a correct answer that was incorrectly NOT selected (WRONG ANSWER)
        }
        array_push($response_text_array, $choices_array[$i]['question_choice_reason']);
    }
} else {
    array_push($results_array, $_POST['radio_1']); // the value is the selected checkbox ($results_array[0])
    for($i = 0; $i < $num_choices; $i++){

        array_push($choice_ids, $choices_array[$i]['question_choice_id']);

        if(($results_array[0] == ($i + 1)) && ($choices_array[$i]['question_choice_correct'] == 1)){
            array_push($answers_data, 'correct_selected'); // a correct answer that was correctly selected
        }
        else if(($results_array[0] != ($i + 1)) && ($choices_array[$i]['question_choice_correct'] == 1)){
            array_push($answers_data, 'correct_not_selected'); // a correct answer that was correctly selected
        }
        else if(($results_array[0] == ($i + 1)) && ($choices_array[$i]['question_choice_correct'] == 0)){
            //correct response
            array_push($answers_data, 'incorrect_selected'); // a correct answer that was correctly selected
        }
        else{
            array_push($answers_data, 'incorrect_not_selected'); // a correct answer that was incorrectly NOT selected (WRONG ANSWER)
        }
        array_push($response_text_array, $choices_array[$i]['question_choice_reason']);
    }
}


$output_array['num_choices'] = $num_choices;
$output_array['choice_ids'] = $choice_ids;
$output_array['user_selection_details'] = $answers_data;
$output_array['reponse_details'] = $response_text_array;


echo json_encode($output_array);


?>
