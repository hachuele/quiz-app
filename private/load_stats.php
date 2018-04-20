<?php require_once('initialize.php'); ?>
<?php
/***********************************************************************
* DESCRIPTION: 'load_stats.php' retrieves and displays results of
* the completed quiz.
*
* --------------------------------------------------------------
* @author: Eric J. Hachuel
* Copyright 2018 University of Southern California. All rights reserved.
*
* DISCLAIMER.  USC MAKES NO EXPRESS OR IMPLIED WARRANTIES, EITHER IN FACT OR
* BY OPERATION OF LAW, BY STATUTE OR OTHERWISE, AND USC SPECIFICALLY AND
* EXPRESSLY DISCLAIMS ANY EXPRESS OR IMPLIED WARRANTY OF MERCHANTABILITY OR
* FITNESS FOR A PARTICULAR PURPOSE, VALIDITY OF THE SOFTWARE OR ANY OTHER
* INTELLECTUAL PROPERTY RIGHTS OR NON-INFRINGEMENT OF THE INTELLECTUAL
* PROPERTY OR OTHER RIGHTS OF ANY THIRD PARTY. SOFTWARE IS MADE AVAILABLE
* AS-IS.
****************************************************************************/
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
