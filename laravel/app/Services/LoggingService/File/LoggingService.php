<?php

namespace App\Services\LoggingService\File;

use App\Services\ConfigLoaderService\ConfigLoaderServiceException;
use App\Services\ConfigLoaderService\IConfigLoaderService;
use App\Services\LoggingService\ILoggingService;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class LoggingService
 *
 * File logging service
 *
 * @package App\Services\LoggingService\File
 */
class LoggingService implements ILoggingService
{
    /** @var Logger $logger */
    private $logger;

    /** @var IConfigLoaderService $configLoaderService */
    private $configLoaderService;

    /** Path to use for logging when none found in config settings */
    const DEFAULT_PATH = 'storage/logs/default.log';

    /**
     * LoggingService constructor.
     *
     * @param IConfigLoaderService $configLoaderService
     */
    public function __construct(IConfigLoaderService $configLoaderService)
    {
        $this->configLoaderService = $configLoaderService;

        try {
            $filename = $this->configLoaderService->get('logging.filename');
        } catch (ConfigLoaderServiceException $e) {
            $this->configLoaderService->set('logging.filename', self::DEFAULT_PATH);
            $filename = self::DEFAULT_PATH;
        }

        $this->logger = new Logger(
            'FoldingCoin', [
            new StreamHandler(
                $filename,
                Logger::DEBUG
            )
        ]);

        $this->logger->useMicrosecondTimestamps(true);
    }

    /**
     * @param string $message
     */
    public function LogError(string $message): void
    {
        $this->logger->error($message);
    }

    /**
     * @param string $message
     */
    public function LogWarning(string $message): void
    {
        $this->logger->warning($message);
    }

    /**
     * @param string $message
     */
    public function LogDebug(string $message): void
    {
        $this->logger->debug($message);
    }
}
