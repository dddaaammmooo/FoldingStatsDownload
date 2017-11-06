# Directory Structure

[Back](README.MD)

### Overview

This folder is the root of the project. It contains several important files and directories.

##### Folders

The following is a breakdown of the Laravel directory structure 

* [app](app/README.MD)
* [bootstrap](bootstrap/README.MD)
* [config](config/README.MD)
* [database](database/README.MD)
* [public](public/README.MD)
* [resources](resources/README.MD)
* [routes](routes/README.MD)
* [storage](storage/README.MD)
* [tests](tests/README.MD)
* [vendor](README_VENDOR.MD)

### Important Files

The following is a brief summary of several key Laravel files and their purposes

###### .env.example

The environment file defines developer specific settings relating to the configuration of
Laravel. This includes important items such as

* Database settings
* Mail gateway settings
* Debug mode settings

This file is synced to the repository to serve as an example of settings. Never modify this 
file, instead make a copy of the file called `.env` in the same directory and edit your
configuration there.

###### .env

This file should always be excluded from the repository. This contains developer specific
overrides of the default Laravel settings described above.

###### composer.json

This file contains the details of all PHP dependencies the project has. You will typically
never need to edit this file directly, and can manage your dependencies using the composer
command-line utility.

###### webpack.mix.js

This script contains the instructions required to create the contents of the `public` folder
from the resources contained in the `resources` and `node_modules` folders. This script
will typically combine and minify any Javascript files, compile and LESS/SASS stylesheets,
and handle other tasks such as version cache busting.

To execute this script, from a command lin execute the following command

```
npm run dev
```
