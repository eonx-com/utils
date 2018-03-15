<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Exceptions\InvalidDateTimeStringException;
use EoneoPay\Utils\UtcDateTime;

class UtcDateTimeTest extends TestCase
{

    /**
     * Test zulu format datetime string generation
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testGetZulu(): void
    {
        $utcDateTime = new UtcDateTime('2018-03-20 00:00:00');

        self::assertEquals('2018-03-20T00:00:00Z', $utcDateTime->getZulu());
    }

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
     * Test get datetime object.
     *
     * @throws InvalidDateTimeStringException
     *
     * @return void
     */
    public function testGetObject(): void
    {
        $object = (new UtcDateTime('2018-03-20 10:10:20'))->getObject();

        self::assertEquals('UTC', $object->getTimezone()->getName());
        self::assertEquals('2018-03-20 10:10:20', $object->format('Y-m-d H:i:s'));
    }
}
