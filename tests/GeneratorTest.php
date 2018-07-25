<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Generator;
use EoneoPay\Utils\Interfaces\GeneratorInterface;
use Tests\EoneoPay\Utils\Stubs\GeneratorStub;

/**
 * @covers \EoneoPay\Utils\Generator
 */
class GeneratorTest extends TestCase
{
    /**
     * Test generation using flags works as expected
     *
     * @return void
     */
    public function testFlagsChangeCharactersUsed(): void
    {
        $generator = new Generator();

        // Generate a string using lowercase letters only
        self::assertRegExp(
            '/^[a-z]+$/',
            $generator->randomString(1000, GeneratorInterface::RANDOM_INCLUDE_ALPHA_LOWERCASE)
        );

        // Generate a string using uppercase letters only
        self::assertRegExp(
            '/^[A-Z]+$/',
            $generator->randomString(1000, GeneratorInterface::RANDOM_INCLUDE_ALPHA_UPPERCASE)
        );

        // Generate a string of integers only
        self::assertRegExp('/^[\d]+$/', $generator->randomString(1000, GeneratorInterface::RANDOM_INCLUDE_INTEGERS));

        // Generate a string of symbols only
        self::assertRegExp(
            \sprintf('/^[%s]+$/', \preg_quote('-=[]\\;\',./~!@#$%^&*()_+{}|:"<>?', '/')),
            $generator->randomString(1000, GeneratorInterface::RANDOM_INCLUDE_SYMBOLS)
        );

        // Test exclusion of certain characters
        $allCharacters = GeneratorInterface::RANDOM_INCLUDE_ALPHA_LOWERCASE |
            GeneratorInterface::RANDOM_INCLUDE_ALPHA_UPPERCASE |
            GeneratorInterface::RANDOM_INCLUDE_INTEGERS |
            GeneratorInterface::RANDOM_INCLUDE_SYMBOLS;

        // Test exclusion of similar characters
        self::assertRegExp(
            '/^[^iIlLoOqQsS015!\\$]+$/',
            $generator->randomString(1000, $allCharacters | GeneratorInterface::RANDOM_EXCLUDE_SIMILAR)
        );

        // Test exclusion of ambiguous characters
        self::assertRegExp(
            \sprintf('/^[^%s]+$/', \preg_quote('-[]\\;\',./!()_{}:"<>?', '/')),
            $generator->randomString(1000, $allCharacters | GeneratorInterface::RANDOM_EXCLUDE_AMBIGIOUS)
        );
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
        self::assertRegExp('/^[\da-z]{16}$/', $string);
    }

    /**
     * Test true random string generation
     *
     * @return void
     */
    public function testTrueRandomStringGeneration(): void
    {
        $generator = new Generator();

        // Run 500 times and make sure strings are always different
        $generated = [];
        for ($loop = 0; $loop < 500; $loop++) {
            $string = $generator->randomString();

            self::assertArrayNotHasKey($string, $generated);

            $generated[$string] = 1;
        }
    }
}
