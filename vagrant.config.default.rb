# ---------------------------------------------------------------------------------------------------------------------
# Vagrant machine configuration
# ---------------------------------------------------------------------------------------------------------------------

# Do not modify this file

# To override these settings, make a separate copy of this file called `vagrant.config` in the same folder and make
# your modifications in that file. This file is synced back to the repository and should only be changed if there is
# a need to make a modification to the provisioning scripts

@config_settings[@config_type] =
{
  "VAGRANTFILE_API_VERSION"     => "2",
  "VAGRANT_BOX"                 => "bento/ubuntu-17.04",
  "VAGRANT_URL"                 => "",
  "VAGRANT_HOSTNAME"            => "vagrant-fldc",

  "LINUX_VERSION"               => "ubuntu-1704",
  "PHP_VERSION"                 => "7.1",

  "CPUS"                        => "2",
  "MEMORY"                      => "8192",
  "PRIVATE_NETWORK_IP"          => "192.168.36.66",
  "PUBLIC_BRIDGE"               => "wlp2s0",
  "PUBLIC_NETWORK"              => "true",

  "FLDC_DB_DRIVER"              => "mysql",
  "FLDC_DB_PORT"                => "3306",
  "FLDC_DB_HOST"                => "localhost",
  "FLDC_DB_DATABASE"            => "folding",
  "FLDC_DB_USERNAME"            => "folding",
  "FLDC_DB_PASSWORD"            => "coin",
  "FLDC_DB_ROOT_PASSWORD"       => "coin",

  "INSTALL_SQLSRV"              => "false",
  "INSTALL_REDIS"               => "false"
}
