<?php
    //start the session
    session_start();
?>
<?php require_once('../../private/initialize.php'); ?>

<!--STATIC VARIABLES FOR CURRENT PAGE-->
<?php
    #define variales for current page
    $site_title = 'HPC Assessments Site';
    $page_title = 'HPC QUIZZ'; #TODO: INCLUDE QUIZZ SELECTED
?>




<!--DATA FROM SQL DATABASE-->
<?php

    //INITIALIZE VARIABLES
    $user_id = 'hachuelb';
    //USER ID LATEST QUESTION IS MAX(QUESTION_ID) FROM USER ANSWERS TABLE



//    unset($_SESSION['currentQuestion']);

    //would set to latest completed question first, then change to whatever user is viewing
    $_SESSION["currentQuestion"] = 1;


//    if(!isset($_SESSION["currentQuestion"])){
//        $_SESSION["currentQuestion"] = 1; //should be the latest completed question from database
//    }


    //ALSO NEED TO PASS USER ID TO DETERMINE NUM QUESTIONS ALREADY COMPLETED ETC ETC - LOAD EXISTING DATA

    $assessment_id = $_GET['assessment_id'] ?? '1'; // get the assessment id from url
    $assessment_name = get_assessment_name($assessment_id); // get the name of this assessment for display


    $question_set = find_questions_by_assessment_id($assessment_id); //get questions for selected assessment
    $num_questions = mysqli_num_rows($question_set); //get the number of questions in the set





    $question_id_num = array();

//    $questions_array = array();
    //add question set to array for future manipulation
//    while($row = mysqli_fetch_array($question_set, MYSQLI_BOTH)){
//        array_push($questions_array, $row);
//    }

//    $choice_set = find_choices_by_question_id(1);
//    $choices_array = array();
//     while($choice = mysqli_fetch_array($choice_set, MYSQLI_BOTH)){
////        array_push($questions_array, $question);
//         echo $choice['question_choice_text'];
//    }

    //use choice id for in the choice while loop (give an id to the check or radio name)
//    for($i = 0; $i < $num_questions; $i++){
//        $hello = "this:" . $questions_array[$i]['question_text'] . '<br>';
//        echo $hello;
//    }

//SELECT MAX( `column` ) FROM `table` ;
    //NEED IF FUNCTION WITH FECTCHED DATA FROM user_answers TO FILL OUT FOR IF ALREADY SUBMITTED BEFORE. SINCE
    //ONLY DATA THERE IF SUBMITTED.

    //TODO: NEED TO REMEBER DATA IN CASE OF A REFRESH WITH A SESSION!! (SAVE CURRENT QUESTION NUMBER FOR EXAMPLE)

    //TODO: WILL NEED TO FETCH USER'S DETAILS TO FILL OUT PERCENT COMPLETE AND QUESTION BY QUESTION

    //only show previous button, for example, if not on first question
    //<?php if ($questionumber == 1) { ...




?>


<!-- *********************************** PAGE HEADER *********************************** -->
<?php require(SHARED_PATH . '/quizz_page_header.php'); ?>

<!-- *********************************** CONTENT START *********************************** -->
<div class="container-fluid assessment_title_main centering_div">
    <div id ="assessment_title_row_div" class="row centered_div">
        <div class="col-sm-12">
            <div class="row">
                <h3 id="assessment_title_txt"><?php echo h($assessment_name) ?></h3>
            </div>
        </div>
    </div>
