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
        // Strip all non-numeric characters from the number string
        $number = \preg_replace('/\D/', '', $number);

        if (\is_numeric($number) === false) {
            throw new InvalidNumericValue('Provided number is not numeric');
        }

        $numbers = \str_split(\strrev($number), 1);
        $sum = [];

        foreach ($numbers as $index => $value) {
            // If it's an even number, skip
            if ($index % 2 === 1) {
                $sum[] = $value;

                continue;
            }

            // Odd numbers are doubled
            $value *= 2;

            // If the multiplied number is greater than 9, subtract nine
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
}
