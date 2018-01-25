<?php require_once('../private/initialize.php'); ?>

<?php
    $course_set = find_all_visible_courses();


    //TODO: USE USER ID TO DIFFERENTIATE BETWEEN COMPLETED ASSIGNMENTS ETC.

    #NEED TO GET USER ID THROUGH SHIB ENV VARIABLES
        #my $pi_sql = "select pi_id from pi_info where pi_rcf_user='$ENV{ShibuscNetID}'";
    //    $user_id = 'hachuelb';
?>

<!--STATIC VARIABLES FOR CURRENT PAGE-->
<?php
    #define variales for current page
    $site_title = 'HPC Assessments Site';
    $page_title = 'HPC ASSESSMENTS PAGE';
?>


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

</div>
<!-- *********************************** CONTENT END *********************************** -->

<!--load personal scripts-->
<script src="<?php echo url_for('js/main-script.js');?>"></script>
<script src="<?php echo url_for('js/quizz-select-script.js');?>"></script>

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quizz_page_footer.php'); ?>

