<?php
/**************************************************************************
* DESCRIPTION: 'public/admin/edit/edit.php' serves as the page
* for editing or creating new quizzes
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
session_start();
require_once('../../../private/initialize.php');
?>

<?php
/* ---------------- Dynamic Naming Variables ---------------- */
$site_title = 'HPC Assessments Admin Site';
$page_title = 'HPC ASSESSMENTS ADMIN: EDIT';
$help_modal_title = 'HPC ASSESSMENTS ADMIN HELP';
$help_modal_txt = '...';

/* ----------------------------------------------------------------------------------------- */
/* -------------------------------------- Get User ID -------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

/* instantiate user ID variable */
$user_id = "";

// TODO: TO DEPLY, REPLACE STATIC 'ADMIN' ASSIGNMENT TO USER ID WITH AUTHENTIC ID
$user_id = "admin";
//$user_id = get_shib_ID();

$_SESSION["user_id"] = $user_id;

/* -------- check if user is an admin, redirect otherwise -------- */
$user_admin = is_user_admin($user_id);
if($user_admin == FALSE){
    echo "User not authorized";
    exit();
}

/* -------- get the assessment id from url (if not found, set to one) -------- */
$assessment_id = $_GET['assessment_id'];

/* ----------------------------------------------------------------------------------------- */
/* ------------------------------------ Retrieve from DB ----------------------------------- */
/* ----------------------------------------------------------------------------------------- */

/* -------- get the name of this assessment for display -------- */
$assessment_data_row = get_assessment_row($assessment_id);

/* if the quiz for the given id does not exist, redirect to main page */
if($assessment_data_row == FALSE){
    redirect_to('../index.php');
}

$assessment_name_edit = $assessment_data_row['assessment_name'];
$assessment_description_txt = $assessment_data_row['assessment_description'];
$assessment_is_visible = $assessment_data_row['assessment_visible'];
$assessment_num_quest = $assessment_data_row['assessment_num_q_to_show'];

/* -------- get questions for selected assessment -------- */
$question_set_edit = find_questions_by_assessment_id($assessment_id);
$num_questions_edit = mysqli_num_rows($question_set_edit);
/* override default value if default is larger than num questions available */
if($assessment_num_quest > $num_questions_edit){
    $assessment_num_quest = $num_questions_edit;
}

?>

<!-- *********************************** PAGE HEADER *********************************** -->
<?php require(SHARED_PATH . '/quiz_admin_header.php'); ?>

