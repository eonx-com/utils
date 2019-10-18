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
     * Determine if a given string contains a given substring.
     *
     * @param string $haystack
     * @param string|string[] $needles
     *
     * @return bool
     */
    public function contains(string $haystack, $needles): bool;

    /**
     * Convert a string to EBCDIC
     *
     * @param string $value
     *
     * @return string
     *
     * @see https://en.wikipedia.org/wiki/EBCDIC
     */
    public function ebcdic(string $value): string;

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string $haystack
     * @param string|string[] $needles
     *
     * @return bool
     */
    public function endsWith(string $haystack, $needles): bool;

    /**
     * Determine if a string is json
     *
     * @param string $string The string to check
     *
     * @return bool
     */
    public function isJson(string $string): bool;

    /**
     * Determine if a string is xml
     *
     * @param string $string The string to check
     *
     * @return bool
     */
    public function isXml(string $string): bool;

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
     * Determine if a given string starts with a given substring.
     *
     * @param string $haystack
     * @param string|string[] $needles
     *
     * @return bool
     */
    public function startsWith(string $haystack, $needles): bool;

    /**
     * Convert a string to studly caps case.
     *
     * @param string $value
     *
     * @return string
     */
    public function studly(string $value): string;
}
