<?php

namespace App\Services\ConfigLoaderService;

interface IConfigLoaderService
{
    /**
     * Get configuration value from storage. Throws an exception if unable to find the requested value
     *
     * @param string $token
     * @param bool $cache
     * @throws ConfigLoaderServiceException
     * @return string
     */
    public function get(string $token, bool $cache = true): string;

    /**
     * Return all config items
     *
     * @param bool $cache
     * @return array
     */
    public function getAll(bool $cache = true): array;

    /**
     * Retrieve a number of tokens simultaneously
     *
     * @param array $tokens
     * @param bool $cache
     * @return array
     */
    public function getArray(array $tokens, bool $cache = true): array;

    /**
     * Save configuration value back to storage
     *
     * @param string $token
     * @param string $value
     * @return self
     */
    public function set(string $token, string $value): self;

    /**
     * Set a number of tokens simultaneously
     *
     * @param array $tokens
     * @return self
     */
    public function setArray(array $tokens): self;

    /**
     * Checks if specified config value exists in storage
     *
     * @param string $token
     * @param bool $cache
     * @return bool
     */
    public function has(string $token, bool $cache = true): bool;

    /**
     * Deletes the specified configuration value from storage
     *
     * @param string $token
     * @return IConfigLoaderService
     */
    public function delete(string $token): IConfigLoaderService;
}
