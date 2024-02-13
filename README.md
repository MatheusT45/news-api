# News API

Inspired by the HackerRank challenge

## Project Specifications

**Environment**

- PHP version: 8.1
- Laravel version: 10.10
- Default Port: 8000

**Commands**

- run database:

```bash
docker compose up -d
```

- run migrations:

```bash
php artisan migrate:fresh
```

- run:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

- install:

```bash
composer install
```

- test:

```bash
yes | php artisan migrate:refresh && ./vendor/bin/phpunit tests --log-junit junit.xml
```

## Question description

In this challenge, consider that you are part of a team-building a News Aggregator Service application.

Each news event is a JSON entry with the following keys:

- userid: login admin user id (Integer)
- publisher: the publisher of the news event (String)
- clicks: the number of clicks that the news has got (Integer)
- title: the headline of the news (String)
- description: the description of the news event (String)

### Example of an event JSON object:

```
{
      "userid": 1,
      "publisher": "DemoPublisher",
       "clicks": 123,
      "title": “News Headline”,
      “description” : “news in detail”
}
```

There is also an Admin model which enables the creation of an admin to handle the news events.

- name: name of the admin (String)
- email: email id of the admin (String)
- password: password used by the admin (String)

## Requirements:

You are provided with the implementation of both the Admin and the News model. The task is to implement a REST service that exposes the /news endpoint, which allows for managing the collection of admins and news events in the following way:

`POST /news/admin`:

- creates a new admin
- expects a JSON user object with the parameters given in the model as the body payload. You can assume that the given object may not be always valid and the response code is 201.
- validation failed then 400 response code will be return

`POST /news/admin/login`:

- the response code is 200
- Checks whether the provided email and password combination is valid or not and gives a response accordingly.
- expects a JSON object with the email and password parameter.
- you can assume that the payload given is always valid.
- for a valid username and password combo, generate a jwt token which is to be used in further routes for security purposes.
- email and password fields are required if validation failed then 400 response code will be return

`POST /news/add`:

- creates a new news event
- the request header must contain the jwt token obtained by the admin login.
- it should be sent in the form: Headers auth-token: JWT TOKEN OF ADMIN
- If the token and the news object is valid then add the news object to the database and use the status code 201.
- If the news object does not contain any of the parameters as given in the above news model then throw a 400 status code.
