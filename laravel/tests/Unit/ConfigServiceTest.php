<?php

namespace Tests\Unit;

use App\Services\ConfigLoaderService\ConfigLoaderServiceException;
use App\Services\ConfigLoaderService\Mock\ConfigLoaderService;
use Tests\TestCase;

class ConfigServiceTest extends TestCase
{
    /**
     * Test retrieval of download URL
     *
     * @return void
     * @throws ConfigLoaderServiceException
     */
    public function testConfigDownloadUrl()
    {
        $configLoader = new ConfigLoaderService();
        $downloadUrl = $configLoader->get('download.url');
        $this->assertEquals('tests/DownloadService/daily_user_summary.txt.bz2', $downloadUrl);
    }

    /**
     * Test retrieval of download timeout
     *
     * @return void
     * @throws ConfigLoaderServiceException
     */
    public function testConfigDownloadTimeout()
    {
        $configLoader = new ConfigLoaderService();
        $downloadTimeout = $configLoader->get('download.timeout');
        $this->assertEquals('300', $downloadTimeout);
    }
}
