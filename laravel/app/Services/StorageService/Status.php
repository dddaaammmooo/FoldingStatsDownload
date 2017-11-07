<?php

namespace App\Services\StorageService;

/**
 * Class Status
 *
 * Constants defining the state of a stats download file in storage
 *
 * @package App\Services\Stats\StorageService
 */
class Status
{
    /**
     * Result type constants
     */

    const STATUS_ERROR = "error";
    
    const STATUS_PENDING_DECOMPRESSION = "pending_decompression";   // File is pending decompression
    const STATUS_PENDING_PROCESSING = "pending_processing";         // File is decompressed and pending processing
    const STATUS_PROCESSED = "processed";                           // File has been processed and is pending cleanup
    const STATUS_ARCHIVED = "archived";                             // File is in archival storage
}
