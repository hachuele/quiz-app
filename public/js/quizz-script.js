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

    /* Instantiate global variables */
    var numQuestions = countNumQuestions();
    var currQ = currentQuestionNum();
//    var currID = currentQuestionID();


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

            //Get the current question to enable next
            var currQuestionNum = currentQuestionNum();


            enableNext(currQuestionNum);
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
    * LOAD NEXT PERFORMS AN AJAX CALL TO RETRIEVE THE NEXT
    * QUESTION DATA (IF AVAILABLE)
    *******************************************************/
    function loadNext(){
        var questionNumber = currentQuestionNum();
//        var questionID = currentQuestionID();
        console.log(questionNumber);
        if($("#next_question_btn_" + questionNumber).is(":enabled")){
            //hide current question (remove active class)
            //activate next question (if any)
            //change next button if at last question (to details page)
            if(questionNumber != numQuestions){
                hideActiveQuestionCard(questionNumber);

                //THIS DOES NOT WORK, CANNOT INCREMENT QUESTION ID.
                activateQuestionCard(++questionNumber);
                footerUpdate();

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
        var questionNumber = currentQuestionNum();
        if($("#previous_question_btn_" + questionNumber).is(":enabled")){

            alert("IM ENABLED!");

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

    //TODO: WILL FOR LOOP ON ENTRY OVER COMPLETED QUESTIONS TO DISABLE
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
        questionNumStr = questionCardNum.match(/[-]\d/g);
        questionNum = questionNumStr[0].match(/\d/g);
        return parseInt(questionNum);
    }



    /* ------------------ CLICK EVENTS ------------------ */



    /******************************************************
    * TOGGLE THE ANSWERS BOX (IF ENABLED)
    *******************************************************/
    $("#view_answers_btn").click(function(){
        toggleAnswersDiv();
    });


    /******************************************************
    * TOGGLE THE ANSWERS BOX (IF ENABLED)
    *******************************************************/
    $("[id^=next_question_btn_]").click(function(){
        loadNext();
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
