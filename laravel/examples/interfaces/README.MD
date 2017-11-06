# Interfaces

[Back](../README.MD)

Create your interfaces somewhere under an appropriate subfolder of `laravel/app/Services`.
Interface filenamed should be prefixed with an uppercase letter `I`.

#### Example: Stats Download Service

##### Interface
###### /laravel/app/Services/Stats/DownloadService/IDownloadService.php

```php
<?php

namespace App\Services\Stats\DownloadService;

/**
 * Interface IDownloadService
 *
 * Responsibilities:
 *
 * - Downloads the stats file
 * - Commit the stats file to later processing
 * - Return a result that identifies the stats file that was downloaded
 *
 * @package App\Services\Stats\DownloadService
 */
interface IDownloadService
{
    /**
     * @return Result
     */
    public function DownloadStats(): Result;
}
```

##### Implementation
###### /laravel/app/Services/Stats/DownloadService/Mock/DownloadService.php

The following is an example of a mock implementation of the IDownloadService interface.

````php
<?php

namespace App\Services\Stats\DownloadService\Mock;

use App\Services\Stats\DownloadService\Result;
use App\Services\Stats\DownloadService\IDownloadService;

/**
 * Class DownloadService
 *
 * Mock stats download service for testing
 *
 * @package App\Services\Stats\DownloadService\Mock
 */
class DownloadService implements IDownloadService
{
    /**
     * Emulate stats download
     *
     * @return Result
     */
    public function DownloadStats(): Result
    {
        $result = new Result();
        $result->setResult(Result::RESULT_SUCCESS);

        return $result;
    }
}
````

# Dependency Injection

Laravel automatically takes care of injecting dependencies into constructors. Simply
pass the interface into the constructor and Laravel will provide you with the
appropriate implementation.

### Selecting Appropriate Implementation

When injecting an interface into controllers it is necessary for the developer to
specify which concrete implementation that Laravel should provide. This is done
centrally in AppServiceProvider.

###### /laravel/app/Providers/AppServiceProvider.php

Simply add a binding for each interface to the `bindInterfaces()` method at the
bottom of the file.

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindInterfaces();
    }

    /**
     * Bind interfaces to their implementations for dependency injection
     *
     * @return void
     */
    private function bindInterfaces(): void
    {
        // Stats download service

        $this->app->bind(
            \App\Services\Stats\DownloadService\IDownloadService::class,            // Interface
            \App\Services\Stats\DownloadService\Mock\DownloadService::class         // Concrete Implementation
        );

        // Stats cleanup service

        $this->app->bind(
            \App\Services\Stats\CleanupService\ICleanupService::class,
            \App\Services\Stats\CleanupService\Mock\CleanupService::class
        );
    }
}
```

### Dependency Injection Example

###### /laravel/app/Console/Command/StatsDownload.php

```php
<?php

namespace App\Console\Commands;

use App\Services\Stats\DownloadService\IDownloadService;
use App\Services\Stats\DownloadService\Result;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Lang;

/**
 * Class StatsDownload
 *
 * To execute this command, from the SSH shell execute:
 *
 *      cd /vagrant/laravel
 *      php artisan stats:download
 *
 * @package App\Console\Commands
 */
class StatsDownload extends Command
{
    /** @var string $signature */
    protected $signature = 'stats:download';

    /** @var string $description */
    protected $description = 'Download the stats file';

    /** @var IDownloadService $downloadService */
    private $downloadService;

    /**
     * Load dependencies
     *
     * @param IDownloadService $downloadService
     */
    public function __construct(IDownloadService $downloadService)
    {
        parent::__construct();

        $this->downloadService = $downloadService;
    }

    /**
     * Perform stats download
     */
    public function handle()
    {
        $this->info("Stats Download Service Started");

        $result = $this->downloadService->DownloadStats();

        if ($result->getResult() == Result::RESULT_SUCCESS) {
            $this->info(Lang::get('statsDownload.success'));
        } elseif ($result->getResult() == Result::RESULT_SUCCESS) {
            $this->error(Lang::get('statsDownload.error'));
        }
    }
}
```
