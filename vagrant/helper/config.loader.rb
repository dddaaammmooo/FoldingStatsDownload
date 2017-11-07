# -*- mode: ruby -*-
# vi: set ft=ruby :

# ---------------------------------------------------------------------------------------------------------------------
# Helper functions for configuration
# ---------------------------------------------------------------------------------------------------------------------

class Array
  def deep_dup
    map {|x| x.deep_dup}
  end
end

class Object
  def deep_dup
    dup
  end
end

class Numeric
  # We need this because number.dup throws an exception
  # We also need the same definition for Symbol, TrueClass and FalseClass
  def deep_dup
  end
end

# ---------------------------------------------------------------------------------------------------------------------
# Load machine configuration
# ---------------------------------------------------------------------------------------------------------------------

# Make sure we have a default configuration file. We cannot proceed without this

if File.file?("vagrant.config.default.rb")

    puts "Loading default configuration settings file..."
    @config_type = "default"

    if @config_settings.nil? then
        @config_settings = {}
        @config_settings[@config_type] = {}
    end

    require_relative "../../vagrant.config.default.rb"
else
    Kernel.abort("Unable to provision virtual machine: `vagrant.config.default.rb` missing")
end

# Check if the developer has a local configuration file, and use this to override
# any settings defined in the default configuration

if File.exist?("vagrant.config.rb")

    puts "Loading local configuration settings file..."
    @config_type = "local"

    if @config_settings.nil? then
        @config_settings = {}
        @config_settings[@config_type] = {}
    end

    require_relative "../../vagrant.config.rb"
    @config = @config_settings["default"].merge(@config_settings["local"])

else

    puts "No local `vagrant.config.rb` settings file found, proceeding with default settings file..."
    @config = @config["default"]
end
