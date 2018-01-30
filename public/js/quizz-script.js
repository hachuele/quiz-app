/************************************************
* *******QUIZZ-SCRIPT CONTAINS FUNCTIONS******* *
* *********SPECIFIC TO THE QUIZZ PAGE********** *
*************************************************/


/******************************************************
* THE RESET QUIZZ PAGE FUNCTION UPDATES/RESTORES THE
* FORM TO ITS ORIGINAL FORMAT.
*******************************************************/
function resetQuizzPage(){
//    $("#answer_explanations_div").hide();
    //RESET THE FORM
//    $('#question_form')[0].reset();
    //DISABLE VIEW ANSWERS AND SUBMIT
//    $("#view_answers_btn").attr('disabled', true);
//    $("#next_question_btn").attr('disabled', true);

    $("[id^=answer_explanations_div]").hide();
    $("[id^=view_answers_btn]").attr('disabled', true);
    $("[id^=next_question_btn]").attr('disabled', true);


}


/******************code runs once DOM ready******************/
$(document).ready(function(){
    //Reset the quizz page then update the footer
    resetQuizzPage();
    footerUpdate();

    /* Instantiate global variables */
    var numQuestions = countNumQuestions();
    var currQ = currentQuestionNum();
    var currID = currentQuestionID();


    /* ------------------ FUNCTION DEFINITIONS ------------------ */

    /************************************************************
    * THE SUBMIT ANSWER FUNCTION SHOWS THE ANSWER BOX.
    * PERFORMS AJAX CALL TO SUBMIT USER DATA AND RETRIEVE ANSWERS
    *************************************************************/
    function submitAnswers(questionNumber){
        //check if the button is enabled (i.e: an answer has been selected)
        if($("#view_answers_btn_" + questionNumber).is(":enabled")){

            /* AJAX CALL TO PROCESS ANSWERS */



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
    //TODO: MUST USE DATABASE INFORMATION TO KNOW IF LAST QUESTION
    function enableNext(questionNum){
        //Enable the next question (if any) //THIS WILL NEED TO BE BASED ON NUM QUESTIONS
        $("#next_question_btn_" + questionNum).prop('disabled', false);
    }

    /******************************************************
    * LOAD NEXT SHOWS THE NEXT QUESTION CARD.
    * (IF ANY AVAILABLE)
    *******************************************************/
    function loadNext(questionNumber){
        var questionID = currentQuestionID();
        console.log(questionNumber);
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
        numQuestions = $(".question_card").length;
        return numQuestions;
    }

    /*********************************************************
    * GETS THE ID OF THE CURRENT QUESTION (question_id)
    **********************************************************/
    function currentQuestionID(){
        //finds item with active class, gets question num using regex
        questionCardID = $('div .active').attr('id');
        questionIDStr = questionCardID.match(/[_]\d/g);
        questionID = questionIDStr[0].match(/\d/g);
        return parseInt(questionID);
    }


    /*********************************************************
    * GETS THE NUMBER OF THE CURRENT QUESTION
    **********************************************************/
    function currentQuestionNum(){
        //finds item with active class, gets question num using regex
        questionCardNum = $('div .active').attr('id');
        questionNumStr = questionCardNum.match(/[_]\d/g);
        questionNum = questionNumStr[0].match(/\d/g);
        return parseInt(questionNum);
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
