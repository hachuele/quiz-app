/***********************************************************************
 * DESCRIPTION: quiz-script.js contains javascript code for the
 * public/quiz/index.php page
 *                            ---
 * @author: Eric J. Hachuel
 * Copyright 2018 University of Southern California. All rights reserved.
 *
 * This software is experimental in nature and is provided on an AS-IS basis only.
 * The University SPECIFICALLY DISCLAIMS ALL WARRANTIES, EXPRESS AND IMPLIED,
 * INCLUDING WITHOUT LIMITATION ANY WARRANTY AS TO MERCHANTIBILITY OR FITNESS
 * FOR A PARTICULAR PURPOSE.
 *
 * This software may be reproduced and used for non-commercial purposes only,
 * so long as this copyright notice is reproduced with each such copy made.
 *
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

/******************code runs once DOM ready******************/
$(document).ready(function(){
    numQuestions = countNumQuestions();

    /* ------------------------------------ FUNCTION DEFINITIONS ------------------------------------ */

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
            var questionIndex = '';
            /* use question index for database inserts */
            if(questionNumber == 1 && numQuestions == 1){
                questionIndex = 'first_last';
            }
            else if(questionNumber == 1){
                questionIndex = 'first';
            }
            else if(questionNumber == numQuestions){
                questionIndex = 'last';
            }
            else{
                questionIndex = 'other';
            }
            alert(questionIndex);
            /* ------ AJAX CALL TO PROCESS ANSWERS ------ */
            $.ajax({
                type     : 'POST',
                url      : '../../private/process_answers.php',
                data     : formData + '&question_id=' + questionID + '&question_type=' + questionType + '&question_index=' + questionIndex,
                dataType : 'json',
                encode   : true
            }).done(function(data){

                var numChoices = data['num_choices'];
                var userAssessmentID = data['user_assessment_id'];
                $('#assessment_title_row_div').attr('data-user-assessment-id', userAssessmentID);
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
                        $("#answer_explanations_div_" + questionNumber).append("<div class=\"alert alert-incorrect\"><strong>Choice " + (i + 1) + ": </strong>" + data['reponse_details'][i] + "</div>");
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
                /* Enable quiz Navigation Buttons */
                enableNext(questionNumber);
                enablePrevious(questionNumber);
                animateProgressBar();

               }).fail(function(data) {
                console.log(data);
                alert("The was an error. Please try again later or contact your HPC POC.");
                });
        }
    }

    /******************************************************
    * ENABLE NEXT CHECKS ENABLES MOVING TO THE NEXT QUESTION
    * OR ENDING THE quiz IF ALL QUESTIONS ANSWERED
    *******************************************************/
    function enableNext(questionNumber){
        //Enable the next question (if any)
        if(questionNumber == numQuestions){
            $("#next_question_btn_" + questionNumber).animate({
                width: '60px'
            });
            $('#next_question_btn_' + questionNumber).removeClass('btn-primary').addClass('btn-success');
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
            }
            /* Show quiz results! */
            else{
                var userAssessmentID = $('#assessment_title_row_div').attr('data-user-assessment-id');

                /* ------ AJAX CALL TO GET FINAL STATISTICS ANSWERS ------ */
                $.ajax({
                    type     : 'POST',
                    url      : '../../private/load_stats.php',
                    data     : 'user_assessment_id=' + userAssessmentID,
                    dataType : 'json',
                    encode   : true
                }).done(function(data){

                    var assessmentID = data['assessment_id'];
                    var numCorrect = data['num_correct'];
                    var numIncorrect = data['num_incorrect'];
                    var finalScore = Math.round(((numCorrect) / (numQuestions)) * 100);
                    var finalScoreString = finalScore + '%';

                    $("#final_score_percent").html(finalScoreString);
                    $('#num_correct_digit').html(numCorrect);
                    $("#num_incorrect_digit").html(numIncorrect);

                    showEndquizDetails(questionNumber);

                   }).fail(function(data) {
                    console.log(data);
                    alert("The was an error. Please try again later or contact your HPC POC.");
                    });
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
    * LOAD LAST SHOWS THE LAST QUESTION OF THE quiz
    * FROM CURRENT END OF quiz SCREEN
    *******************************************************/
    function loadLast(){
        hideEndquizDetails();
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
    * SHOW END OF quiz DETAILS 'PAGE'
    *********************************************************/
    function showEndquizDetails(questionNumber){
        $('#question_card_' + questionNumber).removeClass('active');
        $('#quiz_progress_bar_div').addClass('hidden');
        $('#quiz_statistics_card').addClass('active');
        $("#end_of_quiz_navigation").addClass('active');
    }

    /********************************************************
    * HIDE END OF quiz DETAILS 'PAGE' (SHOW LAST QUESTION)
    *********************************************************/
    function hideEndquizDetails(){
        $('#quiz_statistics_card').removeClass('active');
        $('#quiz_progress_bar_div').addClass('active');
        $('#question_card_' + numQuestions).addClass('active');
    }


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
    * GETS THE PERCENTAGE COMPLETION OF quiz
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

        $("#quiz_progress_bar").attr('aria-valuenow', percentComplete);

        $("#quiz_progress_bar").animate({
            width: widthComplete
        }, 1000);

        $("#quiz_progress_bar").text(widthComplete);

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
    $("[class^=checkbox_item_]").click(function () {
        var questionNumber = currentQuestionNum();
        $('#view_answers_btn_' + questionNumber).attr('disabled', !$('.checkbox_item_' + questionNumber + ':checked').length);
    });

    /******************************************************
    * ENABLE ANSWER BUTTON ON INPUT CLICK (RADIO BUTTONS)
    *******************************************************/
    $("[class^=radio_item_]").click(function () {
        var questionNumber = currentQuestionNum();
        $('#view_answers_btn_' + questionNumber).attr('disabled', false);
    });

    /******************************************************
    * END OF quiz: LOAD LAST QUESTION
    *******************************************************/
    $('#end_previous_question_btn').click(function () {
        loadLast();
    });

});
