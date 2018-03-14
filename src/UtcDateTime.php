<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\UtcDateTimeInterface;

class UtcDateTime implements UtcDateTimeInterface
{
    /**
     * Create UTC DateTime object from datetime string which has format "Y-m-d\TH:i:s\Z".
     *
     * @param string $datetime
     *
     * @return \DateTime
     */
    public function createFromString(string $datetime): \DateTime
    {
        return \DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $datetime, new \DateTimeZone('UTC'));
    }
}