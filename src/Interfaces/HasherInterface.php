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
    public const ALGO = \PASSWORD_BCRYPT;

    /**
     * Transform a string into a one-way hash
     *
     * @param string $string
     * @param integer $cost
     *
     * @return string
     */
    public function hash(string $string, ?int $cost = null): string;

    public function verify(string $string, string $hash): bool;
}
