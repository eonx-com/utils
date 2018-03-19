<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use DateTime;
use DateTimeZone;
use EoneoPay\Utils\Exceptions\InvalidDateTimeStringException;
use EoneoPay\Utils\Interfaces\UtcDateTimeInterface;
use Exception;

class UtcDateTime implements UtcDateTimeInterface
{
    /**
     * Instantiated DateTime instance
     *
     * @var \DateTime
     */
    private $datetime;

    /**
     * Create a utc datetime object from string and throw exception if invalid datetime string provided.
     *
     * @param string|null $timestamp A timestamp parseable by strtotime
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function __construct(?string $timestamp = null)
    {
        if (!$timestamp) {
            throw new InvalidDateTimeStringException('The datetime parameter should not be null', null);
        }

        try {
            $datetime = new DateTime($timestamp);

            // Force UTC timezone for time regardless of passed timezone without adjusting time, this works around
            // the limitation in the constructor where the timezone is ignored if a timestamp or offset is specified
            $this->datetime = (new DateTime($datetime->format('Y-m-d H:i:s'), new DateTimeZone('UTC')))
                ->setTimezone(new DateTimeZone('UTC'));
        } catch (Exception $exception) {
            throw new InvalidDateTimeStringException('The datetime parameter is invalid', null, $exception);
        }
    }

    /**
     * Get the datetime object.
     *
     * @return \DateTime
     */
    public function getObject(): DateTime
    {
        return $this->datetime;
    }

    /**
     * Set the zulu format for the datetime object.
     *
     * @return string
     */
    public function getZulu(): string
    {
        return $this->datetime->format('Y-m-d\TH:i:s\Z');
    }
}
