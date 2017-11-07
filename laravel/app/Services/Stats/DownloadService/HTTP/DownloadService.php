<?php

namespace App\Services\Stats\DownloadService\HTTP;

use App\Services\ConfigLoaderService\ConfigLoaderServiceException;
use App\Services\ConfigLoaderService\IConfigLoaderService;
use App\Services\LoggingService\ILoggingService;
use App\Services\Stats\DownloadService\AbstractDownloadService;
use App\Services\Stats\DownloadService\IDownloadService;
use App\Services\Stats\DownloadService\Result;
use App\Services\StorageService\IStorageService;
use App\Services\StorageService\Status;
use GuzzleHttp\Client as GuzzleHttpClient;

/**
 * Class DownloadService
 *
 * HTTP stats download service for testing
 *
 * @package App\Services\Stats\DownloadService\Mock
 */
class DownloadService extends AbstractDownloadService implements IDownloadService
{
    /** @var GuzzleHttpClient */
    private $client;

    /** @var array $clientOptions */
    private $clientOptions = [];

    /**
     * Perform HTTP stats download
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

        $this->downloadUrl = $this->configLoader->get('download.url');
        $this->loggingService->LogDebug("Stats Download URL: {$this->downloadUrl}");

        // Setup PHP and download timeout

        $this->setupTimeout();

        // Setup HTTP client used for download of statistics

        $this->client = new GuzzleHttpClient();
        $this->clientOptions = [
            'connect_timeout' => 10,
            'read_timeout'    => $this->downloadTimeout,
            'timeout'         => $this->downloadTimeout,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return Result
     */
    public function DownloadStats(): Result
    {
        $result = new Result();

        $this->loggingService->LogDebug("Stats Download Source: {$this->downloadUrl}");
        $this->loggingService->LogDebug("Stats Download Timeout: {$this->downloadTimeout} Seconds");

        $this->timerStart();
        $this->loggingService->LogDebug("Download started");

        // Download the file from the URL storage in service configuration file

        $httpResult = $this->client->request('GET', $this->downloadUrl, $this->clientOptions);

        // Make sure the HTTP request response code indicates can contain a body

        $statusCode = $httpResult->getStatusCode();
        $statusMessage = StatusCodes::getMessage($statusCode);

        $this->loggingService->LogDebug("HTTP status code: {$statusCode} - {$statusMessage}");

        if (!StatusCodes::canHaveBody($statusCode))
        {
            // Did not receive an acceptable HTTP response code

            $result->setResult(Result::RESULT_ERROR);
            $result->setDescription("Error: Unexpected HTTP response code received while downloading");

            return $result;
        }

        $this->timerStop();
        $this->loggingService->LogDebug("Download finished (" . $this->timerGetDuration() . " seconds)");

        return $this->persistToStorage($httpResult->getBody()->getContents());
    }
}
