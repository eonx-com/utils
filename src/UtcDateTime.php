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
     * @param \DateTime|string|null $timestamp A timestamp parsable by strtotime()
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function __construct($timestamp = null)
    {
        try {
            $datetime = new DateTime($timestamp);

            // Force UTC timezone for time regardless of passed timezone without adjusting time, this works around
            // the limitation in the constructor where the timezone is ignored if a timestamp or offset is specified
            $this->datetime = (new DateTime($datetime->format('Y-m-d H:i:s'), new DateTimeZone('UTC')))
                ->setTimezone(new DateTimeZone('UTC'));
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
