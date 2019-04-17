<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use DateTimeZone;
use EoneoPay\Utils\DateTime;
use EoneoPay\Utils\Exceptions\InvalidDateTimeStringException;
use EoneoPay\Utils\UtcDateTime;

class UtcDateTimeTest extends TestCase
{
    /**
     * Test invalid datetime string passed to constructor.
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     *
     * @return void
     */
    public function testConstructorException(): void
    {
        $this->expectException(InvalidDateTimeStringException::class);

        new UtcDateTime('abcd123');
    }

    /**
     * Tests that the constructor accepts a DateTime object
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testConstructorAcceptsDateTime(): void
    {
        self::assertInstanceOf(
            UtcDateTime::class,
            new UtcDateTime(new DateTime('now'))
        );
    }

    /**
     * Test that the create method returns a DateTime instance
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testCreateMethod(): void
    {
        $expected = new DateTime('2019-01-01T00:00:00+00:00');
        $actual = new UtcDateTime(new DateTime('2019-01-01T00:00:00+10:00'));

        self::assertEquals($expected, $actual->getObject());
    }

    /**
     * Ensure datetime is always interpreted as UTC
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     *
     * @return void
     */
    public function testDateTimeIsAlwaysUtc(): void
    {
        $timestamp = '2018-03-20 11:00:00+11:00';
        $utcTimestamp = \gmdate('Y-m-d H:i:s', \strtotime($timestamp));

        // A normal DateTime object will adjust time, if timestamp is sent with +11, the time will be
        // changed removing 11 hours
        self::assertNotSame($utcTimestamp, $timestamp);
        self::assertSame(
            $utcTimestamp,
            (new DateTime($timestamp))->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s')
        );

        // The UtcDateTime object won't adjust the time
        $utcDatetime = (new UtcDateTime($timestamp))->getObject();
        self::assertSame('UTC', $utcDatetime->getTimezone()->getName());
        self::assertSame(\substr($timestamp, 0, -6), $utcDatetime->format('Y-m-d H:i:s'));
    }

    /**
     * Test get datetime object.
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     *
     * @return void
     */
    public function testGetObject(): void
    {
        $utcDatetime = (new UtcDateTime('2018-03-20 10:10:20'))->getObject();
        $expected = (new DateTime('2018-03-20 10:10:20', new DateTimeZone('UTC')))
            ->setTimezone(new DateTimeZone('UTC'));

        self::assertEquals($expected, $utcDatetime);
    }

    /**
     * Test zulu format datetime string generation
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testGetZulu(): void
    {
        $utcDateTime = new UtcDateTime('2018-03-20 00:00:00');

        self::assertEquals('2018-03-20T00:00:00Z', $utcDateTime->getZulu());
    }
}
