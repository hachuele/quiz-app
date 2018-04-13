/***********************************************************************
* DESCRIPTION: admin-script.js contains javascript code for the
* set of admin pages
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

    /* -------------------------------------------------------------------------------------------------- */
    /* --------------------------------------------- INDEX ---------------------------------------------- */
    /* -------------------------------------------------------------------------------------------------- */

    /* --------------------- CLICK EVENTS --------------------- */

    /******************************************************
    * QUIZ EDIT CAROUSEL NEXT
    *******************************************************/
    $("#admin_edit_quiz_btn").click(function(){
        $("#myCarousel").carousel("next");
    });

    /******************************************************
    * QUIZ EDIT CAROUSEL PREV
    *******************************************************/
    $("#admin_edit_return_btn").click(function(){
        $("#myCarousel").carousel("prev");
    });

    /******************************************************
    * CREATE NEW QUIZ MODAL
    *******************************************************/
    $("#create_new_quiz_btn").click(function(){
        $("#NewquizNameModal").modal("toggle");
    });

    /******************************************************
    * EDIT QUIZ NAME MODAL
    *******************************************************/
    $("#edit_quiz_name_span").click(function(){
        $("#quizNameModal").modal("toggle");
    });


    /******************************************************
    * EDIT QUIZ SETTINGS (GEAR)
    *******************************************************/
    $("#edit_settings_span").click(function(){
        $("#quizSettingsModal").modal("toggle");
    });


    /******************************************************
    * EDIT QUIZ REDIRECT
    *******************************************************/
    $("#edit_new_quiz_btn").click(function(){
        var newAssessmentID = $('#edit_new_quiz_btn').attr('data-new-assessment-id');
        window.location.replace("edit/edit.php?assessment_id=" + newAssessmentID);
    });


    /******************************************************
    * CREATE NEW QUIZ
    *******************************************************/
    $("#submit_new_quiz_details").click(function(){
        /* request type for ajax call */
        var requestTypeCreate = 'new_quiz';
        /* error check the input */
        if($("#quiz_name_text").val() == 0 && $("#quiz_descr_text_area").val() == 0){
            $("#quiz_name_text").hide().attr('Placeholder', 'Please enter the name of the quiz...').fadeIn(500).focus();
            $("#quiz_descr_text_area").hide().attr('Placeholder', 'Please enter a short description for the quiz...').fadeIn(500);
        } else if($("#quiz_name_text").val() == 0){
            $("#quiz_name_text").hide().attr('Placeholder', 'Please enter the name of the quiz...').fadeIn(500).focus();
        } else if($("#quiz_descr_text_area").val() == 0){
            $("#quiz_descr_text_area").hide().attr('Placeholder', 'Please enter a short description for the quiz...').fadeIn(500).focus();
        }
        else{
            /* Serialize for data for ajax request */
            var formDataNewquiz = $("#quiz_create_new_form").serialize();

            /* ------ AJAX CALL TO CREATE NEW quiz ------ */
                $.ajax({
                    type     : 'POST',
                    url      : '/assessment_site_hpc/private/edit_quiz_general.php',
                    data     : formDataNewquiz + '&request_type=' + requestTypeCreate,
                    dataType : 'json',
                    encode   : true
                }).done(function(data){
                   /* store the new assessment ID for editing */
                   $('#edit_new_quiz_btn').attr('data-new-assessment-id', data['new_assessment_id']);
                    /* successful response */
                    if(data['error'] == 0){
                        $("#input_error_span").hide();
                        $("#input_name_success_span").hide().removeClass('hidden').fadeIn(500);
                        $("#input_descr_success_span").hide().removeClass('hidden').fadeIn(500);
                        /* disable inputs */
                        $("#quiz_name_text").prop('disabled', true);
                        $("#quiz_descr_text_area").prop('disabled', true);
                        /* show success span */
                        $("#quiz_name_form_group").removeClass("has-error has-feedback").addClass("has-success has-feedback");
                        $("#quiz_descr_form_group").addClass("has-success has-feedback");
                        /* show edit quiz button */
                        $("#submit_new_quiz_details").fadeOut(500, function(){
                            $("#edit_new_quiz_btn").hide().removeClass('hidden').fadeIn(500);
                        });
                    }
                    else{
                        /* display error */
                        $("#input_name_success_span").hide();
                        $("#input_descr_success_span").hide();
                        $("#input_error_span").hide().removeClass('hidden').fadeIn(500);
                        $("#quiz_descr_form_group").removeClass("has-success has-feedback")
                        $("#quiz_name_form_group").removeClass("has-success has-feedback").addClass("has-error has-feedback");
                        $("#alert_new_quiz").text(data['error']);
                        $("#NewquizAlertModal").modal("toggle");
                    }
                   }).fail(function(data) {
                    console.log(data);
                    alert("The was an error. Please try again later or contact your HPC POC.");
                    });
        }
    });



    /* -------------------------------------------------------------------------------------------------- */
    /* ---------------------------------------------- EDIT ---------------------------------------------- */
    /* -------------------------------------------------------------------------------------------------- */

    /******************************************************
    * RELOAD PAGE ON SUCCESSFUL EDIT OF DATA
    *******************************************************/
    $("#success_edit_close_btn").click(function(){
        location.reload();
    });

    /******************************************************
    * ADD NEW QUESTION
    *******************************************************/
    $("#add_new_question_btn").click(function(){
        /* reset form */
        $("#quiz_question_edit_form")[0].reset();
        $('#quizQuestionEditModal').attr('data-selec-quest-id', "");
        /* set modal attribute to new question */
        $('#quizQuestionEditModal').attr('data-question-edit-mode', "new_question");
        $("#quizQuestionEditModal").modal("toggle");
    });

    /******************************************************
    * EDIT EXISTING QUESTION
    *******************************************************/
    $(".edit_question_pencil_btn").click(function(event) {
        /* disable row click event */
        event.stopPropagation();
        /* set modal attribute to new question */
        $('#quizQuestionEditModal').attr('data-question-edit-mode', "edit_question");
        var questionEditID = $(this).attr('data-question-id');
        $('#quizQuestionEditModal').attr('data-selec-quest-id', questionEditID);
        /* retrieve and populate existing data */
        var questionMultiSet = 0;
        var questionReqSet = 0;
        var thisQuestionText = $(this).closest('.question_edit_tbl_row').find('.question_text_td').text();
        var thisQuestionMulti = $(this).closest('.question_edit_tbl_row').attr('data-question-is-multi');
        var thisQuestionReq = $(this).closest('.question_edit_tbl_row').attr('data-question-is-req');

        if(thisQuestionMulti != 0){
            questionMultiSet = 1;
        }
        if(thisQuestionReq != 0){
            questionReqSet = 1;
        }
        /* populate form with existing values */
        $("#quiz_question_text").val(thisQuestionText);
        $("#question_is_multi_check").prop('checked', questionMultiSet);
        $("#question_is_required_check").prop('checked', questionReqSet);
        $("#quizQuestionEditModal").modal("toggle");
    });

    /******************************************************
    * DELETE QUESTION
    *******************************************************/
     $(".delete_question_trash_btn").click(function(event) {
         /* disable row click event */
         event.stopPropagation();
         var questionDeleteID = $(this).attr('data-question-id');
         var questionDeleteString = $(this).closest('tr').find('.question_text_td').text();
         /* set item to delete text*/
         $("#item_to_delete_text").text(questionDeleteString);

         $("#confirmDeleteModal").modal("toggle");


    });



    /******************************************************
    * ADD NEW CHOICE
    *******************************************************/
    $("#add_new_q_choice_btn").click(function(){
        /* reset form */
        $("#quiz_question_choice_edit_form")[0].reset();
        /* set modal attribute to new choice */


        $("#quizQuestionChoiceModal").modal("toggle");
    });


    /******************************************************
    * EDIT CHOICE (must delegate using .on since dynamic)
    *******************************************************/
    $(document.body).on("click", ".edit_choice_pencil_btn", function(event) {
        /* disable row click event */
        event.stopPropagation();
        var choiceEditID = $(this).attr('data-q-choice-id');


    });

    /******************************************************
    * DELETE CHOICE (must delegate using .on since dynamic)
    *******************************************************/
     $(document.body).on("click", ".delete_choice_trash_btn", function(event) {
         /* disable row click event */
         event.stopPropagation();
         var choiceDeleteID = $(this).attr('data-q-choice-id');
         var choiceDeleteString = $(this).closest('tr').find('td').text();
         /* set item to delete text*/
         $("#item_to_delete_text").text(choiceDeleteString);

         $("#confirmDeleteModal").modal("toggle");


    });



    /******************************************************
    * GET CHOICES ON QUESTION CLICK (AJAX)
    *******************************************************/
    $(".question_edit_tbl_row").click(function(){
        var questionEditID = $(this).attr('data-question-id');

        /* ------ AJAX CALL TO GET QUESTION CHOICES ------ */
        $.ajax({
            type     : 'POST',
            url      : '../../../private/retrieve_choices_edit.php',
            data     : '&question_edit_id=' + questionEditID,
            dataType : 'json',
            encode   : true
        }).done(function(data){
            /* hide choice table if show */
            $("#selec_q_choices_tbl").hide();
            /* set name of selected question */
            $("#select_quest_edit_name").empty();
            $("#select_quest_edit_name").append(" :&nbsp;&nbsp;" + data['question_text']);
            /* if no choices exist for selected question, let user know */
            if(data['num_edit_choices'] == 0){
                $("#no_choice_text").fadeOut(500, function(){
                    $("#no_choice_text").text('No choices in database for selected question.').fadeIn(500);
                });
            }
            /* create table with choices */
            else{
                $("#selec_q_choices_tbl_body").empty();
                var numEditChoices = data['num_edit_choices'];
                $("#no_choice_text").hide();
                /* loop through existing choices and display in table */
                for(i = 0; i < numEditChoices; i++){
                    var choiceEditID = data['choices_edit_array'][i]['question_choice_id'];
                    var choiceEditTxt = data['choices_edit_array'][i]['question_choice_text'];
                    var choiceEditCorrect = data['choices_edit_array'][i]['question_choice_correct'];

                    $("#selec_q_choices_tbl_body").append("<tr id=\"choice_edit_tbl_row_" + choiceEditID + "\" data-q-choice-id=" +
                                                           choiceEditID + "></tr>");
                    /* get the text of the choice */
                    $("#choice_edit_tbl_row_" + choiceEditID).append("<td style=\"text-align: left;\">" + choiceEditTxt + "</td>");
                    /* check if given choice is set to correct or incorrect answer */
                    if(choiceEditCorrect == 1){
                        $("#choice_edit_tbl_row_" + choiceEditID).append("<td><span style=\"font-size: 20px;\" class=\"glyphicon glyphicon-ok-circle solution_glyphicon_correct\"></span></td>");
                    } else {
                        $("#choice_edit_tbl_row_" + choiceEditID).append("<td><span style=\"font-size: 20px;\" class=\"glyphicon glyphicon-remove-circle solution_glyphicon_incorrect\"></span></td>");
                    }
                    /* append buttons */
                    var choiceEditBtn = "<td>\
                                            <button type=\"button\" class=\"edit_choice_pencil_btn btn btn-grey-lighten btn-sm\" data-q-choice-id=" +
                                                           choiceEditID + ">\
                                                <span class=\"glyphicon glyphicon-pencil\"></span>\
                                            </button>\
                                        </td>";

                    var choiceDeleteBtn = "<td>\
                                            <button type=\"button\" class=\" delete_choice_trash_btn btn btn-grey-lighten btn-sm\" data-q-choice-id=" +
                                                           choiceEditID + ">\
                                                <span class=\"glyphicon glyphicon-trash\"></span>\
                                            </button>\
                                        </td>";
                    /* append buttons */
                    $("#choice_edit_tbl_row_" + choiceEditID).append(choiceEditBtn);
                    $("#choice_edit_tbl_row_" + choiceEditID).append(choiceDeleteBtn);
                }
                /* show the choices table and the button to add new choices */
                $("#selec_q_choices_tbl").hide().removeAttr("hidden").fadeIn(500);
            }
            /* show button to add new choice */
            $("#add_new_q_choice_div").hide().removeAttr("hidden").fadeIn(500);
            /* update footer to fit size */
            footerUpdate();
            /* scroll to choices */
            $('html, body').animate({
                   scrollTop: $("#questions_choices_edit_div").offset().top}, 2000);

           }).fail(function(data) {
            console.log(data);
            alert("The was an error. Please try again later or contact your HPC POC.");
            });
    });






    /******************************************************
    * EDIT QUIZ GENERAL DETAILS (NAME, DESCR)
    *******************************************************/
    $("#submit_quiz_general_details").click(function(){
        /* request type for ajax call */
        var requestTypeEdit = 'edit_quiz';
        /* error check the input */
        if($("#quiz_name_text").val() == 0 && $("#quiz_descr_text_area").val() == 0){
            $("#quiz_name_text").hide().attr('Placeholder', 'Please enter the name of the quiz...').fadeIn(500).focus();
            $("#quiz_descr_text_area").hide().attr('Placeholder', 'Please enter a short description for the quiz...').fadeIn(500);
        } else if($("#quiz_name_text").val() == 0){
            $("#quiz_name_text").hide().attr('Placeholder', 'Please enter the name of the quiz...').fadeIn(500).focus();
        } else if($("#quiz_descr_text_area").val() == 0){
            $("#quiz_descr_text_area").hide().attr('Placeholder', 'Please enter a short description for the quiz...').fadeIn(500).focus();
        }
        else{
            /* Serialize form data for ajax request */
            var formDataEditquiz = $("#quiz_general_details_edit_form").serialize();
            var assessmentEditID = $("#edit_assessments_main_div").attr('data-assessment-id');

            /* ------ AJAX CALL TO CREATE NEW quiz ------ */
                $.ajax({
                    type     : 'POST',
                    url      : '/assessment_site_hpc/private/edit_quiz_general.php',
                    data     : formDataEditquiz + '&assessment_id=' + assessmentEditID + '&request_type=' + requestTypeEdit,
                    dataType : 'json',
                    encode   : true
                }).done(function(data){
                    /* successful response */
                    if(data['error'] == 0){
                        $("#alert_edit_quiz_success").text("Successfully updated quiz!");
                        $("#editquizSuccessModal").modal("toggle");
                    }
                    else{
                        /* display error */
                        $("#alert_edit_quiz_wrong").text(data['error']);
                        $("#editquizErrorModal").modal("toggle");
                    }
                   }).fail(function(data) {
                    console.log(data);
                    alert("The was an error. Please try again later or contact your HPC POC.");
                    });
        }
    });



    /******************************************************
    * EDIT QUIZ SETTINGS
    *******************************************************/
    $("#submit_settings_btn").click(function(){
        /* Serialize form data for ajax request */
        var formDataSettingsquiz = $("#quiz_settings_edit_form").serialize();
        var assessmentSettingsID = $("#edit_assessments_main_div").attr('data-assessment-id');

        /* ------ AJAX CALL TO CREATE NEW quiz ------ */
            $.ajax({
                type     : 'POST',
                url      : '/assessment_site_hpc/private/edit_quiz_settings.php',
                data     : formDataSettingsquiz + '&assessment_id=' + assessmentSettingsID,
                dataType : 'json',
                encode   : true
            }).done(function(data){
                /* successful response */
                if(data['error'] == 0){
                    $("#alert_edit_quiz_success").text("Successfully updated quiz settings!");
                    $("#editquizSuccessModal").modal("toggle");
                }
                else{
                    /* display error */
                    $("#alert_edit_quiz_wrong").text(data['error']);
                    $("#editquizErrorModal").modal("toggle");
                }

               }).fail(function(data) {
                console.log(data);
                alert("The was an error. Please try again later or contact your HPC POC.");
                });
    });




    /******************************************************
    * CREATE/EDIT QUIZ QUESTION
    *******************************************************/
    $("#submit_question_details").click(function(){
        /* request type for ajax call */
        var requestTypeQuestionEdit = $('#quizQuestionEditModal').attr('data-question-edit-mode');

        /* Serialize form data for ajax request */
        var formDataNewQuestion = $("#quiz_question_edit_form").serialize();
        var assessmentIDEditQuestion = $("#edit_assessments_main_div").attr('data-assessment-id');
        var questionIDEditQuestion = $("#quizQuestionEditModal").attr('data-selec-quest-id');

        /* error check the input */
        if($("#quiz_question_text").val() == 0){
            $("#quiz_question_text").hide().attr('Placeholder', 'Please enter some text for the question...').fadeIn(500).focus();
        }
        else{
            /* ------ AJAX CALL TO CREATE NEW quiz ------ */
            $.ajax({
                type     : 'POST',
                url      : '/assessment_site_hpc/private/edit_quiz_questions.php',
                data     : formDataNewQuestion + '&assessment_id=' + assessmentIDEditQuestion + '&question_id=' + questionIDEditQuestion + '&request_type=' + requestTypeQuestionEdit,
                dataType : 'json',
                encode   : true
            }).done(function(data){

                console.log(data);

                /* successful response */
                if(data['error'] == 0){
                    $("#alert_edit_quiz_success").text("Successfully created quiz question!");
                    $("#editquizSuccessModal").modal("toggle");
                }
                else{
                    /* display error */
                    $("#alert_edit_quiz_wrong").text(data['error']);
                    $("#editquizErrorModal").modal("toggle");
                }


               }).fail(function(data) {
                console.log(data);
                alert("The was an error. Please try again later or contact your HPC POC.");
                });


        }


    });












});
