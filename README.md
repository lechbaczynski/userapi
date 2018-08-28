# Seting up and running

It is standard Laravel app, install it like any other Laravel app. 
I used Homestad virtual machine, set up with vagrant 
( https://laravel.com/docs/5.6/homestead )

Use php version 7+

## Database

I have chosen sqlite driver, to not make you set up the mysql database, 
but of course it can be changed to mysql in config/database.php file.


To set up the database:

Either use the database.sqlite:

touch database/database.sqlite

Then copy .env.example to .env and .env.testing

Make sure .env has right database file set, example:

DB_CONNECTION=sqlite

DB_DATABASE=/home/vagrant/userapi/database/database.sqlite

in .env.testing change it to:

DB_CONNECTION=sqlite_testing

and set up it in config/database.php like for example: 

```
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
```

or (easier version) use sqlite in memory for both testing and dev:

```
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
```

Testing enviroment already uses in-memory sqlite database, for speed and convenience.


Add:
```
            <env name="DB_CONNECTION" value="sqlite_testing"/>
```

in phpunit.xml inside 

```
<php>

</php>
```



### Set up databases 

by running:

php artisan migrate

php artisan db:seed


## Composer

There is a library added for validating e-mail in composer.json

Run composer update


## Testing:

Tests are in the Tests directory - Unit and Feature tests.

Run it like:

./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/

You can also send JSON payload in POST to API address, for example:

    http://host:port/api/subscribers/

example:

    http://localhost:8000/api/subscribers/



Example raw JSON:

```json
    {
    "name": "John",
    "email": "jonh.example@example.com",
    "fields": [
            {
            "type": "number",
            "title": "age"
            },
            {
            "type": "string",
            "title": "source",
            "value": "website"
            }
    ]
    }
```

It should return response like:

    {"created":true,"status":201,"id":8}

Where id is an id of newly created subscriber.

You can see subcribers list (JSON, modified throgh Resource layer) at:

All:

    http://localhost:8000/api/subscribers/

One:

    http://localhost:8000/api/subscribers/6 

(after initial seeding, subcriber number 6 should have a few fields)



# E-mail validation

The e-mail of newly added subscriber is checked, using 

 daveearley/Email-Validation-Tool

https://github.com/daveearley/Email-Validation-Tool

With host checking, but with MX checking turned off 
(it generated false negatives, example on gmail.com )


