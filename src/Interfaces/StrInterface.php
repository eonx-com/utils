<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface StrInterface
{
    /**
     * Convert a string to snake case.
     *
     * @param string $value
     * @param string $delimiter Default to underscore
     *
     * @return string
     */
    public function snake(string $value, ?string $delimiter = null): string;

    /**
     * Convert a value to studly caps case.
     *
     * @param string $value
     *
     * @return string
     */
    public function studly(string $value): string;
}
