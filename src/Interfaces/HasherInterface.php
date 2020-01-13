<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface HasherInterface
{
    /**
     * Algorithm to be used for hashing
     *
     * @var string
     */
    public const DEFAULT_ALGORITHM = \PASSWORD_BCRYPT;

    /**
     * Transform a string into a one-way hash
     *
     * @param string $string
     *
     * @return string
     */
    public function hash(string $string): string;

    /**
     * Compare provided string with a hash to see if the value is correct
     *
     * @param string $string
     * @param string $hash
     *
     * @return bool
     */
    public function verify(string $string, string $hash): bool;
}
