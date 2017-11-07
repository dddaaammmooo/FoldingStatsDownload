# Services

[Back](../README.MD)

This is the folder where most of the statistics download services logic will be
stored.

#### ConfigLoaderService

These classes provide a mechanism to get/set system configuration using a variety
of mechanisms.

* Database
* Redis
* Mock

#### LoggingService

These classes provie a mechanism to perform persistent logging using a variety of
mechanisms.

* File
* Slack
* SMS
* Email

#### Stats\DownloadService

Downloads the stats file

```
vagrant@vagrant-fldc:/vagrant/laravel$ php artisan stats:download
Download completed successfully
vagrant@vagrant-fldc:/vagrant/laravel$ ls storage/app/tests/DownloadService/ -l
total 85416
-rwxrwxrwx 1 vagrant vagrant 30329725 Sep 26 14:49 daily_user_summary_broken.txt.bz2
-rwxrwxrwx 1 vagrant vagrant 40342198 Sep 27 01:29 daily_user_summary.txt
-rwxrwxrwx 1 vagrant vagrant 16786909 Sep 26 14:34 daily_user_summary.txt.bz2
vagrant@vagrant-fldc:/vagrant/laravel$ 
```

#### Stats\UploadService

Uploads the stats file

#### Stats\CleanupService

Perform cleanup operations following a stats upload/download
