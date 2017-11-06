<?php

namespace App\Console\Commands;

use App\Services\LoggingService\ILoggingService;
use Illuminate\Console\Command;

class StatsCleanup extends Command
{
    /** @var string $signature */
    protected $signature = 'stats:cleanup';

    /** @var string $description */
    protected $description = 'Clean up resources used during stats run';

    /** @var ILoggingService $cleanupService */
    private $cleanupService;

    /**
     * Load dependencies
     *
     * @param ILoggingService $cleanupService
     */
    public function __construct(ILoggingService $cleanupService)
    {
        parent::__construct();

        $this->cleanupService = $cleanupService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Stats Cleanup Service Started");
    }
}
