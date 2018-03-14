<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface UtcDateTimeInterface
{
    /**
     * Create UTC DateTime object from datetime string.
     *
     * @return \DateTime
     */
    public function createFromString(string $datetime): \DateTime;
}