<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface CheckDigitInterface
{
    /**
     * Calculate a check digit.
     *
     * @param string $value
     *
     * @return int
     */
    public function calculate(string $value): int;

    /**
     * Validate the value provided has a valid check digit suffixed.
     *
     * @param string $value
     *
     * @return bool
     */
    public function validate(string $value): bool;
}
