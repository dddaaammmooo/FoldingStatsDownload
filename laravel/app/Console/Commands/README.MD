# Stats Download/Upload Service Commands

[Back](../README.MD)

These can be executed as shown below.

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
