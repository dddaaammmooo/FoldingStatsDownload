# Command Line Tools

[Back](../README.MD)

This folder contains scripts to assist with Vagrant.

##### clean-vagrant

This script is designed to be run from within the Vagrant machine. It cleans up the 
machine and prepares it for the creation of a new box image.

##### kill-vagrant

This script is designed to be run from the host machine. It kills any vagrant processes 
that are currently running. This is useful when something crashes, or you kill vagrant half way through a provision.
