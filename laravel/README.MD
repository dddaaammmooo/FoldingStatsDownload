# Laravel

[Back](../README.MD)

## Table Of Contents

##### Fundamentals

* [Why Laravel?](README_LARAVEL.MD)
* Coding Standards
* [Code Examples](examples/README.MD)
* [Unit/Feature Tests](README_TESTS.MD)
* [Directory Structure](README_FOLDER.MD)

#### Important Note Regarding Autoloading

This can be a bit of a gotcha for people new to PHP/Laravel. 

Laravel being based on PHP relies heavily on 'Autoloading'. In the absence of 
compilation and linking of code typical of languages like C/C++/C# this requires
the iterating of the entire folder structure of the application looking for PHP 
files to include each runtime- or on the flipside, manually maintaining a bit
long list of PHP files to include.

Obviously this is a ridiculously slow/stupid thing to do, so to get around this
Laravel does this process automatically once, and it then caches the results of this
autoloading process in arrays stored in the `/laravel/bootstrap/cache` folder.

What this means that is if you decide to create a new class somewhere without
using the provided`'php artisan make:something` command-line utilities this cache
will not get updated- and you will likely find yourself receiving errors saying 
that your new class does not exist when you try to use it.

If you find yourself in this situation, simply run the following command from
the SSH shell to update the cache.

```
cd /vagrant/laravel
composer dump-autoload
``` 
