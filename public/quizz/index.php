<?php
/******************************************************************
 * DESCRIPTION:
 *
 *                             ----
 * @author: Eric J. Hachuel
 * University of Southern California, High-Performance Computing
 ******************************************************************/
session_start();
?>
<?php require_once('../../private/initialize.php'); ?>


<?php
    /* -------- Initialize page variables -------- */
    $page_title = 'HPC QUIZZ';
    $site_title = 'HPC Assessments Site';


    /* -------- Get User ID -------- */
    $user_id = $_SESSION["user_id"];


    //would set to latest completed question first, then change to whatever user is viewing
    $_SESSION["question_id"] = 1;


    if(!isset($_SESSION["question_id"])){
        $_SESSION["question_id"] = 1; //should be the latest completed question from database
    }


    $assessment_id = $_GET['assessment_id'] ?? '1'; // get the assessment id from url

    /* Update Session Variables */
//    $_SESSION["assessment_id"] = $assessment_id;

    /* Get the question_id for the most recently completed question for the current assessment */

    /* ---------------- Get Data from the DB ---------------- */
    $assessment_name = get_assessment_name($assessment_id); // get the name of this assessment for display
    $question_set = find_questions_by_assessment_id($assessment_id); //get questions for selected assessment
    $num_questions = mysqli_num_rows($question_set); //get the number of questions in the set

    /* check if assessment is in progress */
    $is_in_progress = 0;
    /* NOTE: only one row output (max) for a given in progress assessment id and user id */
    $user_assessments_set = get_in_progress_by_assessment_id($assessment_id, $user_id);
    if(mysqli_num_rows($user_assessments_set)){
        $is_in_progress = 1;
    }
    /* if quizz in progress, retrieve required info for page load */
    $latest_quest_seq = 0;

    /* if the current quizz is in progress, instantiate variables to update user profile */
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
    <div id ="assessment_title_row_div" class="row centered_div">
        <div class="col-sm-12">
            <div class="row">
                <h3 id="assessment_title_txt">
                    <button id="back_home_btn" type="button" class="btn btn-default btn-sm">
                        <span class="glyphicon glyphicon-home"></span>
                    </button>
                    <?php echo h($assessment_name) ?>
                </h3>
            </div>
        </div>
    </div>
