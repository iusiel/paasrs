# PAASRS (Poor Attempt At Spaced Repetition Software)

My attempt to create a spaced repetition software. I will be using Laravel 8 for this in order to support PHP 7.3 and above.

## Installation

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
