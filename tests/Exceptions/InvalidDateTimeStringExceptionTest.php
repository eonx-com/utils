<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Exceptions\InvalidDateTimeStringException;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
 *
 * @uses \EoneoPay\Utils\Exceptions\BaseException
 * @uses \EoneoPay\Utils\Exceptions\RuntimeException
 */
class InvalidDateTimeStringExceptionTest extends TestCase
{
    /**
     * Exception should return valid codes.
     *
     * @return void
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $this->processExceptionCodesTest(new InvalidDateTimeStringException(), 1100, 0, 500);
    }
}
