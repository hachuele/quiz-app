/******************************************************************
 * DESCRIPTION:                                                   *
 *                                                                *
 * -------------------------------------------------------------- *
 * @author: Eric J. Hachuel                                       *
 * University of Southern California, High-Performance Computing  *
 ******************************************************************/


/************************************************
* *******QUIZZ-SCRIPT CONTAINS FUNCTIONS******* *
* *********SPECIFIC TO THE QUIZZ PAGE********** *
*************************************************/


/******************code runs once DOM ready******************/
$(document).ready(function(){
    //Reset the quizz page then update the footer
    resetQuizzPage();
    footerUpdate();

    //disable form input for completed questions

    /* Instantiate global variables */
    numQuestions = countNumQuestions();


    /* ------------------ FUNCTION DEFINITIONS ------------------ */


    /******************************************************
    * THE RESET QUIZZ PAGE FUNCTION UPDATES/RESTORES THE
    * FORM TO ITS ORIGINAL FORMAT.
    *******************************************************/
    function resetQuizzPage(){
        $("[id^=answer_explanations_div]").hide();
        $("[id^=view_answers_btn]").attr('disabled', true);
        $("[id^=next_question_btn]").attr('disabled', true);
    }

    /************************************************************
    * THE SUBMIT ANSWER FUNCTION SHOWS THE ANSWER BOX.
    * PERFORMS AJAX CALL TO SUBMIT USER DATA AND RETRIEVE ANSWERS
    *************************************************************/
    function submitAnswers(questionNumber){
        //check if the button is enabled (i.e: an answer has been selected)
        if($("#view_answers_btn_" + questionNumber).is(":enabled")){
            /* Serialize for data for ajax request */
            var formData = $("#question_form_" + questionNumber).serialize();
            var questionID = currentQuestionID();
            var questionType = currentQuestionType();

            /* ------ AJAX CALL TO PROCESS ANSWERS ------ */
            $.ajax({
                type     : 'POST',
                url      : '../../private/process_answers.php',
                data     : formData + '&question_id=' + questionID + '&question_type=' + questionType,
                dataType : 'json',
                encode   : true
            }).done(function(data){

                console.log(data);

                var numChoices = data['num_choices'];


//correct_selected
//correct_not_selected
//incorrect_selected
//incorrect_not_selected

                /* Show answer details within form */
                for(i = 0; i < numChoices; i++){
                    if(questionType == 'checkbox'){
                        if(data['user_selection_details'][i] == 'correct_selected'){
                            $("#question_choice_id_" + data['choice_ids'][i]).addClass('glyphicon glyphicon-ok-circle solution_glyphicon_correct');
                        }
                        else if(data['user_selection_details'][i] == 'incorrect_selected'){
                            $("#question_choice_id_" + data['choice_ids'][i]).addClass('glyphicon glyphicon-remove-circle solution_glyphicon_incorrect');
                        }
                        else if((data['user_selection_details'][i] == 'correct_not_selected')){
                            $("#question_choice_id_" + data['choice_ids'][i]).addClass('glyphicon glyphicon-remove-circle solution_glyphicon_correct_not_selected');
                        }
                    }
                    /* If question is of 'radio' type */
                    else{
                        if(data['user_selection_details'][i] == 'correct_selected'){
                            $("#question_choice_id_" + data['choice_ids'][i]).addClass('glyphicon glyphicon-ok-circle solution_glyphicon_correct');
                        }
                        else if((data['user_selection_details'][i] == 'incorrect_selected')){
                            $("#question_choice_id_" + data['choice_ids'][i]).addClass('glyphicon glyphicon-remove-circle solution_glyphicon_incorrect');
                        }
                    }
                    /* Remove no_display class */
                    $("#question_choice_id_" + data['choice_ids'][i]).addClass('choice_mark');
                }



                /* Show additional answer details in answers_div */




//                <div class='alert alert-danger'><strong>Answer 1: </strong>This answer is wrong due to bla bla bla bla</div>
//                <div class='alert alert-success'><strong>Answer 2: </strong>This answer is correct due to bla bla bla bla</div>
                // add glyphicon to question_choice_id_#

                //$data['correct-glyphicon-class'] = 'glyphicon glyphicon-ok solution_glyphicon_correct';
                //$data['incorrect-glyphicon-class'] = 'glyphicon glyphicon-remove-sign solution_glyphicon_incorrect';


               }).fail(function(data) {


                console.log("Error in Request");

                });



            // update footer
            $("#footer_row").removeClass("footer_adjust_abs").addClass("footer_adjust_rel");

            $("#answer_explanations_div_" + questionNumber).show(1000, function(){
                footerUpdate();
            });

            $('html, body').animate({
                   scrollTop: $("#answer_explanations_div_" + questionNumber).offset().top}, 2000);

            disableFormInput(questionNumber);
            /* Enable Quizz Navigation Buttons */
            enableNext(questionNumber);
            enablePrevious(questionNumber);
        }
    }




    /******************************************************
    * ENABLE NEXT CHECKS ENABLES MOVING TO THE NEXT QUESTION
    * OR ENDING THE QUIZZ IF ALL QUESTIONS ANSWERED
    *******************************************************/
    function enableNext(questionNumber){
        //Enable the next question (if any)
        $("#next_question_btn_" + questionNumber).prop('disabled', false);
    }

    /******************************************************
    * LOAD NEXT SHOWS THE NEXT QUESTION CARD.
    * (IF ANY AVAILABLE)
    *******************************************************/
    function loadNext(questionNumber){
        if($("#next_question_btn_" + questionNumber).is(":enabled")){
            if(questionNumber != numQuestions){
                hideActiveQuestionCard(questionNumber);
                activateQuestionCard(++questionNumber);
                footerUpdate();
            } else{
                /* Redirect to end of quizz page (details) */
                alert('END OF QUIZZ');
            }
        }
    }


    /******************************************************
    * ENABLE PREVIOUS CHECKS ENABLES MOVING TO THE PREVIOUS
    * QUESTION
    *******************************************************/
    function enablePrevious(questionNumber){
        //Enable the previous question (if any)
        if(questionNumber != 1){
            $("#previous_question_btn_" + questionNumber).prop('disabled', false);
        }
    }

    /******************************************************
    * LOAD PREVIOUS PERFORMS AN AJAX CALL TO RETRIEVE THE
    * PREVIOUS' QUESTION DATA (IF AVAILABLE)
    *******************************************************/
    function loadPrevious(questionNumber){
        if($("#previous_question_btn_" + questionNumber).is(":enabled")){
            hideActiveQuestionCard(questionNumber);
            activateQuestionCard(--questionNumber);
            footerUpdate();
        }
    }

    /******************************************************
    * THE HIDE QUESTION CARD FUNCTION HIDES QUESTIONS
    *******************************************************/
    function hideActiveQuestionCard(questionNumber){
        $('#question_card_' + questionNumber).removeClass('active');
    }

    /********************************************************
    * THE ACTIVATE QUESTION CARD FUNCTION ACTIVATES QUESTIONS
    *********************************************************/
    function activateQuestionCard(questionNumber){
        $('#question_card_' + questionNumber).addClass('active');
    }

    //TODO: WILL FOR LOOP ON ENTRY OVER COMPLETED QUESTIONS TO DISABLE
    /******************************************************
    * THE DISABLE FORM INPUT FUNCTION DISABLES ALL INPUTS
    *******************************************************/
    function disableFormInput(questionNumber){
        //Disable buttons and elements on click
        $("#view_answers_btn_" + questionNumber).prop('disabled', true);
        //Disable all checkbox/radio elements
        $("#question_form_" + questionNumber + " :input").prop("disabled", true);
    }


    /*********************************************************
    * COUNTS THE NUMBER OF QUESTIONS IN THE CURRENT ASSESSMENT
    **********************************************************/
    function countNumQuestions(){
        var numQuestions = $(".question_card").length;
        return numQuestions;
    }

    /*********************************************************
    * GETS THE ID OF THE CURRENT QUESTION (question_id)
    **********************************************************/
    function currentQuestionID(){
        //finds item with active class, gets question num using regex
        var questionCardID = $('div .active').attr('data-questionid');
        return parseInt(questionCardID);
    }


    /*********************************************************
    * GETS THE NUMBER OF THE CURRENT QUESTION
    **********************************************************/
    function currentQuestionNum(){
        //finds item with active class, gets question num using regex
        var questionCardNum = $('div .active').attr('id');
        var questionNumStr = questionCardNum.match(/[_]\d/g);
        var questionNum = questionNumStr[0].match(/\d/g);
        return parseInt(questionNum);
    }


    /*********************************************************
    * GETS THE QUESTION TYPE OF THE CURRENT QUESTION
    **********************************************************/
    function currentQuestionType(){
        //finds item with active class, gets question num using regex
        var questionType = $('div .active').attr('data-questiontype');
        return questionType;
    }



    /* --------------------- CLICK EVENTS --------------------- */



    /******************************************************
    * ANSWERS BUTTON CLICK EVENT
    *******************************************************/
    $("[id^=view_answers_btn_]").click(function(){
        var questionNumber = currentQuestionNum();
        submitAnswers(questionNumber);
    });


    /******************************************************
    * NEXT QUESTION CLICK EVENT
    *******************************************************/
    $("[id^=next_question_btn_]").click(function(){
        var questionNumber = currentQuestionNum();
        loadNext(questionNumber);
    });


    /******************************************************
    * PREVIOUS QUESTION CLICK EVENT
    *******************************************************/
    $("[id^=previous_question_btn_]").click(function(){
        var questionNumber = currentQuestionNum();
        loadPrevious(questionNumber);
    });


    //NEED TO ADD QUESTION ID (AND EVERYTHING ELSE ON THE FUNCTIONS PAGE)
    /******************************************************
    * ENABLE ANSWER BUTTON ON INPUT CLICK (CHECKBOXES)
    *******************************************************/
    $('.checkbox_item').click(function () {
        var questionNumber = currentQuestionNum();
        $('#view_answers_btn_' + questionNumber).attr('disabled', !$('.checkbox_item:checked').length);
    });


    /******************************************************
    * ENABLE ANSWER BUTTON ON INPUT CLICK (CHECKBOXES)
    *******************************************************/
    $('.radio_item').click(function () {
        var questionNumber = currentQuestionNum();
        $('#view_answers_btn_' + questionNumber).attr('disabled', false);
    });



});
