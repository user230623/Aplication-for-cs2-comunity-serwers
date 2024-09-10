# Web aplication for cs2 comunity serwers

An application that retrieves game time from the database and adds pluses and minuses on this basis (the next update will include a vacation system). The application is written in PHP 8.1.25

## Description of individual files
### "App" folder
  * #### index.php - This is page with login form
  * #### login.php - Here is the entire login logic
### "Configs" folder
  * #### config.php - Here is table for configurations database
  * #### passwords.php - Here is a logins and passwords website users
### "Data" folder
  * #### dane.php - This is main page of website, displays all admins and their playing time, pluses, etc
  * #### delete_minusy.php - This file removes one minus from the database
  * #### delete_plusy.php - This file removes one plus from the database
  * #### minusy.php - This file adds one minus to the database
  * #### plusy.php -  This file adds one plus to the database
### "Database" folder
  * #### db.php - This file create connection to database
### "Program" folder
  * #### delete.php - This file deleting admin from database
  * #### logout.php - This file logs you out of your account
  * #### main.php - This file added admin to the database
### "Styles" folder
  * #### styles.css - This file includes all design styles
