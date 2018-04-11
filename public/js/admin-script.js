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



    /* --------------------- CLICK EVENTS --------------------- */

    /******************************************************
    * SELECT A QUIZZ TO EDIT
    *******************************************************/
    $("#admin_edit_quizz_btn").click(function(){
        $("#myCarousel").carousel("next");
    });


    /******************************************************
    * X
    *******************************************************/
    $("#admin_edit_return_btn").click(function(){
        $("#myCarousel").carousel("prev");
    });

    /******************************************************
    * X
    *******************************************************/
    $("#create_new_quizz_btn").click(function(){
        $("#NewQuizzNameModal").modal("toggle");
    });

    /******************************************************
    * X
    *******************************************************/
    $("#edit_quizz_name_span").click(function(){
        $("#QuizzNameModal").modal("toggle");
    });

    /******************************************************
    * X
    *******************************************************/
    $("#add_new_question_btn").click(function(){
        $("#QuizzQuestionEditModal").modal("toggle");
    });

    /******************************************************
    * X
    *******************************************************/
    $("#add_new_q_choice_btn").click(function(){
        $("#QuizzQuestionChoiceModal").modal("toggle");
    });

    /******************************************************
    * X
    *******************************************************/
    $("#edit_settings_span").click(function(){
        $("#QuizzSettingsModal").modal("toggle");
    });

    /******************************************************
    * EDIT QUIZZ REDIRECT
    *******************************************************/
    $("#edit_new_quizz_btn").click(function(){
        var newAssessmentID = $('#edit_new_quizz_btn').attr('data-new-assessment-id');
        window.location.replace("edit/edit.php?assessment_id=" + newAssessmentID);
    });

    /******************************************************
    * X
    *******************************************************/
    $(".edit_question_pencil_btn").click(function(event) {
         /* disable row click event */
        alert('edit');
         event.stopPropagation();
    });

    /******************************************************
    * X
    *******************************************************/
     $(".delete_question_trash_btn").click(function(event) {
         /* disable row click event */
         alert('delete');
         event.stopPropagation();
    });

    /******************************************************
    * RELOAD PAGE ON SUCCESSFUL EDIT OF DATA
    *******************************************************/
    $("#success_edit_close_btn").click(function(){
        location.reload();
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
                                                           choiceEditID + "\"></tr>");
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
                                            <button type=\"button\" class=\"edit_choice_pencil_btn btn btn-grey-lighten btn-sm\">\
                                                <span class=\"glyphicon glyphicon-pencil\"></span>\
                                            </button>\
                                        </td>";

                    var choiceDeleteBtn = "<td>\
                                            <button type=\"button\" class=\"delete_choice_trash_btn btn btn-grey-lighten btn-sm\">\
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
    * CREATE NEW QUIZZ
    *******************************************************/
    $("#submit_new_quizz_details").click(function(){
        /* request type for ajax call */
        var requestTypeCreate = 'new_quizz';

        /* error check the input */
        if($("#quizz_name_text").val() == 0 && $("#quizz_descr_text_area").val() == 0){
            $("#quizz_name_text").hide().attr('Placeholder', 'Please enter the name of the quizz...').fadeIn(500).focus();
            $("#quizz_descr_text_area").hide().attr('Placeholder', 'Please enter a short description for the quizz...').fadeIn(500);
        } else if($("#quizz_name_text").val() == 0){
            $("#quizz_name_text").hide().attr('Placeholder', 'Please enter the name of the quizz...').fadeIn(500).focus();
        } else if($("#quizz_descr_text_area").val() == 0){
            $("#quizz_descr_text_area").hide().attr('Placeholder', 'Please enter a short description for the quizz...').fadeIn(500).focus();
        }
        else{
            /* Serialize for data for ajax request */
            var formDataNewQuizz = $("#quizz_create_new_form").serialize();

            /* ------ AJAX CALL TO CREATE NEW QUIZZ ------ */
                $.ajax({
                    type     : 'POST',
                    url      : '/assessment_site_hpc/private/edit_quizz_general.php',
                    data     : formDataNewQuizz + '&request_type=' + requestTypeCreate,
                    dataType : 'json',
                    encode   : true
                }).done(function(data){
                   /* store the new assessment ID for editing */
                   $('#edit_new_quizz_btn').attr('data-new-assessment-id', data['new_assessment_id']);
                    /* successful response */
                    if(data['error'] == 0){
                        $("#input_error_span").hide();
                        $("#input_name_success_span").hide().removeClass('hidden').fadeIn(500);
                        $("#input_descr_success_span").hide().removeClass('hidden').fadeIn(500);
                        /* disable inputs */
                        $("#quizz_name_text").prop('disabled', true);
                        $("#quizz_descr_text_area").prop('disabled', true);
                        /* show success span */
                        $("#quizz_name_form_group").removeClass("has-error has-feedback").addClass("has-success has-feedback");
                        $("#quizz_descr_form_group").addClass("has-success has-feedback");
                        /* show edit quizz button */
                        $("#submit_new_quizz_details").fadeOut(500, function(){
                            $("#edit_new_quizz_btn").hide().removeClass('hidden').fadeIn(500);
                        });
                    }
                    else{
                        /* display error */
                        $("#input_name_success_span").hide();
                        $("#input_descr_success_span").hide();
                        $("#input_error_span").hide().removeClass('hidden').fadeIn(500);
                        $("#quizz_descr_form_group").removeClass("has-success has-feedback")
                        $("#quizz_name_form_group").removeClass("has-success has-feedback").addClass("has-error has-feedback");
                        $("#alert_new_quizz").text(data['error']);
                        $("#NewQuizzAlertModal").modal("toggle");
                    }
                   }).fail(function(data) {
                    console.log(data);
                    alert("The was an error. Please try again later or contact your HPC POC.");
                    });
        }
    });



    /******************************************************
    * EDIT QUIZZ GENERAL SETTINGS
    *******************************************************/
    $("#submit_quizz_general_details").click(function(){
        /* request type for ajax call */
        var requestTypeEdit = 'edit_quizz';

        /* error check the input */
        if($("#quizz_name_text").val() == 0 && $("#quizz_descr_text_area").val() == 0){
            $("#quizz_name_text").hide().attr('Placeholder', 'Please enter the name of the quizz...').fadeIn(500).focus();
            $("#quizz_descr_text_area").hide().attr('Placeholder', 'Please enter a short description for the quizz...').fadeIn(500);
        } else if($("#quizz_name_text").val() == 0){
            $("#quizz_name_text").hide().attr('Placeholder', 'Please enter the name of the quizz...').fadeIn(500).focus();
        } else if($("#quizz_descr_text_area").val() == 0){
            $("#quizz_descr_text_area").hide().attr('Placeholder', 'Please enter a short description for the quizz...').fadeIn(500).focus();
        }

        else{
            /* Serialize form data for ajax request */
            var formDataEditQuizz = $("#quizz_general_details_edit_form").serialize();
            var assessmentEditID = $("#edit_assessments_main_div").attr('data-assessment-id');

            /* ------ AJAX CALL TO CREATE NEW QUIZZ ------ */
                $.ajax({
                    type     : 'POST',
                    url      : '/assessment_site_hpc/private/edit_quizz_general.php',
                    data     : formDataEditQuizz + '&assessment_id=' + assessmentEditID + '&request_type=' + requestTypeEdit,
                    dataType : 'json',
                    encode   : true
                }).done(function(data){

                    if(data['error'] == 0){
                        $("#alert_edit_quizz_success").text("Successfully updated quizz!");
                        $("#editQuizzSuccessModal").modal("toggle");
                    }
                    else{
                        /* display error */
                        $("#alert_edit_quizz_wrong").text(data['error']);
                        $("#editQuizzErrorModal").modal("toggle");
                    }







                   }).fail(function(data) {
                    console.log(data);
                    alert("The was an error. Please try again later or contact your HPC POC.");
                    });




        }




    });










});
