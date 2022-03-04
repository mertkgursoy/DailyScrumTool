# Daily Scrum Tool
Daily Scrum Tool| How to create a "CRUD App" with "Login & Register" system. | PHP & PDO & MySQL


DEMO: https://daily-scrum-tool.herokuapp.com/register.php


To begin with, the crud applications are widely used and really useful for us to create our internal apps for our teams for free.
So firstly, we will prepare the server side (MySQL) then create "Log In" and "Register" forms with PHP and finally complete crud application.

1) MySQL:

In order to use MySQL firstly we need to create database and table by using the following statements below
Create a new Database "daily_scrum_database".

Grant "daily_scrum_database" access to the user with a password(i.e. theUserName & thePass) in MySQL.

In "pdo.php" file, we will be using later this credentials to connect and access the "daily_scrum_database" from project to MySQL.

Create "Users"  Table in this "daily_scrum_database".

Insert a user in this "Users" table (with md5 hash password).

Create "Daily Scrum" Table in this "daily_scrum_database".

Insert a daily note in this "Daily Scrum Table" table.

2) PHP & PDO:

index.php

Firstly we need to create a new project folder than create index.php and redirect the user to login.php.

header.php & footer.php & main.css

Secondly, we'll create "templates" folder in our project folder than create header.php and footer.php files. Also an "asset" named folder will be created in the project folder and "main.css" file will be created in it.

pdo.php
Now, we need to create a pdo.php file to connect our application with MySQL.

register.php & login.php
register.php
A new user will be created with this register.php file.

login.php 
With the user credentials that we created above, we will log in and redirect to daily-scrum-form.php.


daily-scrum-form.php & daily-scrum.php

daily-scrum-form.php
daily-scrum-form.php will be used for creating a daily note.

daily-scrum.php
daily-scrum.php will be used for listing a daily notes in a table.

user.php
This page will be used for listing the users of this tool in table.
