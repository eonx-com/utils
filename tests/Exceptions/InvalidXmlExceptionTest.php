<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Exceptions\InvalidXmlException;
use Tests\EoneoPay\Utils\TestCase;

class InvalidXmlExceptionTest extends TestCase
{
    /**
     * Exception should return valid codes.
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $this->processExceptionCodesTest(new InvalidXmlException(), 1100, 0, 500);
    }
}
