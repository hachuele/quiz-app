<?php require_once('../private/initialize.php'); ?>

<!--STATIC VARIABLES FOR CURRENT PAGE-->
<?php
    #define variales for current page
    $site_title = 'HPC Assessments Site';
    $page_title = 'HPC ASSESSMENTS PAGE';
?>


<!--DATA FROM SQL DATABASE-->
<?php

    #PLACEHOLDER ARRAY FOR AVAILABLE QUIZZES (STAND IN FOR DATABASE)
    $available_courses = [
        ['id' => '1', 'course_name' => 'HPC New User'],
        ['id' => '2', 'course_name' => 'Intro to Linux'],
        ['id' => '3', 'course_name' => 'HPC Installing Software'],
        ['id' => '4', 'course_name' => 'HPC Advanced Topics'],
    ];


    #NEED TO GET USER ID THROUGH SHIB ENV VARIABLES
    #my $pi_sql = "select pi_id from pi_info where pi_rcf_user='$ENV{ShibuscNetID}'";
    $user_id = 'hachuelb';

?>

<!-- *********************************** PAGE HEADER *********************************** -->
<?php require(SHARED_PATH . '/quizz_page_header.php'); ?>


<!-- *********************************** CONTENT START *********************************** -->
<div id="assessment_selection_div" class="container main_content">
    <div id="assessment_select_contents_title" class="page-header">
        <h2>Available Quizzes</h2>
    </div>
    <?php foreach($available_courses as $course) { ?>
        <!--THESE BUTTONS WILL NEED TO BE GENERATED BASED ON DATABASE CONTENT (FOR LOOP)-->
        <button type="button" class="btn btn-primary btn-block" onclick="location.href='<?php echo url_for('quizz/index.php?id=' . h(u($course['id']))); ?>'"><?php echo h($course['course_name']); ?></button>
    <?php } ?>

    <br>

</div>
<!-- *********************************** CONTENT END *********************************** -->

<!--load personal scripts-->
<script src="<?php echo url_for('js/main-script.js');?>"></script>
<script src="<?php echo url_for('js/quizz-select-script.js');?>"></script>

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quizz_page_footer.php'); ?>

