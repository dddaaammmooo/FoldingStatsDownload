<?php

namespace App\Services\AddressValidatorService\BTC;

use App\Services\AddressValidatorService\IAddressValidatorService;
use Exception;

class AddressValidatorService implements IAddressValidatorService
{
    CONST MAINNET = "MAINNET";
    CONST TESTNET = "TESTNET";
    CONST MAINNET_PUBKEY = "00";
    CONST MAINNET_SCRIPT = "05";
    CONST TESTNET_PUBKEY = "6F";
    CONST TESTNET_SCRIPT = "C4";

    /**
     * @param string $addr
     * @param null|string $version
     * @return bool
     */
    private function isValid(string $addr, string $version = null): bool
    {
        $type = $this->typeOf($addr);

        if ($type === false) {
            return false;
        }

        switch ($version) {
            case self::MAINNET:
                $valids = [self::MAINNET_PUBKEY, self::MAINNET_SCRIPT];
                break;
            case self::TESTNET:
                $valids = [self::TESTNET_PUBKEY, self::TESTNET_SCRIPT];
                break;
            case self::MAINNET_PUBKEY:
            case self::MAINNET_SCRIPT:
            case self::TESTNET_PUBKEY:
            case self::TESTNET_SCRIPT:
                $valids = [$version];
                break;
            default:
                $valids = [self::MAINNET_PUBKEY, self::MAINNET_SCRIPT, self::MAINNET];
        }

        return in_array($type, $valids);
    }

    /**
     * @param string $addr
     * @return null|string
     */
    private function typeOf(string $addr): ?string
    {
        if (preg_match('/[^1-9A-HJ-NP-Za-km-z]/', $addr)) {
            return false;
        }

        $decoded = $this->decodeAddress($addr);

        if (strlen($decoded) != 50) {
            return false;
        }

        $version = substr($decoded, 0, 2);

        $check = substr($decoded, 0, strlen($decoded) - 8);
        $check = pack("H*", $check);
        $check = hash("sha256", $check, true);
        $check = hash("sha256", $check);
        $check = strtoupper($check);
        $check = substr($check, 0, 8);

        $isValid = ($check == substr($decoded, strlen($decoded) - 8));

        return ($isValid ? $version : null);
    }

    /**
     * @param string $data
     * @return string
     */
    private function decodeAddress(string $data): string
    {
        $charsetHex = '0123456789ABCDEF';
        $charsetB58 = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';

        $raw = "0";
        for ($i = 0; $i < strlen($data); $i++) {
            $current = (string)strpos($charsetB58, $data[$i]);
            $raw = (string)bcmul($raw, "58", 0);
            $raw = (string)bcadd($raw, $current, 0);
        }

        $hex = "";
        while (bccomp($raw, 0) == 1) {
            $dv = (string)bcdiv($raw, "16", 0);
            $rem = (integer)bcmod($raw, "16");
            $raw = $dv;
            $hex = $hex . $charsetHex[$rem];
        }

        $withPadding = strrev($hex);
        for ($i = 0; $i < strlen($data) && $data[$i] == "1"; $i++) {
            $withPadding = "00" . $withPadding;
        }

        if (strlen($withPadding) % 2 != 0) {
            $withPadding = "0" . $withPadding;
        }

        return $withPadding;
    }

    /**
     * Validate that the address is syntactically correct
     *
     * @param string $address
     * @return bool
     */
    public function validateAddress(string $address): bool
    {
        return $this->isValid($address);
    }
}
