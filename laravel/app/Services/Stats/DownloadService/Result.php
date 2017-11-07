<?php

namespace App\Services\Stats\DownloadService;

/**
 * Class Result
 *
 * This is an object the contains the result of the stats download
 *
 * @package App\Services\Stats\DownloadService
 */
class Result
{
    /**
     * Result type constants
     */
    const RESULT_SUCCESS = 'success';
    const RESULT_ERROR = 'error';

    /**
     * Properties
     */

    /** @var string $result */
    private $result;

    /** @var string $description */
    private $description;
    
    /**
     * Getters & Setters
     */

    /**
     * @return string
     */
    public function getResult(): string {
        return $this->result;
    }

    /**
     * @param string $result
     * @return self|$this
     */
    public function setResult(string $result): self {
        $this->result = $result;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @param string $description
     * @return self|$this
     */
    public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }
}
