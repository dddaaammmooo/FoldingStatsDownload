<?php

namespace App\Services\StorageService;

use Exception;

/**
 * Class File
 *
 * This class represents a file in storage
 *
 * @package App\Services\Stats\StorageService
 */
class File
{
    /**
     * Properties
     */

    /** @var string $status */
    private $status;

    /** @var string $filename */
    private $filename;

    /** @var string $content */
    private $content;

    /** @var null $storageLocation This is the location of the file in storage */
    private $storageLocation = null;

    /** @var string $hash This is the has of the files original contents so we can test if it has been altered */
    private $contentHash;

    /**
     * Returns flag indicating content has changed
     *
     * @return bool
     */
    public function hasContentChanged(): bool
    {
        return $this->contentHash != sha1($this->content);
    }

    /**
     * Ensure the file is in a valid state for upload/download
     *
     * @return bool
     * @throws Exception
     */
    public function validate(): bool
    {
        if ($this->getStatus() === null)
        {
            throw new Exception("File does not have a valid status");
        }

        if ($this->getFilename() === null)
        {
            throw new Exception("File does not have a valid filename");
        }

        return true;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return self|$this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContentHash(): string
    {
        return $this->contentHash;
    }

    /**
     * @param string $contentHash
     *
     * @return self|$this
     */
    public function setContentHash(string $contentHash): self
    {
        $this->contentHash = $contentHash;

        return $this;
    }

    /**
     * Returns the location of the file in storage
     *
     * @return string|null
     */
    public function getStorageLocation(): ?string
    {
        return $this->storageLocation;
    }

    /**
     * @param string $storageLocation
     *
     * @return self|$this
     */
    public function setStorageLocation(string $storageLocation): self
    {
        $this->storageLocation = $storageLocation;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return self|$this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     *
     * @return self|$this
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }
}
