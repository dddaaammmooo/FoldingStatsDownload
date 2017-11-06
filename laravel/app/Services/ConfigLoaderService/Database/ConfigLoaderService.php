<?php

namespace App\Services\ConfigLoaderService\Database;

use App\Models\Config;
use App\Services\ConfigLoaderService\ConfigLoaderServiceException;
use App\Services\ConfigLoaderService\IConfigLoaderService;

class ConfigLoaderService implements IConfigLoaderService
{
    /** @var array $cacheConfig Local cache to decrease database access requirement */
    private $cacheConfig = [];

    /**
     * Preload database configuration on construction
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
        if (!$cache) {
            $this->cacheConfig = Config::select(['token', 'value'])->get()->mapWithKeys(
                function ($item) {
                    return [$item->token => $item->value];
                }
            )->toArray();
        }

        return $this->cacheConfig;
    }

    /**
     * Retrieve a number of tokens simultaneously
     *
     * @param array $tokens
     * @param bool $cache
     * @return array
     * @throws ConfigLoaderServiceException
     */
    public function getArray(array $tokens, bool $cache = true): array
    {
        $returnTokens = [];

        foreach ($tokens as $token) {
            $returnTokens[$token] = $this->get($token, $cache);
        }

        return $returnTokens;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $token
     * @param bool $cache
     * @throws ConfigLoaderServiceException
     * @return string
     */
    public function get(string $token, bool $cache = true): string
    {
        // Check if we have the value in our cache

        if (isset($this->cacheConfig[$token]) && $cache) {
            return $this->cacheConfig[$token];
        }

        // If the value is not cached, attempt to find it in the database

        $config = Config::whereToken($token)->first();

        if ($config instanceof Config) {
            // If we found it, cache it for later

            $this->cacheConfig[$token] = $config->value;

            return $config->value;
        }

        throw new ConfigLoaderServiceException("Requested configuration '{$token}' not found");
    }

    /**
     * {@inheritDoc}
     *
     * @param array $tokens
     * @return IConfigLoaderService
     */
    public function setArray(array $tokens): IConfigLoaderService
    {
        foreach ($tokens as $token => $value) {
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
        // Update (or create) the value in the database

        $config = Config::findOrNew($token);
        $config->token = $token;
        $config->value = $value;
        $config->save();

        // Update the cache for later

        $this->cacheConfig[$token] = $value;

        // Allow chaining

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
        // Check the cache first

        if ($cache) {
            if (isset($this->cacheConfig[$token])) {
                return true;
            }
        }

        // Was not in cache, or we are no accessing the cache. Check the database

        $config = Config::whereToken($token)->first();

        if (!$config instanceof Config) {
            return false;
        }

        // Found in the database, update the cache while we are here

        $this->cacheConfig[$token] = $config->value;

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $token
     * @return IConfigLoaderService
     */
    public function delete(string $token): IConfigLoaderService
    {
        Config::whereToken($token)->delete();
        unset($this->cacheConfig[$token]);

        return $this;
    }
}
