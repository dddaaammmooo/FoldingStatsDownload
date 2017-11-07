<?php

namespace App\Services\Stats\UploadService;

/**
 * Interface IUploadService
 *
 * Responsibilities:
 *
 * - Uploads the stats file
 * - Commit the stats file to later processing
 * - Return a result that identifies the stats file that was uploaded
 *
 * @package App\Services\Stats\UploadService
 */
interface IUploadService
{
    /**
     * @return Result
     */
    public function UploadStats(): Result;
}
