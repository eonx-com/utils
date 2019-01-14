<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Exceptions\InvalidNumericValue;
use EoneoPay\Utils\Interfaces\LuhnInterface;

class Luhn implements LuhnInterface
{
    /**
     * @inheritdoc
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidNumericValue
     */
    public function calculate(string $number): int
    {
        $number = $this->parseNumber($number);

        $numbers = \str_split(\strrev($number));
        $sum = [];

        foreach ($numbers as $index => $value) {
            // If index is an odd number, skip
            if ($this->isEven($index) === false) {
                $sum[] = $value;

                continue;
            }

            // Even numbers are doubled
            $value *= 2;

            // If the multiplied number is greater than 9, subtract 9
            if ($value > 9) {
                $value -= 9;
            }

            $sum[] = $value;
        }

        // Calculate sum multiplied by 9, return last digit
        return \array_sum($sum) * 9 % 10;
    }

    /**
     * @inheritdoc
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidNumericValue
     */
    public function validate(string $number): bool
    {
        $number = $this->parseNumber($number);

        // Ensure number is at least 2 digits
        if (\mb_strlen($number) <= 1) {
            throw new InvalidNumericValue('Number must contain check digit');
        }

        $valueWithoutCheck = \substr($number, 0, -1);

        return \sprintf('%s%d', $valueWithoutCheck, $this->calculate($valueWithoutCheck)) === $number;
    }

    /**
     * Determine if a number provided is even
     *
     * @param int $number
     *
     * @return bool
     */
    private function isEven(int $number): bool
    {
        return $number % 2 === 0;
    }

    /**
     * Format and validate the provided number
     *
     * @param string $number
     *
     * @return string
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidNumericValue
     */
    private function parseNumber(string $number): string
    {
        // Strip whitespace from outside of the string
        $number = \trim($number);

        if (\preg_replace('/\D/', '', $number) !== $number) {
            throw new InvalidNumericValue('Number must only contain integers');
        }

        return $number;
    }
}
