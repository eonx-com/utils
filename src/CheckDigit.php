<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\CheckDigitInterface;

class CheckDigit implements CheckDigitInterface
{
    /**
     * Calculate a check digit based on the Luhn algorithm.
     *
     * @param string $string
     *
     * @return int
     */
    public function calculate(string $string): int
    {
        // Reverse the exploded
        $characters = \array_reverse(\str_split($string));
        $values = [];

        foreach ($characters as $index => $character) {
            // Convert character to ASCII code
            $value = \ord($character);

            // Iterate value until it's greater than 10
            while ($value > 10) {
                $value = (int)\array_sum(\str_split((string)$value));
            }

            // First, or every second character is * 2, and ensured value is below 10
            if ($index === 0 || $index % 2 === 0) {
                $value *= 2;
                $value = $value > 9 ? $value - 9 : $value;
            }

            $values[] = $value;
        }

        $total = (int)\array_sum($values);

        // Last digit is the check digit.
        return $total % 10;
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
