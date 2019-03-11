<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Exceptions\HashingFailedException;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Exceptions\HashingFailedException
 *
 * @uses \EoneoPay\Utils\Exceptions\BaseException
 * @uses \EoneoPay\Utils\Exceptions\RuntimeException
 */
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
