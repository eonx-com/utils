<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

use DateTime;

interface UtcDateTimeInterface
{
    /**
     * Get the datetime object.
     *
     * @return \DateTime
     */
    public function getObject(): DateTime;

    /**
     * Get zulu datetime format.
     *
     * @return string
     */
    public function getZulu(): string;
}
