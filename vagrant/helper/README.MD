# Vagrantfile Helper Scripts

[Back](../README.MD)

This folder contains helper scripts used in the `/Vagrantfile`. These were extracted
out to keep the content of the file slightly cleaner.

##### config.loader.rb

This script performs the loading of Vagrant configuration from the `/vagrant.config.rb`
and `/vagrant.config.default.rb` files.

##### os.rb

This script provides OS detection within the Vagrant provisioning script
