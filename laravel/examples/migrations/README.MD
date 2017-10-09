# Migrations

[Back](../README.MD)

You create tables in Laravel by creating database migrations. A migration is a PHP class
which creates/updates/deletes a table in the database.

#### Links

[Laravel Migration Documentation](https://laravel.com/docs/5.0/migrations#creating-migrations)

## Creating New Table

From the command-line of the virtual machine, execute the following commands.

```
cd /vagrant/laravel
php artisan make:migration create_statistics_table
``` 

In the `/laravel/app/database/migrations` folder you should now see a new file has been
created called `YYYY_MM_DD_HHMMSS_create_statistics_table.php`. Open this file and you
will see the following code.

```
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics');
    }
}

```

## Migrating Up/Down

Each migration contains two methods.

###### Up

The 'up' function contains the actions that will be executed when versioning the
database up (e.g. v0.1 -> v0.2)

###### Down

The 'down' function contains the actions that will be execute when versioning the
database down- or rolling back (e.g. v0.2 -> v0.1)

To ensure the migrations operate as expected it is vitally important that every
action you make in the Up function has an opposite Down action to reverse the change.

#### Executing Migrations

From a command prompt on the virtual machine execute the following commands:

```
cd /vagrant/laravel
php artisan migrate
```

Laravel tracks which migrations have been execute in the database table called
`migrations`. This table is created the first time you execute a migration. Using
this table Laravel ensure that it only executes those migrations which are still
pending.

When you execute the `php artisan migrate` function the system will migrate 'up'
through all of the outstanding migrations until there are no more to process.

#### Rolling Back Migrations

From a command prompt on the virtual machine execute the following commands:

```
cd /vagrant/laravel
php artisan rollback
```
