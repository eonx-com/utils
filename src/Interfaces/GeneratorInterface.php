<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface GeneratorInterface
{
    /**
     * Exclude ambiguous characters
     *
     * @const int
     */
    public const RANDOM_EXCLUDE_AMBIGIOUS = 16;
    /**
     * Exclude similar characters
     *
     * @const int
     */
    public const RANDOM_EXCLUDE_SIMILAR = 32;
    /**
     * Allow lowercase characters in random string
     *
     * @const int
     */
    public const RANDOM_INCLUDE_ALPHA_LOWERCASE = 1;
    /**
     * Allow uppercase characters in random string
     *
     * @const int
     */
    public const RANDOM_INCLUDE_ALPHA_UPPERCASE = 2;
    /**
     * Allow integers in random string
     *
     * @const int
     */
    public const RANDOM_INCLUDE_INTEGERS = 4;
    /**
     * Allow symbols in random string
     *
     * @const int
     */
    public const RANDOM_INCLUDE_SYMBOLS = 8;

    /**
     * Generate a rendom integer
     *
     * @param int|null $minimum The smallest allowable number, defaults to 0
     * @param int|null $maximum The largest allowable number, defaults to PHP_INT_MAX
     *
     * @return int
     */
    public function randomInteger(?int $minimum = null, ?int $maximum = null): int;

    /**
     * Generate a random string
     *
     * @param int|null $length The length of the string to return, defaults to 16
     * @param int|null $flags Generation flags, defaults to RANDOM_INCLUDE_ALPHA_LOWERCASE | RANDOM_INCLUDE_INTEGERS
     *
     * @return string
     */
    public function randomString(?int $length = null, ?int $flags = null): string;

    /**
     * Generate a uuid.
     *
     * @return string
     */
    public function uuid4(): string;
}
