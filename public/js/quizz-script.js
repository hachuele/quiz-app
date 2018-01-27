/************************************************
* *******QUIZZ-SCRIPT CONTAINS FUNCTIONS******* *
* *********SPECIFIC TO THE QUIZZ PAGE********** *
*************************************************/


//WILL  USE THE QUESTION NUMBER !!!!!!
//var active = 1;
//function loadNext(){
//    if($("#next_question_btn_" + active).is(":enabled")){
//    }
//}
//PROBABLY WILL NOT NEED TO RESET PAGE!!!


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
    var numQuestions = countNumQuestions();


    /* ------------------ FUNCTION DEFINITIONS ------------------ */

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




    /******************************************************
    * ENABLE NEXT CHECKS ENABLES MOVING TO THE NEXT QUESTION
    * OR ENDING THE QUIZZ IF ALL QUESTIONS ANSWERED
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
            //hide current question (remove active class)
            //activate next question (if any)
            //change next button if at last question (to details page)
            currQuestionNum = currentQuestionNum();
            if(currentQuestionNum != countNumQuestions){
                hideActiveQuestionCard(currQuestionNum);
                activateQuestionCard(currQuestionNum++);

            } else{
                alert('END OF QUIZZ');
            }
        }
    }


    /******************************************************
    * ENABLE PREVIOUS CHECKS ENABLES MOVING TO THE PREVIOUS
    * QUESTION
    *******************************************************/
    function enablePrevious(){
        //Enable the next question (if any) //THIS WILL NEED TO BE BASED ON NUM QUESTIONS
        $("#previous_question_btn").prop('disabled', false);
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
    function hideActiveQuestionCard(questionNumber){
        $('#question_card_' + questionNumber).removeClass('active');
    }

    /********************************************************
    * THE ACTIVATE QUESTION CARD FUNCTION ACTIVATES QUESTIONS
    *********************************************************/
    function activateQuestionCard(questionNumber){
        $('#question_card_' + questionNumber).addClass('active');
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


    /*********************************************************
    * COUNTS THE NUMBER OF QUESTIONS IN THE CURRENT ASSESSMENT
    **********************************************************/
    function countNumQuestions(){
        numQuestions = $(".question_card").length;
        return numQuestions;
    }

    /*********************************************************
    * GETS THE NUMBER OF THE CURRENT QUESTION
    **********************************************************/
    function currentQuestionNum(){
        //finds item with active class, gets question num using regex
        questionCardID = $('div .active').attr('id');
        questionNum = questionCardID.match(/\d/g);
        return questionNum;
    }



    /* ------------------ CLICK EVENTS ------------------ */



    /******************************************************
    * TOGGLE THE ANSWERS BOX (IF ENABLED)
    *******************************************************/
    $("#view_answers_btn").click(function(){
        toggleAnswersDiv();
    });


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



});
