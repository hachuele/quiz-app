<?php require_once('initialize.php'); ?>
<?php
/******************************************************************
 * DESCRIPTION: load_stats.php retrieves and displays results of
 * the completed quizz.
 *
 * --------------------------------------------------------------
 * @author: Eric J. Hachuel
 * University of Southern California, High-Performance Computing
 ******************************************************************/
session_start();

$user_assessment_id = $_POST['user_assessment_id'];
$final_stats_set = get_user_assessment_by_ua_id($user_assessment_id);
$final_stats_row = mysqli_fetch_assoc($final_stats_set);
$num_choices = mysqli_num_rows($final_stats_row);

$output_array = array();
$output_array['assessment_id'] = $final_stats_row['assessment_id'];
$output_array['num_correct'] = $final_stats_row['user_assessment_num_correct'];
$output_array['num_incorrect'] = $final_stats_row['user_assessment_num_incorrect'];

echo json_encode($output_array);

?>
