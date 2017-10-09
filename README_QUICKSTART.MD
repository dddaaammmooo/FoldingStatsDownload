# Quickstart Guide

[Back](README.MD)

The following guide describe how to get your development environment setup.

## Install Pre-Requisites

### Ubuntu

* From a command prompt execute the following command.

```
sudo apt-get update 
sudo apt-get install -y virtualbox vagrant git 
```

### Windows

The following software is required to be installed on your computer in order to successfully
provision your development environment.

##### Vagrant

* Download the appropriate installer from [https://www.vagrantup.com/downloads.html](https://www.vagrantup.com/downloads.html)
* Install Vagrant using default settings

##### Oracle Virtualbox

###### Windows

* Download the appropriate installer from [https://www.virtualbox.org/wiki/Downloads](https://www.virtualbox.org/wiki/Downloads)
* Install Virtualbox using default settings

##### Git

* Download the appropriate installer from [https://git-scm.com/download/win](https://git-scm.com/download/win)
* Install Git for Windows using default settings
 
## Clone Repository

Create a folder in your chosen location and from a command-prompt execute the following commands.

```
git config --global user.name "Your Name"
git config --global user.email "your-email@here.com"
git clone https://github.com/dddaaammmooo/foldingcoin.git
```

## Create Virtual Machine

Create a copy of the `vagrant.config.default.rb` file and call it `vagrant.config.rb`. Edit
this file and make any necessary changes to the settings of your virtual machine- typically
you will just want to modify the amount of RAM/CPU cores allocated.

Now create your virtual machine by executing the following command.

```
vagrant up
```

After a few minutes vagrant will finish creating your virtual machine. 

Congratulations! Your development environment has now been setup. 

If you kept the default IP address you should now be able to access the web server by visiting
[http://192.168.36.66]([http://192.168.36.66]) in your browser. You can also connect to the 
machine by executing the following command.

```
vagrant ssh
```
