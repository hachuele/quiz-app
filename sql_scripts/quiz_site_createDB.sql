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
user_assessment_latest_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
PRIMARY KEY(user_answer_id),
CONSTRAINT FK_user_answers_user_assessmentID FOREIGN KEY (user_assessment_id)
REFERENCES user_assessments(user_assessment_id),
CONSTRAINT FK_user_answers_assessmentID FOREIGN KEY (assessment_id)
REFERENCES assessments(assessment_id),
CONSTRAINT FK_user_answers_questionID FOREIGN KEY (question_id)
REFERENCES questions(question_id),
CONSTRAINT FK_user_answers_choiceID FOREIGN KEY (question_choice_id)
REFERENCES question_choices(question_choice_id)
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










