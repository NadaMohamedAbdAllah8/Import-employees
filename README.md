# Import Employees

This is a simple project to import employee from large CSV, or a XLSX files. This project uses maatwebsite/excel package and queue to batch import employees.
While importing, if an employee id is repeated this employee will be updated, if it does not exist the data will be inserted
In case any of the imported data had a validation error the data row will not be imported. To check the validation error please check the logs (storage/logs/laravel.log)

## Features

1. Import employees, by uploading file, using POST api/employee

As an admin you can

1. List employees
2. Show employee
3. Delete employee
   Using RESTful API employee
   • GET /api/employee
   • GET /api/employee/{id}
   • DELETE /api/employee/{id}

## Installation

1. Install dependencies by running `composer install`.

2. Create a `.env` file by copying the `.env.example` file.
   Setting up the `.env` file
   a. Setting the database name using DB_DATABASE
   b. Setting QUEUE_CONNECTION=database, in order to use the database as the queue driver.

3. You can use following two commands to migrate and seed the database:

-   `php artisan migrate`

-   `php artisan db:seed`

4. To Generate application keys

-   `php artisan key:generate`

## Usage

1. Start the server by running `php artisan serve`.

2. Start the queue by running `php artisan queue:work`

3. To login as an admin user, go to http://127.0.0.1:8000/login (using default host and port) and use `admin@admin.com` as the username and `password` as the password.

## More to add

A. Tests
B. Notification when the import is over
