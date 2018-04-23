# HPC Quiz Maker

The **HPC Quiz Maker** is a fully-functional, modular web application built with PHP, MySQL, and responsive web design frameworks with the ultimate goal of enabling instructors to easily build and deploy user assessments as needed. 

The tool was built  for the Center for [High-Performance Computing](http://hpcc.usc.edu/) (HPC) at the [University of Southern California](https://www.usc.edu/), a global leader in research computing.



## How it Works

<u>The site includes the following components:</u>

### 1) Main User Dashboard

The main user dashboard serves as the main page for the user. It allows the user to **select from a list of available or in-progress quizzes** to complete, or **view personal statistics** on previously completed assessments. *Below are some sample snapshots of elements within the main user dashboard.*



![MAIN DASHBOARD SAMPLE](./readme_images/main_dashboard.png)



The user may look at a full history of completed quizzes and statistics through the "**SEE MORE**" button located in the *'completed quizzes' card*. As shown, users may take a particular quiz as many times as they deem it necessary.



![user_stats_2](./readme_images/user_stats_2.png)



### 2) Quiz Page

Once a user selects a quiz from the list of available or in-progress assessments, a redirection occurs to the main quiz page. **This page includes all questions for the selected quiz, shown one at a time** (and must be completed in order). 



![question_sample](./readme_images/question_sample.png)



**When a user submits an answer**, these get <u>immediately submitted to the mySQL database</u> through Ajax calls (one answer at a time). This allows the user to come back at a later time to complete the quiz if necessary. It also allows the user to **see the answer details** to a particular question (after they submit), rather than having to wait until the end of the quiz. 



![answer_submit](./readme_images/answer_submit.png)



When the user completes the quiz, **the final statistics card is show**. At this point, once the user goes back to the home page, it will not be possible to return to this particular quiz, as it is no longer in progress. 

The user is, however, able to take the quiz again *(as mentioned in a prior note, users may take the same quiz as many times as desired, and each instance will be saved in the database)*. Below is a sample final statistics card (for a 2-question quiz).



![quizz_stats](./readme_images/quizz_stats.png)



### 3) Main Admin Dashboard 

The main admin dashboard **allows instructors to manage the assessments content**. That includes:

* **a) creating** new quizzes

* **b) editing** existing quizzes

* **c) viewing statistics** from completed and in-progress user assessments (only certain admin users may click on this link)

  ​



![admin_dashboard](./readme_images/admin_dashboard.png)



#### A) Create a New Quiz

Admin users may create new quizzes by clicking on the 'CREATE NEW QUIZ' button on the admin dashboard. This will prompt the user to **populate the quiz name and its short description** (required fields). User may then proceed to edit the quiz.



![new_quiz_submit](./readme_images/new_quiz_submit.png)



#### B) Edit New or Existing Quiz

Admin users may edit an existing quiz by clicking on the 'EDIT EXISTING QUIZ' button and selecting the desired quiz from the shown list. This will **redirect the user to the main edit page for the selected quiz**:



![main_edit_dash](./readme_images/main_edit_dash.png)

This page allows the user to add (questions, and question choices), edit, or delete items, as well as configuring advanced settings and deploying the quiz (show it active to users) once it is ready.



#### C) Statistics Dashboard

*(in development)*



## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. 

### Prerequisites: Local Server Environment

The project was developed locally using [**MAMP (My Apache - MySQL, PHP)**](https://www.mamp.info/en/) for macOS. MAMP is also supported for windows. **Please ensure you are using MAMP 4.2 or higher.**

**MAMP installs a free, local server environment on your local machine.** It also allows you to install Apache, PHP, and MySQL (the main components of the assessments site) without having to mess with any configuration files!

Please carefully follow the steps described on the following MAMP documentation link to successfully set up your local environment:

* https://documentation-3.mamp.info/en/documentation/mamp/
* Download MAMP: https://www.mamp.info/en/



### MySQL Database: Setup

The '**quiz_site_createDB.sql**' script has been provided to ensure to proper setup of the database. The version of the script included in this repository includes a set of sample/dummy quizzes and questions developed for testing purposes. 

You may replace all given fields with relevant questions for your given target audience. 

**NOTE:** *the administration site, which allows admin users to create and edit quizzes through a UI is currently in development. Therefore, in order to deploy a working database, quizzes, questions, and question choices must be entered into the database through manual SQL 'INSERT' statements (see .sql script for sample queries).*

#### Connecting to the MAMP SQL Database

To connect to the MAMP MySQL database instance through the terminal, run the following command:

```bash
/Applications/MAMP/Library/bin/mysql --host=localhost -uroot -proot
```

####Running the MySQL Script to Instantiate the Database

Once connected, run the given  '**quiz_site_createDB.sql**' through the 'source' command:

```bash
mysql> source /path/to/sql/script/quiz_site_createDB.sql
```

If successful, the database should be up and running with the given sample data (if any was provided in the script).



### Local Site 'Deployment'

Once MAMP is installed and properly configured (as described in the documentation link above), the directory set up to serve the files will be set to the **'htdocs'** folder. You may confirm the document root by launching MAMP, and checking your '**Preferences**':



![AMP_preference](./readme_images/MAMP_preferences.png)



You may clone this repository into a local folder on your machine and add the '**assessment_site**' project folder with its public and private sub-folders into htdocs.

You are now ready to run the site locally by launching your favorite web browser with your configured localhost site and appropriate port information. Example below:

```
http://localhost:8888/assessment_site_hpc/public/
```

**NOTE:** For the database connection to properly work, take a look at the `db_credentials.php` file within the project, and **modify parameters as needed:**

```php+HTML
<?php
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");
define("DB_HOST", "localhost");
define("DB_DATABASE", "quizing_db");
?>
```



## Built With

* [Bootstrap](https://getbootstrap.com/) - The web framework used for responsive, mobile-first development

* [jQuery](https://jquery.com/) - JavaScript library

* [PHP](http://www.php.net/) - Server side development

  ​

## Authors

* **Eric Hachuel**  - [personal website](https://www.erichachuel.com)

  ​

## License

The Software is made available for academic or non-commercial purposes only. The license is for a copy of the program for an unlimited term. Individuals requesting a license for commercial use must pay for a commercial license.

Please view the [LICENSE.txt](LICENSE.txt) file for more details.



## Acknowledgments

* **Erin Shaw** - Project Lead

  ​

