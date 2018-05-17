<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Arr;

use EoneoPay\Utils\Arr;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Arr::has
 * @covers \EoneoPay\Utils\Arr::get
 * @covers \EoneoPay\Utils\Arr::set
 */
class MutatorTest extends TestCase
{
    /**
     * The array to test on
     *
     * @var mixed[]
     */
    private $array = [
        'one' => [
            'two' => [
                'three' => 'a',
                'four' => 'b'
            ],
            'five' => 'c'
        ],
        'six' => 'd',
        'seven' => null
    ];

    /**
     * Test get falls back to default if key doesn't exist
     *
     * @return void
     */
    public function testGetFallsBackToDefaultValueIfKeyDoesntExist(): void
    {
        $arr = new Arr();

        // If default value isn't passed, should return null
        self::assertNull($arr->get($this->array, 'invalid.key'));
        self::assertSame('default value', $arr->get($this->array, 'invalid.key', 'default value'));
    }

    /**
     * Test getting an item from the array with dot notation
     *
     * @return void
     */
    public function testGetWithDotNotation(): void
    {
        self::assertSame($this->array['one']['two']['three'], (new Arr())->get($this->array, 'one.two.three'));
    }

    /**
     * Test has bases the result on array keys rather than values
     *
     * @return void
     */
    public function testHasBasesResultOnKeyAndNotValue(): void
    {
        $arr = new Arr();

        self::assertTrue($arr->has($this->array, 'seven'));
        self::assertFalse($arr->has($this->array, 'eight'));
    }

    /**
     * Test has can use dot notation to determine if a key exists
     *
     * @return void
     */
    public function testHasWithDotNotation(): void
    {
        self::assertTrue((new Arr())->has($this->array, 'one.two.three'));
        self::assertFalse((new Arr())->has($this->array, 'one.two.five'));
    }

    /**
     * Test set allows setting an array which can be fetched with dot notation later
     *
     * @return void
     */
    public function testSetArrayAndGetWithDotNotation(): void
    {
        $arr = new Arr();
        $value = ['one' => 'z', 'two' => 'y', 'three' => ['four' => 'x', 'five' => 'w']];

        self::assertNull($arr->get($this->array, 'test'));

        $arr->set($this->array, 'test', $value);
        self::assertSame($value['one'], $arr->get($this->array, 'test.one'));
        self::assertSame($value['three'], $arr->get($this->array, 'test.three'));
        self::assertSame($value['three']['four'], $arr->get($this->array, 'test.three.four'));
    }

    /**
     * Test setting a value to an array using dot notation
     *
     * @return void
     */
    public function testSetWithDotNotation(): void
    {
        $arr = new Arr();

        self::assertNull($arr->get($this->array, 'test.key'));

        $arr->set($this->array, 'test.key', 'value');
        self::assertSame('value', $arr->get($this->array, 'test.key'));
        self::assertSame(['key' => 'value'], $arr->get($this->array, 'test'));
    }
}
