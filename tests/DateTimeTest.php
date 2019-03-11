<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use DateTime as BaseDateTime;
use EoneoPay\Utils\DateTime;
use EoneoPay\Utils\Exceptions\InvalidDateTimeStringException;

/**
 * @covers \EoneoPay\Utils\DateTime
 *
 * @uses \EoneoPay\Utils\Exceptions\BaseException
 */
class DateTimeTest extends TestCase
{
    /**
     * Test date time compatible with base datetime
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testDateIntervalCompatibleWithGlobal(): void
    {
        self::assertInstanceOf(BaseDateTime::class, new DateTime());
    }

    /**
     * Test exception is thrown if string is invalid
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testExceptionThrownIfIntervalIsInvalid(): void
    {
        $this->expectException(InvalidDateTimeStringException::class);

        new DateTime('INVALID');
    }
}
