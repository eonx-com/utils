<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Math;

/**
 * @covers \EoneoPay\Utils\Math
 */
class MathTest extends TestCase
{
    /**
     * Test math functions
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\BcmathNotLoadedException
     */
    public function testFunctionality(): void
    {
        $math = new Math(5);

        self::assertSame('6.01927', $math->add('4', '0.89492040209492040238482', '1.1243525'));
        self::assertSame('4.46967', $math->divide('4', '0.89492040209492040238482'));
        self::assertSame('3.57968', $math->multiply('4', '0.89492040209492040238482'));
        self::assertSame('1.98073', $math->subtract('4', '0.89492040209492040238482', '1.1243525'));
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
        self::assertSame('5', (new Math(0))->add('2', '3'));
        self::assertSame('5.00000', (new Math(5))->add('2', '3'));
        self::assertSame(
            '5.0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000',
            (new Math(100))->add('2', '3')
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
        self::assertSame('5.0', (new Math(1, \PHP_ROUND_HALF_DOWN))->add('2.51', '2.54'));
        self::assertSame('5.0', (new Math(1, \PHP_ROUND_HALF_EVEN))->add('2.51', '2.54'));
        self::assertSame('5.1', (new Math(1, \PHP_ROUND_HALF_UP))->add('2.51', '2.54'));
        self::assertSame('5.1', (new Math(1, \PHP_ROUND_HALF_ODD))->add('2.51', '2.54'));
    }
}
