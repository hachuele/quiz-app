<?php
/**************************************************************************
 * DESCRIPTION: 'public/quizz/index.php' serves as the main page for
 * the actual quizz and all of its questions. These are shown one
 * at a time. If the selected quizz is in progress, the page displays
 * all previously provided answers
 *                             ----
 * @author: Eric J. Hachuel
 * Copyright 2018 University of Southern California. All rights reserved.
 **************************************************************************/
session_start();
require_once('../../private/initialize.php');
?>

<?php
/* ---------------- Dynamic Naming Variables ---------------- */
$site_title = 'HPC Assessments Site';
$page_title = 'HPC ASSESSMENTS';
$help_modal_title = 'HPC QUIZZ HELP';
$help_modal_txt = 'Please complete the selected Quizz...';

/* ----------------------------------------------------------------------------------------- */
/* -------------------------------------- Get User ID -------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

$user_id = 'hachuelb';
$_SESSION["user_id"] = $user_id;

/* -------- get the assessment id from url (if not found, set to one) -------- */
$assessment_id = $_GET['assessment_id'];
$_SESSION["assessment_id"] = $assessment_id;

/* ----------------------------------------------------------------------------------------- */
/* ------------------------------------ Retrieve from DB ----------------------------------- */
/* ----------------------------------------------------------------------------------------- */

/* -------- get the name of this assessment for display -------- */
$assessment_name = get_assessment_name($assessment_id);
/* if the quizz for the given id does not exist, redirect to main page */
if($assessment_name == FALSE){
    redirect_to('../index.php');
}

/* -------- get questions for selected assessment -------- */
$question_set = find_questions_by_assessment_id($assessment_id);
$num_questions = mysqli_num_rows($question_set);

/* check if assessment is in progress */
/* NOTE: only one row output (max) for a given in progress assessment id and user id */
$is_in_progress = 0;
$user_assessments_set = get_in_progress_by_assessment_id($assessment_id, $user_id);
if(mysqli_num_rows($user_assessments_set)){
    $is_in_progress = 1;
}

$user_assessment_id = '';
/* if the current quizz is in progress, instantiate variables to update user profile */
$latest_quest_seq = 0;
if($is_in_progress){
    /* fecth the completed information (response information and status) */
    $user_assessments_row = mysqli_fetch_assoc($user_assessments_set);
    $latest_quest_seq = $user_assessments_row['latest_quest_sequential_num'];
    $user_assessment_id = $user_assessments_row['user_assessment_id'];
}
?>

<!-- *********************************** PAGE HEADER *********************************** -->
<?php require(SHARED_PATH . '/quizz_page_header.php'); ?>

<!-- *********************************** CONTENT START *********************************** -->
<div class="container-fluid assessment_title_main centering_div">
    <div id ="assessment_title_row_div" class="row centered_div" data-user-assessment-id="<?php echo h($user_assessment_id); ?>">
        <div class="col-sm-12">
            <div class="row">
                <h3 id="assessment_title_txt">
                    <button id="back_home_btn" type="button" class="btn btn-default btn-sm">
                        <span class="glyphicon glyphicon-home"></span>
                    </button>
                    <?php echo h($assessment_name); ?>
                </h3>
            </div>
        </div>
    </div>
