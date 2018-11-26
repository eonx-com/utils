<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Arr;

use EoneoPay\Utils\Arr;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Arr::search
 */
class SearchTest extends TestCase
{
    /**
     * Test search ignores non-strings
     *
     * @return void
     */
    public function testSearchIgnoresNonStringValues(): void
    {
        $arr = new Arr();
        $array = [['invalid'], true, 'test'];

        self::assertSame($array[2], $arr->search($array, 'test'));
    }

    /**
     * Test searching for a value in an array works as expected
     *
     * @return void
     */
    public function testSearchLooksForValueInArray(): void
    {
        $arr = new Arr();
        $array = ['test', 'value'];

        // Should return null if value isn't found
        self::assertNull($arr->search($array, 'invalid'));
        self::assertSame($array[1], $arr->search($array, $array[1]));
    }

    /**
     * Test search correctly compares search term without punctuation of case sensitivity
     *
     * @return void
     */
    public function testSearchUsesTermWithoutPunctuationOrCaseSensitivity(): void
    {
        $arr = new Arr();
        $array = ['test', 'user_id', 'ParameterTwo'];

        self::assertSame($array[0], $arr->search($array, 'TEST'));
        self::assertSame($array[1], $arr->search($array, 'UserId'));
        self::assertSame($array[2], $arr->search($array, 'parameter_two'));
    }
}
