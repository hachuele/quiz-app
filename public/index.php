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

    /* ---------------- Define Current Page Variables ---------------- */
    $site_title = 'HPC Assessments Site';
    $page_title = 'HPC ASSESSMENTS PAGE';


    /* ---------------- Get DB Data ---------------- */
    $course_set = find_all_visible_courses();


    /* ---------------- Get Dashboard Data ---------------- */
    $num_completed_quizzes = 0;
    $num_in_progress_quizzes = 0;


    /* ---- Get User ID ---- */

    #NEED TO GET USER ID THROUGH SHIB ENV VARIABLES
        #my $pi_sql = "select pi_id from pi_info where pi_rcf_user='$ENV{ShibuscNetID}'";
    $user_id = 'hachuelb';


    /* ---------------- Set Relevant Session Variables ---------------- */

    if(!isset($_SESSION["user_id"])){
        $_SESSION["user_id"] = $user_id;
    }







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
                    <h4>COMPLETED QUIZZES  <span class="glyphicon glyphicon-ok-circle solution_glyphicon_correct"></span></h4>
                    <hr>
                </div>
                <!--GET COMPLETED DATA FROM DB-->
                <?php
                    if ($num_completed_quizzes == 0){ ?>
                        <div id="zero_completed_dash">
                            <p class="zero_total_txt">0</p>
                        </div>

                <?php } else { ?>

                <div id="completed_quizzes_list"> hello </div>

                <?php } ?>


            </div>
        </div>

        <div class=" col-sm-6" style="margin-top: 15px;">
            <div id="in_progr_quizzes_dash_div" class="dashboard_element_card">
                <div class="dash_card_title_div">
                    <h4>IN-PROGRESS QUIZZES  <span class="glyphicon glyphicon-edit solution_glyphicon_correct_not_selected"></span></h4>
                    <hr>
                </div>
                <!--GET IN PROGRESS DATA FROM DB-->
                <?php
                    if ($num_in_progress_quizzes == 0){ ?>
                        <div id="zero_in_progress_dash">
                            <p class="zero_total_txt">0</p>
                        </div>

                <?php } else { ?>

                <div id="in_progress_quizzes_list"> hello </div>

                <?php } ?>






            </div>
        </div>
    </div>
    <hr>
    <div id="dash_available_row_div" class="row">
        <div class=" col-sm-12">
            <div id="available_quizzes_dash_div" class="dashboard_element_card">
                <div class="dash_card_title_div">
                    <h4>AVAILABLE QUIZZES <span class="glyphicon glyphicon-education solution_glyphicon_correct"></span></h4>
                    <hr>
                </div>

                <!--GET AVAILABLE DATA FROM DB-->
                <div id="available_quizzes_list">
                    <?php while($available_course = mysqli_fetch_assoc($course_set)) { ?>
                        <button type="button" class="btn btn-primary btn-block btn-sm" onclick="location.href='<?php echo url_for('quizz/index.php?assessment_id=' . h(u($available_course['assessment_id']))); ?>'"><?php echo h($available_course['assessment_name']); ?></button>
                    <?php } ?>





                </div>

                <hr>

                <button id="view_compl_quizzes_button" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#UserInfoModal">
                    <span class="glyphicon glyphicon-search"></span> Full Quizz History
                </button>






            </div>
        </div>


    </div>



    <br>



</div>
<!-- *********************************** CONTENT END *********************************** -->

<!--load personal scripts-->
<script src="<?php echo url_for('js/main-script.js');?>"></script>
<script src="<?php echo url_for('js/quizz-select-script.js');?>"></script>

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quizz_page_footer.php'); ?>

