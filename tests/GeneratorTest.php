<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Generator;

/**
 * @covers \EoneoPay\Utils\Generator
 */
class GeneratorTest extends TestCase
{
    /**
     * Test random string generation
     *
     * @return void
     *
     * @throws \Exception Inherited, if entropy can't be gathered
     */
    public function testRandomStringGeneration(): void
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

    /**
     * Ensure random strings do no contain any ambiguous characters
     *
     * @return void
     *
     * @throws \Exception Inherited, if entropy can't be gathered
     */
    public function testRandomStringsDoNotContainAmbiguousCharacters(): void
    {
        $generator = new Generator();

        // Run 500 times to ensure characters are removed
        for ($loop = 0; $loop < 500; $loop++) {
            self::assertNotRegExp('/[015ilos]/i', $generator->randomString());
        }
    }
}
