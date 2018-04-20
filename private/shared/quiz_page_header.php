<?php
/**************************************************************************
* DESCRIPTION: quiz_page_header.php serves as a modular header
* to use accross multiple pages in the site.
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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo h($site_title); ?></title>
        <meta charset="utf-8">
        <meta name="author" content="Eric J. Hachuel">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" media="all" href="<?php echo url_for('stylesheets/general_style.css'); ?>"/>
    </head>
    <body>
        <div class="container-fluid header_main centering_div red_pantone_201c_back">
            <div id ="header_row_div" class="row centered_div">
                <div id="page_title_left_div" class="col-sm-6">
                    <div class="row">
                        <div id="page_title_icon_left_div" class="col-sm-2">
                            <img id="page_title_icon_img" src="<?php echo url_for('images/quiz_logo_1.png'); ?>">
                        </div>
                        <div id="page_title_txt_left_div" class="col-sm-10">
                            <h2 id="page_title_txt"><?php echo h($page_title); ?></h2>
                        </div>
                    </div>
                </div>
                <div id="page_logo_right_div" class="col-sm-6">
                    <img src="<?php echo url_for('images/usc_logo_yellow.png'); ?>" id="page_header_logo">
                </div>
            </div>
        </div>
