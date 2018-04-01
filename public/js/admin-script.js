/******************************************************************
 * DESCRIPTION: admin-script.js contains javascript code for the
 * set of admin pages
 *                            ---
 * @author: Eric J. Hachuel
 * University of Southern California, High-Performance Computing
 ******************************************************************/

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






















});
