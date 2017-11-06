# Vagrant 

[Back](README.MD)

### Links

[Vagrant Tutorial](./README_VAGRANT_TUTORIAL.MD)  
[Vagrant Scripts & Tools](./vagrant/README.MD)  

### Files

###### Vagrantfile

This is a ruby script which is used by Vagrant to configure the virtual machine. It
is responsible for configuring the core machine details (e.g. Network, RAM, CPU,
Shared Folders). The file should typically not need updating, the configuration of
the machine can be changed via the config files below.

###### vagrant.config.default.rb

This file contains the default settings for the configuration of the development virtual
machine. You should not update this file as any changes will be synced to the repository.

###### vagrant.config.rb

This file contains the individual developers settings for the configuration of the
development virtual machine. This file had been added to the `.gitignore` file so should
not be synced back to the repository. 
