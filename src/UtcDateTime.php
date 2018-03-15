<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use DateTime;
use DateTimeZone;

use EoneoPay\Utils\Interfaces\UtcDateTimeInterface;

class UtcDateTime implements UtcDateTimeInterface
{
    private $datetime;

    public function __construct(string $datetime)
    {
        $this->datetime = new DateTime($datetime);
        $this->datetime->setTimezone(new DateTimeZone('UTC'));
        $this->datetime->format('Y-m-d H:i:s');
    }

    /**
     * Set the zulu format for the datetime object.
     *
     * @param string $datetime
     *
     * @return \DateTime
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
    public function getObject()
    {
        return $this->datetime;
    }
}