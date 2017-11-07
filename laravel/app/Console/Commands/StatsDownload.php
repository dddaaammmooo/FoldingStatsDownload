<?php

namespace App\Console\Commands;

use App\Services\Stats\DownloadService\IDownloadService;
use App\Services\Stats\DownloadService\Result;
use App\Services\LoggingService\ILoggingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Lang;

/**
 * Class StatsDownload
 *
 * To execute this command, from the SSH shell execute:
 *
 *      cd /vagrant/laravel
 *      php artisan stats:download
 *
 * @package App\Console\Commands
 */
class StatsDownload extends Command
{
    /** @var string $signature */
    protected $signature = 'stats:download';

    /** @var string $description */
    protected $description = 'Download the stats file';

    /** @var IDownloadService $downloadService */
    private $downloadService;

    /** @var ILoggingService $loggingService */
    private $loggingService;

    /**
     * Load dependencies
     *
     * @param IDownloadService $downloadService
     * @param ILoggingService  $loggingService
     */
    public function __construct(
        IDownloadService $downloadService,
        ILoggingService $loggingService
    )
    {
        parent::__construct();

        $this->downloadService = $downloadService;
        $this->loggingService = $loggingService;
    }

    /**
     * Perform stats download
     */
    public function handle()
    {
        $this->loggingService->LogDebug('Beginning stats download');
        $result = $this->downloadService->DownloadStats();

        if ($result->getResult() == Result::RESULT_SUCCESS)
        {
            $this->loggingService->LogDebug(Lang::get('statsDownload.success'));
        }
        elseif ($result->getResult() == Result::RESULT_ERROR)
        {
            $this->loggingService->logError(Lang::get('statsDownload.error'));
            $this->loggingService->logError($result->getDescription());
        }
    }
}
