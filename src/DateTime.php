<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use DateTime as BaseDateTime;
use DateTimeZone;
use EoneoPay\Utils\Exceptions\InvalidDateTimeStringException;
use Exception;

class DateTime extends BaseDateTime
{
    /**
     * Create a datetime object from string and throw exception if invalid datetime string provided
     *
     * @param \DateTime|string|null $timestamp A timestamp parsable by strtotime()
     * @param \DateTimeZone|null $timezone The timezone to use with the timestamp
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function __construct($timestamp = null, ?DateTimeZone $timezone = null)
    {
        if (($timestamp instanceof \DateTime) === true) {
            $timestamp = $timestamp->format('Y-m-d\TH:i:s.uP');
        }

        if ($timestamp === null) {
            $timestamp = 'now';
        }

        if (\is_string($timestamp) === false) {
            throw new InvalidDateTimeStringException('The date/time provided is invalid');
        }

        try {
            // Create parent object
            parent::__construct($timestamp, $timezone);
        } catch (Exception $exception) {
            throw new InvalidDateTimeStringException('The date/time provided is invalid', null, $exception);
        }
    }

    /**
     * Adds an interval to a supplied DateTime
     *
     * @param \EoneoPay\Utils\DateInterval $interval
     *
     * @return \EoneoPay\Utils\DateTime
     */
    public function addWithoutOverflow(DateInterval $interval): DateTime
    {
        $clone = clone $this;
        $this->add($interval);

        if ($interval->hasPredictableDayIteration() &&
            $this->format('d') !== $clone->format('d')
        ) {
            // We've overflowed months, wind it back.
            $this->modify('last day of last month');
        }

        return $this;
    }
}
