<?php

namespace Tests\Unit;

use App\Services\AddressValidatorService\BTC\AddressValidatorService as BTCAddressValidatorService;
use Tests\TestCase;

class AddressValidatorServiceTest extends TestCase
{
    /**
     * Test BTC address validator
     *
     * @return void
     */
    public function testBtcAddressValidator()
    {
        $validator = new BTCAddressValidatorService();
        $this->assertTrue($validator->validateAddress('1EYfyLQT3wtu4jtcChPQVXsKU1robqRpFU'));
        $this->assertFalse($validator->validateAddress(''));
        $this->assertFalse($validator->validateAddress('1EYfyLQT3wtu4xxxChPQVXsKU1robqRpFU'));
    }
}
