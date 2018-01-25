/*******************************************************
* *******MAIN-SCRIPT CONTAINS GENERAL FUNCTIONS******* *
********************************************************/


/******************************************************
* THE FOOTER UPDATE FUNCTION ENSURE PROPER ALIGNMENT
* OF THE FOOTER TO THE BOTTOM OF THE PAGE.
*******************************************************/

//NOTE: CHANGED MAIN_CONTENT CLASS FROM DIV TO HIGHER LEVEL DIV
//TODO: FIX BUG, NOT CALCULATING WINDOW HEIGHT PROPERLY, DEOSNT WORK IF SCREEN TOO BIG (I.E. MONITOR)

function footerUpdate(){
   //FUNCTION TO DETECT SIZE OF SCREEN AND ADJUST FOOTER CLASS
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
        console.log("resize!");
        footerUpdate();
    });












});
