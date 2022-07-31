# PAASRS (Poor Attempt At Spaced Repetition Software)

My attempt to create a spaced repetition software. I will be using Laravel 8 for this in order to support PHP 7.3 and above.

## Tech Stack

1. Laravel 8
1. PHP 7.3
1. Vue 3
1. MySQL / MariaDB

## Installation

### Via INSTALL.sh

1. Make sure INSTALL.sh has execute permissions.
1. Run `./INSTALL.sh` on the command line.
1. After running the script, you may need to edit the database credentials in the .env file.
    1. If you edited the database credentials in the .env file, you need to run `php artisan migrate` again in order to create the necessary tables and columns in the database.

### Manual Installation

1. Clone this repository.
1. After cloning the repository, run the following:

```
composer install
npm install
```

3. Create .env file from .env.example. Add the correct database credentials and other important details inside the .env file.
1. Run the following:

```
php artisan key:generate
php artisan migrate
```

5. You can also use `php artisan serve` to serve as a dev site. If you use php artisan serve, you can access the site through http://localhost:8000

## How to use

1. Create a deck first.
1. Add new cards to your created deck.
1. You can also use the import cards function. It accepts a csv file. It must have four columns. The columns must be in this correct order, question, answer, extra information and tags.
1. If you want to study, just click on the study button.

## How to run tests

1. Create test database.
1. After creating test database, create .env.testing using .env.example. After the variables as necessary.
1. Run `php artisan migrate:fresh --seed`
1. Run `php artisan test` to execute tests using PHPUnit.

## Common commands used in development

1. `npm run watch` - compiling CSS and JS using webpack
