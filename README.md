# Task List Tech Task (Dogstar Digital Group)

*This project is built using the latest versions of PHP (8.3) and Laravel 11.*

**Commands to run this project:**

Clone the repo off Github to your computer and open it on your favorite code editor.

Go into the folder you cloned the repo in and in the terminal type - *php artisan serve* - to open the project on your local browser. For me it was *http://127.0.0.1:8000/*

Before going futher make sure you run migrations and the seeders:

*php artisan migrate*

*php artisan db:seed*

**The test account information:**

Email: test@example.com

Password: password

**To register a user:** http://localhost:8000/register 

**To login:** *http://localhost:8000/login*

**To see/add/update/delete tasks:** *http://127.0.0.1:8000/tasks*

*After you register you should get a success message and then you can proceed to login. You can also log out and edit your profile if you wish.*


**To work with the database you can use whatever application you are comfortable using to access the database. I am comfortable using the terminal. I placed the following helpful commands below:**

**For the database run:** mysql -u root -p

**To access the specific database:** use task_list;

**To show the tables on it:** show tables;

**To see the specific information on the users/tasks table:** select * from users/tasks: 

When adding new items to the task list -after you have registered and logged in and gone to the task url- in the database if you do *select * from users;* and then *select * from tasks;* you should be able to see the tasks associated with the the specific user by looking at the ID which should be the same number.

### Extra:

**To run tests:** *php artisan test*
