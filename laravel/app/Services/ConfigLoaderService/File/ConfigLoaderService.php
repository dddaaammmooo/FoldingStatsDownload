<?php

namespace App\Services\ConfigLoaderService\File;

use App\Services\ConfigLoaderService\ConfigLoaderServiceException;
use App\Services\ConfigLoaderService\IConfigLoaderService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

/**
 * Class ConfigLoaderService
 *
 * File configuration settings loaded from hard-coded array
 *
 * @package App\Services\ConfigLoaderService\File
 */
class ConfigLoaderService implements IConfigLoaderService
{
    /** @var array $cacheConfig */
    private $cacheConfig = [];

    const CONFIG_FILENAME = 'app.ini';

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
            try
            {
                $content = Storage::get(self::CONFIG_FILENAME);
                $this->cacheConfig = parse_ini_string($content);
            }
            catch (FileNotFoundException $e)
            {
                $this->cacheConfig = [];
            }
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
        $this->persistArray();

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
        $this->persistArray();

        return $this;
    }

    /**
     * Convert the array of values to an INI file and save to disk
     */
    private function persistArray(): void
    {
        $content = "";

        foreach ($this->cacheConfig as $token => $value)
        {
            // Store each array element on new line

            if (is_array($value))
            {
                for ($i = 0; $i < count($value); $i++)
                {
                    $content .= "{$token}[] = \"{$value[$i]}\"\n";
                }
            }
            elseif ($value == "")
            {
                // Blank strings

                $content .= "{$token} = \n";
            }
            else
            {
                // All other non blank values

                $content .= "{$token} = \"{$value}\"\n";
            }
        }

        Storage::put(self::CONFIG_FILENAME, $content);
    }
}