</div>
<div class="container main_content">
    <?php
    $question_num = 1;
    /* loop through quizz questions */
    while($question = mysqli_fetch_assoc($question_set)) {

        /* get choice set to for given question id */
        $choices_array = array();
        $choice_set = find_choices_by_question_id(h($question['question_id']));
        $num_choices = mysqli_num_rows($choice_set);

        /* push choice set into an array for later retrieval */
        while($choice = mysqli_fetch_array($choice_set, MYSQLI_BOTH)){
            array_push($choices_array, $choice);
        }

        /* declare variables for dynamic classes */
        $question_active_class = '';
        $is_completed_question = 0;
        $hidden_incomplete = 'hidden';
        /* use to disable form input */
        $disabled_complete = '';
        $enabled_completed = 'disabled';

        /* check if the current question is prior to the latest completed */
        /* NOTE: latest question is 0 if quizz is not in progress */
        if($question_num <= $latest_quest_seq){
            $is_completed_question = 1;
            $hidden_incomplete ='';
            $disabled_complete = 'disabled';
            $enabled_completed = '';
            $user_answers_array = array();

            /* get the user submitted answers for the current question */
            $user_answers_set = get_user_answers_by_ua_q_id($user_assessment_id, $question['question_id']);
            $num_answers = mysqli_num_rows($user_answers_set);

            /* fill array with user answers */
            while($user_answer = mysqli_fetch_array($user_answers_set, MYSQLI_BOTH)){
                array_push($user_answers_array, $user_answer);
            }
        }

        /* if in progress, show latest completed question, otherwise show question 1 */
        if($question_num == $latest_quest_seq || ($latest_quest_seq == 0 && $question_num == 1)){
            $question_active_class = 'active';
        } else{
            $question_active_class = '';
        }
        /* check if question is a checkbox or a radio set to display properly formatted choices */
        if ($question['question_multivalued'] == 1){
            $question_type_class = 'checkbox';
            $choice_name = 'check';
        } else{
            $question_type_class = 'radio';
            $choice_name = 'radio';
        }
    ?>
    <div id="question_card_<?php echo $question_num; ?>" class="hidden <?php echo $question_active_class; ?> quizz_question_div container question_card" data-questionid="<?php echo h($question['question_id']); ?>" data-questiontype="<?php echo $question_type_class; ?>">
        <div class="page-header">
            <h4><strong>QUESTION <?php echo $question_num; ?>:</strong> <?php echo h($question['question_text']); ?></h4>
        </div>
        <div class="questions_list_div">
            <form id="question_form_<?php echo $question_num; ?>" action="process_answers.php" method="POST">
                <?php
                $choice_num = 1;
                $radio_value = 1;

                /* loop over current question's choices */
                foreach($choices_array as $choice){

                    /* declare variables for dynamic classes */
                    $input_checked = '';
                    $choice_glyph_class = '';
                    $span_choice_mark_class = '';

                    /* check if question is complete, then compare choice against user answer */
                    if($is_completed_question){
                        /* check if choice is selected */
                        $answer_found = 0;
                        foreach($user_answers_array as $answer){
                            /* if answer selected current choice and choice is correct answer (correct selected): */
                            if((($choice['question_choice_id'] == $answer['question_choice_id']) &&  $choice['question_choice_correct'] == 1) && !($answer_found)){
                                $answer_found = 1;
                                $input_checked = 'checked';
                                $choice_glyph_class = 'choice_mark glyphicon glyphicon-ok-circle solution_glyphicon_correct';
                                $span_choice_mark_class = 'choice_mark';
                            }
                            /* if answer selected current choice and choice is incorrect answer (incorrect selected): */
                            else if((($choice['question_choice_id'] == $answer['question_choice_id']) &&  $choice['question_choice_correct'] == 0) && !($answer_found)){
                                $answer_found = 1;
                                $input_checked = 'checked';
                                $choice_glyph_class = 'choice_mark glyphicon glyphicon-remove-circle solution_glyphicon_incorrect';
                                $span_choice_mark_class = 'choice_mark';
                            }
                            /* if answer did NOT select current choice and choice is correct answer(correct not selected): */
                            else if(((($choice['question_choice_id'] != $answer['question_choice_id']) &&  $choice['question_choice_correct'] == 1) && ($question_type_class == 'checkbox')) && !($answer_found)){
                                $answer_found = 1;
                                $choice_glyph_class = 'choice_mark glyphicon glyphicon-remove-circle solution_glyphicon_correct_not_selected';
                                $span_choice_mark_class = 'choice_mark';
                            }
                        }
                    }
                ?>
                <div class="question_item_div center">
                    <div class="<?php echo $question_type_class; ?> question_item">
                        <span id="question_choice_id_<?php echo h($choice['question_choice_id']); ?>" class="<?php echo $choice_glyph_class; ?>" data-choiceid="<?php echo h($choice['question_choice_id']); ?>" ></span><label class="question_label"><input <?php echo $input_checked; ?> <?php echo $disabled_complete; ?> class="<?php echo $question_type_class; ?>_item_<?php echo $question_num; ?>" type="<?php echo $question_type_class; ?>" name="<?php echo $choice_name; ?>_<?php echo $choice_num; ?>" value="<?php echo $radio_value; ?>"><?php echo h($choice['question_choice_text']); ?></label>
                    </div>
                </div>
                <?php
                if ($question['question_multivalued'] == 1){
                    $choice_num++;
                } else{
                    $radio_value++;
                }
                ?>
                <?php } ?>
                <br>
                <hr>
                <div id="answer_explanations_div_<?php echo $question_num; ?>" class="<?php echo $hidden_incomplete; ?> well answer_explanations">
                    <?php
                    /* if in progress and question complete, show answer details */
                    if($is_completed_question){
                        $choice_cur_num = 1;
                        /* loop through the array of choices and check if correct */
                        foreach($choices_array as $choice){
                            if($choice['question_choice_correct'] == 1) {
                    ?>
                    <div class="alert alert-success">
                        <strong>Choice <?php echo $choice_cur_num; ?>: </strong> <?php echo $choice['question_choice_reason']; ?>
                    </div>
                        <?php
                            }
                            /* check if choice is incorrect */
                            else if($choice['question_choice_correct'] == 0) {
                        ?>
                    <div class="alert alert-incorrect">
                        <strong>Choice <?php echo $choice_cur_num; ?>: </strong> <?php echo $choice['question_choice_reason']; ?>
                    </div>
                            <?php } ?>
                        <?php $choice_cur_num++; } ?>
                    <?php } ?>
                </div>
                <div class="row bottom_button_set">
                    <?php
                    if ($question_num == 1){
                        $previous_display = 'no_display';
                    } else{
                        $previous_display = '';
                    }
                    ?>
                    <div class="<?php echo $previous_display; ?> previous_question_btn_div col-xs-3">
                        <button id="previous_question_btn_<?php echo $question_num; ?>" type="button" class="btn btn-primary btn-sm previous_button">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </button>
                    </div>
                    <div class="view_answers_btn_div col-xs-6">
                        <button disabled id="view_answers_btn_<?php echo $question_num; ?>" class="btn btn-primary btn-block btn-sm answers_button" type="button">SUBMIT</button>
                    </div>
                    <div class="next_question_btn_div col-xs-3">
                        <button <?php echo $enabled_completed; ?> id="next_question_btn_<?php echo $question_num; ?>" type="button" class="btn btn-primary btn-sm next_button">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div>
    <?php $question_num++; ?>
    <?php } ?>

    <div id="quizz_statistics_card" class=" hidden container">
        <div class="page-header">
            <h3 style="text-align: center; margin-bottom: 25px;">Quizz Completed!  <span class="glyphicon glyphicon-ok-circle solution_glyphicon_correct"></span></h3>
        </div>
        <div id="quizz_statistics_data_table">
            <div id="total_score_display">
                <p id="final_score_percent"></p>
                <p id="final_score_txt">FINAL SCORE</p>
            </div>
            <hr>
            <div id="quizz_results_summary_div" class="row">
                <div id="num_correct_div" class ="col-xs-6">
                    <p id="num_correct_digit"></p>
                    <p id="num_correct_txt">CORRECT</p>
                </div>
                <div id="num_incorrect_div" class ="col-xs-6">
                    <p id="num_incorrect_digit"></p>
                    <p id="num_incorrect_txt">INCORRECT</p>
                </div>
            </div>
        </div>
        <div id="end_of_quizz_nav_bar">
            <div class="row">
                <div class="col-xs-6">
                    <button id="end_previous_question_btn" type="button" class="btn btn-default btn-sm previous_button">
                        <span class="glyphicon glyphicon-chevron-left"></span> BACK
                    </button>
                </div>
                <div class="col-xs-6">
                    <button id="retry_quizz_btn" type="button" class="btn btn-default btn-sm previous_button" onclick="location.href='<?php echo url_for('quizz/index.php?assessment_id=' . h($assessment_id)); ?>'">
                        <span class="glyphicon glyphicon-repeat" style="margin-right:10px;"></span>RETRY QUIZZ
                    </button>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <?php
    $aria_value_now = 0;
    /* if quizz is in progress - calculate percent complete and display */
    if($is_in_progress){
        $percent_complete_ip = round(($latest_quest_seq / $num_questions) * 100);
        $aria_value_now = $percent_complete_ip;
    }
    ?>
    <div style="max-width: 700px; margin: auto;" id="quizz_progress_bar_div">
        <div class="progress">
          <div id="quizz_progress_bar" class="progress-bar progress-bar-grey" style="width:<?php echo $aria_value_now; ?>%" role="progressbar" aria-valuenow="<?php echo h($aria_value_now); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $aria_value_now; ?>%</div>
        </div>
    </div>

</div>
<!-- *********************************** CONTENT END *********************************** -->

<!--load personal scripts-->
<script src="<?php echo url_for('js/main-script.js');?>"></script>
<script src="<?php echo url_for('js/quizz-script.js');?>"></script>

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quizz_page_footer.php'); ?>

