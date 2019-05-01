<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Arr;

use EoneoPay\Utils\Arr;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Arr::only
 */
class OnlyTest extends TestCase
{
    /**
     * Array to test againts.
     *
     * @var mixed[]
     */
    private static $array = [
        'one' => ['three' => 'a'],
        'two' => 'b',
        'three' => 'c'
    ];

    /**
     * Test only returns subset of specified keys.
     *
     * @return void
     */
    public function testOnlyReturnsElementsWithSpecifiedKeys(): void
    {
        $expectedArray = [
            'one' => ['three' => 'a'],
            'two' => 'b'
        ];

        $actualArray = (new Arr())->only(self::$array, ['one', 'two']);

        self::assertSame($expectedArray, $actualArray);
    }

    /**
     * Test only returns empty array if given array does not contain elements with specified keys.
     *
     * @return void
     */
    public function testOnlyRerurnsEmptyArrayIfGivenArrayDoesNotContainElementsWithSpecifiedKeys(): void
    {
        $resultArray = (new Arr())->only(self::$array, ['four']);

        self::assertSame([], $resultArray);
    }

    /**
     * Test only returns highest level subset of multi dimensional array.
     *
     * @return void
     */
    public function testOnlyReturnsOnlyHighestLevelSubsetOfMultiDimensionalArray(): void
    {
        $expectedArray = ['three' => 'c'];

        $actualArray = (new Arr())->only(self::$array, ['three']);

        self::assertSame($expectedArray, $actualArray);
    }
}
