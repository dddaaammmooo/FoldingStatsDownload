<?php

namespace App\Services\Stats\UploadService\Mock;

use App\Services\Stats\UploadService\Result;
use App\Services\Stats\UploadService\IUploadService;

/**
 * Class UploadService
 *
 * Mock stats upload service for testing
 *
 * @package App\Services\Stats\UploadService\Mock
 */
class UploadService implements IUploadService
{
    /**
     * Emulate stats upload
     *
     * @return Result
     */
    public function UploadStats(): Result
    {
        $result = new Result();
        $result->setResult(Result::RESULT_SUCCESS);

        return $result;
    }
}
