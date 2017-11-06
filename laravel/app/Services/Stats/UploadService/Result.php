<?php

namespace App\Services\Stats\UploadService;

/**
 * Class Result
 *
 * This is an object the contains the result of the stats upload
 *
 * @package App\Services\Stats\UploadService
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
}
