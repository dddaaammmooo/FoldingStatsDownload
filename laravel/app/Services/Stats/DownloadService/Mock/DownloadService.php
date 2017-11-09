<?php

namespace App\Services\Stats\DownloadService\Mock;

use App\Services\ConfigLoaderService\IConfigLoaderService;
use App\Services\LoggingService\ILoggingService;
use App\Services\Stats\DownloadService\IDownloadService;
use App\Services\Stats\DownloadService\Result;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

/**
 * Class DownloadService
 *
 * Mock stats download service for testing
 *
 * @package App\Services\Stats\DownloadService\Mock
 */
class DownloadService implements IDownloadService
{
    /** @var IConfigLoaderService */
    private $configLoader;

    /** @var ILoggingService */
    private $loggingService;

    /**
     * DownloadService constructor.
     *
     * @param IConfigLoaderService $configLoader
     * @param ILoggingService      $loggingService
     */
    public function __construct(
        IConfigLoaderService $configLoader,
        ILoggingService $loggingService
    )
    {
        $this->configLoader = $configLoader;
        $this->loggingService = $loggingService;
    }

    /**
     * Emulate stats download
     *
     * @return Result
     * @throws \App\Services\ConfigLoaderService\ConfigLoaderServiceException
     */
    public function DownloadStats(): Result
    {
        $result = new Result();

        // Get the download URL and timeout

        $downloadUrl = $this->configLoader->get('download.url');

        $this->loggingService->LogDebug("Stats Download URL: {$downloadUrl}");

        $downloadTimeout = $this->configLoader->get('download.timeout');

        $this->loggingService->LogDebug("Stats Download Timeout: {$downloadTimeout} Seconds");

        try {
            // The download URL is actually a local storage patch for the purposes of the mock, so just retrieve it
            // directly from disk

            $bz2File = Storage::get($downloadUrl);

            // Decompress the file

            $txtFile = bzdecompress($bz2File);

            // Check for error (bzdecompress returns number on error)

            if (is_numeric($txtFile)) {
                $result->setResult(Result::RESULT_ERROR);
                $result->setDescription("Error [{$txtFile}]: Unable to decompress BZ2 file ({$downloadUrl})");
            }

            echo $txtFile;

            $result->setResult(Result::RESULT_SUCCESS);
        } catch (FileNotFoundException $e) {

            $result->setResult(Result::RESULT_ERROR);
            $result->setDescription("Error [{$e->getCode()}]: Unable to download BZ2 file ({$e->getMessage()}");
        }

        return $result;
    }
}
