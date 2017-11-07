<?php

namespace App\Services\Stats\DownloadService\HTTP;

use App\Services\ConfigLoaderService\ConfigLoaderServiceException;
use App\Services\ConfigLoaderService\IConfigLoaderService;
use App\Services\LoggingService\ILoggingService;
use App\Services\Stats\DownloadService\AbstractDownloadService;
use App\Services\Stats\DownloadService\IDownloadService;
use App\Services\Stats\DownloadService\Result;
use App\Services\StorageService\IStorageService;
use Exception;
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

        // Download the file from the URL stored in service configuration file

        $scheme = strtolower(parse_url($this->downloadUrl, PHP_URL_SCHEME));

        if ($scheme == "https")
        {
            // If they have specified HTTPS, only allow HTTPS

            $urls = [$this->downloadUrl];
        }
        else
        {
            // Otherwise, try HTTPS first then fall back to HTTP

            $urls = $this->getDownloadUrls();
        }

        // Iterate all of the URLS in order (i.e. HTTPS first, then HTTP)

        foreach ($urls as $scheme => $url)
        {
            $options = $this->clientOptions;

            // If we are trying HTTPS, force all redirects to stay HTTPS

            if ($scheme == "https")
            {
                $options = array_merge(
                    $options, [
                                'allow_redirects' => [
                                    'max'       => 5,         // Allow at most 5 redirects
                                    'strict'    => true,      // Use "strict" RFC compliant redirects
                                    'referer'   => true,      // Add a referer header
                                    'protocols' => ['https'], // Only allow redirects to other HTTPS URLs
                                ],
                            ]
                );
            }

            $this->loggingService->LogDebug("Attempting download from: {$url}");

            try
            {
                $httpResult = $this->client->request('GET', $url, $options);
            }
            catch (Exception $e)
            {
                $this->loggingService->LogDebug("Unexpected exception while downloading: {$e->getMessage()}");
                continue;
            }

            // Make sure the HTTP request response code indicates can contain a body

            $statusCode = $httpResult->getStatusCode();
            $statusMessage = StatusCodes::getMessage($statusCode);

            $this->loggingService->LogDebug("HTTP status code: {$statusCode} - {$statusMessage}");

            if (!StatusCodes::canHaveBody($statusCode))
            {
                // Did not receive an acceptable HTTP response code, skip to the next URL in the array

                $this->loggingService->LogDebug("Unexpected HTTP response code received while downloading");
                continue;
            }

            $this->timerStop();
            $this->loggingService->LogDebug("Download finished (" . $this->timerGetDuration() . " seconds)");

            return $this->persistToStorage($httpResult->getBody()->getContents());
        }

        // Neither HTTPS/HTTP URL was successful

        $result->setResult(Result::RESULT_ERROR);
        $result->setDescription("Error: Unexpected HTTP response code received while downloading");

        return $result;
    }

    /**
     * Decompose the download URL and construct an array with HTTPS & HTTP variants
     *
     * @return array
     */
    private function getDownloadUrls(): array
    {
        $user = parse_url($this->downloadUrl, PHP_URL_USER);
        $password = parse_url($this->downloadUrl, PHP_URL_PASS);
        $host = parse_url($this->downloadUrl, PHP_URL_HOST);
        $port = parse_url($this->downloadUrl, PHP_URL_PORT);
        $path = parse_url($this->downloadUrl, PHP_URL_PATH);
        $query = parse_url($this->downloadUrl, PHP_URL_QUERY);
        $fragment = parse_url($this->downloadUrl, PHP_URL_FRAGMENT);

        $url = "";

        if (!empty($user))
        {
            $url .= $user;
            if (!empty($password))
            {
                $url .= ":{$password}";
            }

            $url .= "@";
        }

        $url .= $host;

        if (!empty($port))
        {
            $url .= ":{$port}";
        }

        $url .= $path;

        if (!empty($query))
        {
            $url .= "?{$query}";
        }

        if (!empty($fragment))
        {
            $url .= "#{$fragment}";
        }

        return [
            "https" => "https://{$url}",
            "http"  => "http://{$url}",
        ];
    }
}
