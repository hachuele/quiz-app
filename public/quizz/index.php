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
    $questions_array = array();

    //ALSO NEED TO PASS USER ID TO DETERMINE NUM QUESTIONS ALREADY COMPLETED ETC ETC - LOAD EXISTING DATA


    $assessment_id = $_GET['assessment_id'] ?? '1'; // get the assessment id from url
    $assessment_name = get_assessment_name($assessment_id); // get the name of this assessment for display


    $current_q_num = 1; //Set current question number to 1


    $question_set = find_questions_by_assessment_id($assessment_id); //get questions for selected assessment
    $num_questions = mysqli_num_rows($question_set); //get the number of questions in the set
    //add question set to array for future manipulation
    while($question = mysqli_fetch_array($question_set, MYSQLI_BOTH)){
        array_push($questions_array, $question);
    }


    //When the user clicks next
    if(isset($_POST['submit'])){




    }







//    for($i = 0; $i < $num_questions; $i++){
//        $hello = "this:" . $questions_array[$i]['question_text'] . '<br>';
//        echo $hello;
//    }




    //NEED IF FUNCTION WITH FECTCHED DATA FROM user_answers TO FILL OUT FOR IF ALREADY SUBMITTED BEFORE. SINCE
    //ONLY DATA THERE IF SUBMITTED.

    //TODO: NEED TO REMEBER DATA IN CASE OF A REFRESH WITH A SESSION!! (SAVE CURRENT QUESTION NUMBER FOR EXAMPLE)

    //TODO: WILL NEED TO FETCH USER'S DETAILS TO FILL OUT PERCENT COMPLETE AND QUESTION BY QUESTION

    //only show previous button, for example, if not on first question
    //<?php if ($questionumber == 1) { ...

//    $choice_set_1 = find_choices_by_question_id(1);
//    $num_choices = mysqli_num_rows($choice_set_1);
//    echo $num_choices;


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

<div class="container main_content">

    <div id="quizz_question_div" class="container question_card">
        <div id="quizz_question_title" class="page-header">
            <h4 id="question_header"><strong>QUESTION 1:</strong> WHAT IS THE FIRST STEP A USER MUST TAKE WHEN LOGGING INTO HPC?</h4>
        </div>
        <div class="questions_list_div">
            <form id="question_form" action="process_answers.php" method="POST">
                <!--INNER FORM ELEMENTS GENERATED DYNAMICALLY WITH DATABASE (ALSO CHANGE CHECKBOX NUMVER DYNAMICALLY-->
                <div class="question_item_div center">
                    <div class="checkbox question_item">
                        <span class=" glyphicon glyphicon-ok solution_glyphicon_correct"></span><label class="question_label"><input class="checkbox_item" type="checkbox" name="check_1" value="">Option 1</label>
                    </div>
                </div>
                <div class="question_item_div center">
                    <div class="checkbox question_item">
                        <span class=" glyphicon glyphicon-ok solution_glyphicon_correct"></span><label class="question_label"><input class="checkbox_item" type="checkbox" name="check_2" value="">Option 2</label>
                    </div>
                </div>
                <div class="question_item_div center">
                    <div class="checkbox question_item">
                        <span class=" glyphicon glyphicon-remove solution_glyphicon_incorrect"></span><label class="question_label"><input class="checkbox_item" type="checkbox" name="check_3" value="">Option 3</label>
                    </div>
                </div>
                <div class="question_item_div center">
                    <div class="checkbox question_item">
                        <span class=" glyphicon glyphicon-remove solution_glyphicon_incorrect"></span><label class="question_label"><input class="checkbox_item" type="checkbox" name="check_4" value="">Option 4</label>
                    </div>
                </div>
                <br>
                <hr>
                <div id="answer_explanations_div" class="well">
                    <div class='alert alert-danger'><strong>Answer 1: </strong>This answer is wrong due to bla bla bla bla</div>
                    <div class='alert alert-success'><strong>Answer 2: </strong>This answer is correct due to bla bla bla bla</div>
                    <div class='alert alert-danger'><strong>Answer 3: </strong>This answer is wrong due to bla bla bla bla</div>
                    <div class='alert alert-success'><strong>Answer 4: </strong>This answer is correct due to bla bla bla bla</div>
                </div>

                <div class="row bottom_button_set">
                    <div id="previous_question_btn_div" class="col-xs-3">
                        <button id="previous_question_btn" type="button" class="btn btn-info">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </button>
                    </div>
                    <div id="view_answers_btn_div" class="col-xs-6">
                        <button id="view_answers_btn" class="btn btn-info btn-block" type="button">SUBMIT</button>
                    </div>
                    <div id="next_question_btn_div" class="col-xs-3">
                        <button id="next_question_btn" type="submit" class="btn btn-info">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div>
    <hr>

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

