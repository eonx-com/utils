<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Arr;

use EoneoPay\Utils\Arr;
use EoneoPay\Utils\Collection;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Arr::collapse
 */
class CollapseTest extends TestCase
{
    /**
     * Test collapse collapses multiple arrays correctly
     *
     * @return void
     */
    public function testCollapseCollapsesArray(): void
    {
        $original = [
            [
                'one' => [
                    'two' => [
                        'three' => 'a',
                        'four' => 'b'
                    ],
                    'five' => 'c'
                ]
            ],
            ['six' => 'd'],
            new Collection(['e' => 1]),
            'ignored'
        ];

        $arr = new Arr();
        $collapsed = $arr->collapse($original);

        // Set expectation and test
        $expected = [
            'one' => [
                'two' => [
                    'three' => 'a',
                    'four' => 'b'
                ],
                'five' => 'c'
            ],
            'six' => 'd',
            'e' => 1
        ];

        self::assertSame($expected, $collapsed);
    }
}
