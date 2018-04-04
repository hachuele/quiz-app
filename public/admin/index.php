
<?php
/******************************************************************
 * DESCRIPTION: 'public/admin/index.php' serves as the main page
 * for administrative purposes. Allows to create and edit quizzes,
 * as well as to view user statistics
 *                             ----
 * @author: Eric J. Hachuel
 * University of Southern California, High-Performance Computing
 ******************************************************************/
session_start();
require_once('../../private/initialize.php');
?>

<?php
/* ---------------- Dynamic Naming Variables ---------------- */
$site_title = 'HPC Assessments Admin Site';
$page_title = 'HPC ASSESSMENTS ADMIN';
$help_modal_title = 'HPC ASSESSMENTS ADMIN HELP';
$help_modal_txt = 'Create a new quizz...';

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
<?php require(SHARED_PATH . '/quizz_admin_header.php'); ?>

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
                                <button style="text-align:left;" type="button" class="quizz_list_btn btn btn-primary btn-block btn-sm">
                                    <span class="pull-left">CREATE NEW QUIZZ</span>
                                    <span style="float:right;" class="pull-right glyphicon glyphicon-plus-sign"></span>
                                </button>
                                <button id="admin_edit_quizz_btn" style="text-align:left;" type="button" class="quizz_list_btn btn btn-primary btn-block btn-sm">
                                    <span class="pull-left">EDIT EXISTING QUIZZ</span>
                                    <span style="float:right;" class="pull-right glyphicon glyphicon-pencil"></span>
                                </button>
                                <button style="text-align:left;" type="button" class="quizz_list_btn btn btn-primary btn-block btn-sm">
                                    <span class="pull-left">STATISTICS DASHBOARD</span>
                                    <span style="float:right;" class="pull-right glyphicon glyphicon-stats"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="dashboard_element_card">
                            <div class="dash_card_title_div">
                                <h4 class="dash_card_title_txt"> SELECT A QUIZZ </h4>
                                <hr>
                            </div>
                            <div class="available_options_list">
                                <?php
                                /* loop through all avaliable courses in the course set */
                                while($available_course = mysqli_fetch_assoc($course_set)) { ?>
                                <button style="text-align:left;" type="button" class="quizz_list_btn btn btn-primary btn-block btn-sm" onclick="location.href='<?php echo url_for('admin/edit/index.php?assessment_id=' . h(u($available_course['assessment_id']))); ?>'">
                                    <span class="pull-left"><?php echo h($available_course['assessment_name']) ?></span>
                                </button>
                                <?php } ?>
                                <button id="admin_edit_return_btn" type="button" style="margin-top: 15px;" class="quizz_list_btn btn btn-grey-lighten btn-block btn-sm" >
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


<!-- ***************************** CREATE QUIZZ: NAME EDIT (MODAL) ***************************** -->
<div id="NewQuizzNameModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align: center;">NEW QUIZZ DETAILS</h4>
      </div>
      <div class="modal-body">
          <form id="quizz_general_details_edit_form">
              <div class="form-group">
                  <label for="quizz_name_text">Quizz Name:</label>
                  <input type="text" class="form-control" id="quizz_name_text">
              </div>
              <div class="form-group">
                  <label for="quizz_descr_text_area">Quizz Description:</label>
                  <textarea class="form-control" rows="3" id="quizz_descr_text_area"></textarea>
              </div>
              <div class="submit_new_record_btn_div">
                  <button disabled id="submit_quizz_general_details" class="btn btn-primary btn-sm" type="button">SUBMIT</button>
              </div>
          </form>
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
<?php require(SHARED_PATH .  '/quizz_page_footer.php'); ?>


