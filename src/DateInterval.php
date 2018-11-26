<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use DateInterval as BaseDateInterval;
use EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException;
use Exception;

class DateInterval extends BaseDateInterval
{
    /**
     * Create a DateInterval object from string and throw exception if invalid interval provided
     *
     * @param string $interval Interval spec string
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
     */
    public function __construct(string $interval)
    {
        try {
            // Create parent object
            parent::__construct($interval);
        } catch (Exception $exception) {
            throw new InvalidDateTimeIntervalException('The date/time interval is invalid', null, $exception);
        }
    }
}
