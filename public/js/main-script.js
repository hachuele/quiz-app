/***************************************************************************
* DESCRIPTION: main-script.js contains javascript code to be
* shared by most pages in the assessments site
*                             ----
* @author: Eric J. Hachuel
* Copyright 2018 University of Southern California. All rights reserved.
*
* DISCLAIMER.  USC MAKES NO EXPRESS OR IMPLIED WARRANTIES, EITHER IN FACT OR
* BY OPERATION OF LAW, BY STATUTE OR OTHERWISE, AND USC SPECIFICALLY AND
* EXPRESSLY DISCLAIMS ANY EXPRESS OR IMPLIED WARRANTY OF MERCHANTABILITY OR
* FITNESS FOR A PARTICULAR PURPOSE, VALIDITY OF THE SOFTWARE OR ANY OTHER
* INTELLECTUAL PROPERTY RIGHTS OR NON-INFRINGEMENT OF THE INTELLECTUAL
* PROPERTY OR OTHER RIGHTS OF ANY THIRD PARTY. SOFTWARE IS MADE AVAILABLE
* AS-IS.
****************************************************************************/

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
    /* update footer on window resize */
    $(window).resize(function() {
        footerUpdate();
    });



});
