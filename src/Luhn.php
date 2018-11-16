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
        // Strip whitespace from outside of the string
        $number = \trim($number);

        if (\preg_replace('/\D/', '', $number) !== $number) {
            throw new InvalidNumericValue('Provided number is not numeric');
        }

        $numbers = \str_split(\strrev($number), 1);
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
        if (\mb_strlen($number) <= 1) {
            return false;
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
}
