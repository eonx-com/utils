<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Arr;

use EoneoPay\Utils\Arr;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Arr::groupBy
 */
class GroupByTest extends TestCase
{
    /**
     * Tests group by
     *
     * @return void
     */
    public function testGroupBy(): void
    {
        $original = [
            ['key' => 'a'],
            ['key' => 'b'],
            ['key' => 'c'],
            ['key' => 'a'],
            ['no-key' => 'sup']
        ];

        $arr = new Arr();
        $grouped = $arr->groupBy($original, 'key', 'z');

        // Set expectation and test
        $expected = [
            'a' => [
                0 => ['key' => 'a'],
                3 => ['key' => 'a']
            ],
            'b' => [
                1 => ['key' => 'b']
            ],
            'c' => [
                2 => ['key' => 'c']
            ],
            'z' => [
                4 => ['no-key' => 'sup']
            ]
        ];

        self::assertSame($expected, $grouped);
    }

    /**
     * Tests group by
     *
     * @return void
     */
    public function testGroupByCallable(): void
    {
        $original = [
            ['key' => 'a'],
            ['key' => 'b'],
            ['key' => 'c'],
            ['key' => 'a'],
            ['no-key' => 'sup']
        ];

        $callable = function ($value) {
            return $value['key'] ?? 'z';
        };

        $arr = new Arr();
        $grouped = $arr->groupBy($original, $callable);

        // Set expectation and test
        $expected = [
            'a' => [
                0 => ['key' => 'a'],
                3 => ['key' => 'a']
            ],
            'b' => [
                1 => ['key' => 'b']
            ],
            'c' => [
                2 => ['key' => 'c']
            ],
            'z' => [
                4 => ['no-key' => 'sup']
            ]
        ];

        self::assertSame($expected, $grouped);
    }
}
