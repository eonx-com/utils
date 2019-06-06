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
        $math = new Math();

        self::assertSame('4.8949', $math->add('4', '0.89492040209492040238482', 4));
        self::assertSame('4.46967', $math->divide('4', '0.89492040209492040238482', 5));
        self::assertSame('3.57968', $math->multiply('4', '0.89492040209492040238482', 5));
        self::assertSame('3.10508', $math->subtract('4', '0.89492040209492040238482', 5));
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
