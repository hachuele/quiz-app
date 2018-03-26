/******************************************************************
 * DESCRIPTION: main-script.js contains javascript code to be
 * shared by most pages in the assessments site
 *                             ----
 * @author: Eric J. Hachuel
 * University of Southern California, High-Performance Computing
 ******************************************************************/

/******************************************************
* THE FOOTER UPDATE FUNCTION ENSURES PROPER ALIGNMENT
* OF THE FOOTER TO THE BOTTOM OF THE PAGE.
*******************************************************/
function footerUpdate(){
    if($(".assessment_title_main").length == 0){
        var header_height = $(".header_main").height();
    }
    else{
        var header_height = $(".header_main").height() + $(".assessment_title_main").height();
    }

    var footer_height = $("#footer_row").height();
    var scrollTopHeigh = $(window).scrollTop();
    var mainContentDiv = $(".main_content").innerHeight();
    var windowHeight = $(window).height();


    if(mainContentDiv + header_height > windowHeight - footer_height){
        $("#footer_row").removeClass("footer_adjust_abs").addClass("footer_adjust_rel");
    }
    else{
        $("#footer_row").removeClass("footer_adjust_rel").addClass("footer_adjust_abs");
    }
}


/*code runs once DOM ready*/
$(document).ready(function(){
    footerUpdate();

    $(window).resize(function() {
        footerUpdate();
    });












});