</div>
<!--CAN USE QUESTION NUMBER TO HIDE/ACTIVATE QUESTIONS - WHEN HAVE COMPLETED SOME QUESTIONS, GO TO THAT ONE-->
<!--ADD ACTIVE CLASS TO MAKE IT VISIBLE, NO NEED TO REMOVE HIDDEN CLASS-->
<div class="container main_content">
    <?php $question_num = 1; ?>
    <!--START MAIN PHP LOOP FOR GENERATING QUESTIONS-->
    <?php while($question = mysqli_fetch_assoc($question_set)) { ?>
    <?php $question_active_class = ''; ?>
    <?php
        if($question_num == $_SESSION["currentQuestion"]){
            $question_active_class = 'active';
        } else{
            $question_active_class = '';
        }
    ?>
    <!--question card's ID is composed of the db 'question_id' field and the question_num variable for future extraction-->
    <div id="question_card_<?php echo h($question['question_id']) ?>-<?php echo $question_num ?>" class="hidden <?php echo $question_active_class ?> quizz_question_div container question_card">
        <div class="page-header">
            <h4><strong>QUESTION <?php echo $question_num ?>:</strong> <?php echo h($question['question_text']) ?></h4>
        </div>
        <div class="questions_list_div">
            <form id="question_form_<?php echo h($question['question_id']) ?>" action="process_answers.php" method="POST">
                <!--LOAD QUESTION CHOICES-->
                <?php
                    if ($question['question_multivalued'] == 1){
                        $question_type_class = 'checkbox';
                        $choice_name = 'check';
                    } else{
                        $question_type_class = 'radio';
                        $choice_name = 'radio';
                    }
                ?>
                <?php $choice_set = find_choices_by_question_id(h($question['question_id'])); ?>
                <?php while($choice = mysqli_fetch_assoc($choice_set)) { ?>

                <div class="question_item_div center">
                    <div class="<?php echo $question_type_class ?> question_item">
                        <!--USE CHOICE ID FOR THE NAME, REMOVE HIDDEN CLASS and add other classes WITH AJAX-->
                        <span class="hidden glyphicon glyphicon-ok solution_glyphicon_correct"></span><label class="question_label"><input class="<?php echo $question_type_class ?>_item" type="<?php echo $question_type_class ?>" name="<?php echo $choice_name ?>_<?php echo h($question['question_id']) ?>" value=""><?php echo h($choice['question_choice_text']) ?></label>
                    </div>
                </div>

                <?php } ?>


<!--
                <div class="question_item_div center">
                    <div class="checkbox question_item">
                        <span class=" glyphicon glyphicon-remove solution_glyphicon_incorrect"></span><label class="question_label"><input class="checkbox_item" type="checkbox" name="check_3" value="">Option 3</label>
                    </div>
                </div>
-->

                <br>
                <hr>
                <!--ECHO/ADD THIS DIV DYNAMICALLY WITH AJAX-->
                <div id="answer_explanations_div" class="well">
                    <div class='alert alert-danger'><strong>Answer 1: </strong>This answer is wrong due to bla bla bla bla</div>
                    <div class='alert alert-success'><strong>Answer 2: </strong>This answer is correct due to bla bla bla bla</div>
                    <div class='alert alert-danger'><strong>Answer 3: </strong>This answer is wrong due to bla bla bla bla</div>
                    <div class='alert alert-success'><strong>Answer 4: </strong>This answer is correct due to bla bla bla bla</div>
                </div>

                <div class="row bottom_button_set">
                    <div class="previous_question_btn_div col-xs-3">
                        <button id="previous_question_btn_<?php echo $question_num ?>" type="button" class="btn btn-info">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </button>
                    </div>
                    <div class="view_answers_btn_div col-xs-6">
                        <button id="view_answers_btn" class="btn btn-info btn-block" type="button">SUBMIT</button>
                    </div>
                    <div class="next_question_btn_div col-xs-3">
                        <button id="next_question_btn_<?php echo $question_num ?>" type="button" class="btn btn-info">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div>
    <hr>
    <!--increment question number for display-->
    <?php $question_num++; ?>
    <!--end of question while loop-->
    <?php } ?>


    <div id="quizz_progress_bar_div">
        <div class="progress">
          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%">40%</div>
        </div>
    </div>
</div>
<!-- *********************************** CONTENT END *********************************** -->

<!--load personal scripts-->
<script src="<?php echo url_for('js/main-script.js');?>"></script>
<script src="<?php echo url_for('js/quizz-script.js');?>"></script>

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quizz_page_footer.php'); ?>

