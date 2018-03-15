<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface UtcDateTimeInterface
{
    /**
     * Get zulu datetime format.
     *
     * @return \DateTime
     */
    public function getZulu(): string;

    /**
     * Get the datetime object.
     *
     * @return \DateTime
     */
    public function getObject(): \DateTime ;
}