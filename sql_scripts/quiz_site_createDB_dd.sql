/*****************************************************************************
* DESCRIPTION: quiz_site_createDB.sql is the SQL script necessary
* to set up the 'quizing_db' database
*                             ----
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

CREATE DATABASE IF NOT EXISTS quizing_db;

USE quizing_db;

-- -----------------------------------------------
-- Table structure for table `assessments`
-- Contains quizzes in the system
-- -----------------------------------------------

DROP TABLE IF EXISTS assessments;
CREATE TABLE assessments(
assessment_id INT(11) NOT NULL AUTO_INCREMENT,
assessment_name VARCHAR(255) NOT NULL,
assessment_description VARCHAR(255) NOT NULL,
assessment_create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
assessment_visible TINYINT(1) NOT NULL DEFAULT 0,
assessment_num_q_to_show INT(11) NOT NULL DEFAULT 10,
assessment_virtual_delete TINYINT(1) NOT NULL DEFAULT 0,
PRIMARY KEY(assessment_id)
);

INSERT INTO assessments(assessment_id, assessment_name, assessment_description, assessment_visible) VALUES
(1, 'HPC New User', 'Assessment for new HPC users', 1),
(2, 'Intro to Linux', 'Assessment for users who have taken the Intro to Linux workshop', 1),
(3, 'HPC Installing Software', 'Assessment for users who have taken the HPC Installing Software workshop', 1),
(4, 'HPC Advanced Topics', 'Assessment for users who have taken the HPC Advanced Topics workshop', 1),
(5, 'Parallel Programming in R', 'Assessment for users who have taken the R Parallel Programming workshop', 0),
(6, 'Intro to Matlab', 'Assessment for users who have taken the Intro to Matlab workshop', 0);

-- -----------------------------------------------

-- -----------------------------------------------
-- Table structure for table `questions`
-- Contains the questions for all quizzes
-- -----------------------------------------------

DROP TABLE IF EXISTS questions;
CREATE TABLE questions(
question_id INT(11) NOT NULL AUTO_INCREMENT,
assessment_id INT(11) NOT NULL,
question_text VARCHAR(255) NOT NULL,
question_multivalued TINYINT(1) NOT NULL,
question_is_required TINYINT(1) NOT NULL DEFAULT 1,
question_virtual_delete TINYINT(1) NOT NULL DEFAULT 0,
PRIMARY KEY(question_id),
CONSTRAINT FK_questions_assessmentID FOREIGN KEY (assessment_id)
REFERENCES assessments(assessment_id) ON DELETE CASCADE
);

INSERT INTO questions(question_id, assessment_id, question_text, question_multivalued) VALUES
(1, 1, 'Which of the following are true about HPC Computers? (Select all that apply)', 1),
(2, 1, 'How can you get help at HPC? (Select all that apply)', 1),
(3, 2, 'What is a Unix/Linux Shell', 0),
(4, 2, 'How many shells does HPC support?', 0),
(5, 3, 'What kind of installs do users typically perform?', 0),
(6, 3, 'Which of the following define Environment Variables? (Select all that apply)', 1),
(7, 4, 'What is the first step when submitting a batch job?', 0),
(8, 4, 'Which of the following commands can you use to check the status of queued jobs? (Select all that apply)', 1),
(9, 1, 'What is HPC? (Select all that apply)', 0),
(10, 5, 'What is R?', 0);

-- -----------------------------------------------

-- -------------------------------------------------------------------
-- Table structure for table `question_choices`
-- Contains the choice information for all questions in all quizzes
-- -------------------------------------------------------------------

DROP TABLE IF EXISTS question_choices;
CREATE TABLE question_choices(
question_choice_id INT(11) NOT NULL AUTO_INCREMENT,
question_id INT(11) NOT NULL,
question_choice_text VARCHAR(255) NOT NULL,
question_choice_correct TINYINT(1) NOT NULL,
question_choice_reason VARCHAR(255) NOT NULL,
question_choice_virtual_delete TINYINT(1) NOT NULL DEFAULT 0,
PRIMARY KEY(question_choice_id),
CONSTRAINT FK_question_choices_questionID FOREIGN KEY (question_id)
REFERENCES questions(question_id) ON DELETE CASCADE
);

INSERT INTO question_choices(question_choice_id, question_id, question_choice_text, question_choice_correct, question_choice_reason) VALUES
(1, 1, 'They run CentOS 6.5', 1, "Choice is correct because X."),
(2, 1, 'They run Linux', 1, "Choice is correct because X."),
(3, 1, 'They are powered down once a year', 0, "Choice is incorrect because Y."),
(4, 2, 'Go to our website at http://hpcc.usc.edu', 1, "Choice is correct because X."),
(5, 2, 'Send mail to the HPC Director', 0, "Choice is incorrect because Y."),
(6, 2, 'Send mail to hpc@usc.edu', 1, "Choice is correct because X."),
(7, 3, 'A command interpreter and processor', 1, "Choice is correct because X."),
(8, 3, 'A software program for data analysis', 0, "Choice is incorrect because Y."),
(9, 3, 'There is no such thing as a Unix/Linux Shell', 0, "Choice is incorrect because Y."),
(10, 4, '0', 0, "Choice is incorrect because Y."),
(11, 4, '1', 0, "Choice is incorrect because Y."),
(12, 4, '2', 1, "Choice is correct because X."),
(13, 5, 'Local', 1, "Choice is correct because X."),
(14, 5, 'Global', 0, "Choice is incorrect because Y."),
(15, 5, 'Neither', 0, "Choice is incorrect because Y."),
(16, 6, 'They are named string variables', 1, "Choice is correct because X."),
(17, 6, 'They are all uppercase', 0, "Choice is incorrect because Y."),
(18, 6, 'The value is accessed by preceding the variable with a $', 1, "Choice is correct because X."),
(19, 7, 'Get a JobID assigned by the Scheduler', 0, "Choice is incorrect because Y."),
(20, 7, 'Submit your PBS file', 1, "Choice is correct because X."),
(21, 7, 'Clean up STDOUT and STDERR files', 0, "Choice is incorrect because Y."),
(22, 8, 'qstat', 1, "Choice is correct because X."),
(23, 8, 'pdsh', 0, "Choice is incorrect because Y."),
(24, 8, 'showq', 1, "Choice is correct because X."),
(25, 9, 'HPC is USCs Center for High-Performance Computing', 1, "Choice is correct because X."),
(26, 9, 'The name of a USC school.', 0, "Choice is incorrect because Y."),
(27, 9, 'A laboratory within USC', 0, "Choice is incorrect because Y."),
(28, 9, 'HPC does not exist', 0, "Choice is incorrect because Y.");


-- -----------------------------------------------

-- -------------------------------------------------------------------------
-- Table structure for table `user_assessments`
-- Contains a record of user assessments (in progress or completed)
-- -------------------------------------------------------------------------

DROP TABLE IF EXISTS user_assessments;
CREATE TABLE user_assessments(
user_assessment_id INT(11) NOT NULL AUTO_INCREMENT,
assessment_id INT(11) NOT NULL,
user_id VARCHAR(255) NOT NULL,
latest_quest_sequential_num INT(11) DEFAULT 1,
user_assessment_num_correct INT(11) NOT NULL,
user_assessment_num_incorrect INT(11) NOT NULL,
user_assessment_start_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
user_assessment_latest_update TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
user_assessment_end_stamp TIMESTAMP NULL DEFAULT NULL,
user_assessment_is_complete TINYINT(1) DEFAULT 0,
PRIMARY KEY(user_assessment_id),
CONSTRAINT FK_user_assessments_assessmentID FOREIGN KEY (assessment_id)
REFERENCES assessments(assessment_id)
);

-- -----------------------------------------------

-- --------------------------------------------------------------------------------------------------
-- Table structure for table `user_answers`
-- Contains user submitted answers for each question in a every assessment completed or in progress
-- --------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS user_answers;
CREATE TABLE user_answers(
user_answer_id INT(11) NOT NULL AUTO_INCREMENT,
user_assessment_id INT(11) NOT NULL,
assessment_id INT(11) NOT NULL,
question_id INT(11) NOT NULL,
question_choice_id INT(11) NOT NULL,
PRIMARY KEY(user_answer_id)
);


-- -----------------------------------------------
-- Table structure for table `admin_users`
-- Contains admins for the system
-- -----------------------------------------------

DROP TABLE IF EXISTS admin_users;
CREATE TABLE admin_users(
admin_id INT(11) NOT NULL AUTO_INCREMENT,
admin_user_id VARCHAR(255) NOT NULL,
admin_can_view_stats TINYINT(1) NOT NULL,
PRIMARY KEY(admin_id)
);


INSERT INTO admin_users(admin_id, admin_user_id, admin_can_view_stats) VALUES
(1, 'hachuelb', 1),
(2, 'erinshaw', 1),
(3, 'admin', 1),
(4, 'johndoe', 0);









