<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\CheckDigitInterface;

class CheckDigit implements CheckDigitInterface
{
    /**
     * Calculate a check digit.
     *
     * @param string $value
     *
     * @return int
     */
    public function calculate(string $value): int
    {
        $weights = [1, 3, 1, 7, 3, 9];
        $sum = 0;

        // Iterate whichever is smaller, size of $weights array, or length of argument string
        $maxIterations = \mb_strlen($value) < \count($weights) ? \mb_strlen($value) : \count($weights);

        // Apply weight to each character in the string
        for ($iteration = 0; $iteration < $maxIterations; $iteration++) {
            $sum += $weights[$iteration] * \intval($value[$iteration], 36);
        }

        return (10 - $sum % 10) % 10;
    }

    /**
     * Validate the value provided has a valid check digit suffixed.
     *
     * @param string $value
     *
     * @return bool
     */
    public function validate(string $value): bool
    {
        if (\mb_strlen($value) <= 1) {
            return false;
        }

        $valueWithoutCheck = \substr($value, 0, -1);

        return \sprintf('%s%d', $valueWithoutCheck, $this->calculate($valueWithoutCheck)) === $value;
    }
}
