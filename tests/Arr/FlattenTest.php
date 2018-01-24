<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Arr;

use EoneoPay\Utils\Arr;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Arr::flatten
 * @covers \EoneoPay\Utils\Arr::unflatten
 */
class FlattenTest extends TestCase
{
    /**
     * The flattened array to test on
     *
     * @var array
     */
    private static $flattened = [
        'one.two.three' => 'a',
        'one.two.four' => 'b',
        'one.two' => [
            'three' => 'a',
            'four' => 'b'
        ],
        'one.five' => 'c',
        'one' => [
            'two' => [
                'three' => 'a',
                'four' => 'b'
            ],
            'five' => 'c'
        ],
        'six' => 'd'
    ];

    /**
     * The unflattened version of the flattened array
     *
     * @var array
     */
    private static $unflattened = [
        'one' => [
            'two' => [
                'three' => 'a',
                'four' => 'b'
            ],
            'five' => 'c'
        ],
        'six' => 'd'
    ];

    /**
     * Test double flattening an array doesn't affect the outcome
     *
     * @return void
     */
    public function testDoubleFlattenDoesntChangeArray(): void
    {
        $arr = new Arr();
        $flattened = $arr->flatten(self::$unflattened);

        self::assertSame(self::$flattened, $flattened);
        self::assertSame(self::$flattened, $arr->flatten($flattened));
    }

    /**
     * Test flatten converts a multi-dimensional array to single dimension
     *
     * @return void
     */
    public function testFlattenConvertsMultiDimensionalArrayToOneDimension(): void
    {
        self::assertSame(self::$flattened, (new Arr())->flatten(self::$unflattened));
    }

    /**
     * Test unflatten doesn't mess with arrays which are already unflattened
     *
     * @return void
     */
    public function testUnflattenDoesntChangeUnflattenedArrays(): void
    {
        self::assertSame(self::$unflattened, (new Arr())->unflatten(self::$unflattened));
    }

    /**
     * Test unflatten restores flattened arrays correctly regardless of how they were flattened
     *
     * @return void
     */
    public function testUnflattenRestoresFlattenedArrays(): void
    {
        $arr = new Arr();

        self::assertSame(self::$unflattened, $arr->unflatten($arr->flatten(self::$unflattened)));
        self::assertSame(self::$unflattened, $arr->unflatten(self::$flattened));
    }
}
