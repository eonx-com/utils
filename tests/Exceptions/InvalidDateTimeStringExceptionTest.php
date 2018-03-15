<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Exceptions\InvalidDateTimeStringException;
use Tests\EoneoPay\Utils\TestCase;

class InvalidDateTimeStringExceptionTest extends TestCase
{
    /**
     * Exception should return valid codes.
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $this->processExceptionCodesTest(new InvalidDateTimeStringException(), 1100, 0, 500);
    }
}
