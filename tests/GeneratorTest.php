<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Generator;
use Tests\EoneoPay\Utils\Stubs\GeneratorStub;

/**
 * @covers \EoneoPay\Utils\Generator
 */
class GeneratorTest extends TestCase
{
    /**
     * Hello
     *
     * @return void
     *
     * @throws \Exception
     */
    public function testGenerateNonAmbiguousString(): void
    {
        $generator = new Generator();

        $value = $generator->generateNonAmbiguousString(20);

        self::assertEquals(20, \mb_strlen($value));

        $value2 = $generator->generateNonAmbiguousString(20);

        self::assertNotSame($value, $value2);
    }

    /**
     * Test string generation falls back correctly if an exception is thrown by the true generation function
     *
     * @return void
     *
     * @throws \Exception Makes generateTrueRandomString emulate not enough entropy
     */
    public function testPseudoRandomStringGeneration(): void
    {
        $generator = new GeneratorStub();

        /** @var \EoneoPay\Utils\Generator $generator */
        $string = $generator->randomString();

        self::assertSame(16, \mb_strlen($string));
        self::assertRegExp('/[\da-f]{16}/', $string);
    }

    /**
     * Test true random string generation
     *
     * @return void
     */
    public function testTrueRandomStringGeneration(): void
    {
        $generator = new Generator();

        $generated = [];

        // Run 500 times and make sure strings are always different
        for ($loop = 0; $loop < 500; $loop++) {
            $string = $generator->randomString();

            self::assertArrayNotHasKey($string, $generated);

            $generated[$string] = 1;
        }
    }
}
