<?php
/***********************************************************************
 * DESCRIPTION: 'public/admin/edit/index.php' serves as the page
 * for editing or creating new quizzes
 *                             ----
 * @author: Eric J. Hachuel
 * Copyright 2018 University of Southern California. All rights reserved.
 ***********************************************************************/
session_start();
require_once('../../../private/initialize.php');
?>

<?php
/* ---------------- Dynamic Naming Variables ---------------- */
$site_title = 'HPC Assessments Admin Site';
$page_title = 'HPC ASSESSMENTS ADMIN: EDIT';
$help_modal_title = 'HPC ASSESSMENTS ADMIN HELP';
$help_modal_txt = 'Create a new quizz or edit an existing one...';

/* ----------------------------------------------------------------------------------------- */
/* -------------------------------------- Get User ID -------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

$user_id = 'hachuelb';
$_SESSION["user_id"] = $user_id;

/* -------- get the assessment id from url (if not found, set to one) -------- */
$assessment_id = $_GET['assessment_id'];

/* ----------------------------------------------------------------------------------------- */
/* ------------------------------------ Retrieve from DB ----------------------------------- */
/* ----------------------------------------------------------------------------------------- */

/* -------- get the name of this assessment for display -------- */



$assessment_data_row = get_assessment_row($assessment_id);

/* if the quizz for the given id does not exist, redirect to main page */
//TODO: REDIRECT NOT WORKING!!!!
//if($assessment_data_row == FALSE){
//    echo 'false';
//    redirect_to('../../index.php');
//}

$assessment_name_edit = $assessment_data_row['assessment_name'];
$assessment_description_txt = $assessment_data_row['assessment_description'];

/* -------- get questions for selected assessment -------- */
$question_set_edit = find_questions_by_assessment_id($assessment_id);
$num_questions_edit = mysqli_num_rows($question_set_edit);






/* NOTE: Question choices shown through ajax call on question row click */



?>

<!-- *********************************** PAGE HEADER *********************************** -->
<?php require(SHARED_PATH . '/quizz_admin_header.php'); ?>

<!-- *********************************** CONTENT START *********************************** -->
<div id="edit_assessments_main_div" class="container-fluid main_content">
    <div id="quizz_title_edit_div" class="page-header">
        <h2 id="quizz_title_txt" class="dash_title_txt"><?php echo h($assessment_name_edit); ?> &nbsp;&nbsp;<span id="edit_quizz_name_span" style="font-size:15px;" class="glyphicon glyphicon-pencil"></span></h2>
    </div>
    <div class="row dash_subsection_div">
        <div class="col-xs-9">
            <h3>Quizz Questions</h3>
        </div>
        <div class="col-xs-3">
            <button id="add_new_question_btn" type="button" class="add_new_item_btn btn btn-success btn-xs">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
        </div>
    </div>
    <hr>
    <div id="quizz_questions_edit_div" class="row dash_content_row_div">
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

            ?>
                    <tr class="question_edit_tbl_row" data-question-id="<?php echo h($question_edit_id); ?>">
                        <td style="text-align: left;"><?php echo h($question['question_text']); ?></td>
                        <td><?php echo h($question_type); ?></td>
                        <td>
                            <button type="button" class="edit_question_pencil_btn btn btn-grey-lighten btn-sm">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                        </td>
                        <td>
                            <button type="button" class="delete_question_trash_btn btn btn-grey-lighten btn-sm">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </td>
                    </tr>
            <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>

            <p style="text-align:left; color: #bdbdbd;">No questions available for the given quizz.</p>

            <?php } ?>


        </div>
    </div>

    <div style="padding-top: 50px;" class="row dash_subsection_div">
        <div class="col-xs-9">
            <h3>Questions Choices<strong id="select_quest_edit_name" style="font-size: 17px;"></strong></h3>
        </div>
        <div id="add_new_q_choice_div" hidden class="col-xs-3">
            <button id="add_new_q_choice_btn" type="button" class="add_new_item_btn btn btn-success btn-xs">
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

<!-- ***************************** QUIZZ NAME EDIT (MODAL) ***************************** -->
<div id="QuizzNameModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align: center;">QUIZZ DETAILS</h4>
      </div>
      <div class="modal-body">
          <form id="quizz_general_details_edit_form">
              <div class="form-group">
                  <label for="quizz_name_text">Quizz Name:</label>
                  <input type="text" class="form-control" id="quizz_name_text" value="<?php echo h($assessment_name_edit); ?>">
              </div>
              <div class="form-group">
                  <label for="quizz_descr_text_area">Quizz Description:</label>
                  <textarea class="form-control" rows="3" id="quizz_descr_text_area"><?php echo h($assessment_description_txt); ?></textarea>
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

<!-- ***************************** QUESTION EDIT (MODAL) ***************************** -->
<div id="QuizzQuestionEditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align: center;">QUIZZ QUESTION</h4>
      </div>
      <div class="modal-body">
          <form id="quizz_question_edit_form">
              <div class="form-group">
                  <label for="quizz_question_text">Question Text:</label>
                  <input type="text" class="form-control" id="quizz_question_text">
              </div>
              <hr>
              <div class="checkbox">
                  <label class="question_label"><input id="question_is_multi_check" type="checkbox"> &nbsp;&nbsp;Multivalued Question</label>
              </div>
              <div class="checkbox">
                  <label class="question_label"><input id="question_is_required_check" type="checkbox"> &nbsp;&nbsp;Required Question</label>
              </div>
              <div class="submit_new_record_btn_div">
                  <button disabled id="submit_question_details" class="btn btn-primary btn-sm" type="button">SUBMIT</button>
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
<div id="QuizzQuestionChoiceModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align: center;">QUESTION CHOICE</h4>
      </div>
      <div class="modal-body">
          <form id="quizz_question_choice_edit_form">
              <div class="form-group">
                  <label for="quizz_choice_text">Choice Text:</label>
                  <input type="text" class="form-control" id="quizz_choice_text">
              </div>
              <hr>
              <div class="checkbox">
                  <label class="question_label"><input id="question_is_correct_check" type="checkbox"> &nbsp;&nbsp;Correct Choice</label>
              </div>
              <hr>
              <div class="form-group">
                  <label for="choice_descr_text_area">Choice Details (explain why correct or incorrect):</label>
                  <textarea class="form-control" rows="3" id="choice_descr_text_area"></textarea>
              </div>
              <div class="submit_new_record_btn_div">
                  <button disabled id="submit_question_details" class="btn btn-primary btn-sm" type="button">SUBMIT</button>
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
