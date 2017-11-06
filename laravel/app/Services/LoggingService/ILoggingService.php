<?php

namespace App\Services\LoggingService;

/**
 * Interface ILoggingService
 *
 * Responsibilities:
 *
 * - Orchestrates between the notification services
 *
 * @package App\Services\LoggingService
 */
interface ILoggingService
{
    /**
     * @param string $message
     */
    public function LogError(string $message): void;

    /**
     * @param string $message
     */
    public function LogWarning(string $message): void;

    /**
     * @param string $message
     */
    public function LogDebug(string $message): void;
}
