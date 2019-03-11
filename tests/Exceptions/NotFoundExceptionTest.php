<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use Tests\EoneoPay\Utils\Stubs\Exceptions\NotFoundExceptionStub;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Exceptions\NotFoundException
 *
 * @uses \EoneoPay\Utils\Exceptions\BaseException
 */
class NotFoundExceptionTest extends TestCase
{
    /**
     * Exception should return valid codes.
     *
     * @return void
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $this->processExceptionCodesTest(new NotFoundExceptionStub(), 1400, 0, 404);
    }
}
