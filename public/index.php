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
    $course_set = find_all_visible_courses();


    /* ---- Get User ID ---- */

    #NEED TO GET USER ID THROUGH SHIB ENV VARIABLES
        #my $pi_sql = "select pi_id from pi_info where pi_rcf_user='$ENV{ShibuscNetID}'";
    $user_id = 'hachuelb';

    if(!isset($_SESSION["user_id"])){
        $_SESSION["user_id"] = $user_id;
    }
?>

<!--STATIC VARIABLES FOR CURRENT PAGE-->
<?php
    #define variales for current page
    $site_title = 'HPC Assessments Site';
    $page_title = 'HPC ASSESSMENTS PAGE';
?>



<!--REDESIGN THIS INTO A DASHBOARD | WANT TO SEE STATISTICS AS SOON AS GET IN!!!!!-->



<!-- *********************************** PAGE HEADER *********************************** -->
<?php require(SHARED_PATH . '/quizz_page_header.php'); ?>


<!-- *********************************** CONTENT START *********************************** -->
<div id="assessment_selection_div" class="container main_content">
    <div id="assessment_select_contents_title" class="page-header">
        <h2>Available Quizzes</h2>
    </div>
    <?php while($available_course = mysqli_fetch_assoc($course_set)) { ?>
        <button type="button" class="btn btn-primary btn-block" onclick="location.href='<?php echo url_for('quizz/index.php?assessment_id=' . h(u($available_course['assessment_id']))); ?>'"><?php echo h($available_course['assessment_name']); ?></button>
    <?php } ?>

    <br>

    <button id="view_compl_quizzes_button" type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#UserInfoModal">
        <span style="float: left;" class="glyphicon glyphicon-search"></span> View Your Quizz Data
    </button>

</div>
<!-- *********************************** CONTENT END *********************************** -->

<!--load personal scripts-->
<script src="<?php echo url_for('js/main-script.js');?>"></script>
<script src="<?php echo url_for('js/quizz-select-script.js');?>"></script>

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quizz_page_footer.php'); ?>

