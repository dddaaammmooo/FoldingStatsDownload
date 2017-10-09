# Directory Structure

[Back](../README.MD)

## routes

The routes directory contains all of the route definitions for your application. By default, 
several route files are included with Laravel: web.php, api.php, console.php and channels.php.

The web.php file contains routes that the RouteServiceProvider places in the web middleware 
group, which provides session state, CSRF protection, and cookie encryption. If your 
application does not offer a stateless, RESTful API, all of your routes will most likely be 
defined in the web.php file.

The api.php file contains routes that the RouteServiceProvider places in the api middleware 
group, which provides rate limiting. These routes are intended to be stateless, so requests 
entering the application through these routes are intended to be authenticated via tokens and 
will not have access to session state.

The console.php file is where you may define all of your Closure based console commands. Each 
Closure is bound to a command instance allowing a simple approach to interacting with each 
command's IO methods. Even though this file does not define HTTP routes, it defines console 
based entry points (routes) into your application.

The channels.php file is where you may register all of the event broadcasting channels that your 
application supports.
