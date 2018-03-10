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
//        $("[id^=answer_explanations_div]").hide();

//        $("[id^=answer_explanations_div]").attr('hidden', 'true');
//
//        $("[id^=view_answers_btn]").attr('disabled', true);
//        $("[id^=next_question_btn]").attr('disabled', true);
    }

    /************************************************************
    * THE SUBMIT ANSWER FUNCTION SHOWS THE ANSWER BOX.
    * PERFORMS AJAX CALL TO SUBMIT USER DATA AND RETRIEVE ANSWERS
    *************************************************************/
    function submitAnswers(questionNumber){
        //check if the button is enabled (i.e: an answer has been selected)
        if($("#view_answers_btn_" + questionNumber).is(":enabled")){

            /* hide the answers div and remove hidden class */
            $("#answer_explanations_div_" + questionNumber).hide().removeClass('hidden');

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

                var numChoices = data['num_choices'];
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
                for(i = 0; i < numChoices; i++){
                    if((data['user_selection_details'][i] == 'correct_selected') || (data['user_selection_details'][i] == 'correct_not_selected')){
                        $("#answer_explanations_div_" + questionNumber).append("<div class=\"alert alert-success\"><strong>Choice " + (i + 1) + ": </strong>" + data['reponse_details'][i] + "</div>");
                    }
                    else if((data['user_selection_details'][i] == 'incorrect_selected') || (data['user_selection_details'][i] == 'incorrect_not_selected')){
                        $("#answer_explanations_div_" + questionNumber).append("<div class=\"alert alert-danger\"><strong>Choice " + (i + 1) + ": </strong>" + data['reponse_details'][i] + "</div>");
                    }
                }

                // update footer
                $("#footer_row").removeClass("footer_adjust_abs").addClass("footer_adjust_rel");
                $("#answer_explanations_div_" + questionNumber).show(1100, function(){
                    footerUpdate();
                });
                // animate show of question answers
                $('html, body').animate({
                       scrollTop: $("#answer_explanations_div_" + questionNumber).offset().top}, 2000);

                disableFormInput(questionNumber);
                /* Enable Quizz Navigation Buttons */
                enableNext(questionNumber);
                enablePrevious(questionNumber);
                animateProgressBar();

               }).fail(function(data) {
                console.log("Error in Request");
                });
        }
    }




    /***************************************************************
    * THE LOAD ANSWERS FUNCTION LOADS PREVIOUSLY COMPLETED QUESTIONS
    * PERFORMS AJAX CALL TO LOOP THROUGH USER DATA AND DISPLAY
    * - (called on page load [doc redy] and reload)
    ****************************************************************/
    function loadPreviousAnswers(){
//CHECK IF IN PROGRESS, LOOP OVER QUESTIONS, DISABLING AND ENBLING BUTTONS AND FORMS


    }


    /******************************************************
    * ENABLE NEXT CHECKS ENABLES MOVING TO THE NEXT QUESTION
    * OR ENDING THE QUIZZ IF ALL QUESTIONS ANSWERED
    *******************************************************/
    function enableNext(questionNumber){
        //Enable the next question (if any)
        if(questionNumber == numQuestions){
            $("#next_question_btn_" + questionNumber).animate({
                width: '60px'
            });
            $('#next_question_btn_' + questionNumber).removeClass('btn-info').addClass('btn-success');
        }
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
                /* Show quizz results! */

                //TODO: AJAX CALL TO GET ALL THE DATA FROM THE TABLE FOR STATISTICS DISPLAY !!!!!!!!


                showEndQuizzDetails(questionNumber);

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
    * LOAD LAST SHOWS THE LAST QUESTION OF THE QUIZZ
    * FROM CURRENT END OF QUIZZ SCREEN
    *******************************************************/
    function loadLast(){
        hideEndQuizzDetails();
        footerUpdate();
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

    /********************************************************
    * SHOW END OF QUIZZ DETAILS 'PAGE'
    *********************************************************/
    function showEndQuizzDetails(questionNumber){
        $('#question_card_' + questionNumber).removeClass('active');
        $('#quizz_progress_bar_div').addClass('hidden');
        $('#quizz_statistics_card').addClass('active');
        $("#end_of_quizz_navigation").addClass('active');
    }

    /********************************************************
    * HIDE END OF QUIZZ DETAILS 'PAGE' (SHOW LAST QUESTION)
    *********************************************************/
    function hideEndQuizzDetails(){
        $('#quizz_statistics_card').removeClass('active');
        $('#quizz_progress_bar_div').addClass('active');
        $('#question_card_' + numQuestions).addClass('active');
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
    * GETS THE PERCENTAGE COMPLETION OF QUIZZ
    **********************************************************/
    function getPercentComplete(){
        var questionNum = currentQuestionNum();
        return parseInt((questionNum / numQuestions) * 100);
    }


    /*********************************************************
    * ANIMATES THE PROGRESS BAR FOR PERCENT COMPLETE
    **********************************************************/
    function animateProgressBar(){
        var percentComplete = getPercentComplete();
        var widthComplete = percentComplete + '%';

        $("#quizz_progress_bar").attr('aria-valuenow', percentComplete);

        $("#quizz_progress_bar").animate({
            width: widthComplete
        }, 1000);

        $("#quizz_progress_bar").text(widthComplete);

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
    * BACK HOME CLICK EVENT
    *******************************************************/
    $("#back_home_btn").click(function(){
        window.location.href = "../index.php";
    });

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

    /******************************************************
    * END OF QUIZZ: LOAD LAST QUESTION
    *******************************************************/
    $('#end_previous_question_btn').click(function () {
        loadLast();
    });

});
