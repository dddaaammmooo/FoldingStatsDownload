# Directory Structure

[Back](../README.MD)

## Console

The Console directory contains all of the custom Artisan commands for your application. 
These commands may be generated using the make:command command. This directory also houses 
your console kernel, which is where your custom Artisan commands are registered and your 
scheduled tasks are defined.

## Creating Your Own Command

SSH into the Vagrant virtual machine and from the `/vagrant/laravel` folder execute the
following command.

```
php artisan make:command ExampleCommand
```

### Important Files

The following is a brief summary of several key Laravel files and their purposes

###### Kernel.php

* Register scheduled tasks
* Register console commands that should also be accessible within PHP application code

### Stats Download/Upload Service Commands

[Stats Download/Upload Service Commands](./Commands/README.MD)
