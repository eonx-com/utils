<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Arr;

use EoneoPay\Utils\Arr;
use EoneoPay\Utils\Collection;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Arr::remove
 */
class RemoveTest extends TestCase
{
    /**
     * Generic array to test against
     *
     * @var array
     */
    private static $array = [
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
     * Test removes unsets an arrays value correctly
     *
     * @return void
     */
    public function testRemoveUnsetsArrayValue(): void
    {
        // Ensure default array isn't modified
        $array = self::$array;
        (new Arr())->remove($array, 'one');

        self::assertSame(['six' => self::$array['six']], $array);
    }

    /**
     * Test removes unsets an arrays value correctly
     *
     * @return void
     */
    public function testRemoveUnsetsArrayValueUsingArrayKeys(): void
    {
        // Ensure default array isn't modified
        $array = self::$array;
        (new Arr())->remove($array, ['one.two.three', 'one.five']);

        $expected = self::$array;
        unset($expected['one']['two']['three'], $expected['one']['five']);
        self::assertSame($expected, $array);
    }

    /**
     * Test nothing changes if key is invalid or no keys are passed
     *
     * @return void
     */
    public function testRemoveDoesNotChangeArrayIfKeyInvalidOrEmpty(): void
    {
        $arr = new Arr();

        // Ensure default array isn't modified
        $array = self::$array;

        // Test invalid key
        $arr->remove($array, ['one.nine.seven']);
        self::assertSame(self::$array, $array);

        // Test no key
        $arr->remove($array, null);
        self::assertSame(self::$array, $array);
    }
}
