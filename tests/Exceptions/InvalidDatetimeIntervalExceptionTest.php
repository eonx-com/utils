<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
 *
 * @uses \EoneoPay\Utils\Exceptions\BaseException
 * @uses \EoneoPay\Utils\Exceptions\RuntimeException
 */
class InvalidDatetimeIntervalExceptionTest extends TestCase
{
    /**
     * Exception should return valid codes.
     *
     * @return void
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $this->processExceptionCodesTest(new InvalidDateTimeIntervalException(), 1100, 0, 500);
    }
}
