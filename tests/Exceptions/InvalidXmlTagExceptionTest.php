<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Exceptions\InvalidXmlTagException;
use Tests\EoneoPay\Utils\TestCase;

class InvalidXmlTagExceptionTest extends TestCase
{
    /**
     * Exception should return valid codes.
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $this->processExceptionCodesTest(new InvalidXmlTagException(), 1100, 0, 500);
    }
}
