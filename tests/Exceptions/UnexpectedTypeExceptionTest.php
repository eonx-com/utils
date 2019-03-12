<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\DateTime;
use EoneoPay\Utils\Exceptions\UnexpectedTypeException;
use stdClass;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Exceptions\UnexpectedTypeException
 */
class UnexpectedTypeExceptionTest extends TestCase
{
    /**
     * Exception should return valid codes.
     *
     * @return void
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $exception = new UnexpectedTypeException(new stdClass(), DateTime::class);
        $this->processExceptionCodesTest($exception, 1100, 0, 500);

        self::assertSame(
            'Unexpected type "stdClass" found, expected "EoneoPay\\Utils\\DateTime"',
            $exception->getMessage()
        );
    }
}
