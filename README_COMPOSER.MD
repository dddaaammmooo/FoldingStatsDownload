# Composer

[Back](README.MD)

Composer is a dependency management system for PHP applications. It allows developers to
declare the libraries that their application depends on and manage the installation and
updating of these.

##### Links

[Basic Usage](https://getcomposer.org/doc/01-basic-usage.md)  
[Understanding Version Constraints](https://getcomposer.org/doc/articles/versions.md)  
[Updating Dependencies](https://getcomposer.org/doc/01-basic-usage.md#updating-dependencies-to-their-latest-versions)

### Finding Packages

Composer packages can be found at [https://packagist.org/](https://packagist.org/)

These packages are not necessarily specific to Laravel, and may require some additional
work to interoperate effectively. Before jumping in it is more than likely somebody else
has already done this work, Laravel specific composer packages can be found at 
[https://packalyst.com/](https://packalyst.com/)

### Installing Packages Via Composer

When looking for third-party libraries for PHP you will typically find that their code
is hosted on Github, and the instructions include details on how to install via Composer.

For example: https://cartalyst.com/manual/sentinel/2.0#installation

From your command-line you need to run the following commands.

```
cd /vagrant/laravel
composer require cartalyst/sentinel "2.0.*"
```

After you run this command the `composer.json` file in the `/vagrant/laravel` folder would
be updated to note the requirement of the `cartalyst/sentinel` package. It also records
the fact that you have requested any minor version of 2.0.x. 

### Updating Composer Packages

Over time developers of third party libraries will update their code. To receive the latest
changes you simply run the following command

```
cd /vagrant/laravel
composer update
```

This will update all of the packages required by your project, while respecting your
version number restrictions.
