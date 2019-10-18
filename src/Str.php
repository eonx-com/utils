<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\StrInterface;
use JsonException;

class Str implements StrInterface
{
    /**
     * Convert a string to camel case.
     *
     * @param string $value
     *
     * @return string
     */
    public function camel(string $value): string
    {
        return \lcfirst($this->studly($value));
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param string $haystack
     * @param string|string[] $needles
     *
     * @return bool
     */
    public function contains(string $haystack, $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && \mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Convert a string to EBCDIC
     *
     * @param string $value
     *
     * @return string
     *
     * @see https://en.wikipedia.org/wiki/EBCDIC
     */
    public function ebcdic(string $value): string
    {
        return \preg_replace(
            '/\s+/',
            ' ',
            \preg_replace("/[^\w\+@\s!\^\\$%&'\(\)\*\-:;=\?\.#,\[\]\/]/", '', $value) ?? ''
        ) ?? '';
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string $haystack
     * @param  string|string[] $needles
     *
     * @return bool
     */
    public function endsWith(string $haystack, $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if (\substr($haystack, -\strlen($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a string is json
     *
     * @param string $string The string to check
     *
     * @return bool
     */
    public function isJson(string $string): bool
    {
        try {
            \json_decode($string, false, 512, \JSON_THROW_ON_ERROR);
        } /** @noinspection PhpUndefinedClassInspection */ catch (JsonException $exception) {
            // If there was an exception, it's not JSON
            return false;
        }

        return true;
    }

    /**
     * Determine if a string is xml
     *
     * @param string $string The string to check
     *
     * @return bool
     */
    public function isXml(string $string): bool
    {
        \libxml_use_internal_errors(true);

        return \simplexml_load_string($string) !== false;
    }

    /**
     * Convert a string to snake case.
     *
     * @param string $value
     * @param string $delimiter Default to underscore
     *
     * @return string
     */
    public function snake(string $value, ?string $delimiter = null): string
    {
        if (\ctype_lower($value) === false) {
            $value = (string)\preg_replace('/\s+/u', '', \ucwords($value));
            $value = (string)\mb_strtolower(\preg_replace(
                '/(.)(?=[A-Z])/u',
                '$1' . ($delimiter ?? '_'),
                $value
            ) ?? '');
        }

        return $value;
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string $haystack
     * @param string|string[] $needles
     *
     * @return bool
     */
    public function startsWith(string $haystack, $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && \strpos($haystack, (string)$needle) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Convert a value to studly caps case.
     *
     * @param string $value
     *
     * @return string
     */
    public function studly(string $value): string
    {
        return \str_replace(' ', '', \ucwords(\str_replace(['-', '_'], ' ', $value)));
    }
}
