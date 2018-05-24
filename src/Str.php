<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\StrInterface;

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
        return \preg_replace('/\s+/', ' ', \preg_replace("/[^\w\+@\s!\^\\$%&'\(\)\*\-:;=\?\.#,\[\]\/]/", '', $value));
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
            ));
        }

        return $value;
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
