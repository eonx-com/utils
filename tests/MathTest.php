<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Exceptions\DivisionByZeroException;
use EoneoPay\Utils\Math;

/**
 * @covers \EoneoPay\Utils\Math
 */
class MathTest extends TestCase
{
    /**
     * Inputs for division by zero occurrences
     *
     * @return iterable|mixed[]
     */
    public function getInputsForDivisionByZero(): iterable
    {
        yield 'Divisor decimal places' => [
            'dividend' => '50',
            'divisor' => '0.0000',
            'precision' => null
        ];

        yield 'Divisor decimal places with no precision' => [
            'dividend' => '50',
            'divisor' => '0.0000',
            'precision' => 0
        ];

        yield 'Divisor decimal places with lots of precision' => [
            'dividend' => '50',
            'divisor' => '0',
            'precision' => 60
        ];

        yield 'Dividing 0 by 0 with default precision' => [
            'dividend' => '0',
            'divisor' => '0',
            'precision' => null
        ];

        yield 'Dividing 0 by 0 with specific precision' => [
            'dividend' => '0',
            'divisor' => '0',
            'precision' => 20
        ];
    }

    /**
     * Test comp() which is wrapper of bccomp()
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\BcmathNotLoadedException
     */
    public function testComp(): void
    {
        $math = new Math();

        self::assertSame(0, $math->comp('1.00001', '1.00001', 5));
        self::assertSame(1, $math->comp('1.00002', '1.00001', 5));
        self::assertSame(-1, $math->comp('1.00001', '1.00002', 5));

        self::assertSame(0, $math->comp('1.00002', '1.00001', 4));
        self::assertSame(0, $math->comp('1.1', '1.2'));
    }

    /**
     * Ensure exception is thrown for the various ways a division by zero input could occur
     *
     * @dataProvider getInputsForDivisionByZero()
     *
     * @param string $dividend
     * @param string $divisor
     * @param int|null $precision
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\BcmathNotLoadedException
     * @throws \EoneoPay\Utils\Exceptions\DivisionByZeroException
     */
    public function testDivisionByZeroThrowsException(string $dividend, string $divisor, ?int $precision): void
    {
        $this->expectException(DivisionByZeroException::class);
        $this->expectExceptionMessage('Division by zero cannot be performed');
        $math = new Math();

        $math->divide($dividend, $divisor, $precision);
    }

    /**
     * Test math functions
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\BcmathNotLoadedException
     * @throws \EoneoPay\Utils\Exceptions\DivisionByZeroException
     */
    public function testFunctionality(): void
    {
        $math = new Math();

        self::assertSame('4.8949', $math->add('4', '0.89492040209492040238482', 4));
        self::assertSame('4.46967', $math->divide('4', '0.89492040209492040238482', 5));
        self::assertSame('3.57968', $math->multiply('4', '0.89492040209492040238482', 5));
        self::assertSame('3.10508', $math->subtract('4', '0.89492040209492040238482', 5));
    }

    /**
     * Test precision
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\BcmathNotLoadedException
     */
    public function testPrecision(): void
    {
        self::assertSame('5', (new Math())->add('2', '3', 0));
        self::assertSame('5.00000', (new Math(5))->add('2', '3'));

        // test if precision set in the method takes priority over the one set in constructor
        self::assertSame('5.0000', (new Math(6))->add('2', '3', 4));

        self::assertSame(
            '5.0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000',
            (new Math())->add('2', '3', 100)
        );
    }

    /**
     * Test rounding method
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\BcmathNotLoadedException
     */
    public function testRoundingMode(): void
    {
        self::assertSame('5.0', (new Math())->add('2.51', '2.54', 1, \PHP_ROUND_HALF_DOWN));
        self::assertSame('5.0', (new Math())->add('2.51', '2.54', 1, \PHP_ROUND_HALF_EVEN));
        self::assertSame('5.1', (new Math())->add('2.51', '2.54', 1, \PHP_ROUND_HALF_UP));
        self::assertSame('5.1', (new Math())->add('2.51', '2.54', 1, \PHP_ROUND_HALF_ODD));
    }
}
