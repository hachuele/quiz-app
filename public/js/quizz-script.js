/************************************************
* *******QUIZZ-SCRIPT CONTAINS FUNCTIONS******* *
* *********SPECIFIC TO THE QUIZZ PAGE********** *
*************************************************/

/******************************************************
* THE TOGGLE ANSWER DIC FUNCTION SHOWS THE ANSWER BOX
* IF THE BUTTON IS ENABLED (WHEN ANSWER SELECTED)
*******************************************************/
function toggleAnswersDiv(){
    //check if the button is enabled (i.e: an answer has been selected)

    //AJAX CALL HERE

    if($("#view_answers_btn").is(":enabled")){
        //WILL NEED TO RETRIEVE ANSWERS FIRST
        $("#answer_explanations_div").show(1000, function(){
            footerUpdate();
            console.log("Worked!");
        });
        disableFormInput();
        enableNext();
    }
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
* THE RESET QUIZZ PAGE FUNCTION UPDATES/RESTORES THE
* FORM TO ITS ORIGINAL FORMAT.
*******************************************************/
function resetQuizzPage(){
    $("#answer_explanations_div").hide();
    //RESET THE FORM
    $('#question_form')[0].reset();
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


    /******************************************************
    * TOGGLE THE ANSWERS BOX (IF ENABLED)
    *******************************************************/
    $("#view_answers_btn").click(function(){
        toggleAnswersDiv();


        var quizzSelectDiv = $(".main_content").outerHeight();
        var windowHeight = $(window).height();

        console.log("QUIZZ DIV HEIGHT toggle: " + quizzSelectDiv);
        console.log("WINDOW HEIGHT toggle: " + windowHeight);

    });

    /******************************************************
    * ENABLE ANSWER BUTTON ON INPUT CLICK
    *******************************************************/
    $('.checkbox_item').click(function () {
        $('#view_answers_btn').attr('disabled', !$('.checkbox_item:checked').length);
    });




});
