<?php

namespace App\Services\Stats\DownloadService;

use App\Services\ConfigLoaderService\ConfigLoaderServiceException;
use App\Services\ConfigLoaderService\IConfigLoaderService;
use App\Services\LoggingService\ILoggingService;
use App\Services\StorageService\File;
use App\Services\StorageService\IStorageService;
use App\Services\StorageService\Status;

abstract class AbstractDownloadService implements IDownloadService
{
    /** @var IStorageService $storageService */
    protected $storageService;

    /** @var IConfigLoaderService $configLoader */
    protected $configLoader;

    /** @var ILoggingService $loggingService */
    protected $loggingService;

    /** @var string $downloadUrl */
    protected $downloadUrl;

    /** @var int $downloadTimeout */
    protected $downloadTimeout;

    /** @var int $timeStart */
    private $timeStart;

    /** @var int $timeFinish */
    private $timeFinish;

    /**
     * Setup download timeout
     *
     * @throws ConfigLoaderServiceException
     */
    protected function setupTimeout()
    {
        $this->downloadTimeout = intval($this->configLoader->get('download.timeout'));

        // Give ourselves sufficient time to attempt the download, and fail gracefully if we timeout

        ini_set('max_execution_time', $this->downloadTimeout + 10);
    }

    /**
     * Start the timer
     */
    protected function timerStart()
    {
        $this->timeFinish = null;
        $this->timeStart = date("U");
    }

    /**
     * Stop the timer
     */
    protected function timerStop()
    {
        $this->timeFinish = date("U");
    }

    /**
     * Return the number of seconds the timer was/is active
     *
     * @return int
     */
    protected function timerGetDuration(): int
    {
        if ($this->timeStart === null)
        {
            return 0;
        }

        $timeFinish = $this->timeFinish === null ? date("U") : $this->timeFinish;

        return $timeFinish - $this->timeStart;
    }

    /**
     * Store the downloaded file in a timestamped file for later decompression
     *
     * @param string $contents
     *
     * @return Result
     */
    protected function persistToStorage(string $contents): Result
    {
        $result = new Result();

        // Check for empty file

        $size = strlen($contents);

        if (strlen($size) === 0) {
            $result->setResult(Result::RESULT_ERROR);
            $result->setDescription("Error: Downloaded file is empty");

            return $result;
        }

        // Filename will be in the format "YYYYMMDD-HHMMSSS_HASH.bz2"

        $filename = date("Ymd-His") . "_" . sha1($contents) . ".bz2";

        $this->loggingService->LogDebug("Attempting to persist to storage as '{$filename}'");

        $file = new File();
        $file->setFilename($filename);
        $file->setContent($contents);
        $file->setStatus(Status::STATUS_PENDING_DECOMPRESSION);

        // Upload to storage

        if ($this->storageService->uploadFile($file))
        {
            // Everything went fine

            $resultDescription = "{$size} bytes persisted successfully";

            $this->loggingService->LogDebug($resultDescription);

            $result->setResult(Result::RESULT_SUCCESS);
            $result->setDescription($resultDescription);

            return $result;
        }

        // Something unexpected went wrong if we get here

        $result->setResult(Result::RESULT_ERROR);
        $result->setDescription("Error: Unexpected error storing downloaded file ({$filename} - {$size} bytes)");

        return $result;
    }
}
