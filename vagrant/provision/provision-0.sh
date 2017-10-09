#!/usr/bin/env bash

sudo rm -rf /vagrant/vagrant/provision.log 2>&1

echo -e "---------------------------------------------------------------------------------------------------------"
echo -e "Provisioning Script"
echo -e "---------------------------------------------------------------------------------------------------------"

# ---------------------------------------------------------------------------------------------------------------------
# Delete old provisioning log
# ---------------------------------------------------------------------------------------------------------------------

rm -rf /vagrant/vagrant/provision.log >/dev/null 2>&1

# ---------------------------------------------------------------------------------------------------------------------
# File string replacement function
# ---------------------------------------------------------------------------------------------------------------------

function sedeasy {
  sudo sed -i "s/$(echo $1 | sed -e 's/\([[\/.*]\|\]\)/\\&/g')/$(echo $2 | sed -e 's/[\/&]/\\&/g')/g" $3
}

# ---------------------------------------------------------------------------------------------------------------------
# Install pre-requisites
# ---------------------------------------------------------------------------------------------------------------------

echo -e "Updating Repositories & Installing Pre-Requisites...\n"
{
    touch /etc/is_vagrant_vm;

    {
        sudo ex +"%s@DPkg@//DPkg" -cwq /etc/apt/apt.conf.d/70debconf
        sudo dpkg-reconfigure debconf -f noninteractive -p critical

        if [ -d "/vagrant/laravel" ]; then
            echo -e "\ncd /vagrant/laravel" >> /home/vagrant/.bashrc
            echo -e "\nexport PATH=\"\$PATH:/vagrant/laravel/tools\"" >> /home/vagrant/.bashrc
            echo -e "\nexport PATH=\"\$PATH:/vagrant/laravel/vendor/phpunit/phpunit\"" >> /home/vagrant/.bashrc
        fi

        echo -e "\nexport PATH=\"\$PATH:/vagrant/vagrant/tools\"" >> /home/vagrant/.bashrc

        sudo apt-key adv --keyserver keyserver.ubuntu.com --recv 68576280
        sudo add-apt-repository -y ppa:ondrej/php
        sudo add-apt-repository -y "deb https://deb.nodesource.com/node_8.x $(lsb_release -sc) main"

        if [ "${INSTALL_SQLSRV}" = "true" ] ;
        then
            curl https://packages.microsoft.com/keys/microsoft.asc | sudo apt-key add -
            sudo curl https://packages.microsoft.com/config/ubuntu/16.04/prod.list > /etc/apt/sources.list.d/mssql-release.list
        fi
    } >/dev/null 2>&1

    sudo apt-get -y update;
    sudo apt-get -y upgrade;

    sudo apt-get install -y python-software-properties curl git inotify-tools
}

# ---------------------------------------------------------------------------------------------------------------------
# Install Apache
# ---------------------------------------------------------------------------------------------------------------------

echo -e "Installing Apache...\n"
{
    apt-get install -y apache2;

    if [ -d "/vagrant/laravel" ]; then
        sudo rm -rf /var/www
        sudo ln -fs /vagrant /var/www
        sedeasy "www/html" "www/laravel/public" "/etc/apache2/sites-available/000-default.conf"
        sedeasy "AllowOverride None" "AllowOverride All" "/etc/apache2/apache2.conf"
        sudo chown -R www-data:www-data /var/www
        sudo chmod 644 /vagrant/laravel/public/.htaccess
    fi

    sudo a2enmod rewrite

    wget --quiet https://dl-ssl.google.com/dl/linux/direct/mod-pagespeed-stable_current_amd64.deb 2>&1 /dev/nul
    sudo dpkg -i mod-pagespeed-*.deb
    sudo apt-get -f -y install
    rm mod-pagespeed-*.deb

    sudo service apache2 restart
}

# ---------------------------------------------------------------------------------------------------------------------
# Install MySQL
# ---------------------------------------------------------------------------------------------------------------------

