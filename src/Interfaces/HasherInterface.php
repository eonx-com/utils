<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface HasherInterface
{
    /**
     * Algorithm to be used for hashing
     *
     * @var int
     */
    public const ALGORITHM = \PASSWORD_BCRYPT;

    /**
     * Default cost if none is supplied when hashing.
     * This value is purposed for the blowfish algorithm
     *
     * @var int
     */
    public const DEFAULT_COST = 10;

    /**
     * Transform a string into a one-way hash
     *
     * @param string $string
     * @param integer $cost
     *
     * @return string
     */
    public function hash(string $string, ?int $cost = null): string;

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
