
<?php
/***********************************************************************
* DESCRIPTION: 'public/admin/index.php' serves as the main page
* for administrative purposes. Allows to create and edit quizzes,
* as well as to view user statistics
*                             ----
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
session_start();
require_once('../../private/initialize.php');
?>

<?php
/* ---------------- Dynamic Naming Variables ---------------- */
$site_title = 'HPC Assessments Admin Site';
$page_title = 'HPC ASSESSMENTS ADMIN';
$help_modal_title = 'HPC ASSESSMENTS ADMIN HELP';
$help_modal_txt = 'Create a new quiz...';

/* ----------------------------------------------------------------------------------------- */
/* -------------------------------------- Get User ID -------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

$user_id = 'hachuelb';
$_SESSION["user_id"] = $user_id;

/* ----------------------------------------------------------------------------------------- */
/* ------------------------------------ Retrieve from DB ----------------------------------- */
/* ----------------------------------------------------------------------------------------- */

/* get set of all quizzes (for edit purposes) */
$course_set = find_all_courses();


?>

<!-- *********************************** PAGE HEADER *********************************** -->
<?php require(SHARED_PATH . '/quiz_admin_header.php'); ?>

<!-- *********************************** CONTENT START *********************************** -->
<div id="assessments_main_dash_div" class="container-fluid main_content">
    <div class="page-header">
        <h2 class="dash_title_txt">HPC Assessments Administration</h2>
    </div>
    <div class="row dash_content_row_div">
        <div class="col-sm-12" style="margin-top: 15px;">
            <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                <div class="carousel-inner" style="min-height: 330px;">
                    <div class="item active">
                        <div class="dashboard_element_card">
                            <div class="dash_card_title_div">
                                <h4 class="dash_card_title_txt">SELECT AN OPTION &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon glyphicon-hand-down blue_darken_2"></span></h4>
                                <hr>
                            </div>
                            <div class="available_options_list">
                                <button id="create_new_quiz_btn" style="text-align:left;" type="button" class="quiz_list_btn btn btn-primary btn-block btn-sm">
                                    <span class="pull-left">CREATE NEW QUIZ</span>
                                    <span style="float:right;" class="pull-right glyphicon glyphicon-plus-sign"></span>
                                </button>
                                <button id="admin_edit_quiz_btn" style="text-align:left;" type="button" class="quiz_list_btn btn btn-primary btn-block btn-sm">
                                    <span class="pull-left">EDIT EXISTING QUIZ</span>
                                    <span style="float:right;" class="pull-right glyphicon glyphicon-pencil"></span>
                                </button>
                                <button disabled style="text-align:left;" type="button" class="quiz_list_btn btn btn-primary btn-block btn-sm">
                                    <span class="pull-left">STATISTICS DASHBOARD</span>
                                    <span style="float:right;" class="pull-right glyphicon glyphicon-stats"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="dashboard_element_card">
                            <div class="dash_card_title_div">
                                <h4 class="dash_card_title_txt"> SELECT A QUIZ </h4>
                                <hr>
                            </div>
                            <div class="available_options_list">
                                <?php
                                /* loop through all avaliable courses in the course set */
                                while($available_course = mysqli_fetch_assoc($course_set)) { ?>
                                <button style="text-align:left;" type="button" class="quiz_list_btn btn btn-primary btn-block btn-sm" onclick="location.href='<?php echo url_for('admin/edit/edit.php?assessment_id=' . h(u($available_course['assessment_id']))); ?>'">
                                    <span class="pull-left"><?php echo h($available_course['assessment_name']) ?></span>
                                </button>
                                <?php } ?>
                                <button id="admin_edit_return_btn" type="button" style="margin-top: 15px;" class="quiz_list_btn btn btn-grey-lighten btn-block btn-sm" >
                                <span class="glyphicon glyphicon-triangle-left"></span>
                            </button>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div>
    <hr>
    <br>
</div>


<!-- ***************************** CREATE NEW quiz (MODAL) ***************************** -->
<div id="NewquizNameModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align: center;">NEW QUIZ DETAILS</h4>
      </div>
      <div class="modal-body">
          <form id="quiz_create_new_form">
              <div id="quiz_name_form_group" class="form-group">
                  <label for="quiz_name_text">Quiz Name:</label>
                  <input name="quiz_name_text_in" type="text" class="form-control" id="quiz_name_text">
                  <span id="input_error_span" class="hidden glyphicon glyphicon-remove form-control-feedback"></span>
                  <span id="input_name_success_span" class="hidden glyphicon glyphicon-ok form-control-feedback"></span>
              </div>
              <div id="quiz_descr_form_group" class="form-group">
                  <label for="quiz_descr_text_area">Quiz Description:</label>
                  <textarea name="quiz_descr_text_in" class="form-control" rows="3" id="quiz_descr_text_area"></textarea>
                  <span id="input_descr_success_span" class="hidden glyphicon glyphicon-ok form-control-feedback"></span>
              </div>
              <div id="submit_new_quiz_div" class="submit_to_db_btn_div">
                  <button id="submit_new_quiz_details" class="btn btn-primary btn-sm" type="button">SUBMIT</button>
                  <button id="edit_new_quiz_btn" class="hidden btn btn-success btn-sm" type="button">EDIT QUIZ</button>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>


<!-- ***************************** ALERT MODAL ***************************** -->
<div id="NewquizAlertModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-body">
          <div class="alert alert-danger">
              <p id="alert_new_quiz"></p>
          </div>
      </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>


<!-- *********************************** CONTENT END *********************************** -->

<script src="<?php echo url_for('js/main-script.js');?>"></script>
<script src="<?php echo url_for('js/admin-script.js');?>"></script>

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quiz_page_footer.php'); ?>


