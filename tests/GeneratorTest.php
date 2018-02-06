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

        self::assertNotSame($generator->randomString(), $generator->randomString());
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

        // Run 100 times to ensure characters are removed
        for ($loop = 0; $loop < 100; $loop++) {
            self::assertNotRegExp('/[ilos015]/i', $generator->randomString());
        }
    }
}
