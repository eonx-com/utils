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
        return $this->dateTime->format('Y-m-d\TH:i:s\Z');
    }
}