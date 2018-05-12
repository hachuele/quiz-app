# Deployment: Local and Public Server



## Getting Started: Local Install

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

#### Running the MySQL Script to Instantiate the Database

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



## Getting Started: Public Server Install

These instructions will get you a copy of the project up and running on a public server. 

On the machine that hosts the web server, CD into the web server’s Document Root and clone this application, e.g.:

```bash
cd /var/www/html 
git clone https://github.com/hachuele/quiz-app.git
```

Assuming the MariaDB/MysqlDB server is on the same machine, run mysql and create the database using the given scripts

```bash
mysql> source /var/www/html/quiz-app/sql_scripts/quiz_site_createDB.sql
```
