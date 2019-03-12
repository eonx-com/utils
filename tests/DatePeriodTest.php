<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\DateInterval;
use EoneoPay\Utils\DatePeriod;
use EoneoPay\Utils\DateTime;

/**
 * @covers \EoneoPay\Utils\DatePeriod
 *
 * @uses \EoneoPay\Utils\DateTime
 */
class DatePeriodTest extends TestCase
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength) Method builds test data sets
     *
     * @return mixed[]
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
     */
    public function getOutputData(): array
    {
        return [
            'monthly on the 1st for 6 months' => [[
                'start' => new DateTime('2019-01-01T00:00:00+0000'),
                'interval' => new DateInterval('P1M'),
                'end' => new DateTime('2019-06-30T23:59:59+0000'),
                'expected' => [
                    new DateTime('2019-01-01T00:00:00+0000'),
                    new DateTime('2019-02-01T00:00:00+0000'),
                    new DateTime('2019-03-01T00:00:00+0000'),
                    new DateTime('2019-04-01T00:00:00+0000'),
                    new DateTime('2019-05-01T00:00:00+0000'),
                    new DateTime('2019-06-01T00:00:00+0000')
                ]
            ]],

            'finishing midway through a period' => [[
                'start' => new DateTime('2019-01-01T00:00:00+0000'),
                'interval' => new DateInterval('P1M'),
                'end' => new DateTime('2019-03-20T23:59:59+0000'),
                'expected' => [
                    new DateTime('2019-01-01T00:00:00+0000'),
                    new DateTime('2019-02-01T00:00:00+0000'),
                    new DateTime('2019-03-01T00:00:00+0000')
                ]
            ]],

            'weekly' => [[
                'start' => new DateTime('2019-01-01T00:00:00+0000'),
                'interval' => new DateInterval('P1W'),
                'end' => new DateTime('2019-02-28T23:59:59+0000'),
                'expected' => [
                    new DateTime('2019-01-01T00:00:00+0000'),
                    new DateTime('2019-01-08T00:00:00+0000'),
                    new DateTime('2019-01-15T00:00:00+0000'),
                    new DateTime('2019-01-22T00:00:00+0000'),
                    new DateTime('2019-01-29T00:00:00+0000'),
                    new DateTime('2019-02-05T00:00:00+0000'),
                    new DateTime('2019-02-12T00:00:00+0000'),
                    new DateTime('2019-02-19T00:00:00+0000'),
                    new DateTime('2019-02-26T00:00:00+0000')
                ]
            ]],

            'yearly' => [[
                'start' => new DateTime('2019-01-01T00:00:00+0000'),
                'interval' => new DateInterval('P1Y'),
                'end' => new DateTime('2022-12-31T23:59:59+0000'),
                'expected' => [
                    new DateTime('2019-01-01T00:00:00+0000'),
                    new DateTime('2020-01-01T00:00:00+0000'),
                    new DateTime('2021-01-01T00:00:00+0000'),
                    new DateTime('2022-01-01T00:00:00+0000')
                ]
            ]],

            'silly unusual schedule' => [[
                'start' => new DateTime('2019-01-01T00:00:00+0000'),
                'interval' => new DateInterval('P10D'),
                'end' => new DateTime('2019-03-31T23:59:59+0000'),
                'expected' => [
                    new DateTime('2019-01-01T00:00:00+0000'),
                    new DateTime('2019-01-11T00:00:00+0000'),
                    new DateTime('2019-01-21T00:00:00+0000'),
                    new DateTime('2019-01-31T00:00:00+0000'),
                    new DateTime('2019-02-10T00:00:00+0000'),
                    new DateTime('2019-02-20T00:00:00+0000'),
                    new DateTime('2019-03-02T00:00:00+0000'),
                    new DateTime('2019-03-12T00:00:00+0000'),
                    new DateTime('2019-03-22T00:00:00+0000')
                ]
            ]],

            'broken php behaviour monthly' => [[
                'start' => new DateTime('2018-12-31T00:00:00+0000'),
                'interval' => new DateInterval('P1M'),
                'end' => new DateTime('2019-07-05T23:59:59+0000'),
                'expected' => [
                    new DateTime('2018-12-31T00:00:00+0000'),
                    new DateTime('2019-01-31T00:00:00+0000'),
                    new DateTime('2019-02-28T00:00:00+0000'),
                    new DateTime('2019-03-31T00:00:00+0000'),
                    new DateTime('2019-04-30T00:00:00+0000'),
                    new DateTime('2019-05-31T00:00:00+0000'),
                    new DateTime('2019-06-30T00:00:00+0000')
                ]
            ]],

            'broken php behaviour yearly' => [[
                'start' => new DateTime('2016-02-29T00:00:00+0000'),
                'interval' => new DateInterval('P1Y'),
                'end' => new DateTime('2020-07-05T23:59:59+0000'),
                'expected' => [
                    new DateTime('2016-02-29T00:00:00+0000'),
                    new DateTime('2017-02-28T00:00:00+0000'),
                    new DateTime('2018-02-28T00:00:00+0000'),
                    new DateTime('2019-02-28T00:00:00+0000'),
                    new DateTime('2020-02-29T00:00:00+0000')
                ]
            ]],

            'end before start' => [[
                'start' => new DateTime('2019-01-01T00:00:00+0000'),
                'interval' => new DateInterval('P1Y'),
                'end' => new DateTime('2018-12-15T00:00:00+0000'),
                'expected' => []
            ]],

            'daily' => [[
                'start' => new DateTime('2019-01-01T00:00:00+0000'),
                'interval' => new DateInterval('P1D'),
                'end' => new DateTime('2019-01-05T23:59:59+0000'),
                'expected' => [
                    new DateTime('2019-01-01T00:00:00+0000'),
                    new DateTime('2019-01-02T00:00:00+0000'),
                    new DateTime('2019-01-03T00:00:00+0000'),
                    new DateTime('2019-01-04T00:00:00+0000'),
                    new DateTime('2019-01-05T00:00:00+0000')
                ]
            ]],

            'caught in the middle of the php bug' => [[
                'start' => new DateTime('2018-12-30T00:00:00+0000'),
                'interval' => new DateInterval('P1M'),
                'end' => new DateTime('2019-03-01T00:00:00+0000'),
                'expected' => [
                    new DateTime('2018-12-30T00:00:00+0000'),
                    new DateTime('2019-01-30T00:00:00+0000'),
                    new DateTime('2019-02-28T00:00:00+0000')
                ]
            ]]
        ];
    }

    /**
     * Tests the output of DatePeriod
     *
     * @dataProvider getOutputData
     *
     * @param mixed[] $data
     *
     * @return void
     */
    public function testOutput(array $data): void
    {
        $period = new DatePeriod($data['start'], $data['interval'], $data['end']);

        static::assertEquals($data['expected'], \iterator_to_array($period));
    }
}
