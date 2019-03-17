<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use DateTime as BaseDateTime;
use EoneoPay\Utils\DateInterval;
use EoneoPay\Utils\DateTime;
use EoneoPay\Utils\Exceptions\InvalidDateTimeStringException;

/**
 * @covers \EoneoPay\Utils\DateTime
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

    /**
     * Tests addWithoutOverflow
     *
     * @dataProvider getAddWithoutOverflowData
     *
     * @param string $start
     * @param string $interval
     * @param string $expected
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
     */
    public function testAddWithoutOverflow(string $start, string $interval, string $expected): void
    {
        $date = new DateTime($start);
        $date->addWithoutOverflow(new DateInterval($interval));

        static::assertEquals($date, new DateTime($expected));
    }

    /**
     * Data provider
     *
     * @return mixed[]
     */
    public function getAddWithoutOverflowData(): iterable
    {
        yield 'normal' => [
            '2019-01-01T00:00:00+0000',
            'P1M',
            '2019-02-01T00:00:00+0000'
        ];

        yield 'overflow month' => [
            '2019-01-31T00:00:00+0000',
            'P1M',
            '2019-02-28T00:00:00+0000'
        ];

        yield 'overflow year' => [
            '2016-02-29T00:00:00+0000',
            'P1Y',
            '2017-02-28T00:00:00+0000'
        ];
    }
}
