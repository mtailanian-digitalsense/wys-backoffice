# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/7.x/installation)

Clone the repository

    git clonegit@gitlab.com:want_cl/backoffice-contract.git

Make a copy `.env.example` and rename to `.env`

Switch to` the repo folder

    cd back-office

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate
    
Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Generate the encryption keys needed to create secure access tokens

    php artisan passport:install


Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000


**too long; didn't read command list**

    git clonegit@gitlab.com:want_cl/backoffice-contract.git
    cd back-office
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate
    php artisan passport:install

**Production**

Remember to create the symbolic link for the public disk.

    artisan storage:link
    
    
**Make sure you set the correct database connection information before running the migrations** 

    php artisan migrate
    php artisan serve
    
**For create an admin user run this command**

     php artisan orchid:admin admin admin@admin.com password

## Database seeding

**Populate the database with seed data and start using it with ready content.**

Run the countries and regions seeder, and you're done

    php artisan db:seed --class="CountriesTableSeeder"
    php artisan db:seed --class="RegionTableSeeder"
    
   
You can check the countries codes [here](https://gitlab.com/want_cl/backoffice-contract/-/wikis/List-of-countries)

***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:refresh
    
## Task Scheduling

More about Task Scheduling in [Laravel Documentation.](https://laravel.com/docs/scheduling)

    crontab -e
    * * * * * php /path-to-project/artisan schedule:run 1>> /dev/null 2>&1
    
Tasks run every 10 minutes.

## API Specification


> [Full API Spec](https://gitlab.com/want_cl/backoffice-contract/-/wikis/API-REST)

----------

# Code overview

## Dependencies

- [laravel/passport](https://github.com/laravel/passport) - For authentication using JSON Web Tokens
- [orchid/platform](https://github.com/orchidsoftware/platform) - RAD platform for building back-office application.
- [laracsv](https://github.com/usmanhalalit/laracsv) - For  generate CSV files from Eloquent model.
- [laravel-cors](https://github.com/barryvdh/laravel-cors) - For handling Cross-Origin Resource Sharing (CORS)
## Folders

- `app` - Contains all the Eloquent models
- `app/Http/Controllers/Api` - Contains all the api controllers
- `app/Http/Middleware` - Contains the JWT auth middleware
- `app/Http/Requests/Api` - Contains all the api form requests
- `app/Orchid/` - Contains all the Orchid files.
- `config` - Contains all the application configuration files
- `database/factories` - Contains the model factory for all the models
- `database/migrations` - Contains all the database migrations
- `database/seeds` - Contains the database seeder
- `routes` - Contains all the api routes defined in api.php file

## Environment variables

- `.env` - Environment variables can be set in this file

***Note*** : You can quickly set the database information and other variables in this file and have the application fully working.

----------
 
# Authentication
 
This applications uses Laravel Passport to handle authentication. The token is passed with each request using the `Authorization` header with `Bearer` scheme. Please check the following sources to learn more about Passport.
 
 
| **Required** 	| **Key**              	| **Value**	|
|----------	|------------------	|------------------	|
| Optional 	| Authorization    	| Bearer {Token}    |
| Yes      	| X-Requested-With 	| XMLHttpRequest   	|
 
- https://laravel.com/docs/7.x/passport

----------

# Cross-Origin Resource Sharing (CORS)
 
This applications has CORS enabled by default on all API endpoints. The default configuration allows requests from `http://localhost:3000`. The CORS allowed origins can be changed by setting them in the config file.
 
- https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS
- https://en.wikipedia.org/wiki/Cross-origin_resource_sharing
- https://www.w3.org/TR/cors

