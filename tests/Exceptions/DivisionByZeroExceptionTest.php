<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Exceptions\DivisionByZeroException;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Exceptions\DivisionByZeroException
 */
class DivisionByZeroExceptionTest extends TestCase
{
    /**
     * Exception should return valid codes.
     *
     * @return void
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $this->processExceptionCodesTest(new DivisionByZeroException(), 1130, 0, 500);
    }
}
