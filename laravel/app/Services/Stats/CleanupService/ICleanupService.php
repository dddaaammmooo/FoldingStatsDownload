<?php

namespace App\Services\Stats\CleanupService;

/**
 * Interface ICleanupService
 *
 * Responsibilities:
 *
 * - Clean up resources used during stats run
 *
 * @package App\Services\Stats\CleanupService
 */
interface ICleanupService
{
    /**
     * @return Result
     */
    public function CleanupStats(): Result;
}