echo -e "Installing MySQL...\n"
{
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password ${FLDC_DB_ROOT_PASSWORD}"
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password ${FLDC_DB_ROOT_PASSWORD}"
    sudo apt-get install -y mysql-server
    sedeasy "127.0.0.1" "0.0.0.0" "/etc/mysql/mysql.conf.d/mysqld.cnf"
    MYSQL_PWD=${FLDC_DB_ROOT_PASSWORD} mysql -uroot -e "CREATE USER '${FLDC_DB_USERNAME}'@'%' IDENTIFIED BY '${FLDC_DB_PASSWORD}';";
    MYSQL_PWD=${FLDC_DB_ROOT_PASSWORD} mysql -uroot -e "CREATE USER '${FLDC_DB_USERNAME}'@'localhost' IDENTIFIED BY '${FLDC_DB_PASSWORD}';";
    MYSQL_PWD=${FLDC_DB_ROOT_PASSWORD} mysql -uroot -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY '${FLDC_DB_ROOT_PASSWORD}' WITH GRANT OPTION;";
    MYSQL_PWD=${FLDC_DB_ROOT_PASSWORD} mysql -uroot -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '${FLDC_DB_ROOT_PASSWORD}' WITH GRANT OPTION;";
    MYSQL_PWD=${FLDC_DB_ROOT_PASSWORD} mysql -uroot -e "GRANT ALL PRIVILEGES ON *.* TO '${FLDC_DB_USERNAME}'@'%' IDENTIFIED BY '${FLDC_DB_PASSWORD}' WITH GRANT OPTION;";
    MYSQL_PWD=${FLDC_DB_ROOT_PASSWORD} mysql -uroot -e "GRANT ALL PRIVILEGES ON *.* TO '${FLDC_DB_USERNAME}'@'localhost' IDENTIFIED BY '${FLDC_DB_PASSWORD}' WITH GRANT OPTION;";
    MYSQL_PWD=${FLDC_DB_ROOT_PASSWORD} mysql -uroot -e "FLUSH PRIVILEGES;";
    MYSQL_PWD=${FLDC_DB_ROOT_PASSWORD} mysql -uroot -e "SHOW GRANTS;";
    MYSQL_PWD=${FLDC_DB_ROOT_PASSWORD} mysql -uroot -e "DROP DATABASE IF EXISTS ${FLDC_DB_DATABASE}"
    MYSQL_PWD=${FLDC_DB_ROOT_PASSWORD} mysql -uroot -e "CREATE DATABASE ${FLDC_DB_DATABASE}"
    unset MYSQL_PWD
}

# ---------------------------------------------------------------------------------------------------------------------
# Install PHP
# ---------------------------------------------------------------------------------------------------------------------

echo -e "Installing PHP ${PHP_VERSION}...\n"
{
    if [ "${PHP_VERSION}" == "7.1" ] ;
    then
        sudo apt-get install -y php7.1 php7.1-mysql php7.1-bcmath php7.1-mbstring php7.1-xml php7.1-curl php7.1-zip php7.1-bz2 php7.1-dev php-xdebug php-pear libapache2-mod-php7.1

        # Configure Xdebug (Not supported by 7.2 currently)

        sudo echo -e "\n\n[Xdebug]" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.remote_enable=true" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.remote_host=192.168.36.1" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.remote_port=9000" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.remote_handler=dbgp" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.idekey=PHPSTORM" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.file_link_format = phpstorm://open?%f:%l" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.max_nesting_level = 512" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.auto_trace=On" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.trace_options=On;" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.trace_enable_trigger=On;" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.trace_output_dir=\"/home/vagrant\"" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.profiler_append=On;" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.profiler_output_dir=\"/home/vagrant\"" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.profiler_enable=On;" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.profiler_enable_trigger=On;" >> /etc/php/7.1/apache2/php.ini
        sudo echo -e "xdebug.profiler_output_dir=\"/home/vagrant\"" >> /etc/php/7.1/apache2/php.ini

    elif [ "${PHP_VERSION}" == "7.2" ] ;
    then
        sudo apt-get install -y php7.2 php7.2-mysql php7.2-bcmath php7.2-mbstring php7.2-xml php7.2-curl php7.2-zip php7.2-bz2 php7.2-dev php-pear libapache2-mod-php7.2
    fi

    # Enable PHP errors

    sedeasy "display_errors = Off" "display_errors = On" "/etc/php/${PHP_VERSION}/apache2/php.ini"
    sedeasy "display_startup_errors = Off" "display_startup_errors = On" "/etc/php/${PHP_VERSION}/apache2/php.ini"

    sudo service apache2 restart
    sudo service mysql restart
}

# ---------------------------------------------------------------------------------------------------------------------
# Install Composer
# ---------------------------------------------------------------------------------------------------------------------

echo -e "Installing Composer...\n"
{
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
}

# ---------------------------------------------------------------------------------------------------------------------
# Install NPM
# ---------------------------------------------------------------------------------------------------------------------

echo -e "Installing NodeJS...\n"
{
    sudo apt-get install -y nodejs
}

# ---------------------------------------------------------------------------------------------------------------------
# Install Microsoft SQL Server PDO Driver
# ---------------------------------------------------------------------------------------------------------------------

if [ "${INSTALL_SQLSRV}" == "true" ] ;
then
    echo -e "Installing Microsoft SQL Server & PDO Driver...\n"
    {
        sudo ACCEPT_EULA=Y apt-get install -y msodbcsql mssql-tools
        sudo apt-get install -y unixodbc-dev
        echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bash_profile
        echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
        source ~/.bashrc
        sudo pear config-set php_ini `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"` system
        sudo pecl install sqlsrv
        sudo pecl install pdo_sqlsrv
        sudo echo "extension=sqlsrv.so" >> /etc/php/${PHP_VERSION}/apache2/php.ini
        sudo echo "extension=pdo_sqlsrv.so" >> /etc/php/${PHP_VERSION}/apache2/php.ini
    }
