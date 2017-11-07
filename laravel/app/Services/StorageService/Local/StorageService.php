<?php

namespace App\Services\StorageService\Local;

use App\Services\ConfigLoaderService\ConfigLoaderServiceException;
use App\Services\ConfigLoaderService\IConfigLoaderService;
use App\Services\StorageService\File;
use App\Services\StorageService\IStorageService;
use App\Services\StorageService\Status;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

/**
 * Class StorageService
 *
 * Local disk storage implementation
 *
 * @package App\Services\Stats\StorageService\Local
 */
class StorageService implements IStorageService
{
    /** @var IConfigLoaderService */
    private $configLoader;

    /** @var string $basePath */
    private $basePath;

    /**
     * StorageService constructor.
     *
     * @param IConfigLoaderService $configLoaderService
     *
     * @throws ConfigLoaderServiceException
     */
    public function __construct(IConfigLoaderService $configLoaderService)
    {
        $this->configLoader = $configLoaderService;
        $this->basePath = $this->configLoader->get('storage.path');
    }

    /**
     * Download file from disk storage
     *
     * @param string $status
     * @param string $filename
     *
     * @return File
     * @throws ConfigLoaderServiceException
     */
    public function downloadFile(string $status, string $filename): File
    {
        $file = new File;
        $file->setFilename($filename);

        try
        {
            $storageLocation = "{$this->basePath}/{$status}/{$filename}";

            // Retrieve the file and calculate hash

            $content = Storage::get($storageLocation);
            $hash = sha1($content);

            $file->setStorageLocation($storageLocation);
            $file->setStatus($status);
            $file->setContent($content);
            $file->setContentHash($hash);
        }
        catch (FileNotFoundException $e)
        {
            // The file was not found

            $file->setStatus(Status::STATUS_ERROR);
            $file->setContent(null);
            $file->setContentHash(null);
        }

        return $file;
    }

    /**
     * {@inheritdoc}
     *
     * @param File $file
     *
     * @return bool
     * @throws Exception If the file fails validation
     */
    public function uploadFile(File &$file): bool
    {
        $result = false;

        // Only attempt to upload if the file object has been setup correctly

        if ($file->validate())
        {
            $filename = "{$this->basePath}/{$file->getStatus()}/{$file->getFilename()}";

            // Only upload if the file has changed contents, status or was not already in storage

            if ($file->getStorageLocation() !== $filename || $file->hasContentChanged())
            {
                // Upload the file to storage

                Storage::put($filename, $file->getContent());

                // If the file had moved from its original location- delete the old file

                if ($file->getStorageLocation() !== $filename && $file->getStorageLocation() !== null)
                {
                    // File has moved, delete the original

                    Storage::delete($file->getStorageLocation());
                }

                // Update the file object

                $file->setStorageLocation($filename);
                $file->setContentHash(sha1($file->getContent()));
            }

            $result = true;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $status Storage status constant
     *
     * @return array
     */
    public function getFileListByStatus(string $status): array
    {
        $results = [];

        $files = Storage::allFiles("{$this->basePath}/{$status}");

        foreach ($files as $file) {
            $results[] = pathinfo($file, PATHINFO_BASENAME);
        }

        return $results;
    }
}
