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


    $in_progress_quizz_array = array();
    $in_progress_by_user_set = find_in_progress_quizzes_by_user($user_id);
    $num_in_progress_quizzes = mysqli_num_rows($in_progress_by_user_set);


    /* fill array with in progress quizzes for user */
    while($ip_quizz = mysqli_fetch_array($in_progress_by_user_set, MYSQLI_BOTH)){
        array_push($in_progress_quizz_array, $ip_quizz);
    }


    /* ---------------- Get Dashboard Data ---------------- */
//    $num_completed_quizzes = 0;
//    $num_in_progress_quizzes = 0;





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

                <div id="completed_quizzes_list">
                    <table id="compl_quizzes_tbl" class="table table-hover">
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th>NAME</th>
                                <th>SCORE</th>
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
                                                + h($completed_quizz['user_assessment_num_incorrect']))) * 100.0 ." %";
                                    ?>

                                    <?php if ($final_score <= 75.0) { ?>
                                    <td id="compl_quizz_dash_score_low"><strong><?php echo h($final_score) ?></strong></td>
                                    <?php } else { ?>
                                    <td id="compl_quizz_dash_score_high"><strong><?php echo h($final_score) ?></strong></td>

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
<!-- *********************************** CONTENT END *********************************** -->

<!--load personal scripts-->
<script src="<?php echo url_for('js/main-script.js');?>"></script>
<script src="<?php echo url_for('js/quizz-select-script.js');?>"></script>

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quizz_page_footer.php'); ?>

