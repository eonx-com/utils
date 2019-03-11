<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Arr;

use EoneoPay\Utils\Arr;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Arr::replace
 *
 * @uses \EoneoPay\Utils\Arr::flatten
 * @uses \EoneoPay\Utils\Arr::set
 * @uses \EoneoPay\Utils\Arr::unflatten
 */
class ReplaceTest extends TestCase
{
    /**
     * Test replacing a simple array with keys works in the same way as array_replace_recursive
     *
     * @return void
     */
    public function testReplaceSimpleArrayWithKeysSameAsArrayReplaceRecursive(): void
    {
        $array1 = ['one' => 'a', 'two' => 'b'];
        $array2 = ['two' => 'c', 'three' => 'd'];

        $arrayReplace = \array_replace_recursive($array1, $array2);
        $arrReplace = (new Arr())->replace($array1, $array2);

        self::assertSame($arrayReplace, $arrReplace);
        self::assertCount(3, $arrReplace);
    }

    /**
     * Test replacing a simple array without keys works in the same way as array_replace
     *
     * @return void
     */
    public function testReplaceSimpleArrayWithoutKeysSameAsArrayReplace(): void
    {
        $array1 = ['one', 'two'];
        $array2 = ['two', 'three'];

        $arrayReplace = \array_replace($array1, $array2);
        $arrReplace = (new Arr())->replace($array1, $array2);

        self::assertSame($arrayReplace, $arrReplace);
        self::assertCount(2, $arrReplace);
    }

    /**
     * Test reaplcing recursively replaces arrays in the same way as array_replace_recursive
     *
     * @return void
     */
    public function testReplaceTreatsMultiDimensionalArraysLikeArrayReplaceRecursive(): void
    {
        $array1 = ['one' => ['two' => 'a']];
        $array2 = ['one' => 'b'];

        $arrayReplace = \array_replace_recursive($array1, $array2);
        $arrReplace = (new Arr())->replace($array1, $array2);

        self::assertSame($arrayReplace, $arrReplace);
    }

    /**
     * Test replacing dot notation and non-dot notation arrays together works correctly
     *
     * @return void
     */
    public function testReplaceWithDotAndNonDotNotationArraysWorks(): void
    {
        $array1 = ['one.two' => 'a', 'one.three' => 'b', 'two' => 'z'];
        $array2 = ['one' => ['four' => 'c']];

        $arrayReplace = \array_replace_recursive($array2, ['two' => 'z']);
        $arrReplace = (new Arr())->replace($array1, $array2);

        self::assertSame($arrayReplace, $arrReplace);
    }

    /**
     * Test replacing dot notation arrays together in the same way as normal arrays
     *
     * @return void
     */
    public function testReplaceWorksWithDotNotationArrays(): void
    {
        $array1 = ['one.two' => 'a'];
        $array2 = ['one' => ['two' => 'a']];
        $array3 = ['one' => 'b'];

        $arrayReplace = \array_replace_recursive($array2, $array3);
        $arrReplace = (new Arr())->replace($array1, $array3);

        self::assertSame($arrayReplace, $arrReplace);
    }
}
