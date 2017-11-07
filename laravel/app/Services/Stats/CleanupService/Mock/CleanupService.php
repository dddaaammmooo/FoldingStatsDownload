<?php

namespace App\Services\Stats\CleanupService\Mock;

use App\Services\Stats\CleanupService\ICleanupService;
use App\Services\Stats\CleanupService\Result;

/**
 * Class CleanupService
 *
 * Mock stats cleanup service for testing
 *
 * @package App\Services\Stats\CleanupService\Mock
 */
class CleanupService implements ICleanupService
{
    /**
     * Emulate stats Cleanup
     *
     * @return Result
     */
    public function CleanupStats(): Result
    {
        $result = new Result();
        $result->setResult(Result::RESULT_SUCCESS);

        return $result;
    }
}
