<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface LuhnInterface
{
    /**
     * Calculate the check digit for a number, using Luhn algorithm
     *
     * @param string $number
     *
     * @return int
     */
    public function calculate(string $number): int;

    /**
     * Validate the provided number has a valid check digit
     *
     * @param string $number
     *
     * @return bool
     */
    public function validate(string $number): bool;
}
