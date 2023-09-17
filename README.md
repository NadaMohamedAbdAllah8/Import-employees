## Installation

1. Install dependencies by running `composer install`.

2. Create a `.env` file by copying the `.env.example` file and setting the database. You can define IMPORT_CHUNK_SIZE in the .env file to set the imported chunk sizes. (There is a default value of 500 in case IMPORT_CHUNK_SIZE is not defined).

3. You can use following two commands to migrate and seed the database:

-   `php artisan migrate`

-   `php artisan db:seed`

## Usage

1. Start the server by running `php artisan serve`.

2. To login as an admin user, go to http://127.0.0.1:8000/login (using default host and port) and use `admin` as the username and `password` as the password.

### Testing

Run `php artisan test`
