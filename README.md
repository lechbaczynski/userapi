## Running

It is standard Laravel app, install it like any other Laravel app. 
I used Homestad virtual machine, set up with vagrant 
( https://laravel.com/docs/5.6/homestead )

I have chosen sqlite driver, to not make you set up the mysql databse, 
but of course it can be changed to mysql in config/database.php file.


To set up  the database:

Either use the database.sqlite:

touch database/database.sqlite

Then copy .env.example to .env and .env.testing

Make sure .env has right database file set, example:

DB_CONNECTION=sqlite
DB_DATABASE=/home/vagrant/userapi/database/database.sqlite

in .env.testing change it to:

DB_CONNECTION=sqlite_testing

and set up it in config/database.php like for example: 

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],
        'sqlite_testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ],


or (easier version) use sqlite in memory for both testing and dev:

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ],
        'sqlite_testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ],


Testing enviroment already uses in-memory sqlite database, for speed and convenience.


Or use any other DB like mysql


# Set up databases by running 
php artisan migrate
php artisan db:seed




## Testing:

./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/
