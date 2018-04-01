
<?php
/******************************************************************
 * DESCRIPTION: 'public/admin/edit/index.php' serves as the page
 * for editing or creating new quizzes
 *                             ----
 * @author: Eric J. Hachuel
 * University of Southern California, High-Performance Computing
 ******************************************************************/
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
$assessment_name_edit = get_assessment_name($assessment_id);

/* if the quizz for the given id does not exist, redirect to main page */
//TODO: REDIRECT NOT WORKING!!!!
//if($assessment_name_edit == FALSE){
//    redirect_to('../index.php');
//}

/* -------- get questions for selected assessment -------- */
$question_set = find_questions_by_assessment_id($assessment_id);
$num_questions = mysqli_num_rows($question_set);
//echo $num_questions;

/* NOTE: Question choices shown through ajax call on question row click */



?>

<!-- *********************************** PAGE HEADER *********************************** -->
<?php require(SHARED_PATH . '/quizz_admin_header.php'); ?>

<!-- *********************************** CONTENT START *********************************** -->
<div id="edit_assessments_main_div" class="container-fluid main_content">
    <div id="quizz_title_edit_div" class="page-header">
        <h2 id="quizz_title_txt" class="dash_title_txt">Quizz name &nbsp;&nbsp;<span id="edit_quizz_name_span" style="font-size:15px;" class="glyphicon glyphicon-pencil"></span></h2>
    </div>
    <div class="row dash_subsection_div">
        <div class="col-xs-9">
            <h3>Quizz Questions</h3>
        </div>
        <div class="col-xs-3">
            <button id="add_new_question_btn" type="button" class="add_new_item_btn btn btn-primary btn-xs">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
        </div>
    </div>
    <hr>
    <div id="quizz_questions_edit_div" class="row dash_content_row_div">
        <div class="col-sm-12">



        </div>
    </div>

    <div class="row dash_subsection_div">
        <div class="col-xs-9">
            <h3>Questions Choices<strong style="font-size: 17px;">:&nbsp;&nbsp; question_text</strong></h3>
        </div>
        <div class="col-xs-3">
            <button id="add_new_q_choice_btn" type="button" class="add_new_item_btn btn btn-primary btn-xs">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
        </div>
    </div>
    <hr>
    <div id="questions_choices_edit_div" class="row dash_content_row_div">
        <div class="col-sm-12">



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
          <h4 class="modal-title" style="text-align: center;">QUIZZ NAME</h4>
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
