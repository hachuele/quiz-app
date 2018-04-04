<?php
/***********************************************************************
 * DESCRIPTION: public/index.php serves as the main page for the
 * user's dashboard. Used to select new or in progress quizzes
 * and view user statistics for previously completed quizzes
 *                             ----
 * @author: Eric J. Hachuel
 * Copyright 2018 University of Southern California. All rights reserved.
 ***********************************************************************/
session_start();
require_once('../private/initialize.php');
?>

<?php
/* ---------------- Dynamic Naming Variables ---------------- */
$site_title = 'HPC Assessments Site';
$page_title = 'HPC ASSESSMENTS';
$help_modal_title = 'HPC QUIZZ HELP';
$help_modal_txt = 'Please complete the selected Quizz...';

/* ----------------------------------------------------------------------------------------- */
/* -------------------------------------- Get User ID -------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

$user_id = 'hachuelb';
$_SESSION["user_id"] = $user_id;

/* ----------------------------------------------------------------------------------------- */
/* ------------------------------------ Retrieve from DB ----------------------------------- */
/* ----------------------------------------------------------------------------------------- */

/* get set of all available quizzes */
$course_set = find_all_visible_courses();
/* declare array to store all completed quizz information for the current user */
$completed_quizz_array = array();
/* declare array to store unique completed quizz information for the current user */
$completed_quizz_unique_array = array();
/* get all completed quizz data from given user */
$completed_by_user_set = find_completed_quizzes_by_user($user_id);
$num_completed_quizzes = mysqli_num_rows($completed_by_user_set);
/* declare array to track quizzes that have been checked (to find unique quizzes) */
$quizzes_checked = array();

while($complete_quizz = mysqli_fetch_array($completed_by_user_set, MYSQLI_BOTH)){
    array_push($completed_quizz_array, $complete_quizz);
    /* if current assessment ID has not yet been seen add to unique list */
    if(!in_array($complete_quizz['assessment_id'], $quizzes_checked)){
        array_push($quizzes_checked, $complete_quizz['assessment_id']);
        array_push($completed_quizz_unique_array, $complete_quizz);
    }
}

/* get set of all in-progress quizzes for given user */
$in_progress_quizz_array = array();
$in_progress_by_user_set = find_in_progress_quizzes_by_user($user_id);
$num_in_progress_quizzes = mysqli_num_rows($in_progress_by_user_set);

/* fill array with in progress quizzes for user */
while($ip_quizz = mysqli_fetch_array($in_progress_by_user_set, MYSQLI_BOTH)){
    array_push($in_progress_quizz_array, $ip_quizz);
}

?>

<!-- *********************************** PAGE HEADER *********************************** -->
<?php require(SHARED_PATH . '/quizz_page_header.php'); ?>

