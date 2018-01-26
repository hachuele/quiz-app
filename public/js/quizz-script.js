/************************************************
* *******QUIZZ-SCRIPT CONTAINS FUNCTIONS******* *
* *********SPECIFIC TO THE QUIZZ PAGE********** *
*************************************************/




//WILL  USE THE QUESTION NUMBER !!!!!!
//var active = 1;
//
//function loadNext(){
//    if($("#next_question_btn_" + active).is(":enabled")){
//
//        //THE DATA SHOULD BE THE QUESTION NUMBER
//        //AJAX CALL TO LOAD QUESTIONS
//
//    }
//
//
//}


//USE JQUERY REGEX TO FIND ACTIVE QUESTION NUMBER

//PROBABLY WILL NOT NEED TO RESET PAGE!!!

//NOTE QUESTION FORM WILL CHANGE TO question_form_1...etc so won't reset.

/******************************************************
* THE RESET QUIZZ PAGE FUNCTION UPDATES/RESTORES THE
* FORM TO ITS ORIGINAL FORMAT.
*******************************************************/
function resetQuizzPage(){
    $("#answer_explanations_div").hide();
    //RESET THE FORM
//    $('#question_form')[0].reset();
    //DISABLE VIEW ANSWERS AND SUBMIT
    $("#view_answers_btn").attr('disabled', true);
    $("#submit_answers_btn").attr('disabled', true);
    $("#next_question_btn").attr('disabled', true);

}


/******************code runs once DOM ready******************/
$(document).ready(function(){
    //Reset the quizz page then update the footer
    resetQuizzPage();
    footerUpdate();

    // get current active question number (class ACTIVE) REGEX
    var currentQuestion = 1;


    /******************************************************
    * TOGGLE THE ANSWERS BOX (IF ENABLED)
    *******************************************************/
    $("#view_answers_btn").click(function(){
        toggleAnswersDiv();
    });


    /******************************************************
    * THE TOGGLE ANSWER DIC FUNCTION SHOWS THE ANSWER BOX
    * IF THE BUTTON IS ENABLED (WHEN ANSWER SELECTED)
    *******************************************************/
    function toggleAnswersDiv(){
        //check if the button is enabled (i.e: an answer has been selected)
        if($("#view_answers_btn").is(":enabled")){
            //AJAX CALL TO PROCESS ANSWERS



            $("#answer_explanations_div").show(1000, function(){
                footerUpdate();
                console.log("Worked!");
            });
            disableFormInput();
            enableNext();
        }
    }

    //NEED TO ADD QUESTION ID (AND EVERYTHING ELSE ON THE FUNCTIONS PAGE)
    /******************************************************
    * ENABLE ANSWER BUTTON ON INPUT CLICK (CHECKBOXES)
    *******************************************************/
    $('.checkbox_item').click(function () {
        $('#view_answers_btn').attr('disabled', !$('.checkbox_item:checked').length);
    });


    /******************************************************
    * ENABLE ANSWER BUTTON ON INPUT CLICK (CHECKBOXES)
    *******************************************************/
    $('.radio_item').click(function () {
        $('#view_answers_btn').attr('disabled', false);
    });



    /******************************************************
    * ENABLE NEXT CHECKS ENABLES MOVING TO THE NEXT QUESTION
    * OR SUBMITTING THE QUIZZ IF ALL QUESTIONS ANSWERED
    *******************************************************/
    //TODO: MUST USE DATABASE INFORMATION TO KNOW IF LAST QUESTION
    function enableNext(){
        //Enable the next question (if any) //THIS WILL NEED TO BE BASED ON NUM QUESTIONS
        $("#next_question_btn").prop('disabled', false);
    }

    /******************************************************
    * LOAD NEXT PERFORMS AN AJAX CALL TO RETRIEVE THE NEXT
    * QUESTION DATA (IF AVAILABLE)
    *******************************************************/
    function loadNext(){
        if($("#next_question_btn").is(":enabled")){

            //THE DATA SHOULD BE THE QUESTION NUMBER
            //AJAX CALL TO LOAD QUESTIONS

        }


    }


    /******************************************************
    * LOAD PREVIOUS PERFORMS AN AJAX CALL TO RETRIEVE THE
    * PREVIOUS' QUESTION DATA (IF AVAILABLE)
    *******************************************************/
    function loadPrevious(){
        if($("#previous_question_btn").is(":enabled")){

            //THE DATA SHOULD BE THE QUESTION NUMBER
            //AJAX CALL TO LOAD QUESTIONS

        }


    }


    /******************************************************
    * THE HIDE QUESTION CARD FUNCTION HIDES QUESTIONS
    *******************************************************/
    function hideQuestionCard(){

    }



    /******************************************************
    * THE DISABLE FORM INPUT FUNCTION DISABLES ALL INPUTS
    *******************************************************/
    function disableFormInput(){
        //Disable buttons and elements on click
        $("#view_answers_btn").prop('disabled', true);
        //Disable all checkbox/radio elements
        $("#question_form :input").prop("disabled", true);
    }




});
