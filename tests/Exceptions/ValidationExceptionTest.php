<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use Tests\EoneoPay\Utils\Stubs\Exceptions\ValidationExceptionStub;
use Tests\EoneoPay\Utils\TestCase;

class ValidationExceptionTest extends TestCase
{
    /**
     * Exception should return valid codes.
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $this->processExceptionCodesTest(new ValidationExceptionStub(), 1000, 0, 400);
    }
}
