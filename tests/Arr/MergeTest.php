<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Arr;

use EoneoPay\Utils\Arr;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Arr::merge
 */
class MergeTest extends TestCase
{
    /**
     * Test merging a simple array with keys works in the same way as array_merge_recursive
     *
     * @return void
     */
    public function testMergeSimpleArrayWithKeysSameAsArrayMergeRecursive(): void
    {
        $array1 = ['one' => 'a', 'two' => 'b'];
        $array2 = ['two' => 'c', 'three' => 'd'];

        $arrayMerge = \array_merge_recursive($array1, $array2);
        $arrMerge = (new Arr())->merge($array1, $array2);

        self::assertSame($arrayMerge, $arrMerge);
        self::assertCount(3, $arrMerge);
    }

    /**
     * Test merging a simple array without keys works in the same way as array_merge
     *
     * @return void
     */
    public function testMergeSimpleArrayWithoutKeysSameAsArrayMerge(): void
    {
        $array1 = ['one', 'two'];
        $array2 = ['two', 'three'];

        $arrayMerge = \array_merge($array1, $array2);
        $arrMerge = (new Arr())->merge($array1, $array2);

        self::assertSame($arrayMerge, $arrMerge);
        self::assertCount(4, $arrMerge);
    }

    /**
     * Test merging recursively merges arrays in the same way as array_merge_recursive
     *
     * @return void
     */
    public function testMergeTreatsMultiDimensionalArraysLikeArrayMergeRecursive(): void
    {
        $array1 = ['one' => ['two' => 'a']];
        $array2 = ['one' => 'b'];

        $arrayMerge = \array_merge_recursive($array1, $array2);
        $arrMerge = (new Arr())->merge($array1, $array2);

        self::assertSame($arrayMerge, $arrMerge);
    }

    /**
     * Test merging dot notation arrays together in the same way as normal arrays
     *
     * @return void
     */
    public function testMergeWorksWithDotNotationArrays(): void
    {
        $array1 = ['one.two' => 'a'];
        $array2 = ['one' => ['two' => 'a']];
        $array3 = ['one' => 'b'];

        $arrayMerge = \array_merge_recursive($array2, $array3);
        $arrMerge = (new Arr())->merge($array1, $array3);

        self::assertSame($arrayMerge, $arrMerge);
    }

    /**
     * Test merging dot notation and non-dot notation arrays together works correctly
     *
     * @return void
     */
    public function testMergeWithDotAndNonDotNotationArraysWorks(): void
    {
        $array1 = ['one.two' => 'a', 'one.three' => 'b'];
        $array2 = ['one' => ['four' => 'c']];
        $expected = ['one' => ['two' => 'a', 'three' => 'b', 'four' => 'c']];

        self::assertSame($expected, (new Arr())->merge($array1, $array2));
    }
}