</div>
<div class="container main_content">
    <?php $question_num = 1; ?>
    <!--START MAIN PHP LOOP FOR GENERATING QUESTIONS-->
    <?php while($question = mysqli_fetch_assoc($question_set)) { ?>
    <!--INSTANTIATE ACTIVE CLASS TO EMPTY-->
    <?php
        /* get choice set to for given question id */
        $choices_array = array();
        $choice_set = find_choices_by_question_id(h($question['question_id']));
        $num_choices = mysqli_num_rows($choice_set);
        /* push choice set into an array for later retrieval */
        while($choice = mysqli_fetch_array($choice_set, MYSQLI_BOTH)){
            array_push($choices_array, $choice);
        }


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



    ?>




    <?php
        if($question_num == $latest_quest_seq || ($latest_quest_seq == 0 && $question_num == 1)){
            $question_active_class = 'active';
        } else{
            $question_active_class = '';
        }
    ?>
    <?php
        if ($question['question_multivalued'] == 1){
            $question_type_class = 'checkbox';
            $choice_name = 'check';
        } else{
            $question_type_class = 'radio';
            $choice_name = 'radio';
        }
    ?>
    <!--question card's ID is composed of the db 'question_id' field and the question_num variable for future extraction-->
    <div id="question_card_<?php echo $question_num ?>" class="hidden <?php echo $question_active_class ?> quizz_question_div container question_card" data-questionid="<?php echo h($question['question_id']) ?>" data-questiontype="<?php echo $question_type_class ?>">
        <div class="page-header">
            <h4><strong>QUESTION <?php echo $question_num ?>:</strong> <?php echo h($question['question_text']) ?></h4>
        </div>
        <div class="questions_list_div">
            <form id="question_form_<?php echo $question_num ?>" action="process_answers.php" method="POST">
                <!--LOAD QUESTION CHOICES-->
                <?php
                    $choice_num = 1;
                    $radio_value = 1;
                ?>
                <?php
                    foreach($choices_array as $choice){
                        /* insantiate classes */
                        $input_checked = '';
                        $choice_glyph_class = '';
                        $span_choice_mark_class = '';
                        /* check if question is complete, then choice against user answer */
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
                        <span id="question_choice_id_<?php echo h($choice['question_choice_id']); ?>" class="<?php echo $choice_glyph_class; ?>" data-choiceid="<?php echo h($choice['question_choice_id']); ?>" ></span><label class="question_label"><input <?php echo $input_checked; ?> <?php echo $disabled_complete; ?> class="<?php echo $question_type_class; ?>_item" type="<?php echo $question_type_class; ?>" name="<?php echo $choice_name; ?>_<?php echo $choice_num; ?>" value="<?php echo $radio_value; ?>"><?php echo h($choice['question_choice_text']); ?></label>
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
                            foreach($choices_array as $choice){
                                if($choice['question_choice_correct'] == 1) {
                    ?>

                        <div class="alert alert-success">
                            <strong>Choice <?php echo $choice_cur_num; ?>: </strong> <?php echo $choice['question_choice_reason']; ?>
                        </div>

                                <?php
                                }
                                else if($choice['question_choice_correct'] == 0) {
                                ?>

                        <div class="alert alert-danger">
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
                    <div class="<?php echo $previous_display ?> previous_question_btn_div col-xs-3">
                        <button id="previous_question_btn_<?php echo $question_num ?>" type="button" class="btn btn-info btn-sm previous_button">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </button>
                    </div>
                    <div class="view_answers_btn_div col-xs-6">
                        <button disabled id="view_answers_btn_<?php echo $question_num ?>" class="btn btn-info btn-block btn-sm answers_button" type="button">SUBMIT</button>
                    </div>
                    <div class="next_question_btn_div col-xs-3">
                        <button id="next_question_btn_<?php echo $question_num ?>" type="button" class="btn btn-info btn-sm next_button">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div>
    <!--increment question number for display-->
    <?php $question_num++; ?>
    <!--end of question while loop-->
    <?php } ?>
    <!--Quizz Statistics Div for end of quiz results (Ajax call - will fill through javascript)-->
    <div id="quizz_statistics_card" class=" hidden container">
        <div class="page-header">
            <h3 style="text-align: center; margin-bottom: 25px;">Quizz Completed!  <span class="glyphicon glyphicon-ok-circle solution_glyphicon_correct"></span></h3>
        </div>
        <div id="quizz_statistics_data_table">
            <div id="total_score_display">
                <p id="final_score_percent">85%</p>
                <p id="final_score_txt">FINAL SCORE</p>
            </div>
            <hr>
            <div id="quizz_results_summary_div" class="row">
                <div id="num_correct_div" class ="col-xs-6">
                    <p id="num_correct_digit">6</p>
                    <p id="num_correct_txt">CORRECT</p>
                </div>
                <div id="num_incorrect_div" class ="col-xs-6">
                    <p id="num_incorrect_digit">2</p>
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
                    <button id="retry_quizz_btn" type="button" class="btn btn-default btn-sm previous_button">
                        <span class="glyphicon glyphicon-repeat" style="margin-right:10px;"></span>RETRY QUIZZ
                    </button>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div style="max-width: 700px; margin: auto;" id="quizz_progress_bar_div">
        <div class="progress">
          <div id="quizz_progress_bar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
        </div>
    </div>
</div>
<!-- *********************************** CONTENT END *********************************** -->

<!--load personal scripts-->
<script src="<?php echo url_for('js/main-script.js');?>"></script>
<script src="<?php echo url_for('js/quizz-script.js');?>"></script>

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quizz_page_footer.php'); ?>

