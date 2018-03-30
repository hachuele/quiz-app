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





















});
