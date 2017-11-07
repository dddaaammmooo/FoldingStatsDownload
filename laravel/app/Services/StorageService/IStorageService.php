<?php

namespace App\Services\StorageService;

/**
 * Interface IStorageService
 *
 * Responsibilities:
 *
 * - Allow persistent storage of files
 * - Allow downloading of files in storage
 * - Allow marking of file status
 * - Allow downloading of a particular status currently in storage
 *
 * @package App\Services\Stats\DownloadService
 */
interface IStorageService
{
    /**
     * Upload the content to storage
     *
     * @param File $file
     *
     * @return bool
     */
    public function uploadFile(File &$file): bool;

    /**
     * Download the requested file from storage
     *
     * @param string $status
     * @param string $filename
     *
     * @return File
     */
    public function downloadFile(string $status, string $filename): File;

    /**
     * Retrieve a list of files with the given status
     *
     * @param string $status Storage status constant
     *
     * @return array
     */
    public function getFileListByStatus(string $status): array;
}
