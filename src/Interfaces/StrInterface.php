<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface StrInterface
{
    /**
     * Convert a string to camel case.
     *
     * @param string $value
     *
     * @return string
     */
    public function camel(string $value): string;

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
     * Convert a string to studly caps case.
     *
     * @param string $value
     *
     * @return string
     */
    public function studly(string $value): string;
}
