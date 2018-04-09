/***********************************************************************
 * DESCRIPTION: main-script.js contains javascript code to be
 * shared by most pages in the assessments site
 *                             ----
 * @author: Eric J. Hachuel
 * Copyright 2018 University of Southern California. All rights reserved.
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
