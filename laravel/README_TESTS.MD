# Unit/Feature Tests

[Back](README.MD)

The code within this project should always be developed using the principle of TDD. This is
facilitated through the use of PHPunit tests.

## Executing Tests

From the SSH shell of your development environment execute the following commands.

```
cd /vagrant/laravel
phpunit
```

If PHPunit is not included in your path it can be executed as follows.

```
cd /vagrant/laravel
./vendor/phpunit/phpunit/phpunit
```

Successful test execution should look something like this.

```
PHPUnit 6.3.1 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 432 ms, Memory: 10.00MB

OK (1 test, 3 assertions)
```

A failure case will look like this.

```
PHPUnit 6.3.1 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 417 ms, Memory: 10.00MB

There was 1 failure:

1) Tests\Unit\AddressValidatorServiceTest::testBtcAddressValidator
Failed asserting that true is false.

/vagrant/laravel/tests/Unit/AddressValidatorServiceTest.php:18

FAILURES!
Tests: 1, Assertions: 1, Failures: 1.

```

## Creating Unit Tests

To create a unit test, execute the following commands from the command-line.

```
cd /vagrant/laravel
php artisan make::test AddressValidatorServiceTest --unit
```

A boilerplate unit test will be created in the `/vagrant/laravel/tests/Unit` folder.

Inside the test file create any number of PHP functions. As long as the function name
begins with 'test' (all lowercase) PHPunit will execute the test.

```php
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
```

## Creating Feature Tests

Feature tests are the same as unit tests from a coding perspective, however fundamentally
they differ from a unit test in that they are designed to test the end-to-end working
of a larger functional area of the system (e.g. stats download).

To create a feature test, execute the following commands.


```
cd /vagrant/laravel
php artisan make::test StatsDownloadTest 
```

The resulting boilerplate test will be created in the  `/vagrant/laravel/tests/feature` folder.

## Access Laravel Facades In Tests

There may be times when you are required to interact with Laravel functionality inside
your tests. To do this you need to have PHPunit execute the Laravel bootstrapper.  To do this
simply modify the test as follows to include the `CreatesApplication` trait in the class.

```php
<?php

namespace Tests\Unit;

use App\Services\AddressValidatorService\BTC\AddressValidatorService as BTCAddressValidatorService;
use Tests\TestCase;
use Tests\CreatesApplication;

class AddressValidatorServiceTest extends TestCase
{
    use CreatesApplication;

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
```
