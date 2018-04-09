<?php require_once('initialize.php'); ?>
<?php
/***********************************************************************
 * DESCRIPTION: load_stats.php retrieves and displays results of
 * the completed quizz.
 *
 * --------------------------------------------------------------
 * @author: Eric J. Hachuel
 * Copyright 2018 University of Southern California. All rights reserved.
 * ----------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ***********************************************************************/
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