else
    echo -e "Skipping Microsoft SQL Server & PDO Driver Installation\n"
fi

# ---------------------------------------------------------------------------------------------------------------------
# Install Redis
# ---------------------------------------------------------------------------------------------------------------------

if [ "${INSTALL_REDIS}" == "true" ] ;
then
    echo -e "Installing Redis...\n"
    {
        sudo apt-get -y install build-essential tcl
        cd /tmp
        curl -O http://download.redis.io/redis-stable.tar.gz
        tar xzvf redis-stable.tar.gz
        cd redis-stable
        make
        make test
        sudo make install
        sudo mkdir /etc/redis
        sudo cp /tmp/redis-stable/redis.conf /etc/redis
        sedeasy "supervised no" "supervised systemd" "/etc/redis/redis.conf"
        sedeasy "dir ./" "dir /var/lib/redis" "/etc/redis/redis.conf"
        sudo echo "[Unit]" >> /etc/systemd/system/redis.service
        sudo echo "Description=Redis In-Memory Data Store" >> /etc/systemd/system/redis.service
        sudo echo "After=network.target" >> /etc/systemd/system/redis.service
        sudo echo "" >> /etc/systemd/system/redis.service
        sudo echo "[Service]" >> /etc/systemd/system/redis.service
        sudo echo "User=redis" >> /etc/systemd/system/redis.service
        sudo echo "Group=redis" >> /etc/systemd/system/redis.service
        sudo echo "ExecStart=/usr/local/bin/redis-server /etc/redis/redis.conf" >> /etc/systemd/system/redis.service
        sudo echo "ExecStop=/usr/local/bin/redis-cli shutdown" >> /etc/systemd/system/redis.service
        sudo echo "Restart=always" >> /etc/systemd/system/redis.service
        sudo echo "" >> /etc/systemd/system/redis.service
        sudo echo "[Install]" >> /etc/systemd/system/redis.service
        sudo echo "WantedBy=multi-user.target" >> /etc/systemd/system/redis.service
        sudo adduser --system --group --no-create-home redis
        sudo mkdir /var/lib/redis
        sudo chown redis:redis /var/lib/redis
        sudo chmod 770 /var/lib/redis
        sudo systemctl start redis
    }
else
    echo -e "Skipping Redis Installation"
fi

# ---------------------------------------------------------------------------------------------------------------------
# Add crontab job for Laravel task scheduler
# ---------------------------------------------------------------------------------------------------------------------

if [ -d "/vagrant/laravel" ]; then

    echo -e "Configuring Laravel Scheduler CRON Job..."

    echo "* * * * * php /vagrant/laravel/artisan schedule:run >> /dev/null 2>&1" > cronjobs
    crontab cronjobs
    rm cronjobs

fi

# ---------------------------------------------------------------------------------------------------------------------
# Setup NPM/Composer
# ---------------------------------------------------------------------------------------------------------------------

if [ -d "/vagrant/laravel" ]; then

    cd /vagrant/laravel
    composer install

    # Windows hosts cannot use symlinks inside the development machine. Need to disable during NPM installation

    if [ -f "/etc/is_windows" ]; then
        npm install --no-bin-links
    else
        npm install
    fi

fi

# ---------------------------------------------------------------------------------------------------------------------
# Setup Laravel
# ---------------------------------------------------------------------------------------------------------------------

cd /vagrant/laravel

if [ ! -f ".env" ]; then
    cp .env.example .env
    sedeasy "APP_NAME=" "APP_NAME=${VAGRANT_HOSTNAME}" .env
    sedeasy "APP_URL=" "APP_URL=http://${VAGRANT_HOSTNAME}" .env
    sedeasy "DB_CONNECTION=" "DB_CONNECTION=${FLDC_DB_DRIVER}" .env
    sedeasy "DB_HOST=" "DB_HOST=${FLDC_DB_HOST}" .env
    sedeasy "DB_PORT=" "DB_PORT=${FLDC_DB_PORT}" .env
    sedeasy "DB_DATABASE=" "DB_DATABASE=${FLDC_DB_DATABASE}" .env
    sedeasy "DB_USERNAME=" "DB_USERNAME=${FLDC_DB_USERNAME}" .env
    sedeasy "DB_PASSWORD=" "DB_PASSWORD=${FLDC_DB_PASSWORD}" .env
    sedeasy "REDIS_HOST=" "REDIS_HOST=${REDIS_HOST}" .env
    sedeasy "REDIS_PORT=" "REDIS_PORT=${REDIS_PORT}" .env
    sedeasy "REDIS_PASSWORD=" "REDIS_PASSWORD=${REDIS_PASSWORD}" .env
fi

php artisan key:generate

./tools/clean-laravel
