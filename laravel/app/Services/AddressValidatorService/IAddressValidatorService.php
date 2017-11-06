<?php

namespace App\Services\AddressValidatorService;

interface IAddressValidatorService
{
    /**
     * Validate that the address is syntactically correct
     *
     * @param string $address
     * @return bool
     */
    public function validateAddress(string $address): bool;
}
