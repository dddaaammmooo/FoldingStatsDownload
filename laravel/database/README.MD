# Directory Structure

[Back](../README.MD)

## database

The database directory contains your database migration and seeds.

### Migrations

Migrations are like version control for your database, allowing your team to 
easily modify and share the application's database schema. Migrations are 
typically paired with Laravel's schema builder to easily build your 
application's database schema. 

If you have ever had to tell a teammate to manually add a column to their local 
database schema, you've faced the problem that database migrations solve.

To create a migration, use the make:migration Artisan command from the 
command-line

```
cd /vagrant/laravel
php artisan make:migration create_user_table
php artisan make:migration create_download_table
php artisan make:migration alter_user_table
```

### Seeds

Laravel includes a simple method of seeding your database with test data using
seed classes. All seed classes are stored in the database/seeds directory. Seed
classes may have any name you wish, but we should follow some sensible 
convention, such as UsersTableSeeder.
