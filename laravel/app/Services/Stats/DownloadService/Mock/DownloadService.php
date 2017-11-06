<?php

namespace App\Services\Stats\DownloadService\Mock;

use App\Services\ConfigLoaderService\IConfigLoaderService;
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

    public function __construct(IConfigLoaderService $configLoader)
    {
        $this->configLoader = $configLoader;
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
        $downloadTimeout = $this->configLoader->get('download.timeout');

        try {
            // The download URL is actually a local storage patch for the purposes of the mock

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
