<?php

namespace Tests\Unit;

use App\Services\ConfigLoaderService\ConfigLoaderServiceException;
use App\Services\ConfigLoaderService\Mock\ConfigLoaderService;
use Tests\TestCase;

class ConfigServiceTest extends TestCase
{
    // Known default mock values

    const DOWNLOAD_URL = 'tests/DownloadService/daily_user_summary.txt.bz2';
    const DOWNLOAD_TIMEOUT = '300';

    /**
     * Test retrieval of default download URL
     *
     * @return void
     * @throws ConfigLoaderServiceException
     */
    public function testConfigDownloadUrl()
    {
        $configLoader = new ConfigLoaderService();
        $downloadUrl = $configLoader->get('download.url');
        $this->assertEquals(self::DOWNLOAD_URL, $downloadUrl);
    }

    /**
     * Test retrieval of default download timeout
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

    /**
     * Test update and subsequent retrieval of download timeout
     *
     * @return void
     * @throws ConfigLoaderServiceException
     */
    public function testSetConfigDownloadUrl()
    {
        $configLoader = new ConfigLoaderService();

        // Create a fake URL

        $fakeUrl = 'http://www.fake.com/stats.bz2';

        // Test default value

        $downloadUrl = $configLoader->get('download.url');
        $this->assertEquals(self::DOWNLOAD_URL, $downloadUrl);

        // Save a fake URL

        $configLoader->set('download.url', $fakeUrl);

        // Read fake URL back

        $downloadUrl = $configLoader->get('download.url');
        $this->assertEquals($fakeUrl, $downloadUrl);
    }
}