<!-- *********************************** CONTENT START *********************************** -->
<div id="assessments_main_dash_div" class="container-fluid main_content">
    <div class="page-header">
        <h2 class="dash_title_txt">HPC Assessments Dashboard</h2>
    </div>
    <div class="row dash_content_row_div">
        <div class="col-sm-6" style="margin-top: 15px;">
            <div class="dashboard_element_card">
                <div class="dash_card_title_div">
                    <h4 class="dash_card_title_txt">COMPLETED QUIZZES &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-ok-circle solution_glyphicon_correct"></span></h4>
                    <hr>
                </div>
                <?php
                if ($num_completed_quizzes == 0){ ?>
                <div id="zero_completed_dash">
                    <p class="zero_total_txt">0</p>
                </div>
                <?php } else { ?>

                <div class="completed_quizzes_list">
                    <table class="table table-hover compl_quizzes_tbl">
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th>NAME</th>
                                <th style="text-align: center;">SCORE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $num_unique_shown = 0;
                            foreach($completed_quizz_unique_array as $completed_quizz){
                                /* show a maximum number of unique, completed quizzes on the dashboard */
                                if(++$num_unique_shown > 3){
                                    break;
                                }
                            ?>
                            <tr>
                                <?php
                                /* format the SQL timestamp to show (MM-DD-YYYY) */
                                $time = strtotime(h($completed_quizz['user_assessment_end_stamp']));
                                $time_formated = date("m-d-Y", $time);
                                ?>
                                <td><?php echo h($time_formated); ?></td>
                                <?php
                                /* get the assessment name with the given ID */
                                $assessment_name = get_assessment_name($completed_quizz['assessment_id']);
                                ?>
                                <td><?php echo h($assessment_name); ?></td>
                                <?php
                                /* calculate the final score for the given completed quizz */
                                $final_score = round(((h($completed_quizz['user_assessment_num_correct'])) / (h($completed_quizz['user_assessment_num_correct'])
                                        + h($completed_quizz['user_assessment_num_incorrect']))) * 100) ."%";
                                ?>
                                <?php if ($final_score <= 75.0) { ?>
                                <td class="compl_quizz_dash_score_low"><strong><?php echo h($final_score); ?></strong></td>
                                <?php } else { ?>
                                <td class="compl_quizz_dash_score_high"><strong><?php echo h($final_score); ?></strong></td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <button id="view_compl_quizzes_button" type="button" class="btn btn-grey-lighten btn-sm" data-toggle="modal" data-target="#UserInfoModal">
                    <span class="glyphicon glyphicon-search"></span> <span style="font-size: 10px;">SEE MORE</span>
                </button>
                <?php } ?>
            </div>
        </div>
        <div class=" col-sm-6" style="margin-top: 15px;">
            <div id="available_quizzes_dash_div" class="dashboard_element_card">
                <div class="dash_card_title_div">
                    <h4 class="dash_card_title_txt">AVAILABLE QUIZZES &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-education blue_darken_2"></span></h4>
                    <hr>
                </div>
                <div class="available_options_list">
                    <?php
                    /* loop through all avaliable courses in the course set */
                    while($available_course = mysqli_fetch_assoc($course_set)) {
                        $is_in_progress = 0;
                        $perc_compl = '';
                        /* check if the available course is currently in progress*/
                        /* NOTE: can only have one quizz in progress with the same ID */
                        foreach($in_progress_quizz_array as $ip_quizz){
                            if($ip_quizz['assessment_id'] == $available_course['assessment_id']){
                                $is_in_progress = 1;
                                /* get the number of questions for the in progress quizz */
                                $num_questions = get_num_question_by_assessment_id($ip_quizz['assessment_id']);
                                /* calculate the final score for the given completed quizz */
                                $perc_compl = '(' . round((h($ip_quizz['latest_quest_sequential_num']) / h($num_questions)) * 100) ."%)";
                            }
                        }

                        /* check if current course is in progress, display appropriate button */
                        $button_class = "btn-primary";
                        $button_glyphicon = "";
                        if($is_in_progress){
                            $button_class = "btn-info";
                            $button_glyphicon = "glyphicon glyphicon-menu-right";
                        } else{
                            $button_class = "btn-primary";
                        }
                    ?>
                    <button style="text-align:left;" type="button" class="quizz_list_btn btn <?php echo h($button_class); ?> btn-block btn-sm" onclick="location.href='<?php echo url_for('quizz/index.php?assessment_id=' . h(u($available_course['assessment_id']))); ?>'">
                        <span class="pull-left"><?php echo h($available_course['assessment_name']) . " " . $perc_compl; ?></span>
                        <span style="float:right;" class="pull-right <?php echo h($button_glyphicon); ?>"></span>
                    </button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <br>
</div>

<!-- ***************************** FULL STATISTICS DISPLAY (MODAL) ***************************** -->
<div id="UserInfoModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Help Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align: center;">USER QUIZZ HISTORY</h4>
          <p class="blue_darken_2" style="text-align: center;"><strong><?php echo h($user_id); ?></strong></p>
      </div>
      <div class="modal-body">
          <div class="page-header">
            <h4 style="text-align: center;">IN PROGRESS QUIZZES &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-edit blue_darken_2"></span></h4>
          </div>

          <?php if($num_in_progress_quizzes > 0){ ?>

          <div class="all_statistics_lists">
              <table class="table table-hover compl_quizzes_tbl">
                  <thead>
                    <tr>
                        <th>STARTED</th>
                        <th>NAME</th>
                        <th style="text-align: center;">% COMPL</th>
                        <th style="text-align: center;">CONTINUE</th>
                    </tr>
                  </thead>
                  <tbody>
                        <?php foreach($in_progress_quizz_array as $ip_quizz){ ?>
                        <tr>
                            <?php
                            /* format the SQL timestamp to show (MM-DD-YYYY) */
                            $time = strtotime(h($ip_quizz['user_assessment_start_stamp']));
                            $time_formated = date("m-d-Y", $time);
                            ?>
                            <td><?php echo h($time_formated); ?></td>
                            <?php
                            /* get the assessment name with the given ID */
                            $assessment_name = get_assessment_name($ip_quizz['assessment_id']);
                            ?>
                            <td><?php echo h($assessment_name); ?></td>
                            <?php
                            /* get the number of questions for the quizz */
                            $num_questions = get_num_question_by_assessment_id($ip_quizz['assessment_id']);
                            /* calculate the final score for the given completed quizz */
                            $perc_compl = round((h($ip_quizz['latest_quest_sequential_num']) / h($num_questions)) * 100) ."%";
                            ?>
                            <td class="compl_quizz_dash_score_high"><strong><?php echo h($perc_compl); ?></strong></td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-grey-lighten btn-xs" onclick="location.href='<?php echo url_for('quizz/index.php?assessment_id=' . h(u($ip_quizz['assessment_id']))); ?>'">
                                    <span class="glyphicon glyphicon-menu-right"></span>
                                </button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
              </table>
          </div>

          <?php } else { ?>

          <p style="text-align: center; color: #bdbdbd;">No quizzes currently in progress.</p>

          <?php } ?>

          <div class="page-header">
            <h4 style="text-align: center;">COMPLETED QUIZZES &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-ok-circle solution_glyphicon_correct"></span></h4>
          </div>
          <div class="all_statistics_lists">
              <table class="table table-hover compl_quizzes_tbl">
                  <thead>
                        <tr>
                            <th>COMPLETED</th>
                            <th>NAME</th>
                            <th style="text-align: center;">CORRECT</th>
                            <th style="text-align: center;">INCORRECT</th>
                            <th style="text-align: center;">SCORE</th>
                        </tr>
                  </thead>
                  <tbody>
                    <?php foreach($completed_quizz_array as $completed_quizz){ ?>
                    <tr>
                        <?php
                        /* format the SQL timestamp to show (MM-DD-YYYY) */
                        $time = strtotime(h($completed_quizz['user_assessment_end_stamp']));
                        $time_formated = date("m-d-Y", $time);
                        ?>
                        <td><?php echo h($time_formated); ?></td>
                        <?php
                        /* get the assessment name with the given ID */
                        $assessment_name = get_assessment_name($completed_quizz['assessment_id']);
                        ?>
                        <td><?php echo h($assessment_name) ?></td>
                        <td class="compl_quizz_dash_score_high"><?php echo h($completed_quizz['user_assessment_num_correct']); ?></td>
                        <td class="compl_quizz_dash_score_low"><?php echo h($completed_quizz['user_assessment_num_incorrect']); ?></td>
                        <?php
                        /* calculate the final score for the given completed quizz */
                        $final_score = round(((h($completed_quizz['user_assessment_num_correct'])) / (h($completed_quizz['user_assessment_num_correct'])
                                + h($completed_quizz['user_assessment_num_incorrect']))) * 100) ."%";
                        ?>
                        <?php if ($final_score <= 75.0) { ?>
                        <td class="compl_quizz_dash_score_low"><strong><?php echo h($final_score); ?></strong></td>
                        <?php } else { ?>
                        <td class="compl_quizz_dash_score_high"><strong><?php echo h($final_score); ?></strong></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </tbody>
              </table>
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

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quizz_page_footer.php'); ?>

