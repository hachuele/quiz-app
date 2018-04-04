/***********************************************************************
 * DESCRIPTION: admin-script.js contains javascript code for the
 * set of admin pages
 *                            ---
 * @author: Eric J. Hachuel
 * Copyright 2018 University of Southern California. All rights reserved.
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



    $("#admin_edit_return_btn").click(function(){
        $("#myCarousel").carousel("prev");
    });


    $("#edit_quizz_name_span").click(function(){
        $("#QuizzNameModal").modal("toggle");

    });

    $("#add_new_question_btn").click(function(){
        $("#QuizzQuestionEditModal").modal("toggle");

    });

    $("#add_new_q_choice_btn").click(function(){
        $("#QuizzQuestionChoiceModal").modal("toggle");

    });

    $(".edit_question_pencil_btn").click(function(event) {
         /* disable row click event */
        alert('edit');
         event.stopPropagation();
    });

     $(".delete_question_trash_btn").click(function(event) {
         /* disable row click event */
         alert('delete');
         event.stopPropagation();
    });

    /******************************************************
    * GET CHOICES ON QUESTION CLICK
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
                        $("#choice_edit_tbl_row_" + choiceEditID).append("<td><span class=\"glyphicon glyphicon-ok-circle solution_glyphicon_correct\"></span></td>");
                    } else {
                        $("#choice_edit_tbl_row_" + choiceEditID).append("<td><span class=\"glyphicon glyphicon-remove-circle solution_glyphicon_incorrect\"></span></td>");
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
                $("#add_new_q_choice_div").hide().removeAttr("hidden").fadeIn(500);
            }

           }).fail(function(data) {

            console.log(data);
            alert("The was an error. Please try again later or contact your HPC POC.");
            });


    });










});
