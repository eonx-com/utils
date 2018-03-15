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
    private $datetime;

    /**
     * Create a utc datetime object from string and throw exception if invalid datetime string provided.
     *
     * @param string $datetime
     *
     * @throws InvalidDateTimeStringException
     */
    public function __construct(string $datetime)
    {
        try {
            $this->datetime = (new DateTime($datetime, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('UTC'));
        } catch (Exception $exception) {
            throw new InvalidDateTimeStringException('The date/time provided is invalid');
        }
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

    /**
     * Get the datetime object.
     *
     * @return DateTime
     */
    public function getObject(): DateTime
    {
        return $this->datetime;
    }
}
