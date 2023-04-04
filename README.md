## Local setup

This application is built and tested against PHP 7.4 and MySQL 5.7. To verify that your local setup meets the requirements:

```
$ php -v
$ mysql -v
```

## Setup instructions

Clone this repository:

```
$ git clone git@github.com:earthyolafflutestick/mailerlite-test-app.git
```

Move into your repository folder:

```
$ cd mailerlite-test-app
```

Copy the `.env.example` file to `.env`:

```
$ cp .env.example .env
```

The application looks for a database named `mailerlite_test_app`. You can pick a different name — in that case, make sure to update the relevant entry in your `.env` file:

```
DB_DATABASE=mailerlite_test_app
```

Log into MySQL, and create the database:

```
$ mysql -u root;
mysql> CREATE DATABASE mailerlite_test_app;
mysql> exit;
```

Import the `api_keys.sql` file in your repository folder:

```
$ mysql -u root mailerlite_test_app < api_keys.sql
```

If you haven't yet, install [Composer](https://getcomposer.org/doc/00-intro.md). Then install the application dependencies:

```
$ composer install
```

Generate your application secret key:

```
$ php artisan key:generate
```

Finally, serve your application:

```
$ php artisan serve
```

## Notes
* This was my first attempt at this test, and it uses the [latest version](https://developers.mailerlite.com/docs/#mailerlite-api) of the MailerLite API.
* Toward completion, I realized the older version of the API suspciously fitted the assignment much better. So I wrapped up what I had here, and started from scratch with another version [here](https://github.com/earthyolafflutestick/mailerlite-legacy-app). This version doesn't even use Datatables: it's a plan old SSR app.
* The application uses [Bulma](https://bulma.io/) as its CSS framework. That's also the name of my bulldog.
