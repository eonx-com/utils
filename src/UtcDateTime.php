<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use DateTimeZone;
use EoneoPay\Utils\Exceptions\InvalidDateTimeStringException;
use EoneoPay\Utils\Interfaces\UtcDateTimeInterface;
use Exception;

class UtcDateTime implements UtcDateTimeInterface
{
    /**
     * Instantiated DateTime instance
     *
     * @var \EoneoPay\Utils\DateTime
     */
    private $datetime;

    /**
     * Create a utc datetime object from string and throw exception if invalid datetime string provided.
     *
     * @param \DateTime|string $timestamp A timestamp parsable by strtotime()
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function __construct($timestamp = null)
    {
        if ($timestamp === null) {
            return;
        }

        try {
            $datetime = ($timestamp instanceof \DateTime) ? $timestamp : new DateTime($timestamp);

            // Force UTC timezone for time regardless of passed timezone without adjusting time, this works around
            // the limitation in the constructor where the timezone is ignored if a timestamp or offset is specified
            $this->datetime = (new DateTime($datetime->format('Y-m-d H:i:s'), new DateTimeZone('UTC')))
                ->setTimezone(new DateTimeZone('UTC'));
        } catch (Exception $exception) {
            throw new InvalidDateTimeStringException('The date/time provided is invalid', null, $exception);
        }
    }

    /**
     * Creates a DateTime instance from the specified timestamp and optional timezone.
     *
     * @param \DateTime|string $timestamp A timestamp parsable by strtotime()
     * @param \DateTimeZone|null $timezone
     *
     * @return \EoneoPay\Utils\DateTime
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function create($timestamp, ?\DateTimeZone $timezone = null): DateTime
    {
        try {
            return new DateTime($timestamp, $timezone);
        } catch (Exception $exception) {
            throw new InvalidDateTimeStringException('The date/time provided is invalid', null, $exception);
        }
    }

    /**
     * @inheritdoc
     */
    public function getObject(): DateTime
    {
        return $this->datetime;
    }

    /**
     * @inheritdoc
     */
    public function getZulu(): string
    {
        return $this->datetime->format(self::FORMAT_ZULU);
    }
}
