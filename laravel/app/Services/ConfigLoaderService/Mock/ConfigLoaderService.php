<?php

namespace App\Services\ConfigLoaderService\Mock;

use App\Services\ConfigLoaderService\ConfigLoaderServiceException;
use App\Services\ConfigLoaderService\IConfigLoaderService;

/**
 * Class ConfigLoaderService
 *
 * Mock configuration settings loaded from hard-coded array
 *
 * @package App\Services\ConfigLoaderService\Mock
 */
class ConfigLoaderService implements IConfigLoaderService
{
    /** @var array $cacheConfig */
    private $cacheConfig = [];

    /**
     * Define database configuration here
     */
    public function __construct()
    {
        $this->cacheConfig = $this->getAll(false);
    }

    /**
     * Retrieve all config tokens
     *
     * @param bool $cache
     * @return array
     */
    public function getAll(bool $cache = true): array
    {
        if (!$cache)
        {
            $this->cacheConfig = [
                'logging.filename' => 'storage/logs/foldingcoin.log',
                'download.url'     => 'tests/Data/daily_user_summary.txt.bz2',
                'download.timeout' => '300',
                'storage.path'     => 'storage',
            ];
        }

        return $this->cacheConfig;
    }

    /**
     * Retrieve a number of tokens simultaneously
     *
     * @param array $tokens
     * @param bool  $cache
     * @return array
     * @throws ConfigLoaderServiceException
     */
    public function getArray(array $tokens, bool $cache = true): array
    {
        $returnTokens = [];

        foreach ($tokens as $token)
        {
            $returnTokens[$token] = $this->get($token, $cache);
        }

        return $returnTokens;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $token
     * @param bool   $cache
     * @throws ConfigLoaderServiceException
     * @return string
     */
    public function get(string $token, bool $cache = true): string
    {
        if (!isset($this->cacheConfig[$token]))
        {
            throw new ConfigLoaderServiceException("Requested configuration '{$token}' not found");
        }

        return $this->cacheConfig[$token];
    }

    /**
     * {@inheritDoc}
     *
     * @param array $tokens
     * @return IConfigLoaderService
     */
    public function setArray(array $tokens): IConfigLoaderService
    {
        foreach ($tokens as $token => $value)
        {
            $this->set($token, $value);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $token
     * @param string $value
     * @return IConfigLoaderService
     */
    public function set(string $token, string $value): IConfigLoaderService
    {
        $this->cacheConfig[$token] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $token
     * @return bool
     */
    public function has(string $token, bool $cache = true): bool
    {
        return isset($this->cacheConfig[$token]);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $token
     * @return IConfigLoaderService
     */
    public function delete(string $token): IConfigLoaderService
    {
        unset($this->cacheConfig[$token]);

        return $this;
    }
}
