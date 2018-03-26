
<?php
/******************************************************************
 * DESCRIPTION: 'public/admin/index.php' serves as the main page
 * for administrative purposes. Allows to create and edit quizzes,
 * as well as to view user statistics
 *                             ----
 * @author: Eric J. Hachuel
 * University of Southern California, High-Performance Computing
 ******************************************************************/
session_start();
require_once('../../private/initialize.php');
?>

<?php
/* ---------------- Dynamic Naming Variables ---------------- */
$site_title = 'HPC Assessments Admin Site';
$page_title = 'HPC ASSESSMENTS ADMIN';
$help_modal_title = 'HPC ASSESSMENTS ADMIN HELP';
$help_modal_txt = 'Create a new quizz...';

/* ----------------------------------------------------------------------------------------- */
/* -------------------------------------- Get User ID -------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

#my $pi_sql = "select pi_id from pi_info where pi_rcf_user='$ENV{ShibuscNetID}'";
$user_id = 'hachuelb';
$_SESSION["user_id"] = $user_id;

/* ----------------------------------------------------------------------------------------- */
/* ------------------------------------ Retrieve from DB ----------------------------------- */
/* ----------------------------------------------------------------------------------------- */


?>

<!-- *********************************** PAGE HEADER *********************************** -->
<?php require(SHARED_PATH . '/quizz_admin_header.php'); ?>

<!-- *********************************** CONTENT START *********************************** -->
<div id="assessments_main_dash_div" class="container-fluid main_content">
    <div class="page-header">
        <h2 id="dash_title_txt">HPC Assessments: Create or Modify</h2>
    </div>

    <hr>
    <br>
</div>


<!-- *********************************** CONTENT END *********************************** -->

<script src="<?php echo url_for('js/main-script.js');?>"></script>

<!-- *********************************** PAGE FOOTER *********************************** -->
<?php require(SHARED_PATH .  '/quizz_page_footer.php'); ?>


