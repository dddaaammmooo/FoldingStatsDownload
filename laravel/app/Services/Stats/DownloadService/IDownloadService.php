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
