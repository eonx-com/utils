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
     * Test date interval accepts \DateInterval through the constructor
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
     */
    public function testConstructorAcceptsDateInterval(): void
    {
        $expected = \DateInterval::createFromDateString('+1 day 12 hours')->format('%Y-%M-%D %H:%I:%S');
        $actual = (new DateInterval(new DateInterval('P1DT12H')))->format('%Y-%M-%D %H:%I:%S');

        self::assertSame(
            $expected,
            $actual
        );
    }

    /**
     * Test date interval fails on integer
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
     */
    public function testConstructorFailsInteger(): void
    {
        $this->expectException(InvalidDateTimeIntervalException::class);

        new DateInterval(5);
    }

    /**
     * Test date interval fails on object
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
     */
    public function testConstructorFailsObject(): void
    {
        $this->expectException(InvalidDateTimeIntervalException::class);

        new DateInterval(new class {
        });
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

    /**
     * Tests predictable day iteration method
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
     */
    public function testPredictableDayIteration(): void
    {
        static::assertTrue((new DateInterval('P1M'))->hasPredictableDayIteration());
        static::assertTrue((new DateInterval('P1Y'))->hasPredictableDayIteration());
        static::assertFalse((new DateInterval('P1MT1S'))->hasPredictableDayIteration());
        static::assertFalse((new DateInterval('P1M1D'))->hasPredictableDayIteration());
    }
}
