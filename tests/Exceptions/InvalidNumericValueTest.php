<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Exceptions\InvalidNumericValue;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Exceptions\InvalidNumericValue
 */
class InvalidNumericValueTest extends TestCase
{
    /**
     * Exception should return valid codes.
     *
     * @return void
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $exception = new InvalidNumericValue(null, null, null, []);
        $this->processExceptionCodesTest($exception, 1010, 1, 400);
    }
}
