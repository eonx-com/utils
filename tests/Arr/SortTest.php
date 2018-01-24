<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Arr;

use EoneoPay\Utils\Arr;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Arr::sort
 */
class SortTest extends TestCase
{
    /**
     * Test sort method sorts keys recursively
     *
     * @return void
     */
    public function testSortOrganisesKeysRecursively(): void
    {
        $unsorted = ['z' => ['a' => 1, 'b' => ['u' => 17, 'g' => 3]], 'c' => 10];
        $sorted = ['c' => 10, 'z' => ['a' => 1, 'b' => ['g' => 3, 'u' => 17]]];

        self::assertSame($sorted, (new Arr())->sort($unsorted));
    }
}
