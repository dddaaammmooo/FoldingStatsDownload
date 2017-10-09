# Vagrant

[Back](README.MD)

Vagrant is a wrapper for virtualisation hypervisors (e.g. Virtualbox, VMware) and is
designed to allow developers to maintain a consistent development enviroment by
automating the setup and configuration of virtual machines.

## Download

The latest version of Vagrant can be downloaded from  [https://www.vagrantup.com/downloads.html](https://www.vagrantup.com/downloads.html).

## Hypervisor

Vagrant operates out of the box with the Virtualbox hypervisor. VMware is supported also,
however as this is a paid add-on it has not been used when the projects Vagrant image
was configured. As such changes would be required to the `/Vagrantfile` in order for
this to work correctly.

Virtualbox is the supported hypervisor for use with the Vagrant image in this repository
and can be downloaded for free from [https://www.virtualbox.org/wiki/Downloads](https://www.virtualbox.org/wiki/Downloads).

## Usage

All interaction with Vagrant is done via the command-line.

When interacting with Vagrant it is important to note that you must be in the root folder
of the repository that contains the `Vagrantfile` file. 

#### Power On Virtual Machine

To turn on your developer machine simply run the following command.

```
vagrant up
```

The first time you run this command it will create the virtual machine and install all
of the pre-requisite software.

#### Power Off Virtual Machine

To turn off your developer machine simply run the following command.

```
vagrant halt
```  

This command will perform a "clean" shutdown of the virtual machine.

If for any reason the virtual machine is not responsive you can execute the following 
command which will perform a forced power off.

```
vagrant halt --f
```  

#### Restoring Default Machine Settings

In order to get the virtual machine back to its default settings simply run the following
two commands.

```
vagrant destroy --f
vagrant up
```

This will delete the virtual machine image from your computer, and recreate a new
machine with default settings.

#### Connecting To Virtual Machine

You can SSH to the virtual machine by executing the following command.

```
vagrant ssh
```