<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

use EoneoPay\Utils\DateTime;

interface UtcDateTimeInterface
{
    /**
     * ISO 8601 date and time format with timezone.
     *
     * @var string
     */
    public const FORMAT_ZULU = 'Y-m-d\TH:i:s\Z';

    /**
     * Creates a DateTime instance from the specified timestamp and optional timezone.
     *
     * @param \DateTime|string $timestamp A timestamp parsable by strtotime()
     * @param \DateTimeZone|null $timezone
     *
     * @return \EoneoPay\Utils\DateTime
     */
    public function create($timestamp, ?\DateTimeZone $timezone): DateTime;

    /**
     * Get the datetime object.
     *
     * @return \EoneoPay\Utils\DateTime
     */
    public function getObject(): DateTime;

    /**
     * Get zulu datetime format.
     *
     * @return string
     */
    public function getZulu(): string;
}
