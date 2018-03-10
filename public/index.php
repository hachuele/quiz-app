<?php

/******************************************************************
 * DESCRIPTION:
 *
 *                             ----
 * @author: Eric J. Hachuel
 * University of Southern California, High-Performance Computing
 ******************************************************************/
session_start();
?>

<?php require_once('../private/initialize.php'); ?>

<?php

    /* ---- Get User ID ---- */

    #NEED TO GET USER ID THROUGH SHIB ENV VARIABLES
        #my $pi_sql = "select pi_id from pi_info where pi_rcf_user='$ENV{ShibuscNetID}'";
    $user_id = 'johndoe';

    /* ---------------- Define Current Page Variables ---------------- */
    $site_title = 'HPC Assessments Site';
    $page_title = 'HPC ASSESSMENTS PAGE';


    /* ---------------- Get DB Data ---------------- */

    /* get set of all available quizzes */
    $course_set = find_all_visible_courses();
    $completed_quizz_array = array();
    $completed_quizz_unique_array = array();
    /* get all completed quizz data from given user */
    $completed_by_user_set = find_completed_quizzes_by_user($user_id);
    $num_completed_quizzes = mysqli_num_rows($completed_by_user_set);
    /* fill array with completed quizzes for user */
    $quizzes_checked = array();
    while($complete_quizz = mysqli_fetch_array($completed_by_user_set, MYSQLI_BOTH)){
        array_push($completed_quizz_array, $complete_quizz);
        /* if current assessment ID has not yet been seenm add to unique list */
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


    /* ---------------- Set Relevant Session Variables ---------------- */

//    if(!isset($_SESSION["user_id"])){
        $_SESSION["user_id"] = $user_id;
//    }







?>




<!-- *********************************** PAGE HEADER *********************************** -->
<?php require(SHARED_PATH . '/quizz_page_header.php'); ?>

<!-- *********************************** CONTENT START *********************************** -->
<div id="assessment_selection_div" class="container-fluid main_content">
    <div id="assessment_select_contents_title" class="page-header">
        <h2>HPC Assessments Dashboard</h2>
    </div>

    <div id="dash_compl_ip_row_div" class="row" style="margin:auto;">
        <div class="col-sm-6" style="margin-top: 15px;">
            <div id="completed_quizzes_dash_div" class="dashboard_element_card">
                <div class="dash_card_title_div">
                    <h4>LATEST COMPLETED QUIZZES  <span class="glyphicon glyphicon-ok-circle solution_glyphicon_correct"></span></h4>
                    <hr>
                </div>
                <!--GET COMPLETED DATA FROM DB-->
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
                              foreach($completed_quizz_unique_array as $completed_quizz){


                            ?>
                                <tr>
                                    <?php
                                        /* format the SQL timestamp to show (MM-DD-YYYY) */
                                        $time = strtotime(h($completed_quizz['user_assessment_end_stamp']));
                                        $time_formated = date("m-d-Y", $time);
                                    ?>
                                    <td><?php echo h($time_formated) ?></td>
                                    <?php
                                        /* get the assessment name with the given ID */
                                        $assessment_name = get_assessment_name($completed_quizz['assessment_id']);
                                    ?>
                                    <td><?php echo h($assessment_name) ?></td>
                                    <?php
                                        /* calculate the final score for the given completed quizz */
                                        $final_score = ((h($completed_quizz['user_assessment_num_correct'])) / (h($completed_quizz['user_assessment_num_correct'])
                                                + h($completed_quizz['user_assessment_num_incorrect']))) * 100.0 ."%";
                                    ?>

                                    <?php if ($final_score <= 75.0) { ?>
                                    <td class="compl_quizz_dash_score_low"><strong><?php echo h($final_score) ?></strong></td>
                                    <?php } else { ?>
                                    <td class="compl_quizz_dash_score_high"><strong><?php echo h($final_score) ?></strong></td>

                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <button id="view_compl_quizzes_button" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#UserInfoModal">
                    <span class="glyphicon glyphicon-search"></span> Full Quizz History
                </button>
                <?php } ?>

            </div>
        </div>

        <div class=" col-sm-6" style="margin-top: 15px;">
            <div id="available_quizzes_dash_div" class="dashboard_element_card">
                <div class="dash_card_title_div">
                    <h4>AVAILABLE &amp; IN-PROGRESS QUIZZES <span class="glyphicon glyphicon-education solution_glyphicon_correct_not_selected"></span></h4>
                    <hr>
                </div>
                <!--GET AVAILABLE DATA FROM DB, CHECK IF ANY IN PROGRESS-->
                <div id="available_quizzes_list">
                    <?php while($available_course = mysqli_fetch_assoc($course_set)) {
                        $is_in_progress = 0;
                        /* check if the available course is currently in progress*/
                        /* note: can only have one quizz in progress with the same ID */
                        foreach($in_progress_quizz_array as $ip_quizz){
                            if($ip_quizz['assessment_id'] == $available_course['assessment_id']){
                                $is_in_progress = 1;
                            }
                        }
                    ?>
                        <?php
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
                        <button style="text-align:left;" type="button" class="btn <?php echo h($button_class); ?> btn-block btn-sm" onclick="location.href='<?php echo url_for('quizz/index.php?assessment_id=' . h(u($available_course['assessment_id']))); ?>'">
                            <span class="pull-left"><?php echo h($available_course['assessment_name']); ?></span>
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
          <p style="text-align: center; color: #e53935;"><strong><?php echo h($_SESSION["user_id"]); ?></strong></p>
      </div>
      <div class="modal-body">
          <div class="page-header">
            <h4 style="text-align: center;">COMPLETED QUIZZES  <span class="glyphicon glyphicon-ok-circle solution_glyphicon_correct"></span></h4>
          </div>
          <!-- COMPLETED QUIZZES -->
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
                                <td><?php echo h($time_formated) ?></td>
                                <?php
                                    /* get the assessment name with the given ID */
                                    $assessment_name = get_assessment_name($completed_quizz['assessment_id']);
                                ?>


                                <td><?php echo h($assessment_name) ?></td>
                                <td class="compl_quizz_dash_score_high"><?php echo h($completed_quizz['user_assessment_num_correct']) ?></td>
                                <td class="compl_quizz_dash_score_low"><?php echo h($completed_quizz['user_assessment_num_incorrect']) ?></td>
                                <?php
                                    /* calculate the final score for the given completed quizz */
                                    $final_score = ((h($completed_quizz['user_assessment_num_correct'])) / (h($completed_quizz['user_assessment_num_correct'])
                                            + h($completed_quizz['user_assessment_num_incorrect']))) * 100.0 ."%";
                                ?>
                                <?php if ($final_score <= 75.0) { ?>
                                <td class="compl_quizz_dash_score_low"><strong><?php echo h($final_score) ?></strong></td>
                                <?php } else { ?>
                                <td class="compl_quizz_dash_score_high"><strong><?php echo h($final_score) ?></strong></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
              </table>
          </div>
          <div class="page-header">
            <h4 style="text-align: center;">IN PROGRESS QUIZZES   <span class="glyphicon glyphicon-edit solution_glyphicon_correct_not_selected"></span></h4>
          </div>

          <!-- QUIZZES IN PROGRESS -->
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
                                <td><?php echo h($time_formated) ?></td>
                                <?php
                                    /* get the assessment name with the given ID */
                                    $assessment_name = get_assessment_name($ip_quizz['assessment_id']);
                                ?>
                                <td><?php echo h($assessment_name) ?></td>

                                <?php
                                    /* get the number of questions for the quizz */
                                    $num_questions = get_num_question_by_assessment_id($ip_quizz['assessment_id']);
                                    /* calculate the final score for the given completed quizz */
                                    $perc_compl = (h($ip_quizz['latest_quest_sequential_num']) / h($num_questions)) * 100.0 ."%";
                                ?>
                                <td class="compl_quizz_dash_score_high"><strong><?php echo h($perc_compl) ?></strong></td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo url_for('quizz/index.php?assessment_id=' . h(u($ip_quizz['assessment_id']))); ?>'">
                                        <span  class="glyphicon glyphicon-menu-right"></span>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
              </table>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- *********************************** CONTENT END *********************************** -->

<!--load personal scripts-->
<script src="<?php echo url_for('js/main-script.js');?>"></script>
<script src="<?php echo url_for('js/quizz-select-script.js');?>"></script>

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quizz_page_footer.php'); ?>

