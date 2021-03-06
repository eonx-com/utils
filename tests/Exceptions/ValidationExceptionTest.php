<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use Tests\EoneoPay\Utils\Stubs\Exceptions\ValidationExceptionStub;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Exceptions\ValidationException
 */
class ValidationExceptionTest extends TestCase
{
    /**
     * Exception should return given errors array via getErrors().
     *
     * @return void
     */
    public function testGetErrorsReturnRightArray(): void
    {
        $errors = ['error1' => 'error1'];
        $exception = new ValidationExceptionStub(null, null, null, null, $errors);

        self::assertEquals($errors, $exception->getErrors());
    }

    /**
     * Exception should return valid codes.
     *
     * @return void
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $exception = new ValidationExceptionStub(null, null, null, null, []);
        $this->processExceptionCodesTest($exception, 1000, 0, 400);
    }
}
