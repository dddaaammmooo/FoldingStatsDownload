<?php

namespace App\Services\Stats\DownloadService\Mock;

use App\Services\ConfigLoaderService\ConfigLoaderServiceException;
use App\Services\ConfigLoaderService\IConfigLoaderService;
use App\Services\LoggingService\ILoggingService;
use App\Services\Stats\DownloadService\AbstractDownloadService;
use App\Services\Stats\DownloadService\IDownloadService;
use App\Services\Stats\DownloadService\Result;
use App\Services\StorageService\IStorageService;
use Illuminate\Support\Facades\App;

/**
 * Class DownloadService
 *
 * Mock stats download service for testing
 *
 * @package App\Services\Stats\DownloadService\Mock
 */
class DownloadService extends AbstractDownloadService implements IDownloadService
{
    /**
     * Perform mock stats download
     *
     * @param IConfigLoaderService $configLoader
     * @param ILoggingService      $loggingService
     * @param IStorageService      $storageService
     *
     * @throws ConfigLoaderServiceException
     */
    public function __construct(
        IConfigLoaderService $configLoader,
        ILoggingService $loggingService,
        IStorageService $storageService
    )
    {
        $this->configLoader = $configLoader;
        $this->loggingService = $loggingService;
        $this->storageService = $storageService;

        // Get the download URL and timeout from service configuration

        $this->downloadUrl = App::basePath($this->configLoader->get('download.url'));

        // Setup PHP and download timeout

        $this->setupTimeout();
    }

    /**
     * {@inheritdoc}
     *
     * @return Result
     */
    public function DownloadStats(): Result
    {
        $this->loggingService->LogDebug("Stats Download Source: {$this->downloadUrl}");
        $this->loggingService->LogDebug("Stats Download Timeout: {$this->downloadTimeout} Seconds");

        $this->timerStart();
        $this->loggingService->LogDebug("Download started");

        $contents = file_get_contents($this->downloadUrl);

        $this->timerStop();
        $this->loggingService->LogDebug("Download finished (" . $this->timerGetDuration() . " seconds)");

        return $this->persistToStorage($contents);
    }
}
