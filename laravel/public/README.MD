# Directory Structure

[Back](../README.MD)

## public

The public directory contains the index.php file, which is the entry point for all requests 
entering your application. This directory also houses your assets such as images, JavaScript, 
and CSS.

## Important Note

The contents of this folder are generated via NPM and you should never directly modify the files 
in this folder. If you want to add new images, scripts or other assets that should be deployed
to the web server, this should be done by placing them in the `resources` folder and updating
the `webpack.mix.js` file appropriately.


