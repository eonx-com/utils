<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Exceptions\HashingFailedException;
use Tests\EoneoPay\Utils\TestCase;

class HashingFailedExceptionTest extends TestCase
{
    /**
     * Exception should return valid codes.
     *
     * @return void
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $this->processExceptionCodesTest(new HashingFailedException(), 1100, 0, 500);
    }
}