<!-- *********************************** CONTENT START *********************************** -->
<div id="edit_assessments_main_div" class="container-fluid main_content" data-assessment-id="<?php echo h($assessment_id); ?>">
    <div id="quiz_title_edit_div" class="page-header">
        <button style="margin-right:15px;" id="back_home_admin_btn" type="button" class="back_home_btn btn btn-primary btn-sm">
            <span class="glyphicon glyphicon-home"></span>
        </button>
        <h2 id="quiz_title_txt" class="dash_title_txt"><?php echo h($assessment_name_edit); ?> &nbsp;&nbsp;<span id="edit_quiz_name_span" style="font-size:15px;" class="glyphicon glyphicon-pencil"></span><span id="edit_settings_span" style="font-size:25px; float:right;" class="glyphicon glyphicon-cog"></span></h2>
    </div>
    <div class="row dash_subsection_div">
        <div class="col-xs-9">
            <h3 style="color: #9e9e9e;">Quiz Questions</h3>
        </div>
        <div class="col-xs-3">
            <button id="add_new_question_btn" type="button" class="add_new_item_btn btn btn-primary btn-sm">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
        </div>
    </div>
    <hr>
    <div id="quiz_questions_edit_div" class="row dash_content_row_div">
        <div class="col-sm-12">
            <?php if($num_questions_edit > 0){ ?>
            <table class="admin_dash_tbl_card question_choice_tbl table table-hover">
                  <thead style="background-color:#e3f2fd;">
                        <tr>
                            <th>QUESTION TEXT</th>
                            <th style="text-align: center;">TYPE</th>
                            <th style="text-align: center;">EDIT</th>
                            <th style="text-align: center;">REMOVE</th>
                        </tr>
                  </thead>
                  <tbody>
                <?php
                while($question = mysqli_fetch_assoc($question_set_edit)) {
                    /* check if question is checkbox or radio type */
                    $question['question_multivalued'] == 1 ? $question_type = 'checkbox' : $question_type = 'radio';
                    $question_edit_id = $question['question_id'];
                    $question_is_multi = $question['question_multivalued'];
                    $question_is_req = $question['question_is_required'];
                ?>
                    <tr id="question_edit_tbl_row_<?php echo h($question_edit_id); ?>" class="question_edit_tbl_row" data-question-id="<?php echo h($question_edit_id); ?>" data-question-is-multi="<?php echo h($question_is_multi); ?>" data-question-is-req="<?php echo h($question_is_req); ?>">
                        <td class="question_text_td" style="text-align: left;"><?php echo h($question['question_text']); ?></td>
                        <td><?php echo h($question_type); ?></td>
                        <td>
                            <button type="button" class="edit_question_pencil_btn btn btn-grey-lighten btn-sm" data-question-id="<?php echo h($question_edit_id); ?>">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                        </td>
                        <td>
                            <button type="button" class="delete_question_trash_btn btn btn-grey-lighten btn-sm" data-question-id="<?php echo h($question_edit_id); ?>">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
            <p style="text-align:left; color: #bdbdbd;">No questions available for the given quiz.</p>
            <?php } ?>
        </div>
    </div>
    <div style="padding-top: 50px;" class="row dash_subsection_div">
        <div class="col-xs-9">
            <h3 style="color: #9e9e9e;">Questions Choices<strong id="select_quest_edit_name" style="font-size: 17px;"></strong></h3>
        </div>
        <div id="add_new_q_choice_div" hidden class="col-xs-3">
            <button id="add_new_q_choice_btn" type="button" class="add_new_item_btn btn btn-primary btn-sm">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
        </div>
    </div>
    <hr>
    <div id="questions_choices_edit_div" class="row dash_content_row_div">
        <div class="col-sm-12">
            <p id="no_choice_text" style="text-align:left; color: #bdbdbd;">Please select a question to view or add choices.</p>
            <table hidden id="selec_q_choices_tbl" class="admin_dash_tbl_card question_choice_tbl table table-hover">
                  <thead style="background-color:#e0f2f1;">
                        <tr>
                            <th>CHOICE TEXT</th>
                            <th style="text-align: center;">VALUE</th>
                            <th style="text-align: center;">EDIT</th>
                            <th style="text-align: center;">REMOVE</th>
                        </tr>
                  </thead>
                  <tbody id="selec_q_choices_tbl_body">
                  </tbody>
            </table>
        </div>
    </div>
    <hr>
    <br>
</div>

<!-- ***************************** QUIZ NAME EDIT (MODAL) ***************************** -->
<div id="quizNameModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align: center;">QUIZ DETAILS</h4>
      </div>
      <div class="modal-body">
          <form id="quiz_general_details_edit_form">
              <div class="form-group">
                  <label for="quiz_name_text">Quiz Name:</label>
                  <input name="quiz_name_text_in" type="text" class="form-control" id="quiz_name_text" value="<?php echo h($assessment_name_edit); ?>">
              </div>
              <div class="form-group">
                  <label for="quiz_descr_text_area">Quiz Description:</label>
                  <textarea name="quiz_descr_text_in" class="form-control" rows="3" id="quiz_descr_text_area"><?php echo h($assessment_description_txt); ?></textarea>
              </div>
              <div class="submit_to_db_btn_div">
                  <button id="submit_quiz_general_details" class="btn btn-primary btn-sm" type="button">SUBMIT</button>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>

<!-- ***************************** QUIZ SETTINGS (MODAL) ***************************** -->
<div id="quizSettingsModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align: center;">QUIZ SETTINGS</h4>
      </div>
      <div class="modal-body">
          <form id="quiz_settings_edit_form">
              <div class="form-group">
                  <label for="select_num_questions_show">Select the number of questions to show:</label>
                  <select name ="num_quest_show_sel" class="form-control" id="select_num_questions_show">
                  <?php
                      /* restore number of questions to show */
                  for($i = 1; $i <= $num_questions_edit; $i++){
                      if($i == $assessment_num_quest){
                          $selected_num = "selected";
                      } else{
                          $selected_num = "";
                      }
                  ?>
                    <option <?php echo h($selected_num); ?>><?php echo $i; ?></option>
                  <?php } ?>
                  </select>
                </div>
              <div class="checkbox">
                  <label class="question_label"><input name="is_quiz_active_check" id="quiz_is_visible" type="checkbox" <?php echo ($assessment_is_visible == 1 ? 'checked' : ''); ?>> &nbsp;&nbsp;Quiz Active to Users</label>
              </div>
              <div class="submit_to_db_btn_div">
                  <button id="submit_settings_btn" class="btn btn-primary btn-sm" type="button">UPDATE</button>
              </div>
          </form>
          <hr>
          <div class="submit_to_db_btn_div">
            <button id="delete_quiz_btn" class="btn-red-delete btn btn-sm" type="button">DELETE QUIZZ</button>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>

<!-- ***************************** QUESTION EDIT (MODAL) ***************************** -->
<div id="quizQuestionEditModal" class="modal fade" role="dialog" data-question-edit-mode="" data-selec-quest-id="">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align: center;">QUIZ QUESTION</h4>
      </div>
      <div class="modal-body">
          <form id="quiz_question_edit_form">
              <div class="form-group">
                  <label for="quiz_question_text">Question Text:</label>
                  <input name="quiz_question_text_in" type="text" class="form-control" id="quiz_question_text">
              </div>
              <hr>
              <div class="checkbox">
                  <label class="question_label"><input name="is_quest_multi_check" id="question_is_multi_check" type="checkbox"> &nbsp;&nbsp;Multivalued Question</label>
              </div>
              <div class="checkbox">
                  <label class="question_label"><input name="is_quest_req_check" id="question_is_required_check" type="checkbox"> &nbsp;&nbsp;Required Question</label>
              </div>
              <div class="submit_to_db_btn_div">
                  <button id="submit_question_details" class="btn btn-primary btn-sm" type="button">SUBMIT</button>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>

<!-- ***************************** QUESTION CHOICE EDIT (MODAL) ***************************** -->
<div id="quizQuestionChoiceModal" class="modal fade" role="dialog" data-choice-edit-mode="" data-selec-quest-id="" data-question-is-multi="" data-selec-choice-id="">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align: center;">QUESTION CHOICE</h4>
      </div>
      <div class="modal-body">
          <form id="quiz_question_choice_edit_form">
              <div class="form-group">
                  <label for="quiz_choice_text">Choice Text:</label>
                  <input name="quiz_choice_text_in" type="text" class="form-control" id="quiz_choice_text">
              </div>
              <hr>
              <div class="checkbox">
                  <label class="question_label"><input name="is_choice_corr_check" id="choice_is_correct_check" type="checkbox"> &nbsp;&nbsp;Correct Choice</label>
              </div>
              <hr>
              <div class="form-group">
                  <label for="choice_descr_text_area">Choice Details (explain why correct or incorrect):</label>
                  <textarea name="choice_descr_text_in" class="form-control" rows="3" id="choice_descr_text_area"></textarea>
              </div>
              <div class="submit_to_db_btn_div">
                  <button id="submit_question_choice_details" class="btn btn-primary btn-sm" type="button">SUBMIT</button>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>

<!-- ***************************** CONFIRM DELETE MODAL ***************************** -->
<div id="confirmDeleteModal" class="modal fade" role="dialog" data-delete-type="" data-delete-id="">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="text-align: center;">CONFIRM DELETE</h4>
            <p style="text-align:center; margin-top:10px; color: #9e9e9e;"><strong id="item_to_delete_text"></strong></p>
        </div>
      <div class="modal-body">
          <div>
            <button id="delete_item_btn" class="btn-red-delete btn-block btn btn-lg" type="button">
                <span class="glyphicon glyphicon-trash"></span>
            </button>
          </div>
      </div>
        <div class="modal-footer">
        <button id="confirm_delete_close_btn" type="button" class="btn btn-default btn-sm" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>

<!-- ***************************** ALERT MODAL [ERROR] ***************************** -->
<div id="editquizErrorModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-body">
          <div class="alert alert-danger">
              <p id="alert_edit_quiz_wrong"></p>
          </div>
      </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>

<!-- ***************************** SUCCESS EDIT MODAL ***************************** -->
<div id="editquizInfoModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-body">
          <div class="alert alert-info">
              <p id="alert_info_quiz"></p>
          </div>
      </div>
        <div class="modal-footer">
        <button id="alert_info_close_btn" type="button" class="btn btn-default btn-sm" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>

<!-- ***************************** SUCCESS EDIT MODAL ***************************** -->
<div id="editquizSuccessModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-body">
          <div class="alert alert-success">
              <p id="alert_edit_quiz_success"></p>
          </div>
      </div>
        <div class="modal-footer">
        <button id="success_edit_close_btn" type="button" class="btn btn-default btn-sm" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>

<!-- *********************************** CONTENT END *********************************** -->

<script src="<?php echo url_for('js/main-script.js');?>"></script>
<script src="<?php echo url_for('js/admin-script.js');?>"></script>

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quiz_page_footer.php'); ?>
