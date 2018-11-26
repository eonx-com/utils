<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use DateInterval as BaseDateInterval;
use EoneoPay\Utils\DateInterval;
use EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException;

/**
 * @covers \EoneoPay\Utils\DateInterval
 */
class DateIntervalTest extends TestCase
{
    /**
     * Test date interval compatible with base interval
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
     */
    public function testDateIntervalCompatibleWithGlobal(): void
    {
        self::assertInstanceOf(BaseDateInterval::class, new DateInterval('P1D'));
    }

    /**
     * Test exception is thrown if interval is invalid
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
     */
    public function testExceptionThrownIfIntervalIsInvalid(): void
    {
        $this->expectException(InvalidDateTimeIntervalException::class);

        new DateInterval('INVALID');
    }
}
