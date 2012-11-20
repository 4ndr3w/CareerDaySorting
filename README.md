## CareerDaySorter

### Introduction
This application is designed to sort students into career blocks for career day.

Features include:
* Admin interface to manage careers and students
* Importing career lists from CSV
* Web-based student signup system
* Automatic and manual sorting
* Automatic sorting is aware of "career groups" and will try to place students in similar careers if none of their prefered choices are available


Hardcoded parameters:
* Each student has 4 choices
* A student's schedule consists of 3 blocks, one of them is an assembly. The assembly's block depends on their grade.
* Seniors are able to opt-out to do career shadowing/college visit

### Installation

Prerequisites:
* A webserver able to execute PHP files
* PHP with the MySQL extention
* A MySQL Database

Installation instructions:

1. Create an empty MySQL database and import the tables from structure.sql
2. Put the contents of this folder where your webserver is serving from
3. Rename or copy config.php.example to config.php
4. Open config.php with your favorite editor and set your MySQL connection information
5. Go to the admin page with your web browser and start adding careers

### Suggested Workflow
This is the suggested workflow for running CareerDaySorter:

1. Construct a list of careers in an Excel document, using the format specified in importCareers.php
2. Export the career list as a CSV and import it to CareerDaySorter using importCareers.php
3. Ensure all data was proporly imported
4. Distribute the link to index.php or signup.php to teachers/students
5. AFTER all students have signed up, go to the admin page and run an automatic sort
6. Manually resolve any students that can't be automaticlly sorted
7. Print student schedules and career attendance lists ( or save as PDF )

### Hidden Careers
"Careers" such as the class assembly and career shadowing exist as hidden careers inside the database. A hidden career does not appear in the signup list for students.
If you did not import using the structure file, you will need to manually insert the row for these events and set their ID in config.php

By default, Assembly has an ID of 999 and Career Shadowing/College Visit has an ID of 998.