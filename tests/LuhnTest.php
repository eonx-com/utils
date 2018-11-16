<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Exceptions\InvalidNumericValue;
use EoneoPay\Utils\Luhn;
use ReflectionMethod;

/**
 * @covers \EoneoPay\Utils\Luhn
 */
class LuhnTest extends TestCase
{
    /**
     * Ensure isEven method is accurate
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function testIsEvenMethod(): void
    {
        $luhn = new Luhn();

        $method = new ReflectionMethod(Luhn::class, 'isEven');
        $method->setAccessible(true);

        self::assertTrue($method->invoke($luhn, 0));
        self::assertTrue($method->invoke($luhn, 2));
        self::assertTrue($method->invoke($luhn, 4));
        self::assertTrue($method->invoke($luhn, 6));
        self::assertTrue($method->invoke($luhn, 8));
        self::assertTrue($method->invoke($luhn, 10));
        self::assertTrue($method->invoke($luhn, 622 * 2));

        self::assertFalse($method->invoke($luhn, 1));
        self::assertFalse($method->invoke($luhn, 67289));
    }

    /**
     * Test the check luhn calculator determines the correct check digit
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidNumericValue
     */
    public function testLuhnCalculate(): void
    {
        $luhn = new Luhn();

        // Ensure the check digit from provided numbers validate with the luhn implementation
        foreach ($this->getVariousNumbers() as $number) {
            // Split off the last digit off the number, because it is the check digit
            [$value, $checkDigit] = [\substr($number, 0, -1), \substr($number, -1)];

            $calculatedDigit = $luhn->calculate($value);

            self::assertSame((int)$checkDigit, $calculatedDigit);
        }
    }

    /**
     * Test the check luhn calculator determines throws exception if input is not a numeric value
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidNumericValue
     */
    public function testLuhnCalculateThrowsExceptionIfInputNotNumeric(): void
    {
        $this->expectException(InvalidNumericValue::class);
        $this->expectExceptionMessage('Provided number is not numeric');

        $luhn = new Luhn();
        $luhn->calculate('aString');
    }

    /**
     * Test the luhn validator returns expected value
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidNumericValue
     */
    public function testLuhnValidate(): void
    {
        $luhn = new Luhn();

        // Ensure the check digit from provided numbers validate with the luhn implementation
        foreach ($this->getVariousNumbers() as $number) {
            self::assertTrue($luhn->validate($number));
        }

        // 1 digit or less should return false because it's impossible to contain a check digit
        self::assertFalse($luhn->validate(''));
        self::assertFalse($luhn->validate('9'));
    }

    /**
     * Array of numbers sourced from various sources containing a check digit calculated with the luhn algorithm
     *
     * @return string[] Numbers containing check digits
     */
    private function getVariousNumbers(): array
    {
        // Randomly generated BPAY CRNs
        $string = '7435972576483,5529195749968,6483161247582,8191247798753,5629741857867,9941534893298,9459326864856';

        // Credit Cards generated from PayPal
        $string .= ',4734202400811094,4707306039780803,4036158190851002';

        return \explode(',', $string);
    }
}
