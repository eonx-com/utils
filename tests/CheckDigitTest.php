<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\CheckDigit;

/**
 * @covers \EoneoPay\Utils\CheckDigit
 */
class CheckDigitTest extends TestCase
{
    /**
     * Test the check digit generator is consistent, and works as expected.
     *
     * @return void
     */
    public function testCheckDigitGeneration(): void
    {
        $strings = [
            'thisIsAReallyGoodString' => null,
            'somethingShort' => null,
            'a' => null,
            'abcdefghijklmnopqrstuvwxyz123456789' => null,
            '!@#$%^&*()' => null,
            '9' => null,
            '72968203253' => null,
            \str_repeat('anAmazingStringWithNumbers100', 100) => null
        ];

        $checkDigitClass = new CheckDigit();

        // Test each string 25 times to ensure check digit does not change from initial generation
        foreach ($strings as $string => &$checkDigit) {
            // PHP Converts strings of whole numbers to integer for some reason
            $string = (string)$string;

            $checkDigit = $checkDigitClass->calculate($string);

            // Ensure result is only one digit
            self::assertLessThan(10, $checkDigit);

            for ($iteration = 0; $iteration < 25; $iteration++) {
                // Check digit should not change from original result after any amount of iterations
                self::assertEquals($checkDigit, $checkDigitClass->calculate($string));
            }

            unset($checkDigit);
        }

        // Ensure check digit is varying amongst results
        self::assertGreaterThan(1, \count(\array_unique(\array_values($strings))));

        // Ensure all strings + check digit are validated successfully
        foreach ($strings as $string => $checkDigit) {
            self::assertTrue($checkDigitClass->validate(\sprintf('%s%d', $string, $checkDigit)));
        }

        // Check to see if validate method is being realistic.
        self::assertFalse($checkDigitClass->validate('AnInvalidString'));

        // Single digits should validate as false because there is no suffix digit
        foreach (\range(0, 9) as $number) {
            self::assertFalse($checkDigitClass->validate((string)$number));
        }
    }
}
