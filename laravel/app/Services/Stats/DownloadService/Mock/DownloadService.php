<?php

namespace App\Services\Stats\DownloadService\Mock;

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
    /**
     * Emulate stats download
     *
     * @return Result
     */
    public function DownloadStats(): Result
    {
        $result = new Result();

        // Read the BZ2 archive from the tests folder

        try {
            $bz2File = Storage::get('tests/DownloadService/daily_user_summary.txt.bz2');

            // Decompress the file and save the uncompressed version back to the tests folder

            Storage::put('tests/DownloadService/daily_user_summary.txt', bzdecompress($bz2File));

            $result->setResult(Result::RESULT_SUCCESS);
        } catch (FileNotFoundException $e) {
            $result->setResult(Result::RESULT_ERROR);
            $result->setDescription($e->getMessage());
        }

        return $result;
    }
}
