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


## Testing API - subscribers

### Creating

You can create subscriber *with or without fields* in one request:

You can send JSON payload in POST to API address, for example:

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


### Showing

You can see subcribers list (JSON, modified throgh Resource layer) at:

All:

    http://localhost:8000/api/subscribers/

One:

    http://localhost:8000/api/subscribers/6 

(after initial seeding, subcriber number 6 should have a few fields)


These index and show functions are used only for making it easier 
for you to check how the app works.
Should be removed if we do not want to expose
subscribers' and fields' data to anyone.

### E-mail validation

The e-mail of newly added subscriber is checked, using 

 daveearley/Email-Validation-Tool

https://github.com/daveearley/Email-Validation-Tool

With syntax and host checking functions, but with MX checking turned off.

MX checking generated false negatives, for example for gmail.com


### Updating


You can send JSON payload in PUT to API address, for example:

    http://host:port/api/subscribers/1

example:

```json
    {
	"name": "Johny1",
	"state": "junk"
    }
````

or

```json
    {
       "name": "Johny1"
    }
```

You *cannot change e-mail address*, this would be bad idea.


## Testing API - fields

### Creating

You can POST raw JSON like:

```json
    {
        "title": "Yet another field",
        "type": "string",
        "subscriber_email": "ihavefields@example.com"
    }
````

to 

  http://localhost:8000/api/fields/

It should return JSON like:

```
    {"created":true,"status":201,"id":9}
```

### Updating

You can PUT JSON like:

```
    {
        "title": "Yet another field",
        "type": "string",
        "value": "foobar"
    }
```

to 

  http://localhost:8000/api/fields/1

It should return JSON like:

```
    {"updated":true,"status":200,"id":1}
```

When updating a field that does not exists, like 

    http://localhost:8000/api/fields/1

It returns 404



# PSR-2

Some files in database folder break PSR-2 rule:

  Each class must be in a namespace of at least one level (a top-level vendor name)

This is due to Laravel design, see:

https://stackoverflow.com/questions/41233837/why-laravel-migrations-class-doesnt-have-a-namespace

"It's a design matter, basically. There are people out there using namespaced 
migrations. But the way migrations are loaded and stored in the migration 
database table, by the migrator, could be a problem to have them namespaced."



