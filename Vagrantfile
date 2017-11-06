# ---------------------------------------------------------------------------------------------------------------------
# Vagrant provisioning script
# ---------------------------------------------------------------------------------------------------------------------

# -*- mode: ruby -*-
# vi: set ft=ruby :

runPath = "#{File.dirname(__FILE__)}/"

require "yaml"
require File.expand_path("#{runPath}/vagrant/helper/os.rb")

if OS.windows? then
    puts "Windows operating system is not currently supported due to lack of support for symlinks\n"
    abort;
end

# ---------------------------------------------------------------------------------------------------------------------
# If we are provisioning, clear the screen for a bit of extra cleanliness
# ---------------------------------------------------------------------------------------------------------------------

if ARGV.include?("up") || (ARGV.include?("reload") && ARGV.include?("--provision"))

    if OS.linux? then
        system("clear")
    end

    puts "Provisioning development environment. Please wait...\n\n"

end

# ---------------------------------------------------------------------------------------------------------------------
# Load configuration files
# ---------------------------------------------------------------------------------------------------------------------

require File.expand_path("#{runPath}/vagrant/helper/config.loader.rb")

# ---------------------------------------------------------------------------------------------------------------------
# Setup virtual machine
# ---------------------------------------------------------------------------------------------------------------------

Vagrant.configure(@config["VAGRANTFILE_API_VERSION"]) do |config|

    config.vm.box_check_update = false
    config.vm.box = @config["VAGRANT_BOX"]

    if !@config["VAGRANT_URL"].nil?
        config.vm.box_url = @config["VAGRANT_URL"]
    end

    config.vm.hostname = @config["VAGRANT_HOSTNAME"]

    # -----------------------------------------------------------------------------------------------------------------
    # Make machine accessible to the host computer by setting up a private network
    # -----------------------------------------------------------------------------------------------------------------

    # There is an annoying problem with Vagrant where it fails to identify the correct interface names when setting up
    # the network cards, and it will error out looking for 'eth1' which does not exist. To work around this we have to
    # disable auto_config on the network interface and manually configure the interface using a provision script.

    config.vm.network :private_network, ip: @config["PRIVATE_NETWORK_IP"], auto_config: false

    # Manually configure private IP address between host and VM

    config.vm.provision "shell",
        run: "always",
        args: "#{@config["PRIVATE_NETWORK_IP"]}",
        inline: "ifconfig enp0s8 $1 netmask 255.255.255.0 up"

    # -----------------------------------------------------------------------------------------------------------------
    # Make machine accessible on public network if required
    # -----------------------------------------------------------------------------------------------------------------

    if @config["PUBLIC_NETWORK"]

        # If a specific NIC was given pass that to prevent Vagrant from asking for a selection

        if !@config["PUBLIC_BRIDGE"].nil?
            config.vm.network :public_network, bridge: @config["PUBLIC_BRIDGE"], auto_config: false
        else
            config.vm.network :public_network, auto_config: false
        end

        # Manually enable public interface and turn on DHCP so we get an IPv4 address

        config.vm.provision "shell",
            run: "always",
            inline: "ifconfig enp0s9 up;dhclient enp0s9;"
    end

    # -----------------------------------------------------------------------------------------------------------------
    # Configure CPU and memory
    # -----------------------------------------------------------------------------------------------------------------

    config.vm.provider :virtualbox do |vb|

        vb.memory = @config["MEMORY"]
        vb.cpus = @config["CPUS"]

        # This solves the issue with being unable to connect to the VM on provisioning because
        # the network adapter is not ticked as being connected

        vb.customize ['modifyvm', :id, '--cableconnected1', 'on']

    end

    # -----------------------------------------------------------------------------------------------------------------
    # This stops an annoying error message that appears for no good reason whenever you try to echo
    # something from the provision script
    # -----------------------------------------------------------------------------------------------------------------

    config.ssh.shell = "bash -c 'BASH_ENV=/etc/profile exec bash'"

    # -----------------------------------------------------------------------------------------------------------------
    # Create APT cache folder to speed up repeat provisioning for the virtual machine
    # -----------------------------------------------------------------------------------------------------------------

    # Windows does not support symlinking which is required by APT in linux. So we cannot do this on windows hosts

    if OS.linux? then
        @apt_cache = "./.vagrant/apt-cache/#{@config["LINUX_VERSION"]}"

        system("mkdir -p ./.vagrant/apt-cache/#{@apt_cache}")

        if File.directory?(@apt_cache)
                config.vm.synced_folder @apt_cache, "/var/cache/apt",
                    owner: "vagrant",
                    group: "vagrant",
                    mount_options: ["dmode=777,fmode=777"]
            end
    end

    # -----------------------------------------------------------------------------------------------------------------
    # Setup folder permissions for special Laravel folders
    # -----------------------------------------------------------------------------------------------------------------

    if File.directory?('./laravel/storage')
        config.vm.synced_folder "./laravel/storage", "/vagrant/laravel/storage",
            owner: "vagrant",
            group: "vagrant",
            mount_options: ["dmode=777,fmode=777"]
    end

    if File.directory?('./laravel/bootstrap/cache')
        config.vm.synced_folder "./laravel/bootstrap/cache", "/vagrant/laravel/bootstrap/cache",
            owner: "vagrant",
            group: "vagrant",
            mount_options: ["dmode=777,fmode=777"]
    end

    # -----------------------------------------------------------------------------------------------------------------
    # Create a file on the vagrant box for use by scripts to identify if we are in vagrant or the host machine
    # -----------------------------------------------------------------------------------------------------------------

    if OS.linux? then
        config.vm.provision "shell", inline: "touch /etc/is_linux"
    elsif OS.windows?
        config.vm.provision "shell", inline: "touch /etc/is_windows"
    end

    config.vm.provision "shell", inline: "touch /etc/is_vagrant_vm"

    # -----------------------------------------------------------------------------------------------------------------
    # Execute provisioning scripts
    # -----------------------------------------------------------------------------------------------------------------

    if ARGV.include?("up") || (ARGV.include?("reload") && ARGV.include?("--provision"))
        puts "-------------------------------------------------------------------------------------------------------------\n"
        puts "Scheduled Provisioning Plan\n"
        puts "-------------------------------------------------------------------------------------------------------------\n\n"
        puts "Selected Box Image: #{@config["VAGRANT_BOX"]}\n"
    end

    # -----------------------------------------------------------------------------------------------------------------
    # Create sorted list of provisioning scripts to be run
    # -----------------------------------------------------------------------------------------------------------------

    @scripts = Dir.glob("./vagrant/provision/*.sh").sort

    @scripts.each do |f|
        if ARGV.include?("up") || (ARGV.include?("reload") && ARGV.include?("--provision"))
            puts "Scheduled Provisioning Script: #{f}"
        end

        config.vm.provision "shell",
            path: "#{f}",
            env: @config
    end

end
