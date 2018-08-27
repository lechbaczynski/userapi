## Running

It is standard Laravel app, install it like any other Laravel app. 
I used Homestad virtual machine, set up with vagrant 
( https://laravel.com/docs/5.6/homestead )

I have chosen sqlite driver, to not make you set up the mysql databse, 
but of course it can be change to mysql in config/database.php


To set up  the database:
either use the database.sqlite:

touch database/database.sqlite

make sure .env has right database file set, example:

DB_CONNECTION=sqlite

DB_DATABASE=/home/vagrant/userapi/database/database.sqlite


or use sqlite in memory:

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


Testing enviroment already uses in-memory sqlite databse, for speed and convenience.


or use any other DB like mysql

set up databases by running 
php artisan migrate
php artisan db:seed



## Testing:

./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/
